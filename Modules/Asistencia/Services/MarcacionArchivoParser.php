<?php
namespace Modules\Asistencia\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/** Lectores del reporte antiguo ZKTeco y del reporte mensual Hikvision. */
class MarcacionArchivoParser
{
    public const MAX_REGISTROS = 100000;
    public function leer(string $ruta, string $extension): array
    {
        if (in_array($extension,['txt','dat','csv','log'],true)) {
            return $this->ztkeco((string)file_get_contents($ruta));
        }
        if (!in_array($extension,['xlsx','xls'],true)) throw new \DomainException('Formato no admitido. Use TXT/DAT/CSV/LOG de ZKTeco o XLS/XLSX de Hikvision.');
        $tipo=IOFactory::identify($ruta);
        if (!in_array($tipo,['Xls','Xlsx'],true)) throw new \DomainException('El contenido no corresponde a un libro Excel XLS/XLSX.');
        // Límite de expansión para evitar que un XLSX pequeño agote la memoria.
        if ($tipo==='Xlsx') {
            $zip=new \ZipArchive();
            if ($zip->open($ruta)!==true) throw new \DomainException('Libro XLSX inválido.');
            $bytes=0;
            for ($i=0;$i<$zip->numFiles;$i++) $bytes+=(int)$zip->statIndex($i)['size'];
            $zip->close();
            if ($bytes>64*1024*1024) throw new \DomainException('El libro excede el tamaño descomprimido permitido (64 MB).');
        }
        $reader=IOFactory::createReader($tipo);
        $hojas=$reader->listWorksheetInfo($ruta);
        $info=array_values(array_filter($hojas,fn($h)=>$h['worksheetName']==='Attendance Record'));
        if (!$info) throw new \DomainException('Hikvision: falta la hoja Attendance Record.');
        if ($info[0]['totalRows']>20000 || $info[0]['totalColumns']>100) throw new \DomainException('El reporte supera 20 000 filas o 100 columnas.');
        $reader->setLoadSheetsOnly(['Attendance Record']);
        $book=$reader->load($ruta);
        try { return $this->hikvision($book); }
        finally { $book->disconnectWorksheets(); }
    }

    public static function documento(string $valor): string
    {
        $valor=trim($valor);
        if (!preg_match('/^\d{1,12}$/D',$valor)) throw new \DomainException('Documento no numérico o mayor de 12 dígitos.');
        return strlen($valor)<8 ? str_pad($valor,8,'0',STR_PAD_LEFT) : $valor;
    }

    public static function fecha(string $valor): string
    {
        $valor=trim($valor);
        foreach (['Y-m-d H:i:s','Y-m-d H:i'] as $formato) {
            $fecha=\DateTimeImmutable::createFromFormat('!'.$formato,$valor,new \DateTimeZone('America/Lima'));
            if ($fecha && $fecha->format($formato)===$valor) return $fecha->format('Y-m-d H:i:s');
        }
        throw new \DomainException('Fecha/hora inválida; se espera AAAA-MM-DD HH:MM[:SS].');
    }

    private function resultado(string $tipo, array $registros, array $errores, int $totalErrores): array
    {
        $unicos=[]; $duplicados=0;
        foreach ($registros as $r) {
            $key=$r['documento'].'|'.$r['fecha'];
            if (isset($unicos[$key])) $duplicados++; else $unicos[$key]=$r;
        }
        if (!$registros && !$totalErrores) { $errores[]='El archivo no contiene marcaciones.'; $totalErrores++; }
        return ['tipo'=>$tipo,'registros'=>array_values($unicos),'duplicados'=>$duplicados,'errores'=>$errores,'total_errores'=>$totalErrores,'total'=>count($registros)+$totalErrores];
    }

    public function ztkeco(string $contenido): array
    {
        $contenido=preg_replace('/^\xEF\xBB\xBF/','',$contenido);
        if (str_contains($contenido,"\0")) throw new \DomainException('El archivo ZKTeco debe ser texto UTF-8, no un archivo binario.');
        $registros=[]; $errores=[]; $totalErrores=0;
        foreach (preg_split('/\r\n|\n|\r/',$contenido) as $i=>$linea) {
            if (trim($linea)==='') continue;
            if (count($registros)+$totalErrores>=self::MAX_REGISTROS) throw new \DomainException('Máximo 100 000 marcaciones por archivo.');
            try {
                $c=explode("\t",$linea);
                if (count($c)<2) throw new \DomainException('Se requieren documento y fecha separados por tabulación.');
                $registros[]=['documento'=>self::documento($c[0]),'fecha'=>self::fecha($c[1]),'ubicacion'=>'Fila '.($i+1)];
            } catch (\DomainException $e) { $totalErrores++; if (count($errores)<100) $errores[]='Fila '.($i+1).': '.$e->getMessage(); }
        }
        return $this->resultado('ZKTECO',$registros,$errores,$totalErrores);
    }

    public function hikvision(Spreadsheet $libro): array
    {
        $hoja=$libro->getSheetByName('Attendance Record');
        if (!$hoja) throw new \DomainException('Falta la hoja Attendance Record.');
        $rango=(string)$hoja->getCell('A4')->getValue();
        preg_match_all('/(\d{4})\/(\d{2})\/(\d{2})/',$rango,$fechas,PREG_SET_ORDER);
        if (!$fechas) throw new \DomainException('No se encontró el mes del reporte en A4 (AAAA/MM/DD).');
        [$texto,$anio,$mes,$dia]=$fechas[0];
        if (!checkdate((int)$mes,(int)$dia,(int)$anio)) throw new \DomainException('La fecha de A4 es inválida.');
        foreach ($fechas as $f) if (!checkdate((int)$f[2],(int)$f[3],(int)$f[1]) || $f[1]!==$anio || $f[2]!==$mes) throw new \DomainException('El reporte Hikvision debe corresponder a un único mes válido.');
        $registros=[]; $errores=[]; $totalErrores=0; $documento=null;
        for ($fila=7;$fila<=$hoja->getHighestDataRow();$fila++) {
            $id=trim((string)$hoja->getCell('A'.$fila)->getValue());
            if ($id!=='') {
                try { $documento=self::documento($id); }
                catch (\DomainException $e) { $documento=null; $totalErrores++; if(count($errores)<100)$errores[]="Fila {$fila}: ".$e->getMessage(); }
            }
            for ($d=1;$d<=31;$d++) {
                $celda=$hoja->getCell([$d+3,$fila]);
                $valor=trim((string)$celda->getValue());
                if ($valor==='') continue;
                try {
                    if ($celda->isFormula()) throw new \DomainException('Las marcaciones deben ser valores, no fórmulas.');
                    if (!$documento) throw new \DomainException('Marca sin documento de trabajador.');
                    if (!checkdate((int)$mes,$d,(int)$anio)) throw new \DomainException('El día no existe en este mes.');
                    // Excel puede representar una hora única como fracción de día.
                    if (is_numeric($valor) && (float)$valor>=0 && (float)$valor<1) {
                        $segundos=(int)round((float)$valor*86400);
                        if ($segundos>=86400) throw new \DomainException('Hora fuera del día.');
                        $valor=gmdate('H:i:s',$segundos);
                    }
                    foreach (preg_split('/\r\n|\n|\r/',$valor) as $hora) {
                        if (trim($hora)==='') continue;
                        if (count($registros)>=self::MAX_REGISTROS) throw new \DomainException('Máximo 100 000 marcaciones por archivo.');
                        $fecha=self::fecha(sprintf('%04d-%02d-%02d ',(int)$anio,(int)$mes,$d).trim($hora));
                        $registros[]=['documento'=>$documento,'fecha'=>$fecha,'ubicacion'=>$celda->getCoordinate()];
                    }
                } catch (\DomainException $e) { $totalErrores++; if(count($errores)<100)$errores[]=$celda->getCoordinate().': '.$e->getMessage(); }
            }
        }
        return $this->resultado('HIKVISION',$registros,$errores,$totalErrores);
    }
}

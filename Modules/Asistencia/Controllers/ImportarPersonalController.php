<?php

namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ImportarPersonalController extends BaseModuleController
{
    private const HEADERS = [
        'Tipo documento', 'Número documento', 'Apellido paterno', 'Apellido materno', 'Nombres', 'Sexo',
        'Lugar nacimiento', 'Fecha nacimiento (AAAA-MM-DD)', 'Residencia', 'RUC', 'Teléfono', 'Email',
        'Estado civil', 'Fecha ingreso', 'Establecimiento', 'Oficina', 'Cargo', 'Modalidad contrato',
        'Código personal', 'Fecha inicio', 'Fecha término', 'Fecha cese', 'Plaza', 'Nivel', 'Estado laboral',
        'Profesión', 'Principal (Sí/No)', 'Colegiatura', 'Número colegiatura', 'Segunda especialidad', 'RNE',
        'Habilitado (Sí/No)', 'Fecha habilitación', 'Fecha vencimiento',
    ];

    public function index()
    {
        return view('Modules\\Asistencia\\Views\\personal\\importar', ['resultado' => session()->getFlashdata('import_resultado')]);
    }

    public function plantilla()
    {
        $book = new Spreadsheet();
        $sheet = $book->getActiveSheet();
        $sheet->setTitle('Personal');
        $sheet->fromArray(self::HEADERS, null, 'A1');
        $sheet->getStyle('A1:AH1')->getFont()->setBold(true);
        $sheet->freezePane('A2');
        foreach (range('A', 'Z') as $col) $sheet->getColumnDimension($col)->setWidth(22);
        foreach (['AA','AB','AC','AD','AE','AF','AG','AH'] as $col) $sheet->getColumnDimension($col)->setWidth(24);
        $sheet->getStyle('A2:AH500')->getNumberFormat()->setFormatCode('@');
        $instructions = $book->createSheet();
        $instructions->setTitle('Instrucciones');
        $instructions->fromArray([
            ['Campo', 'Indicaciones'],
            ['Relaciones', 'Escriba el nombre exacto de Tipo documento, Establecimiento, Oficina, Cargo, Modalidad contrato, Profesión, Colegiatura y Segunda especialidad.'],
            ['Fechas', 'Formato AAAA-MM-DD. Deje vacío cuando el campo sea opcional.'],
            ['Estado laboral', 'Activo, Licencia, Vacaciones, Suspendido o Cesado (también acepta 1 a 5).'],
            ['Sí/No', 'Para Principal y Habilitado use Sí o No.'],
            ['Texto', 'No cambie los encabezados. Complete una persona por fila en la hoja Personal.'],
        ], null, 'A1');
        $instructions->getStyle('A1:B1')->getFont()->setBold(true);
        $instructions->getColumnDimension('A')->setWidth(24);
        $instructions->getColumnDimension('B')->setWidth(110);
        $response = service('response');
        $response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->setHeader('Content-Disposition', 'attachment; filename="Plantilla_Importar_Personal.xlsx"');
        (new Xlsx($book))->save('php://output');
        return $response;
    }

    public function previsualizar()
    {
        $file = $this->request->getFile('archivo_excel');
        if (!$file || !$file->isValid() || !in_array(strtolower($file->getClientExtension()), ['xlsx', 'xls'], true)) {
            return redirect()->to(site_url('asistencia/personal/importar'))->with('error', 'Seleccione un archivo Excel .xlsx o .xls válido.');
        }
        try {
            $sheet = IOFactory::load($file->getTempName())->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, false);
        } catch (\Throwable $e) {
            return redirect()->to(site_url('asistencia/personal/importar'))->with('error', 'No se pudo leer el archivo. Verifique que use la plantilla.');
        }
        if (!$rows || array_slice($rows[0], 0, count(self::HEADERS)) !== self::HEADERS) {
            return redirect()->to(site_url('asistencia/personal/importar'))->with('error', 'Las columnas no coinciden con la plantilla actual.');
        }
        $db = \Config\Database::connect();
        $catalogs = [
            0 => ['casis_tipo_documento_identidad','tdi_nombre','tdi_ide'], 14 => ['casis_establecimiento','est_nombre','est_ide'],
            15 => ['casis_oficina','ofi_nombre','ofi_ide'], 16 => ['casis_cargo','car_nombre','car_ide'],
            17 => ['casis_modalidad_contrato','mco_nombre','mco_ide'], 25 => ['casis_profesion','pro_nombre','pro_ide'],
            27 => ['casis_colegiatura','col_nombre','col_ide'], 29 => ['casis_segunda_especialidad','se_nombre','se_ide'],
        ];
        $lookup = [];
        foreach ($catalogs as $i => [$table,$label,$id]) {
            foreach ($db->table($table)->where('deleted_at', null)->get()->getResultArray() as $item) $lookup[$i][mb_strtolower(trim($item[$label]))] = (int)$item[$id];
        }
        $errors = []; $valid = []; $seenDocs = []; $seenCodes = [];
        foreach (array_slice($rows, 1, null, true) as $index => $raw) {
            $row = array_map(static fn($v) => trim((string)$v), array_pad($raw, count(self::HEADERS), ''));
            if (implode('', $row) === '') continue;
            $line = $index + 1; $e = [];
            foreach ([0,1,2,4,14,17,18,19] as $required) if ($row[$required] === '') $e[] = self::HEADERS[$required] . ' es obligatorio';
            foreach ($catalogs as $i => $_) if ($row[$i] !== '' && !isset($lookup[$i][mb_strtolower($row[$i])])) $e[] = self::HEADERS[$i] . ' no existe en el catálogo';
            foreach ([7,13,19,20,21,32,33] as $i) if ($row[$i] !== '' && !$this->fechaValida($row[$i])) $e[] = self::HEADERS[$i] . ' debe usar AAAA-MM-DD';
            if (!in_array(mb_strtolower($row[5]), ['', 'masculino','femenino','m','f'], true)) $e[] = 'Sexo inválido';
            if ($row[1] !== '') {
                $doc = (string)($lookup[0][mb_strtolower($row[0] ?? '')] ?? 'tipo-invalido') . ':' . $row[1];
                if (isset($seenDocs[$doc]) || $db->table('casis_persona')->where('per_numero_documento', $row[1])->where('deleted_at', null)->countAllResults()) $e[] = 'Documento ya registrado o repetido en el archivo';
                $seenDocs[$doc] = true;
            }
            if ($row[18] !== '' && (isset($seenCodes[mb_strtolower($row[18])]) || $db->table('casis_personal')->where('perl_codigo', $row[18])->where('deleted_at', null)->countAllResults())) $e[] = 'Código personal ya registrado o repetido';
            $seenCodes[mb_strtolower($row[18])] = true;
            if ($e) $errors[] = ['fila'=>$line, 'errores'=>$e]; else $valid[] = $row;
        }
        session()->set('importar_personal_validos', $valid);
        session()->set('importar_personal_tiene_errores', (bool)$errors);
        return view('Modules\\Asistencia\\Views\\personal\\importar', ['resultado'=>['validos'=>$valid,'errores'=>$errors]]);
    }

    public function confirmar()
    {
        $rows = session()->get('importar_personal_validos');
        if (!$rows) return redirect()->to(site_url('asistencia/personal/importar'))->with('error', 'No hay filas validadas pendientes.');
        if (session()->get('importar_personal_tiene_errores')) return redirect()->to(site_url('asistencia/personal/importar'))->with('error', 'Corrija los errores del archivo y vuelva a validarlo; no se importaron filas.');
        $db = \Config\Database::connect(); $now = date('Y-m-d H:i:s'); $user = $this->getAuditUserId();
        $maps = [0=>['casis_tipo_documento_identidad','tdi_nombre','tdi_ide'],14=>['casis_establecimiento','est_nombre','est_ide'],15=>['casis_oficina','ofi_nombre','ofi_ide'],16=>['casis_cargo','car_nombre','car_ide'],17=>['casis_modalidad_contrato','mco_nombre','mco_ide'],25=>['casis_profesion','pro_nombre','pro_ide'],27=>['casis_colegiatura','col_nombre','col_ide'],29=>['casis_segunda_especialidad','se_nombre','se_ide']];
        $db->transBegin();
        try {
            foreach ($rows as $r) {
                $ids=[]; foreach ($maps as $i=>[$table,$label,$id]) { if ($r[$i] === '') { $ids[$i]=null; continue; } $item=$db->table($table)->where($label,$r[$i])->where('deleted_at',null)->get()->getRowArray(); if (!$item) throw new \RuntimeException('El catálogo cambió después de la validación: '.$label.' '.$r[$i]); $ids[$i]=(int)$item[$id]; }
                $audit=['created_at'=>$now,'updated_at'=>$now,'created_by'=>$user,'updated_by'=>$user];
                $db->table('casis_persona')->insert(['per_tdi_ide'=>$ids[0],'per_numero_documento'=>$r[1],'per_paterno'=>$r[2]?:null,'per_materno'=>$r[3]?:null,'per_nombre'=>$r[4]?:null,'per_sexo'=>$r[5]?:null,'per_lugar_nacimiento'=>$r[6]?:null,'per_fecha_nacimiento'=>$this->fecha($r[7]),'per_residencia'=>$r[8]?:null,'per_ruc'=>$r[9]?:null,'per_telefono'=>$r[10]?:null,'per_email'=>$r[11]?:null,'per_estadocivil'=>$r[12]?:null,'per_ingreso'=>$this->fecha($r[13])]+$audit);
                $persona=(int)$db->insertID();
                $db->table('casis_personal')->insert(['perl_per_ide'=>$persona,'perl_est_ide'=>$ids[14],'perl_ofi_ide'=>$ids[15],'perl_car_ide'=>$ids[16],'perl_mco_ide'=>$ids[17],'perl_codigo'=>$r[18],'perl_fecha_inicio'=>$this->fecha($r[19]),'perl_fecha_termino'=>$this->fecha($r[20]),'perl_fecha_cese'=>$this->fecha($r[21]),'perl_plaza'=>$r[22]?:null,'perl_nivel'=>$r[23]?:null,'perl_estado'=>$this->estado($r[24]),'perl_observacion'=>null]+$audit);
                $personal=(int)$db->insertID();
                if ($ids[25]) $db->table('casis_personal_profesion')->insert(['pp_perl_ide'=>$personal,'pp_principal'=>$this->si($r[26])?1:0,'pp_pro_ide'=>$ids[25],'pp_col_ide'=>$ids[27],'pp_numero_colegiatura'=>$r[28]?:null,'pp_se_ide'=>$ids[29],'pp_rne'=>$r[30]?:null,'pp_habilitado'=>$this->si($r[31])?1:0,'pp_fecha_habilitacion'=>$this->fecha($r[32]),'pp_fecha_vencimiento'=>$this->fecha($r[33])]+$audit);
            }
            if ($db->transStatus() === false) throw new \RuntimeException('La base de datos rechazó la importación.');
            $db->transCommit(); session()->remove('importar_personal_validos'); session()->remove('importar_personal_tiene_errores');
            return redirect()->to(site_url('asistencia/personal/importar'))->with('import_resultado',['completados'=>count($rows)]);
        } catch (\Throwable $e) { $db->transRollback(); return redirect()->to(site_url('asistencia/personal/importar'))->with('error','No se importó ningún registro: '.$e->getMessage()); }
    }

    private function fechaValida(string $v): bool { $d=\DateTime::createFromFormat('!Y-m-d',$v); return $d && $d->format('Y-m-d')===$v; }
    private function fecha(string $v): ?string { return $v===''?null:$v; }
    private function si(string $v): bool { return in_array(mb_strtolower(trim($v)),['si','sí','1','true','principal','habilitado'],true); }
    private function estado(string $v): int { $m=['activo'=>1,'licencia'=>2,'vacaciones'=>3,'suspendido'=>4,'cesado'=>5]; return is_numeric($v)?(int)$v:($m[mb_strtolower($v)]??1); }
}

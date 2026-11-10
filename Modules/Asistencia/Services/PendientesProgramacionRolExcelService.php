<?php

namespace Modules\Asistencia\Services;

use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

/** Creates a readable monthly report plus a pending-only import workbook. */
class PendientesProgramacionRolExcelService
{
    public function generar(int $periodoId, string $busqueda = ''): Spreadsheet
    {
        $data = (new PendientesProgramacionRolService())->reporte($periodoId);
        if (!$data['periodo']) throw new \DomainException('No hay un periodo disponible para exportar.');
        $data['oficinas'] = $this->filtrar($data['oficinas'], $busqueda);

        $anio = (int) substr($data['periodo']['mes'], 0, 4);
        $mes = (int) substr($data['periodo']['mes'], 5, 2);
        $diasMes = (int) (new \DateTimeImmutable(sprintf('%04d-%02d-01', $anio, $mes)))->format('t');
        $libro = (new ProgramacionTurnoService())->generarPlantillaExcel($anio, $mes);
        $importacion = $libro->getSheetByName('Programacion');
        $filasExistentes = $importacion->getHighestDataRow();
        if ($filasExistentes > 1) $importacion->removeRow(2, $filasExistentes - 1);

        $fila = 2;
        foreach ($data['oficinas'] as $oficina) {
            foreach ($oficina['pendientes'] as $persona) {
                $importacion->setCellValueExplicit('A' . $fila, $persona['documento'], DataType::TYPE_STRING);
                $importacion->setCellValue('B' . $fila, $anio);
                $importacion->setCellValue('C' . $fila, $mes);
                $importacion->getStyle('A' . $fila . ':AH' . $fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                for ($dia = $diasMes + 1; $dia <= 31; $dia++) {
                    $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($dia + 3);
                    $importacion->getStyle($col . $fila)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('F1F5F9');
                }
                $fila++;
            }
        }
        if ($fila > 2) $importacion->getStyle('A2:A' . ($fila - 1))->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
        $importacion->freezePane('D2');
        $importacion->setAutoFilter('A1:AH' . max(1, $fila - 1));

        $legible = $libro->createSheet();
        $legible->setTitle('Pendientes_Legible');
        $legible->setShowGridLines(true);
        $colHoras = 7 + $diasMes;
        $ultimaCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colHoras);
        $legible->mergeCells('A1:' . $ultimaCol . '1');
        $legible->setCellValue('A1', sprintf('ROL MENSUAL DE PROGRAMACION DE TURNOS - PERIODO %02d/%04d', $mes, $anio));
        $legible->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
        $legible->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('1C3254');
        $legible->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $ruta = implode(' / ', array_filter([$data['periodo']['alcance'], $data['periodo']['nivel'] ?? 'ESTABLECIMIENTO']));
        $metadatos = [
            'Periodo' => $data['periodo']['mes'],
            'Alcance jerarquico' => $ruta,
            'Busqueda aplicada' => trim($busqueda) !== '' ? trim($busqueda) : 'Sin filtro',
            'Personal pendiente' => (string) array_sum(array_map(static fn(array $o): int => count($o['pendientes']), $data['oficinas'])),
            'Generado' => date('d/m/Y H:i'),
        ];
        $metaRow = 3;
        foreach ($metadatos as $etiqueta => $valor) {
            $legible->mergeCells('A' . $metaRow . ':' . $ultimaCol . $metaRow);
            $legible->setCellValueExplicit('A' . $metaRow, $etiqueta . ': ' . $valor, DataType::TYPE_STRING);
            $legible->getStyle('A' . $metaRow)->getAlignment()->setWrapText(true);
            $legible->getRowDimension($metaRow)->setRowHeight(22);
            $metaRow++;
        }

        $headers = ['N°', 'DNI', 'APELLIDOS Y NOMBRES', 'CARGO', 'ESTABLECIMIENTO', 'OFICINA'];
        foreach ($headers as $idx => $header) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($idx + 1);
            $legible->setCellValue($col . '9', $header);
        }
        for ($dia = 1; $dia <= $diasMes; $dia++) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + $dia);
            $legible->setCellValue($col . '9', $dia);
        }
        $legible->setCellValue($ultimaCol . '9', 'TOTAL HORAS');
        $legible->getStyle('A9:' . $ultimaCol . '9')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF'], 'size' => 10],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => '2563EB']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'CBD5E1']]],
        ]);

        $fila = 10;
        $numero = 1;
        foreach ($data['oficinas'] as $oficina) {
            foreach ($oficina['pendientes'] as $persona) {
                $legible->setCellValue('A' . $fila, $numero++);
                $legible->setCellValueExplicit('B' . $fila, $persona['documento'], DataType::TYPE_STRING);
                $legible->setCellValue('C' . $fila, $persona['nombre']);
                $legible->setCellValue('D' . $fila, $persona['cargo']);
                $legible->setCellValue('E' . $fila, $oficina['establecimiento']);
                $legible->setCellValue('F' . $fila, $oficina['oficina']);
                $legible->setCellValue($ultimaCol . $fila, 0);
                $legible->getStyle('A' . $fila . ':' . $ultimaCol . $fila)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('E2E8F0');
                $fila++;
            }
        }
        if ($fila > 10) $legible->getStyle('B10:B' . ($fila - 1))->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
        foreach (['A' => 6, 'B' => 14, 'C' => 34, 'D' => 24, 'E' => 30, 'F' => 28] as $col => $width) $legible->getColumnDimension($col)->setWidth($width);
        for ($col = 7; $col < $colHoras; $col++) $legible->getColumnDimensionByColumn($col)->setWidth(5.5);
        $legible->getColumnDimension($ultimaCol)->setWidth(15);
        $legible->freezePane('G10');
        $legible->setAutoFilter('A9:' . $ultimaCol . max(9, $fila - 1));
        $legible->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A3)->setFitToWidth(1)->setFitToHeight(0);
        $libro->setActiveSheetIndex(0);
        $libro->getProperties()->setTitle('Programacion_Pendiente_' . str_replace('-', '', $data['periodo']['mes']));
        return $libro;
    }

    private function filtrar(array $oficinas, string $busqueda): array
    {
        $termino = mb_strtolower(trim($busqueda));
        if ($termino === '') return $oficinas;
        foreach ($oficinas as &$oficina) {
            $oficina['pendientes'] = array_values(array_filter($oficina['pendientes'], static function (array $p) use ($oficina, $termino): bool {
                $texto = mb_strtolower(implode(' ', [
                    $oficina['diresa'], $oficina['red'], $oficina['microred'], $oficina['establecimiento'], $oficina['oficina'],
                    $p['codigo'], $p['documento'], $p['nombre'], $p['cargo'],
                ]));
                return mb_strpos($texto, $termino) !== false;
            }));
        }
        unset($oficina);
        return array_values(array_filter($oficinas, static fn(array $o): bool => $o['pendientes'] !== []));
    }
}

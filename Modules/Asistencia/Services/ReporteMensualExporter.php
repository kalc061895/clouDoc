<?php

namespace Modules\Asistencia\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReporteMensualExporter
{
    public function excel(array $reporte): string
    {
        $book = new Spreadsheet();
        $sheet = $book->getActiveSheet()->setTitle('Resumen mensual');
        $headers = ['Documento', 'Trabajador', 'DIRESA', 'Establecimiento', 'Oficina', 'Turnos por código', 'Turnos programados', 'Horas programadas', 'Marcaciones', 'Días con marcación', 'Días licencia', 'Permisos registrados', 'Horas permiso', 'Días vacaciones'];
        $sheet->setCellValue('A1', 'Reporte mensual: ' . $reporte['inicio'] . ' a ' . $reporte['fin'])->mergeCells('A1:N1');
        $sheet->setCellValueExplicit('A2', $reporte['ambito'], DataType::TYPE_STRING)->mergeCells('A2:N2');
        $sheet->setCellValueExplicit('A3', $reporte['nota'], DataType::TYPE_STRING)->mergeCells('A3:N3');
        $sheet->getStyle('A3')->getAlignment()->setWrapText(true); $sheet->getRowDimension(3)->setRowHeight(65);
        $sheet->fromArray($headers, null, 'A5');
        $row = 6;
        foreach ($reporte['filas'] as $p) {
            $values = [$p['per_numero_documento'], $p['trabajador'], $p['dir_nombre'], $p['est_nombre'], $p['ofi_nombre'], $p['turnos_resumen'], $p['turnos'], $p['horas'], $p['marcaciones'], $p['dias_marcados'], $p['dias_licencia'], $p['permisos'], $p['horas_permiso'], $p['dias_vacacion']];
            foreach ($values as $col => $value) $sheet->setCellValueExplicit([$col + 1, $row], $value ?? '', $col < 6 ? DataType::TYPE_STRING : DataType::TYPE_NUMERIC);
            $row++;
        }
        $this->estilo($sheet, 'N', max(5, $row - 1), 5);
        $sheet->getStyle('H6:H' . max(6, $row - 1))->getNumberFormat()->setFormatCode('0.00');
        $sheet->getStyle('M6:M' . max(6, $row - 1))->getNumberFormat()->setFormatCode('0.00');
        $detail = $book->createSheet()->setTitle('Detalle diario');
        $detail->fromArray(['Documento', 'Trabajador', 'Establecimiento', 'Oficina', 'Fecha', 'Turnos programados / UPSS / servicio', 'Marcaciones / reloj / origen', 'Licencias', 'Permisos / estado', 'Vacaciones'], null, 'A1');
        $row = 2;
        foreach ($reporte['filas'] as $p) foreach ($p['dias'] as $fecha => $dia) {
            $values = [$p['per_numero_documento'], $p['trabajador'], $p['est_nombre'], $p['ofi_nombre'], $fecha];
            foreach (['turnos', 'marcaciones', 'licencias', 'permisos', 'vacaciones'] as $key) $values[] = implode("\n", $dia[$key]);
            foreach ($values as $col => $value) $detail->setCellValueExplicit([$col + 1, $row], $value ?? '', DataType::TYPE_STRING);
            $row++;
        }
        $this->estilo($detail, 'J', max(1, $row - 1), 1);
        foreach (['F', 'G', 'H', 'I', 'J'] as $col) $detail->getColumnDimension($col)->setWidth(45);
        $book->setActiveSheetIndex(0);
        $path = tempnam(WRITEPATH . 'cache', 'reporte-');
        if ($path === false) throw new \RuntimeException('No se pudo crear la exportación.');
        try { (new Xlsx($book))->save($path); return file_get_contents($path); }
        finally { $book->disconnectWorksheets(); unlink($path); }
    }

    private function estilo($sheet, string $last, int $rows, int $header): void
    {
        $sheet->getStyle('A' . $header . ':' . $last . $header)->applyFromArray(['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '24466B']]]);
        $sheet->getStyle('A1:' . $last . $rows)->getAlignment()->setWrapText(true)->setVertical('top');
        foreach (range('A', $last) as $col) $sheet->getColumnDimension($col)->setWidth(in_array($col, ['B', 'C', 'D', 'E', 'F']) ? 28 : 17);
        $sheet->freezePane('C' . ($header + 1)); $sheet->setAutoFilter('A' . $header . ':' . $last . $rows);
        $sheet->getPageSetup()->setOrientation('landscape')->setFitToWidth(1)->setFitToHeight(0)->setRowsToRepeatAtTopByStartAndEnd($header, $header);
    }

    public function pdf(array $reporte): string
    {
        $options = new \Dompdf\Options(); $options->set('isRemoteEnabled', false); $options->set('isPhpEnabled', false); $options->set('defaultFont', 'DejaVu Sans');
        $pdf = new \Dompdf\Dompdf($options); $pdf->setPaper('A4', 'landscape');
        $pdf->loadHtml(view('Modules\Asistencia\Views\reportes\imprimir', ['reporte' => $reporte, 'pdf' => true]), 'UTF-8'); $pdf->render();
        $canvas = $pdf->getCanvas();
        $canvas->page_text(690, 572, 'Página {PAGE_NUM} de {PAGE_COUNT}', $pdf->getFontMetrics()->getFont('DejaVu Sans'), 8);
        return $pdf->output();
    }
}

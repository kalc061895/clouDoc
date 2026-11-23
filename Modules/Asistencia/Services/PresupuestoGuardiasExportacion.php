<?php

namespace Modules\Asistencia\Services;

use CodeIgniter\Database\BaseConnection;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PresupuestoGuardiasExportacion
{
    public function __construct(private BaseConnection $db)
    {
    }

    public function generar(array $reporte, string $formato, int $usuario): array
    {
        if (!in_array($formato, ['xlsx', 'pdf'], true)) throw new \DomainException('Formato de exportación inválido.');
        if (!$reporte['periodo'] || !$reporte['filas']) throw new \DomainException('Seleccione un período y filtros con resultados.');
        $codigo = 'PG-' . str_replace('-', '', $reporte['periodo']['mes']) . '-' . strtoupper(bin2hex(random_bytes(8)));
        $fecha = (new \DateTimeImmutable('now', new \DateTimeZone('America/Lima')))->format('Y-m-d H:i:s');
        $reporte['codigo'] = $codigo;
        $reporte['generado_at'] = $fecha;
        $reporte['usuario_id'] = $usuario;
        unset($reporte['periodos'], $reporte['opciones']);
        $bytes = $formato === 'pdf' ? $this->pdf($reporte) : $this->excel($reporte);
        $archivo = ['codigo' => $codigo, 'formato' => $formato, 'contenido' => $bytes];
        $registro = $archivo;
        $registro['contenido'] = base64_encode($bytes);
        $ok = $this->db->table('casis_presupuesto_guardia_exportacion')->insert($registro + [
            'periodo_id' => $reporte['periodo']['id'], 'usuario_id' => $usuario, 'generado_at' => $fecha,
            'sha256' => hash('sha256', $bytes),
            'snapshot' => json_encode($reporte, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
        ]);
        if (!$ok) throw new \RuntimeException('No se pudo registrar la exportación.');
        return $archivo;
    }

    public function historial(?int $usuario): array
    {
        $q = $this->db->table('casis_presupuesto_guardia_exportacion')
            ->select('codigo, formato, generado_at, usuario_id');
        if ($usuario !== null) $q->where('usuario_id', $usuario);
        // El código no habilita la descarga. El acceso se revalida con los envíos archivados.
        return $q->orderBy('id', 'DESC')->limit(30)->get()->getResultArray();
    }

    public function archivo(string $codigo, ?int $usuario, ?array $ambito): array
    {
        $q = $this->db->table('casis_presupuesto_guardia_exportacion')->where('codigo', $codigo);
        if ($usuario !== null) $q->where('usuario_id', $usuario);
        $fila = $q->get()->getRowArray();
        if (!$fila) throw new \DomainException('Exportación no disponible.', 404);
        $snapshot = json_decode($fila['snapshot'], true, 512, JSON_THROW_ON_ERROR);
        if ($ambito !== null && array_diff(array_column($snapshot['fuentes'], 'id_oficina'), $ambito)) {
            throw new \DomainException('La exportación está fuera de su ámbito vigente.', 403);
        }
        $contenido = base64_decode($fila['contenido'], true);
        if ($contenido === false || !hash_equals($fila['sha256'], hash('sha256', $contenido))) {
            throw new \RuntimeException('La huella del archivo no coincide.');
        }
        $fila['contenido'] = $contenido;
        return $fila;
    }

    private function pdf(array $reporte): string
    {
        $pdf = new Dompdf(['isRemoteEnabled' => false, 'isPhpEnabled' => false, 'defaultFont' => 'DejaVu Sans']);
        $pdf->setPaper('A4', 'landscape');
        $pdf->loadHtml(view('Modules\Asistencia\Views\presupuesto_guardias\pdf', ['reporte' => $reporte]), 'UTF-8');
        $pdf->render();
        $canvas = $pdf->getCanvas();
        $font = $pdf->getFontMetrics()->getFont('DejaVu Sans');
        $canvas->page_text(30, 563, $reporte['codigo'], $font, 8);
        $canvas->page_text(722, 563, '{PAGE_NUM} de {PAGE_COUNT}', $font, 8);
        return $pdf->output();
    }

    private function excel(array $reporte): string
    {
        $libro = new Spreadsheet();
        $hoja = $libro->getActiveSheet()->setTitle('Presupuesto');
        $hoja->mergeCells('A1:K1')->setCellValue('A1', 'Presupuesto de guardias de roles aprobados');
        $hoja->mergeCells('A2:K2')->setCellValue('A2', $reporte['codigo'] . ' · ' . $reporte['generado_at']);
        $periodo = $reporte['periodo'];
        $hoja->mergeCells('A3:K3')->setCellValueExplicit('A3', $periodo['mes'] . ' · ' . ($periodo['grupo'] ?? ''), DataType::TYPE_STRING);
        $filtros = [];
        foreach ($reporte['filtros'] as $campo => $valor) $filtros[] = PresupuestoGuardiasService::FILTROS[$campo] . ' · ' . $valor;
        $hoja->mergeCells('A4:K4')->setCellValueExplicit('A4', implode(' | ', $filtros), DataType::TYPE_STRING);
        if ($reporte['pendientes']) {
            $hoja->mergeCells('A5:K5')->setCellValue('A5', 'Presupuesto parcial. Filas pendientes de valorizar: ' . $reporte['pendientes']);
            $hoja->getStyle('A5')->getFont()->setBold(true);
        }
        $hoja->fromArray(PresupuestoGuardiasService::COLUMNAS, null, 'A6', true);
        $fila = 7;
        foreach ($reporte['filas'] as $i => $persona) {
            $valores = $persona['valores'];
            $hoja->setCellValue('A' . $fila, $i + 1);
            foreach (['B', 'C', 'D', 'E'] as $j => $col) $hoja->setCellValueExplicit($col . $fila, (string) $valores[$j], DataType::TYPE_STRING);
            $hoja->fromArray(array_slice($valores, 4, 5), null, 'F' . $fila, true);
            $hoja->setCellValue('K' . $fila, $valores[9] === null ? 'Sin tarifa' : $valores[9] / 100);
            $fila++;
        }
        $totales = $reporte['totales'];
        $totales[5] /= 100;
        $hoja->setCellValue('E' . $fila, $reporte['pendientes'] ? 'TOTAL PARCIAL' : 'TOTAL');
        $hoja->fromArray($totales, null, 'F' . $fila, true);
        foreach (array_combine(range('A', 'K'), [7, 16, 38, 31, 17, 8, 8, 8, 8, 18, 18]) as $col => $ancho) {
            $hoja->getColumnDimension($col)->setWidth($ancho);
        }
        $hoja->getStyle('A6:K6')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $hoja->getStyle('A6:K6')->getFill()->setFillType('solid')->getStartColor()->setRGB('17324D');
        $hoja->getStyle('A1')->getFont()->setBold(true)->setSize(15);
        $hoja->getStyle('E' . $fila . ':K' . $fila)->getFont()->setBold(true);
        $hoja->getStyle('K7:K' . $fila)->getNumberFormat()->setFormatCode('#,##0.00');
        $hoja->setAutoFilter('A6:K' . ($fila - 1));
        $hoja->freezePane('F7');
        ob_start();
        try {
            (new Xlsx($libro))->save('php://output');
            return ob_get_contents();
        } finally {
            ob_end_clean();
            $libro->disconnectWorksheets();
        }
    }
}

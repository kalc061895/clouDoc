<?php

namespace Modules\Asistencia\Services;

use Dompdf\Dompdf;
use Dompdf\Options;

class RolPdfRenderer
{
    public function generar(array $documento): string
    {
        $options = new Options();
        $options->set('isRemoteEnabled', false);
        $options->set('isPhpEnabled', false);
        $options->set('defaultFont', 'DejaVu Sans');
        $pdf = new Dompdf($options);
        $pdf->setPaper('A4', 'landscape');
        $pdf->loadHtml(view('Modules\Asistencia\Views\roles\pdf', ['documento' => $documento]), 'UTF-8');
        $pdf->render();
        $canvas = $pdf->getCanvas();
        $font = $pdf->getFontMetrics()->getFont('DejaVu Sans', 'normal');
        $canvas->page_text(28, $canvas->get_height() - 22, 'Documento generado sin firma digital. Estado vigente: consultar el historial de roles.', $font, 7, [0.35, 0.35, 0.35]);
        $canvas->page_text($canvas->get_width() - 125, $canvas->get_height() - 22, 'Página {PAGE_NUM} de {PAGE_COUNT}', $font, 7);
        return $pdf->output();
    }
}

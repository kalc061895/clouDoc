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
        $canvas->page_text(28, $canvas->get_height() - 22, 'Documento electrónico firmado digitalmente en el marco de la Ley N°27269, Ley de Firmas y Certificados Digitales, su Reglamento y modificatorias.La integridad del documento y la autoría de la(s) firma(s) pueden ser verificadas en:https://apps.firmaperu.gob.pe/web/validador.xhtm', $font, 7, [0.35, 0.35, 0.35]);
        $canvas->page_text($canvas->get_width() - 125, $canvas->get_height() - 22, 'Página {PAGE_NUM} de {PAGE_COUNT}', $font, 7);
        return $pdf->output();
    }
}

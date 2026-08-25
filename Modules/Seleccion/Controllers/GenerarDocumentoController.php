<?php

namespace Modules\Seleccion\Controllers;

use App\Controllers\BaseController;
use Modules\Seleccion\Services\GenerarDocumentoService;
use CodeIgniter\HTTP\ResponseInterface;
use Dompdf\Dompdf;
use Dompdf\Options;

class GenerarDocumentoController extends BaseController
{
    protected $docService;

    public function __construct()
    {
        $this->docService = new GenerarDocumentoService();
    }

    public function generarSolicitud($convocatoriaId)
    {
        $data = $this->docService->obtenerDatosSolicitud($convocatoriaId);
        if (empty($data)) {
            return redirect()->back()->with('error', 'Postulante no encontrado.');
        }

        return $this->_renderPdf('Modules\Seleccion\Views\documentos\solicitud_pdf', $data, 'Solicitud_' . $data['documento'] . '.pdf');
    }

    public function generarFichaUnica($convocatoriaId)
    {
        $data = $this->docService->obtenerDatosFichaUnica($convocatoriaId);
        if (empty($data)) {
            return redirect()->back()->with('error', 'Postulante no encontrado.');
        }

        return $this->_renderPdf('Modules\Seleccion\Views\documentos\ficha_unica_pdf', $data, 'FichaUnica_' . $data['postulante']['documento']. '.pdf');
    }

    public function generarFichaAutoevaluacion($convocatoriaId)
    {

        $data = $this->docService->obtenerDatosAutoevaluacion($convocatoriaId);
        if (empty($data['postulante'])) {
            return redirect()->back()->with('error', 'Postulante no encontrado.');
        }

        return $this->_renderPdf('Modules\Seleccion\Views\documentos\autoevaluacion_pdf', $data, 'Autoevaluacion_' . $data['postulante']['documento'] . '.pdf');

    }
    public function generarConstanciaInscripcion($convocatoriaId)
    {

        $data = $this->docService->obtenerDatosInscripcion($convocatoriaId);
        if (empty($data['postulante'])) {
            return redirect()->back()->with('error', 'Postulante no encontrado.');
        }

        return $this->_renderPdf('Modules\Seleccion\Views\documentos\constancia_pdf', $data, 'Autoevaluacion_' . $data['postulante']['pos_documento'] . '.pdf');

    }

    private function _renderPdf(string $viewPath, array $data, string $filename)
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        $html = view($viewPath, $data);
        
        return $html;

        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            // para que se descargue automaticamente
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '.pdf"')
            ->setBody($dompdf->output());
    }
}

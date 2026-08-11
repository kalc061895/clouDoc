<?php

namespace Modules\Seleccion\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Seleccion\Services\ConvocatoriaService;
class AdminController extends BaseController
{
    public function index()
    {
        return view('contratacion/postulaciones/resumen');
    }
    
    public function convocatorias()
    {
        $convocatoriaService = new ConvocatoriaService();
        return $this->response->setJSON(
            $convocatoriaService->convocatoriasPublicadas()
        );
    }

    public function plazas($idConvocatoria)
    {
        $convocatoriaService = new ConvocatoriaService();
        return $this->response->setJSON(
            $convocatoriaService->plazasPorConvocatoria($idConvocatoria)
        );
    }
}

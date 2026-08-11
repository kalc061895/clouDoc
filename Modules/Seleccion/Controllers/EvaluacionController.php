<?php

namespace Modules\Seleccion\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Seleccion\Services\EvaluacionService;
use Modules\Seleccion\Models\CalificacionPreviaModel;
use Modules\Seleccion\Services\CalificacionPreviaService;

class EvaluacionController extends BaseController
{
    protected $service;

    public function __construct()
    {
        $this->service = new EvaluacionService();
    }

    public function index()
    {
        return view('Modules\Seleccion\Views\comision/evaluacion');
    }

    public function convocatorias()
    {
        return $this->response->setJSON(
            $this->service->listarConvocatorias()
        );
    }

    public function postulantes(int $idConvocatoria)
    {
        return $this->response->setJSON(
            $this->service->listarPostulantes($idConvocatoria)
        );
    }

    public function postulacion(int $idPostulacion)
    {
        return view(
            'contratacion/comision/evaluar_postulacion',
            $this->service->verPostulacion($idPostulacion)            
        );
        //
    }
    public function guardar()
    {
        $calificacionPreviaService = new CalificacionPreviaService();
        return $this->response->setJSON(
            $calificacionPreviaService->guardar(
                $this->request->getPost(),
                auth()->id()
            )
        );
    }
    public function resultados()
    {
        return view('Modules\Seleccion\Views\reporte/inicio');
    }
    public function resultadosPreEvaluacion(int $idConvocatoria)
    {
        $calificacionPreviaService = new CalificacionPreviaService();
        return $this->response->setJSON(
            $calificacionPreviaService->resultadosPorConvocatoria($idConvocatoria)
        );
    }
}

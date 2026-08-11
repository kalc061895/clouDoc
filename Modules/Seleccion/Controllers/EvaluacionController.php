<?php

namespace App\Controllers\Contratacion;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\Contratacion\EvaluacionService;
use App\Models\Contratacion\CalificacionPreviaModel;
use App\Services\Contratacion\CalificacionPreviaService;

class EvaluacionController extends BaseController
{
    protected $service;

    public function __construct()
    {
        $this->service = new EvaluacionService();
    }

    public function index()
    {
        return view('contratacion/comision/evaluacion');
    }

    public function convocatorias()
    {
        return $this->response->setJSON(
            $this->service->listarConvocatorias()
        );
    }

    public function postulantes($idConvocatoria)
    {
        return $this->response->setJSON(
            $this->service->listarPostulantes($idConvocatoria)
        );
    }

    public function postulacion($idPostulacion)
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
        return view('contratacion/reporte/inicio');
    }
    public function resultadosPreEvaluacion($idConvocatoria)
    {
        $calificacionPreviaService = new CalificacionPreviaService();
        return $this->response->setJSON(
            $calificacionPreviaService->resultadosPorConvocatoria($idConvocatoria)
        );
    }
}

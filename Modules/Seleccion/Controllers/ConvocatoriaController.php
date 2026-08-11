<?php

namespace App\Controllers\Contratacion;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\Contratacion\ConvocatoriaService;

class ConvocatoriaController extends BaseController
{
     protected $service;

    public function __construct()
    {
        $this->service = new ConvocatoriaService();
    }

    public function index()
    {
        return view('contratacion/convocatorias/inicio');
    }

    public function listar()
    {
        return $this->response->setJSON(
            $this->service->listar()
        );
    }

    public function guardar()
    {
        return $this->response->setJSON(
            $this->service->guardar($this->request->getPost())
        );
    }

    public function editar($id)
    {
        return $this->response->setJSON(
            $this->service->obtener($id)
        );
    }

    public function eliminar($id)
    {
        return $this->response->setJSON(
            $this->service->eliminar($id)
        );
    }

    public function vigentes()
    {
        return view('contratacion/postulante/convocatorias/inicio');
    }

    public function listarVigentes()
    {
        return $this->response->setJSON(
            $this->service->listarVigentes()
        );
    }

}

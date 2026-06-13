<?php

namespace App\Controllers\Contratacion;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\Contratacion\InformacionExtraService;
class InformacionExtraController extends BaseController
{
    protected $service;

    public function __construct()
    {
        $this->service = new InformacionExtraService();
    }
    public function listar()
    {
        return $this->response->setJSON(
            $this->service->listar(auth()->id())
        );
    }

    public function guardar()
    {
        return $this->response->setJSON(
            $this->service->guardar(auth()->id(), $this->request)
        );
    }

    public function eliminar($id)
    {
        return $this->response->setJSON(
            $this->service->eliminar(auth()->id(), $id)
        );
    }
}

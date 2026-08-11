<?php

namespace Modules\Seleccion\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Seleccion\Services\FormacionService;
class FormacionController extends BaseController
{
    protected $service;

    public function __construct()
    {
        $this->service = new FormacionService();
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

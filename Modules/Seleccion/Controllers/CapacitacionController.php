<?php

namespace App\Controllers\Contratacion;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\Contratacion\CapacitacionService;
class CapacitacionController extends BaseController
{
    protected $service;

    public function __construct()
    {
        $this->service = new CapacitacionService();
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
            $this->service->guardar( 
                auth()->id(), 
                $this->request
                )
        );
    }

    public function eliminar($id)
    {
        $this->service->eliminar($id);

        return $this->response->setJSON([
            'status' => 'success'
        ]);
    }
}

<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
use App\Models\OficinaModel;

class APItest extends BaseController
{
    use ResponseTrait;
    
    /**
     * Mostrar todas las oficinas.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $model = new OficinaModel();
        $oficinas = $model->findAll();

        return $this->respond([
            'status' => 'success',
            'data' => $oficinas
        ]);
    }
    
    /**
     * Mostrar una oficina por ID.
     *
     * @param int $id
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        $model = new OficinaModel();
        $oficina = $model->find($id);

        if ($oficina) {
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $oficina
            ]);
        } else {
            return $this->response->setStatusCode(404)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Oficina no encontrada'
                ]);
        }
    }
     public function createUser()
    {
        $data = [
            'status' => 'success',
            'message' => 'User created successfully'
        ];

        return $this->response->setJSON($data);
    }
    
    function test()
    {

        $data = [
            'status' => 'success',
            'message' => 'API is working correctly'
        ];

        return $this->response->setJSON($data);
    }

}

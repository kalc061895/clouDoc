<?php

namespace App\Controllers\Contratacion;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\Contratacion\PostulanteService;

class PostulanteController extends BaseController
{
    protected $postulanteService;

    public function __construct()
    {
        $this->postulanteService = new PostulanteService();
    }
    public function index()
    {
        //
    }

    public function guardarDatos()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Acceso no permitido'
            ]);
        }

        $userId = auth()->id(); // Shield

        $data = [
            'dni'              => $this->request->getPost('dni'),
            'nombres'          => $this->request->getPost('nombres'),
            'paterno'          => $this->request->getPost('paterno'),
            'materno'          => $this->request->getPost('materno'),
            'fecha_nacimiento' => $this->request->getPost('fecha_nacimiento'),
            'direccion'        => $this->request->getPost('direccion'),
            'telefono'         => $this->request->getPost('telefono'),
        ];

        try {
            $postulanteId = $this->postulanteService->guardarDatosPersonales($userId, $data);

            return $this->response->setJSON([
                'status' => 'success',
                'postulante_id' => $postulanteId
            ]);
        } catch (\Exception $e) {

            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function verDatos()
    {

        $userId = auth()->id(); // Shield
        return $this->response->setJSON(
            $this->postulanteService->verDatosPersonales(auth()->id())
        );
    }
    public function verResultado()
    {
        return view('contratacion/postulante/ver_resultado');
    }
}

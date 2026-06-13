<?php

namespace App\Controllers\Contratacion;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Contratacion\PlazaModel;
class PlazaController extends BaseController
{
    protected $plazaModel;

     public function __construct()
    {
        $this->plazaModel = new PlazaModel();
    }

    public function index()
    {
        return view('contratacion/plazas/index');
    }

    public function listar($idConvocatoria)
    {
        return $this->response->setJSON(
            $this->plazaModel->where('id_convocatoria', $idConvocatoria)->findAll()
        );
    }
    public function listarPlazas()
    {
        return $this->response->setJSON(
            $this->plazaModel
            ->select('plazas.*, postulaciones.id_postulacion')
            ->join('postulaciones', 'plazas.id_convocatoria = postulaciones.id_convocatoria', 'left')
            ->join('postulantes', 'postulantes.id_postulante = postulaciones.id_postulante', 'left')
            ->where('postulantes.user_id', auth()->id())
            ->findAll()
        );
    }

    public function guardar()
    {
        $this->plazaModel->save($this->request->getPost());
        return $this->response->setJSON(['status' => 'success']);
    }

    public function editar($id)
    {
        return $this->response->setJSON(
            $this->plazaModel->find($id)
        );
    }

    public function eliminar($id)
    {
        $this->plazaModel->delete($id);
        return $this->response->setJSON(['status' => 'success']);
    }
}

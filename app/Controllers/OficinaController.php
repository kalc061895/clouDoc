<?php

namespace App\Controllers;

use App\Models\OficinaModel;
use CodeIgniter\Controller;

class OficinaController extends Controller
{
    protected $oficinaModel;

    public function __construct()
    {
        $this->oficinaModel = new OficinaModel();
    }

    public function index()
    {
        $data['oficinas'] = $this->oficinaModel->findAll();
        return view('config/oficinas', $data);
    }
    public function listado()
{
    $model = new OficinaModel();
    $oficinas = $model->findAllWithParents();

    $data = [];
    foreach ($oficinas as $o) {
        $data[] = [
            'id'            => $o['id'],
            'nombre'        => $o['nombre'],
            'abreviatura'   => $o['abreviatura'],
            'tipo'          => $o['tipo'],
            'descripcion'   => $o['descripcion'],
            'rango'         => $o['rango'],
            'oficina_padre' => $o['oficina_padre'],
            'oficina_padre_id' => $o['oficina_padre_id'],
            'activo'        => $o['activo'],
        ];
    }

    return $this->response->setJSON(['data' => $data]);
}



    public function guardar()
    {
        $id = $this->request->getPost('idoficina');
        $data = [
            'nombre'         => $this->request->getPost('nombre'),
            'abreviatura'    => $this->request->getPost('abreviatura'),
            'tipo'           => $this->request->getPost('tipo'),
            'descripcion'    => $this->request->getPost('descripcion'),
            'rango'          => $this->request->getPost('rango'),
            'oficina_padre_id' => $this->request->getPost('oficina_padre_id') ?: null,
            'activo'         => $this->request->getPost('activo'),
        ];

        if ($id == 0) {
            $this->oficinaModel->insert($data);
            $data['id'] = $this->oficinaModel->getInsertID();
        } else {
            $this->oficinaModel->update($id, $data);
            $data['id'] = $id;
        }

        return $this->response->setJSON($data);
    }

    public function editar()
    {
        $id = $this->request->getGet('id');
        $oficina = $this->oficinaModel->find($id);
        return $this->response->setJSON($oficina);
    }

    public function eliminar()
    {
        $id = $this->request->getPost('id');
        $this->oficinaModel->delete($id);
        return $this->response->setJSON(['status' => 'ok']);
    }
}

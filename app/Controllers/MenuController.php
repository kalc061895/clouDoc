<?php

namespace App\Controllers;

use App\Models\MenuModel;
use CodeIgniter\Controller;

class MenuController extends Controller
{
    protected $menuModel;

    public function __construct()
    {
        $this->menuModel = new MenuModel();
    }

    // Vista principal
    public function index()
    {
        $data['menus'] = $this->menuModel->findAll();
        return view('config/menus', $data);
    }

    // Listado para DataTables
    public function listado()
    {
        $menus = $this->menuModel->findAllWithParents();

        $data = [];
        foreach ($menus as $m) {
            $data[] = [
                'id'          => $m['id'],
                'name'        => $m['name'],
                'abbr'        => $m['abbr'],
                'type'        => $m['type'],
                'url'         => $m['url'],
                'icon'        => $m['icon'],
                'status'      => $m['status'],
                'order'       => $m['order'],
                'separator'   => $m['separator'],
                'parent_id'   => $m['parent_id'],
                'parent_name' => $m['parent_name'],
            ];
        }

        return $this->response->setJSON(['data' => $data]);
    }

    // Guardar (insertar/actualizar)
    public function guardar()
    {
        $id = $this->request->getPost('idmenu');

        $data = [
            'name'       => $this->request->getPost('name'),
            'abbr'       => $this->request->getPost('abbr'),
            'type'       => $this->request->getPost('type'),
            'url'        => $this->request->getPost('url'),
            'icon'       => $this->request->getPost('icon'),
            'status'     => $this->request->getPost('status'),
            'order'      => $this->request->getPost('order'),
            'separator'  => $this->request->getPost('separator'),
            'parent_id'  => $this->request->getPost('parent_id') ?: null,
        ];

        if ($id == 0) {
            $this->menuModel->insert($data);
            $data['id'] = $this->menuModel->getInsertID();
        } else {
            $this->menuModel->update($id, $data);
            $data['id'] = $id;
        }

        return $this->response->setJSON($data);
    }

    // Obtener datos de un menú
    public function editar()
    {
        $id = $this->request->getGet('id');
        $menu = $this->menuModel->find($id);
        return $this->response->setJSON($menu);
    }

    // Eliminar
    public function eliminar()
    {
        $id = $this->request->getPost('id');
        $this->menuModel->delete($id);
        return $this->response->setJSON(['status' => 'ok']);
    }
}

<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;
use App\Models\MenuModel; // Asegúrate de ajustar el namespace según tu estructura de modelos

class MenuController extends Controller
{
    use ResponseTrait;

    protected $menuModel;

    public function __construct()
    {
        $this->menuModel = new MenuModel(); // Carga el modelo de menús
    }

    public function index()
    {
        $menus = $this->menuModel->findAll(); // Obtener todos los menús
        return $this->respond($menus); // Responder con los menús en formato JSON
    }

    public function show($id)
    {
        $menu = $this->menuModel->find($id); // Obtener un menú por ID
        if ($menu === null) {
            return $this->failNotFound('Menú no encontrado.');
        }
        return $this->respond($menu); // Responder con el menú en formato JSON
    }

    public function create()
    {
        $data = $this->request->getJSON();
        if (empty($data)) {
            return $this->failValidationError('No se proporcionaron datos válidos.');
        }
        $menuId = $this->menuModel->insert($data); // Insertar nuevo menú
        $menu = $this->menuModel->find($menuId);
        return $this->respondCreated($menu); // Responder con el menú creado en formato JSON
    }

    public function update($id)
    {
        $data = $this->request->getJSON();
        if (empty($data)) {
            return $this->failValidationError('No se proporcionaron datos válidos.');
        }
        $existingMenu = $this->menuModel->find($id);
        if ($existingMenu === null) {
            return $this->failNotFound('Menú no encontrado.');
        }
        $this->menuModel->update($id, $data); // Actualizar menú
        $updatedMenu = $this->menuModel->find($id);
        return $this->respondUpdated($updatedMenu); // Responder con el menú actualizado en formato JSON
    }

    public function delete($id)
    {
        $existingMenu = $this->menuModel->find($id);
        if ($existingMenu === null) {
            return $this->failNotFound('Menú no encontrado.');
        }
        $this->menuModel->delete($id); // Eliminar menú
        return $this->respondDeleted(['id' => $id]); // Responder con el ID del menú eliminado en formato JSON
    }
}

<?php

namespace App\Controllers;

use App\Models\MenuGroupUserModel;
use App\Models\MenuModel;
use App\Models\GroupUserModel;

class MenuGroupUserController extends BaseController
{
    protected $menuGroupUserModel;
    protected $menuModel;
    protected $groupUserModel;

    public function __construct()
    {
        $this->menuGroupUserModel = new MenuGroupUserModel();
        $this->menuModel = new MenuModel();
        $this->groupUserModel = new GroupUserModel();
    }

    public function index()
    {
        return view('config/menugroupuser/menu_group_user');
    }

    public function fetchData()
    {
        $request = \Config\Services::request();
        $data = $this->menuGroupUserModel->getDatatables($request);
        echo json_encode($data);
    }

    public function save()
    {
        $data = [
            'menu_id' => $this->request->getPost('menu_id'),
            'group_user_id' => $this->request->getPost('group_user_id'),
        ];
        if ($this->request->getPost('id')) {
            // Update
            $id = $this->request->getPost('id');
            $this->menuGroupUserModel->update($id, $data);
        } else {
            // Insert
            $this->menuGroupUserModel->save($data);
        }
    }

    public function delete($id)
    {
        $this->menuGroupUserModel->delete($id);
    }

    public function getMenus()
    {
        $menus = $this->menuModel->findAll();
        return $this->response->setJSON($menus);
    }

    public function getGroups()
    {
        $groups = $this->groupUserModel->findAll();
        return $this->response->setJSON($groups);
    }
}

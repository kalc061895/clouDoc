<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use CodeIgniter\Controller;
use CodeIgniter\Shield\Entities\User;
use App\Models\OficinaModel;

class UserController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UsuarioModel();
    }

    public function index()
    {
        $oficinaModel = new OficinaModel();
        $data = [
            'users' => $this->userModel->findInfo(),
            'oficina' => $oficinaModel->findAll(),
            'grupousuario' => $this->userModel->getGrupos(),
        ];

        //$data['users'] = $this->userModel->findAll();
        return view('config/users/usuario', $data);
    }
    public function findUser()
    {
        // Get the User Provider (UserModel by default)
        $users = auth()->getProvider();

        // Find by the user_id
        $user = $users->findById($this->request->getPost('id'));
        // Find by the user email
        $user = $users->findByCredentials(['email' => '[email protected]']);
    }

    public function create()
    {
        return view('users/create');
    }

    public function store()
    {
        // Get the User Provider (UserModel by default)
        $users = auth()->getProvider();

        $user = new User([
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'nombres' => $this->request->getPost('nombres'),
            'paterno' => $this->request->getPost('paterno'),
            'materno' => $this->request->getPost('materno'),
            'dni' => $this->request->getPost('dni'),
            'cargo' => $this->request->getPost('cargo'),
            'telefono' => $this->request->getPost('telefono'),
            'oficina_id' => $this->request->getPost('oficina_id'),
            //'person_id' => $this->request->getPost('person_id'),
        ]);

        $users->save($user);

        // To get the complete user object with ID, we need to get from the database
        $user = $users->findById($users->getInsertID());

        // Add to default group
        //$users->addToDefaultGroup($user);
        $users->addGroup($this->request->getPost('group_user'));
    }

    public function edit($id)
    {
        $data['user'] = $this->userModel->find($id);
        return view('users/edit', $data);
    }

    public function update($id)
    {
        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'mother_maiden_name' => $this->request->getPost('mother_maiden_name'),
            'id_number' => $this->request->getPost('id_number'),
            'position' => $this->request->getPost('position'),
            'telephone_number' => $this->request->getPost('telephone_number'),
            'office_id' => $this->request->getPost('office_id'),
        ];

        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $data);
        return redirect()->to('/users');
    }

    public function delete($id)
    {
        $this->userModel->delete($id);
        return redirect()->to('/users');
    }
}

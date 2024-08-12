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

        //print_r($this->request->getPost());
        //return 0;
        // Get the User Provider (UserModel by default)
        $users = new UsuarioModel();

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
            'active' => $this->request->getPost('estado'),
        ]);

        $users->save($user);

        // To get the complete user object with ID, we need to get from the database
        $user = $users->findById($users->getInsertID());
        // Add to default group
        //$users->addToDefaultGroup($user);
        //$users->addGroup($this->request->getPost('group_user'));
        $user->addGroup($this->request->getPost('group_user') ?? 'user');
        $oficina = new OficinaModel();
        $nombre_oficina = $oficina->find($user->oficina_id);
        $data = [
            'id' => $user->id,
            'nombres' => $user->paterno . " " . $user->materno . " " . $user->nombres,
            'cargo' => $user->cargo,
            'active' => $user->active,
            'nombre_oficina' => $nombre_oficina['nombre'],
        ];
        return json_encode($data);
    }

    public function edit()
    {
        $id = $this->request->getGet('id');

        $_user = new UsuarioModel();
        $user = $_user->find($id);
        $grupo = $_user->getGroup($id);

        $data = [
            'user' => $user,
            'id' => $user->id,
            'username' => $user->username,
            'email'    => $user->email,
            'password' => $user->password,
            'nombres' => $user->nombres,
            'paterno' => $user->paterno,
            'materno' => $user->materno,
            'dni' => $user->dni,
            'cargo' => $user->cargo,
            'telefono' => $user->telefono,
            'group_user' => $grupo[0]->group,
            'oficina_id' => $user->oficina_id,
            'estado' => ($user->active) ? 1 : 0,
        ];

        return json_encode($data);
    }

    public function update()
    {
        $users = NEW UsuarioModel();

        $user = $users->findById($this->request->getPost('idusuario'));
        $_password = $this->request->getPost('password');
        $user->fill([
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $_password,
            'nombres' => $this->request->getPost('nombres'),
            'paterno' => $this->request->getPost('paterno'),
            'materno' => $this->request->getPost('materno'),
            'dni' => $this->request->getPost('dni'),
            'cargo' => $this->request->getPost('cargo'),
            'telefono' => $this->request->getPost('telefono'),
            'oficina_id' => $this->request->getPost('oficina_id'),
            'active' => $this->request->getPost('estado'),
        ]);
        $users->save($user);
        $users->actualizarGrupo($user->id,$this->request->getPost('group_user') ?? 'user');
        // To get the complete user object with ID, we need to get from the database
        //$user = $users->findById($users->getUpdateID());
        // Add to default group
        //$users->addToDefaultGroup($user);
        //$users->addGroup($this->request->getPost('group_user'));
        //$user->addGroup($this->request->getPost('group_user') ?? 'user');
        $oficina = new OficinaModel();
        $nombre_oficina = $oficina->find($user->oficina_id);
        $data = [
            'id' => $user->id,
            'nombres' => $user->paterno . " " . $user->materno . " " . $user->nombres,
            'cargo' => $user->cargo,
            'active' => $user->active,
            'nombre_oficina' => $nombre_oficina['nombre'],
        ];
        return json_encode($data);
    }

    public function delete()
    {
        $id = $this->request->getPost('id');
        $users = auth()->getProvider();
        $users->delete($id, true);
        return json_encode($users);
    }
}

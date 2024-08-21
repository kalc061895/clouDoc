<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;
use App\Models\UsuarioModel;
class UserDefaultSeeder extends Seeder
{
    public function run()
    {
        // User data
        $users = [
            [
                'email' => 'admintest@example.com',
                'username' => 'admintest',
                'password' => 'admintest', // This will be hashed by Shield
                'nombres' => 'Admintest',
                'paterno' => 'User',
                'active' => '1',
                'materno' => 'User',
                'oficina_id' => '1',
                'group' => 1,
            ],
            [
                'email' => 'user2@example.com',
                'username' => 'user2',
                'password' => 'password', // This will be hashed by Shield
                'nombres' => 'user2',
                'paterno' => 'User2',
                'materno' => 'User2',
                'oficina_id' => '2',
                'active' => '1',
                'group' => 2,
            ],
            // Add more users as needed
        ];

        foreach ($users as $user) {
            // Create a new user entity
            $_insertUser = new User($user);
            $userEntity = new UsuarioModel();
            $userEntity->save($_insertUser);
            $insertedId = $userEntity->insertID();
            $userEntity->actualizarGrupo($insertedId,$user['group']);
        }
    }
}

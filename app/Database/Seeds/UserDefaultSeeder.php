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
                'email' => 'superadmin@cloudoc.com',
                'username' => 'superadmin',
                'password' => 'super@dmin', // This will be hashed by Shield
                'active' => '1',
                // personales
                'nombres' => 'SuperAdmin',
                'paterno' => 'SAPaterno',
                'materno' => 'SAMaterno',
                'dni' => '00000001',
                'cargo' => 'Super Administrador',
                'telefono' => '00000001',
                'oficina_id' => '1',
                'group' => 'superadmin',
            ],
            [
                'email' => 'admin@cloudoc.com',
                'username' => 'admin',
                'password' => '@dmin', // This will be hashed by Shield
                'active' => '1',
                // personales
                'nombres' => 'Admin',
                'paterno' => 'APaterno',
                'materno' => 'AMaterno',
                'dni' => '00000002',
                'cargo' => 'Administrador',
                'telefono' => '00000002',
                'oficina_id' => '1',
                'group' => 'admin',
            ],
            [
                'email' => 'tramite@cloudoc.com',
                'username' => 'tramite',
                'password' => 'tr@mite', // This will be hashed by Shield
                'active' => '1',
                // personales
                'nombres' => 'tramite',
                'paterno' => 'TPaterno',
                'materno' => 'TMaterno',
                'dni' => '00000003',
                'cargo' => 'tramite',
                'telefono' => '00000003',
                'oficina_id' => '2',
                'group' => 'tramite',
            ],
            [
                'email' => 'oficina@cloudoc.com',
                'username' => 'oficina',
                'password' => 'oficin@', // This will be hashed by Shield
                'active' => '1',
                // personales
                'nombres' => 'Oficina Ejemplo',
                'paterno' => 'OPaterno',
                'materno' => 'OMaterno',
                'dni' => '00000004',
                'cargo' => 'oficina',
                'telefono' => '00000004',
                'oficina_id' => '4',
                'group' => 'oficina',
            ],
            [
                'email' => 'area@cloudoc.com',
                'username' => 'area',
                'password' => '@re@', // This will be hashed by Shield
                'active' => '1',
                // personales
                'nombres' => 'Area Ejemplo',
                'paterno' => 'APaterno',
                'materno' => 'AMaterno',
                'dni' => '00000005',
                'cargo' => 'Area Ejemplo',
                'telefono' => '00000005',
                'oficina_id' => '27',
                'group' => 'area',
            ],
            
        ];

        foreach ($users as $user) {
            // Create a new user entity
            $_insertUser = new User($user);
            $userEntity = new UsuarioModel();
            $userEntity->save($_insertUser);
            $insertedId = $userEntity->getInsertID();
            $userEntity->actualizarGrupo($insertedId,$user['group']);
        }
    }
}

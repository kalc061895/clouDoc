<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GroupUserSeeder extends Seeder
{
    public function run()
    {
        // Datos de los grupos de usuarios
        $data = [
            [
                'name' => 'SuperAdmin',
            ],
            [
                'name' => 'Admin',
            ],
            [
                'name' => 'MesaDePartes',
            ],
            [
                'name' => 'Oficina',
            ],
            [
                'name' => 'Area',
            ],
        ];

        // Insertar los datos en la base de datos
        $this->db->table('group_user')->insertBatch($data);
    }
}

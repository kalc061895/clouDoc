<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuGroupUserSeeder extends Seeder
{
    public function run()
    {
        // Datos de los grupos de usuarios
        $data = [
            [
                'menu_id' => 21,
                'group_user_id' => 3,
            ],
            [
                'menu_id' => 22,
                'group_user_id' => 3,
            ],
            [
                'menu_id' => 23,
                'group_user_id' => 3,
            ],
            [
                'menu_id' => 24,
                'group_user_id' => 3,
            ],
            [
                'menu_id' => 7,
                'group_user_id' => 4,
            ],
            [
                'menu_id' => 8,
                'group_user_id' => 4,
            ],
            [
                'menu_id' => 9,
                'group_user_id' => 4,
            ],
            [
                'menu_id' => 10,
                'group_user_id' => 4,
            ],

        ];

        // Insertar los datos en la base de datos
        $this->db->table('menu_group_user')->insertBatch($data);
    }
}

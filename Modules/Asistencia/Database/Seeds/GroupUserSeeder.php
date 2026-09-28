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
                'id' => 100,
                'name' => 'asistencia',
            ],
            [
                'id' => 101,
                'name' => 'asisdiresa',
            ],
            [
                'id' => 102,
                'name' => 'asisred',
            ],
            [
                'id' => 103,
                'name' => 'asismicrored',
            ],
            [
                'id' => 104,
                'name' => 'asisestablecimiento',
            ],
            [
                'id' => 105,
                'name' => 'asisconsulta',
            ],
            [
                'id' => 106,
                'name' => 'asisauditoria',
            ],
            [
                'id' => 107,
                'name' => 'asiempleado',
            ],
        ];

        // Insertar los datos en la base de datos
        $this->db->table('group_user')->insertBatch($data);
    }
}

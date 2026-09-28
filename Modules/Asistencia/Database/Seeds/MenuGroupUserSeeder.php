<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuGroupUserSeeder extends Seeder
{
    public function run()
    {
        // Datos de los grupos de usuarios
        $data = [];

        // Grupo 1 y 2: Menús 1 al 28
        for ($menu_id = 600; $menu_id <= 663; $menu_id++) {
            $data[] = [
                'group_user_id' => 1,
                'menu_id' => $menu_id,
            ];
            /*
            $data[] = [
                'group_user_id' => 100,
                'menu_id' => $menu_id,
            ];
            */
        }


        // Insertar los datos en la base de datos
        $this->db->table('menu_group_user')->insertBatch($data);
    }
}

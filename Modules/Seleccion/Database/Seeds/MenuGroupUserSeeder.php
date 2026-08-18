<?php

namespace Modules\Seleccion\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuGroupUserSeeder extends Seeder
{
    public function run()
    {
        // Datos de los grupos de usuarios
        $data = [];

        // Grupo 1 y 2: Menús 1 al 28
        for ($menu_id = 1; $menu_id <= 28; $menu_id++) {
            $data[] = [
                'group_user_id' => 1,
                'menu_id' => $menu_id,
            ];
            $data[] = [
                'group_user_id' => 2,
                'menu_id' => $menu_id,
            ];
        }

        // Grupo 3: Menús 1 al 6, 15 al 22
        for ($menu_id = 1; $menu_id <= 6; $menu_id++) {
            $data[] = [
                'group_user_id' => 3,
                'menu_id' => $menu_id,
            ];
        }
        for ($menu_id = 15; $menu_id <= 22; $menu_id++) {
            $data[] = [
                'group_user_id' => 3,
                'menu_id' => $menu_id,
            ];
        }

        // Grupo 4 y 5: Menús 7 al 14, 18, 20, 22
        for ($menu_id = 7; $menu_id <= 14; $menu_id++) {
            $data[] = [
                'group_user_id' => 4,
                'menu_id' => $menu_id,
            ];
            $data[] = [
                'group_user_id' => 5,
                'menu_id' => $menu_id,
            ];
        }

        // Menú 18
        $data[] = [
            'group_user_id' => 4,
            'menu_id' => 18,
        ];
        $data[] = [
            'group_user_id' => 5,
            'menu_id' => 18,
        ];

        // Menú 20
        $data[] = [
            'group_user_id' => 4,
            'menu_id' => 20,
        ];
        $data[] = [
            'group_user_id' => 5,
            'menu_id' => 20,
        ];

        // Menú 22
        $data[] = [
            'group_user_id' => 4,
            'menu_id' => 22,
        ];
        $data[] = [
            'group_user_id' => 5,
            'menu_id' => 22,
        ];

        // =======================================================
        // MÓDULO DE SELECCIÓN Y CONTRATACIÓN (NUEVOS MENÚS)
        // Asignación exclusiva para Superadmin (group_user_id = 1)
        // =======================================================
        $nuevos_menus_seleccion = array_merge(
            [500],                  // Módulo Principal
            range(510, 513),        // Administración
            range(520, 528),        // Portal Postulante
            range(530, 532),        // Comisión Evaluadora
            range(540, 556)         // Configuración Mantenedores (Gestión)
        );

        foreach ($nuevos_menus_seleccion as $menu_id) {
            $data[] = [
                'group_user_id' => 1,
                'menu_id'       => $menu_id,
            ];
        }

        // Insertar los datos en la base de datos
        $this->db->table('menu_group_user')->insertBatch($data);
    }
}

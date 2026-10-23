<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

/** Agrega la opción sin modificar las demás asignaciones de navegación. */
class RemuneracionesMenuSeeder extends Seeder
{
    public function run()
    {
        $menus = array_column(MenuSeeder::menus(), null, 'id');
        $menu = $menus[1034];
        $actual = $this->db->table('menus')->where('id', 1034)->get()->getRowArray();
        if ($actual && $actual['url'] !== $menu['url']) throw new \RuntimeException('El menú 1034 pertenece a otra opción.');
        if (!$this->db->table('menus')->where('id', 1010)->countAllResults()) throw new \RuntimeException('Ejecute primero MenuSeeder de Asistencia.');
        $this->db->transStart();
        if ($actual) $this->db->table('menus')->where('id', 1034)->update($menu);
        else $this->db->table('menus')->insert($menu);
        foreach (['asistencia', 'asi_sua'] as $nombre) {
            $grupo = $this->db->table('group_user')->where('name', $nombre)->get()->getRowArray();
            if (!$grupo) continue;
            foreach ([1010, 1034] as $menuId) {
                $rel = ['group_user_id' => $grupo['id'], 'menu_id' => $menuId];
                if (!$this->db->table('menu_group_user')->where($rel)->countAllResults()) $this->db->table('menu_group_user')->insert($rel);
            }
        }
        $this->db->transComplete();
        if (!$this->db->transStatus()) throw new \RuntimeException('No se pudo agregar el menú de remuneraciones.');
    }
}

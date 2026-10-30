<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

/** Actualiza solo la navegación de gestión de roles y conserva los menús personalizados. */
class GestionRolesMenuSeeder extends Seeder
{
    public function run()
    {
        $menus = array_filter(MenuSeeder::menus(), fn($m) => in_array($m['id'], [1080, 1081, 1082, 1083, 1084, 1085, 1044, 1004], true));
        foreach ($menus as $menu) {
            $actual = $this->db->table('menus')->where('id', $menu['id'])->get()->getRowArray();
            if ($actual && ($actual['url'] !== $menu['url'] || ($menu['url'] === null && $actual['name'] !== $menu['name']))) throw new \RuntimeException('El menú ' . $menu['id'] . ' pertenece a otra opción.');
        }
        $this->db->transStart();
        foreach ($menus as $menu) {
            $q = $this->db->table('menus');
            if ($q->where('id', $menu['id'])->countAllResults()) $q->where('id', $menu['id'])->update($menu);
            else $q->insert($menu);
            foreach (MenuGroupUserSeeder::asignaciones() as $nombre => $ids) {
                if (!in_array($menu['id'], $ids, true)) continue;
                $grupo = $this->db->table('group_user')->where('name', $nombre)->get()->getRowArray();
                if (!$grupo) continue;
                $rel = ['group_user_id' => $grupo['id'], 'menu_id' => $menu['id']];
                if (!$this->db->table('menu_group_user')->where($rel)->countAllResults()) $this->db->table('menu_group_user')->insert($rel);
            }
        }
        // Preservar el acceso de perfiles personalizados a las dos opciones trasladadas.
        $relaciones = $this->db->table('menu_group_user')->whereIn('menu_id', [1004, 1044])->get()->getResultArray();
        foreach ($relaciones as $relacion) {
            $rel = ['group_user_id' => $relacion['group_user_id'], 'menu_id' => 1080];
            if (!$this->db->table('menu_group_user')->where($rel)->countAllResults()) $this->db->table('menu_group_user')->insert($rel);
        }
        $this->db->transComplete();
        if (!$this->db->transStatus()) throw new \RuntimeException('No se pudo actualizar el menú de gestión de roles.');
    }
}

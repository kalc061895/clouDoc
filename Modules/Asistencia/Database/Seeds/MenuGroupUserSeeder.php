<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuGroupUserSeeder extends Seeder
{
    /** Matriz de navegación. Las acciones dentro de una pantalla mantienen su autorización propia. */
    public static function asignaciones(): array
    {
        $menus = array_column(MenuSeeder::menus(), null, 'id');
        $map = [
            'asistencia' => array_keys($menus), // Compatibilidad con perfiles existentes.
            'asi_sua' => array_keys($menus),
            'asi_adm' => [1006, 1001, 1002, 1003, 1004, 1005, 1041, 1042, 1043, 1051, 1018, 1019, 1025, 1032, 1033, 1061],
            'asi_apo' => [1006, 1001, 1003, 1005, 1042, 1051, 1061],
        ];
        foreach ($map as &$ids) {
            foreach ($ids as $id) {
                $parent = $menus[$id]['parent_id'];
                while ($parent !== null) { $ids[] = $parent; $parent = $menus[$parent]['parent_id']; }
            }
            $ids = array_values(array_unique($ids)); sort($ids);
        }
        unset($ids);
        return $map;
    }

    public function run()
    {
        foreach (GroupUserSeeder::GROUPS as $id => $name) {
            $group = $this->db->table('group_user')->where('id', $id)->get()->getRowArray();
            if (!$group || $group['name'] !== $name) throw new \RuntimeException('Ejecute primero GroupUserSeeder de Asistencia.');
        }
        foreach (MenuSeeder::menus() as $menu) {
            $existing = $this->db->table('menus')->where('id', $menu['id'])->get()->getRowArray();
            if (! $existing || $existing['url'] !== $menu['url'] || $existing['name'] !== $menu['name']) {
                throw new \RuntimeException('Ejecute primero MenuSeeder de Asistencia.');
            }
        }
        $this->db->transStart();
        $asignaciones = self::asignaciones();
        $ownedIds = array_column(MenuSeeder::menus(), 'id');
        foreach (GroupUserSeeder::GROUPS as $id => $name) {
            // Sin tocar menús externos al catálogo ni membresías de usuarios.
            $exclude = array_values(array_diff($ownedIds, $asignaciones[$name]));
            if ($exclude) $this->db->table('menu_group_user')->where('group_user_id', $id)->whereIn('menu_id', $exclude)->delete();
            foreach ($asignaciones[$name] as $menuId) {
                $relation = ['group_user_id' => $id, 'menu_id' => $menuId];
                $builder = $this->db->table('menu_group_user');
                if (!$builder->where($relation)->countAllResults()) $builder->insert($relation);
            }
        }
        $this->db->transComplete();
        if ($this->db->transStatus() === false) {
            throw new \RuntimeException('No se pudieron vincular los menús de Asistencia.');
        }
    }
}

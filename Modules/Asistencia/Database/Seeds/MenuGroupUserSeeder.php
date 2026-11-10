<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuGroupUserSeeder extends Seeder
{
    /** Matriz de navegación. Las acciones dentro de una pantalla mantienen su autorización propia. */
    public static function asignaciones(): array
    {
        $menus = array_column(MenuSeeder::menus(), null, 'id');
        $menusTerritoriales = array_values(array_diff(array_keys($menus), [1086, 1094]));
        $map = [
            'asistencia' => array_keys($menus), // Compatibilidad con perfiles existentes.
            'asi_sua' => array_keys($menus),
            'asi_adm' => [1006, 1001, 1002, 1003, 1088, 1004, 1005, 1041, 1042, 1043, 1044, 1083, 1084, 1085, 1051, 1052, 1053, 1055, 1056, 1018, 1019, 1025, 1032, 1033, 1061, 1072, 1091, 1092, 1093, 1094],
            'asi_apo' => [1006, 1001, 1003, 1005, 1042, 1051, 1061],
            'asi_dm' => array_keys($menus),
            'asi_apoyo' => [1006, 1001, 1003, 1005, 1042, 1051, 1061],
            'asi_diresa' => array_values(array_unique(array_merge($menusTerritoriales, [1089]))),
            'asi_red' => array_values(array_unique(array_merge($menusTerritoriales, [1089]))),
            'asi_mred' => array_values(array_unique(array_merge($menusTerritoriales, [1089]))),
            'asi_est' => array_values(array_unique(array_merge($menusTerritoriales, [1089]))),
            'asi_diresa_apoyo' => [1006, 1001, 1002, 1003, 1004, 1005, 1041, 1042, 1043, 1044, 1083, 1084, 1085, 1051, 1053, 1061, 1091, 1092, 1089],
            'asi_red_apoyo' => [1006, 1001, 1002, 1003, 1004, 1005, 1041, 1042, 1043, 1044, 1083, 1084, 1085, 1051, 1053, 1061, 1091, 1092, 1089],
            'asi_mred_apoyo' => [1006, 1001, 1002, 1003, 1004, 1005, 1041, 1042, 1043, 1044, 1083, 1084, 1085, 1051, 1053, 1061, 1091, 1092, 1089],
            'asi_est_apoyo' => [1006, 1001, 1002, 1003, 1004, 1005, 1041, 1042, 1043, 1044, 1083, 1084, 1085, 1051, 1053, 1061, 1091, 1092, 1089],
            'asi_diresa_registro' => [1006, 1001, 1002, 1003, 1051, 1061],
            'asi_red_registro' => [1006, 1001, 1002, 1003, 1051, 1061],
            'asi_mred_registro' => [1006, 1001, 1002, 1003, 1051, 1061],
            'asi_est_registro' => [1006, 1001, 1002, 1003, 1051, 1061],
            'asi_diresa_consulta' => [1006, 1001, 1003, 1042, 1051, 1061],
            'asi_red_consulta' => [1006, 1001, 1003, 1042, 1051, 1061],
            'asi_mred_consulta' => [1006, 1001, 1003, 1042, 1051, 1061],
            'asi_est_consulta' => [1006, 1001, 1003, 1042, 1051, 1061],
            'asi_auditoria' => [1051, 1094],
            'asi_rol' => [1041, 1042, 1044, 1004, 1083, 1089],
            'asi_dire' => [1083],
            'asi_rrhh' => [1083],
        ];
        $map['asistencia'] = array_values(array_diff(array_keys($menus), [1086, 1087])); // Perfil institucional de compatibilidad.
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

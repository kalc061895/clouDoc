<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuGroupUserSeeder extends Seeder
{
    public function run()
    {
        $group = $this->db->table('group_user')->where('id', GroupUserSeeder::GROUP_ID)->get()->getRowArray();
        if (! $group || $group['name'] !== 'asistencia') {
            throw new \RuntimeException('Ejecute primero GroupUserSeeder de Asistencia.');
        }
        foreach (MenuSeeder::menus() as $menu) {
            $existing = $this->db->table('menus')->where('id', $menu['id'])->get()->getRowArray();
            if (! $existing || $existing['url'] !== $menu['url'] || $existing['name'] !== $menu['name']) {
                throw new \RuntimeException('Ejecute primero MenuSeeder de Asistencia.');
            }
        }
        $this->db->transStart();
        foreach (MenuSeeder::menus() as $menu) {
            $relation = ['group_user_id' => GroupUserSeeder::GROUP_ID, 'menu_id' => $menu['id']];
            $builder = $this->db->table('menu_group_user');
            if (! $builder->where($relation)->countAllResults()) {
                $builder->insert($relation);
            }
        }
        $this->db->transComplete();
        if ($this->db->transStatus() === false) {
            throw new \RuntimeException('No se pudieron vincular los menús de Asistencia.');
        }
    }
}

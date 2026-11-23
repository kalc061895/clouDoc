<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;
use Modules\Asistencia\Database\Seeds\MenuSeeder;

class AgruparCatalogosAsistencia extends Migration
{
    public function up()
    {
        $menus = array_column(MenuSeeder::menus(), null, 'id');
        foreach (MenuSeeder::GRUPOS_CATALOGOS as $id) {
            $actual = $this->db->table('menus')->where('id', $id)->get()->getRowArray();
            if ($actual && ($actual['url'] !== null || $actual['name'] !== $menus[$id]['name'] || (int) $actual['parent_id'] !== 1010)) {
                throw new \RuntimeException('El identificador ' . $id . ' ya pertenece a otro menú.');
            }
        }

        $this->db->transStart();
        foreach (MenuSeeder::GRUPOS_CATALOGOS as $id) {
            if (!$this->db->table('menus')->where('id', $id)->countAllResults()) {
                $this->db->table('menus')->insert($menus[$id]);
            }
            $hijos = array_keys(array_filter($menus, static fn(array $menu): bool => $menu['parent_id'] === $id));
            $this->db->table('menus')->whereIn('id', $hijos)->where('parent_id', 1010)->update(['parent_id' => $id]);
            $grupos = $this->db->table('menu_group_user permisos')
                ->select('permisos.group_user_id')->distinct()
                ->join('menus m', 'm.id = permisos.menu_id')
                ->whereIn('m.id', $hijos)->where('m.parent_id', $id)->where('m.status', 'active')
                ->get()->getResultArray();
            foreach ($grupos as $grupo) {
                $relacion = ['group_user_id' => $grupo['group_user_id'], 'menu_id' => $id];
                if (!$this->db->table('menu_group_user')->where($relacion)->countAllResults()) {
                    $this->db->table('menu_group_user')->insert($relacion);
                }
            }
        }
        $this->db->transComplete();
        if (!$this->db->transStatus()) throw new \RuntimeException('No se pudieron agrupar los catálogos.');
    }

    public function down()
    {
        $grupos = MenuSeeder::GRUPOS_CATALOGOS;
        $this->db->transStart();
        $this->db->table('menus')->whereIn('parent_id', $grupos)->update(['parent_id' => 1010]);
        $this->db->table('menu_group_user')->whereIn('menu_id', $grupos)->delete();
        $this->db->table('menus')->whereIn('id', $grupos)->delete();
        $this->db->transComplete();
    }
}

<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;
use Modules\Asistencia\Database\Seeds\MenuSeeder;

class AddBandejasRevisionRol extends Migration
{
    public function up()
    {
        $menus = array_filter(MenuSeeder::menus(), static fn ($m) => in_array($m['id'], [1098, 1099], true));
        foreach ($menus as $menu) {
            $actual = $this->db->table('menus')->where('id', $menu['id'])->get()->getRowArray();
            if ($actual && $actual['url'] !== $menu['url']) throw new \RuntimeException('El identificador del menú ya está ocupado.');
        }
        foreach ($menus as $menu) {
            if (!$this->db->table('menus')->where('id', $menu['id'])->countAllResults()) $this->db->table('menus')->insert($menu);
            $perfiles = ['asistencia', 'asi_sua', 'asi_adm', 'asi_dm', $menu['id'] === 1098 ? 'asi_rrhh' : 'asi_dire'];
            foreach ($this->db->table('group_user')->whereIn('name', $perfiles)->get()->getResultArray() as $grupo) {
                foreach ([1080, $menu['id']] as $menuId) {
                    $rel = ['group_user_id' => $grupo['id'], 'menu_id' => $menuId];
                    if (!$this->db->table('menu_group_user')->where($rel)->countAllResults()) $this->db->table('menu_group_user')->insert($rel);
                }
            }
        }
    }

    public function down()
    {
        foreach ([1098 => 'recursos-humanos', 1099 => 'direccion'] as $id => $ruta) {
            if (!$this->db->table('menus')->where('id', $id)->where('url', 'asistencia/gestion-roles/' . $ruta)->countAllResults()) continue;
            $this->db->table('menu_group_user')->where('menu_id', $id)->delete();
            $this->db->table('menus')->where('id', $id)->delete();
        }
    }
}

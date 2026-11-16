<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;
use Modules\Asistencia\Database\Seeds\MenuSeeder;

class AddUsuariosAsistenciaMenu extends Migration
{
    public function up()
    {
        $menu = array_column(MenuSeeder::menus(), null, 'id')[1100];
        $actual = $this->db->table('menus')->where('id', 1100)->get()->getRowArray();
        if ($actual && $actual['url'] !== $menu['url']) throw new \RuntimeException('El menú 1100 ya está ocupado.');
        if (!$actual) $this->db->table('menus')->insert($menu);
        foreach ($this->db->table('group_user')->whereIn('name', ['asistencia','asi_sua','asi_adm','asi_dm'])->get()->getResultArray() as $grupo) {
            foreach ([1000, 1100] as $menuId) {
                $rel = ['group_user_id'=>$grupo['id'], 'menu_id'=>$menuId];
                if (!$this->db->table('menu_group_user')->where($rel)->countAllResults()) $this->db->table('menu_group_user')->insert($rel);
            }
        }
    }

    public function down()
    {
        if ($this->db->table('menus')->where('id',1100)->where('url','asistencia/usuarios')->countAllResults()) {
            $this->db->table('menu_group_user')->where('menu_id',1100)->delete();
            $this->db->table('menus')->where('id',1100)->delete();
        }
    }
}

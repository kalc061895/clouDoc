<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

/** Gives the asistencia profile a direct menu entry for the UPSS-office CRUD. */
class GrantTuaSusaludCrudToAsistencia extends Migration
{
    public function up()
    {
        $group=$this->db->table('group_user')->where('name','asistencia')->get()->getRowArray();
        $menu=$this->db->table('menus')->where('id',1095)->where('url','asistencia/reportes/tua-susalud/asignaciones')->get()->getRowArray();
        if(!$group||!$menu)return;
        $relation=['group_user_id'=>(int)$group['id'],'menu_id'=>1095];
        if(!$this->db->table('menu_group_user')->where($relation)->countAllResults())$this->db->table('menu_group_user')->insert($relation);
    }

    public function down()
    {
        // The menu remains available through the main TUA SUSALUD migration/seeder.
    }
}

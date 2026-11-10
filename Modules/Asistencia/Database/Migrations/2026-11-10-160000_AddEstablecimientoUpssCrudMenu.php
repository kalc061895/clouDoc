<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEstablecimientoUpssCrudMenu extends Migration
{
    private const MENU = [
        'id'=>1096,'parent_id'=>1010,'type'=>'secondary','name'=>'UPSS por establecimiento',
        'url'=>'asistencia/gestordb/establecimiento-upss','icon'=>'hospital-line-duotone',
        'status'=>'active','order'=>1036,'separator'=>null,
    ];

    public function up()
    {
        $actual=$this->db->table('menus')->where('id',1096)->get()->getRowArray();
        if($actual && (($actual['url']??null)!==self::MENU['url']||($actual['name']??null)!==self::MENU['name']))throw new \RuntimeException('El menú 1096 ya pertenece a otra opción.');
        if(!$this->db->table('menus')->where('id',1010)->countAllResults())throw new \RuntimeException('No existe el menú padre de catálogos de Asistencia.');
        if($actual)$this->db->table('menus')->where('id',1096)->update(self::MENU);else $this->db->table('menus')->insert(self::MENU);
        foreach(['asistencia','asi_sua','asi_adm','asi_dm','asi_diresa','asi_diresa_apoyo','asi_diresa_registro','asi_diresa_consulta','asi_red','asi_red_apoyo','asi_red_registro','asi_red_consulta','asi_mred','asi_mred_apoyo','asi_mred_registro','asi_mred_consulta','asi_est','asi_est_apoyo','asi_est_registro','asi_est_consulta'] as $name){
            $group=$this->db->table('group_user')->where('name',$name)->get()->getRowArray();if(!$group)continue;
            $rel=['group_user_id'=>(int)$group['id'],'menu_id'=>1096];
            if(!$this->db->table('menu_group_user')->where($rel)->countAllResults())$this->db->table('menu_group_user')->insert($rel);
        }
    }

    public function down()
    {
        $this->db->table('menu_group_user')->where('menu_id',1096)->delete();
        $this->db->table('menus')->where('id',1096)->where('url',self::MENU['url'])->delete();
    }
}

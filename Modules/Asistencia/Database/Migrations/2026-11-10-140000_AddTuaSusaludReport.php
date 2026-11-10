<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTuaSusaludReport extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('casis_tua_servicio_oficina')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'oficina_id' => ['type' => 'INT', 'unsigned' => true],
                'servicio_id' => ['type' => 'INT', 'unsigned' => true],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
                'created_by' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
                'updated_by' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey('oficina_id', 'uq_tua_servicio_oficina');
            $this->forge->addKey('servicio_id');
            $this->forge->addForeignKey('oficina_id', 'casis_oficina', 'ofi_ide', 'CASCADE', 'CASCADE');
            $this->forge->addForeignKey('servicio_id', 'casis_upss_servicio', 'uss_ide', 'CASCADE', 'CASCADE');
            $this->forge->createTable('casis_tua_servicio_oficina');
        }

        $menus = [
            1054 => [1050, 'TUA SUSALUD', null, 'document-text-line-duotone', 1028],
            1055 => [1054, 'Reporte TUA SUSALUD', 'asistencia/reportes/tua-susalud', 'document-text-line-duotone', 1029],
            1095 => [1054, 'Asignación de servicios por oficina', 'asistencia/reportes/tua-susalud/asignaciones', 'settings-line-duotone', 1030],
        ];
        foreach ($menus as $id => [$parent, $name, $url, $icon, $order]) {
            $actual = $this->db->table('menus')->where('id', $id)->get()->getRowArray();
            if ($actual && $id === 1095 && (($actual['url'] ?? null) !== $url || ($actual['name'] ?? null) !== $name)) {
                throw new \RuntimeException('El menú ' . $id . ' ya pertenece a otra opción.');
            }
        }
        $this->db->transBegin();
        try {
            foreach ($menus as $id => [$parent, $name, $url, $icon, $order]) {
                $menu = ['id'=>$id,'parent_id'=>$parent,'type'=>'secondary','name'=>$name,'url'=>$url,'icon'=>$icon,'status'=>'active','order'=>$order,'separator'=>null];
                if ($this->db->table('menus')->where('id', $id)->countAllResults()) $this->db->table('menus')->where('id', $id)->update($menu);
                else $this->db->table('menus')->insert($menu);
            }
            foreach (['asistencia','asi_sua','asi_adm','asi_dm','asi_diresa','asi_red','asi_mred','asi_est'] as $name) {
                $group = $this->db->table('group_user')->where('name',$name)->get()->getRowArray();
                if (!$group) continue;
                $menuIds = in_array($name,['asistencia','asi_sua','asi_dm','asi_diresa','asi_red','asi_mred','asi_est'],true) ? [1054,1055,1095] : [1054,1055];
                foreach ($menuIds as $menuId) {
                    $relation = ['group_user_id'=>(int)$group['id'],'menu_id'=>$menuId];
                    if (!$this->db->table('menu_group_user')->where($relation)->countAllResults()) $this->db->table('menu_group_user')->insert($relation);
                }
            }
            $admin = $this->db->table('group_user')->where('name','asi_adm')->get()->getRowArray();
            if ($admin) {
                $relation = ['group_user_id'=>(int)$admin['id'],'menu_id'=>1095];
                if (!$this->db->table('menu_group_user')->where($relation)->countAllResults()) $this->db->table('menu_group_user')->insert($relation);
            }
            $this->db->transCommit();
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    public function down()
    {
        $this->db->table('menu_group_user')->whereIn('menu_id', [1054,1055,1095])->delete();
        $this->db->table('menus')->where('id', 1095)->where('url', 'asistencia/reportes/tua-susalud/asignaciones')->delete();
        $this->db->table('menus')->where('id', 1055)->update(['name'=>'Reporte TUASusalud','url'=>null,'status'=>'inactive']);
        $this->db->table('menus')->where('id', 1054)->update(['name'=>'SUSALUD','status'=>'inactive']);
        $this->forge->dropTable('casis_tua_servicio_oficina', true);
    }
}

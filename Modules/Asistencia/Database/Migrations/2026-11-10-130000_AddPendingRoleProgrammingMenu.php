<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

/** Agrega el menú de seguimiento de personal sin turnos programados. */
class AddPendingRoleProgrammingMenu extends Migration
{
    public function up()
    {
        $menu = [
            'id' => 1089,
            'parent_id' => 1080,
            'type' => 'secondary',
            'name' => 'Programacion pendiente',
            'url' => 'asistencia/gestion-roles/pendientes',
            'icon' => 'list-check-line-duotone',
            'status' => 'active',
            'order' => 1027,
            'separator' => null,
        ];
        $actual = $this->db->table('menus')->where('id', 1089)->get()->getRowArray();
        if ($actual && ($actual['url'] !== $menu['url'] || $actual['name'] !== $menu['name'])) {
            throw new \RuntimeException('El menu 1089 ya pertenece a otra opcion.');
        }
        if (!$actual) $this->db->table('menus')->insert($menu);

        $perfiles = [
            'asistencia', 'asi_sua', 'asi_adm', 'asi_dm', 'asi_rol',
            'asi_diresa', 'asi_red', 'asi_mred', 'asi_est',
            'asi_diresa_apoyo', 'asi_red_apoyo', 'asi_mred_apoyo', 'asi_est_apoyo',
        ];
        foreach ($perfiles as $nombre) {
            $grupo = $this->db->table('group_user')->where('name', $nombre)->get()->getRowArray();
            if (!$grupo) continue;
            $relacion = ['group_user_id' => (int) $grupo['id'], 'menu_id' => 1089];
            if (!$this->db->table('menu_group_user')->where($relacion)->countAllResults()) {
                $this->db->table('menu_group_user')->insert($relacion);
            }
        }
    }

    public function down()
    {
        $this->db->table('menu_group_user')->where('menu_id', 1089)->delete();
        $this->db->table('menus')->where('id', 1089)->where('url', 'asistencia/gestion-roles/pendientes')->delete();
    }
}

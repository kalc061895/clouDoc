<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

/** Registra los perfiles dedicados y sus menús del flujo de roles. */
class AddRoleWorkflowProfiles extends Migration
{
    private const GROUPS = [
        1501 => 'asi_rol',
        1502 => 'asi_dire',
        1503 => 'asi_rrhh',
    ];

    /** Menús mínimos necesarios, incluidos sus separadores padre. */
    private const MENUS = [
        1040 => [null, 'ROLES', null, 'document-text-line-duotone', 1014],
        1080 => [null, 'GESTIÓN DE ROLES', null, 'calendar-mark-line-duotone', 1018],
        1041 => [1040, 'Generación de roles', 'asistencia/roles/generacion', 'calendar-line-duotone', 1015],
        1042 => [1040, 'Historial de roles', 'asistencia/roles/historial', 'history-line-duotone', 1016],
        1044 => [1080, 'Roles por oficina', 'asistencia/gestion-roles', 'document-text-line-duotone', 1019],
        1004 => [1080, 'Programación de turnos', 'asistencia/programacion', 'calendar-line-duotone', 1020],
        1083 => [1080, 'Envíos y aprobaciones', 'asistencia/gestion-roles/envios', 'document-text-line-duotone', 1023],
    ];

    private const ACCESS = [
        1501 => [1040, 1041, 1042, 1080, 1044, 1004, 1083],
        1502 => [1080, 1083],
        1503 => [1080, 1083],
    ];

    public function up()
    {
        foreach (self::GROUPS as $id => $name) {
            $byId = $this->db->table('group_user')->where('id', $id)->get()->getRowArray();
            $byName = $this->db->table('group_user')->where('name', $name)->get()->getRowArray();
            if (($byId && $byId['name'] !== $name) || ($byName && (int) $byName['id'] !== $id)) {
                throw new \RuntimeException('Conflicto al registrar el perfil ' . $name . '.');
            }
        }

        foreach (self::MENUS as $id => [$parent, $name, $url, $icon, $order]) {
            $existing = $this->db->table('menus')->where('id', $id)->get()->getRowArray();
            if ($existing && (($existing['url'] ?? null) !== $url || $existing['name'] !== $name)) {
                throw new \RuntimeException('El menú ' . $id . ' ya pertenece a otra opción.');
            }
        }

        $this->db->transBegin();
        try {
            foreach (self::GROUPS as $id => $name) {
                if (!$this->db->table('group_user')->where('id', $id)->countAllResults()) {
                    $this->db->table('group_user')->insert(['id' => $id, 'name' => $name]);
                }
            }

            foreach (self::MENUS as $id => [$parent, $name, $url, $icon, $order]) {
                $menu = [
                    'id' => $id,
                    'parent_id' => $parent,
                    'type' => $parent === null ? 'separator' : 'secondary',
                    'name' => $name,
                    'url' => $url,
                    'icon' => $icon,
                    'status' => 'active',
                    'order' => $order,
                    'separator' => $parent === null ? $name : null,
                ];
                if ($this->db->table('menus')->where('id', $id)->countAllResults()) {
                    $this->db->table('menus')->where('id', $id)->update($menu);
                } else {
                    $this->db->table('menus')->insert($menu);
                }
            }

            foreach (self::ACCESS as $groupId => $menuIds) {
                foreach ($menuIds as $menuId) {
                    $relation = ['group_user_id' => $groupId, 'menu_id' => $menuId];
                    if (!$this->db->table('menu_group_user')->where($relation)->countAllResults()) {
                        $this->db->table('menu_group_user')->insert($relation);
                    }
                }
            }
            $this->db->transCommit();
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    public function down()
    {
        foreach (self::GROUPS as $id => $name) {
            $this->db->table('menu_group_user')->where('group_user_id', $id)->whereIn('menu_id', array_merge(...array_values(self::ACCESS)))->delete();
            // Keep the profile row if an account has already been assigned to it.
            if ($this->db->tableExists('auth_groups_users') && $this->db->table('auth_groups_users')->where('group', $name)->countAllResults()) {
                continue;
            }
            $this->db->table('group_user')->where('id', $id)->where('name', $name)->delete();
        }
    }
}

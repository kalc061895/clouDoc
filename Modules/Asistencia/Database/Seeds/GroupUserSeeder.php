<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GroupUserSeeder extends Seeder
{
    public const GROUP_ID = 1000;
    public const GROUPS = [
        // Asistencia SuperAdmin
        1000 => 'asistencia',
        1001 => 'asi_sua',
        // Asistencia Administrador
        1002 => 'asi_adm',
        1003 => 'asi_apo',
        1004 => 'asi_dm',
        1005 => 'asi_apoyo',
        // Asistencia Diresa
        1100 => 'asi_diresa',
        1101 => 'asi_diresa_apoyo',
        1102 => 'asi_diresa_registro',
        1103 => 'asi_diresa_consulta',
        // Asistencia Red
        1200 => 'asi_red',
        1201 => 'asi_red_apoyo',
        1202 => 'asi_red_registro',
        1203 => 'asi_red_consulta',
        // Asistencia MicroRed
        1300 => 'asi_mred',
        1301 => 'asi_mred_apoyo',
        1302 => 'asi_mred_registro',
        1303 => 'asi_mred_consulta',
        // Asistencia Establecimiento
        1400 => 'asi_est',
        1401 => 'asi_est_apoyo',
        1402 => 'asi_est_registro',
        1403 => 'asi_est_consulta',
        // Auditoria Asistencia
        1500 => 'asi_auditoria',
    ];

    public function run()
    {
        // Comprobar todas las colisiones antes de crear grupos. No reasignar usuarios.
        foreach (self::GROUPS as $id => $name) {
            $builder = $this->db->table('group_user');
            $existing = $builder->where('id', $id)->get()->getRowArray();
            if ($existing && $existing['name'] !== $name) throw new \RuntimeException('El grupo ' . $id . ' ya pertenece a otro perfil.');
            if ($builder->where('name', $name)->where('id !=', $id)->countAllResults()) throw new \RuntimeException('Existe ' . $name . ' con otro ID. Revise sus relaciones antes de ejecutar el seeder.');
        }
        $this->db->transStart();
        foreach (self::GROUPS as $id => $name) {
            if (!$this->db->table('group_user')->where('id', $id)->countAllResults()) {
                $this->db->table('group_user')->insert(['id' => $id, 'name' => $name]);
            }
        }
        $this->db->transComplete();
        if (!$this->db->transStatus()) throw new \RuntimeException('No se pudieron crear los perfiles de Asistencia.');
    }
}

<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GroupUserSeeder extends Seeder
{
    public const GROUP_ID = 1000;
    public const GROUPS = [1000 => 'asistencia', 1001 => 'asi_sua', 1002 => 'asi_adm', 1003 => 'asi_apo'];

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

<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GroupUserSeeder extends Seeder
{
    public const GROUP_ID = 1000;

    public function run()
    {
        $builder = $this->db->table('group_user');
        $existing = $builder->where('id', self::GROUP_ID)->get()->getRowArray();
        if ($existing && $existing['name'] !== 'asistencia') {
            throw new \RuntimeException('El grupo 1000 ya pertenece a otro módulo.');
        }
        if ($builder->where('name', 'asistencia')->where('id !=', self::GROUP_ID)->countAllResults()) {
            throw new \RuntimeException('Existe asistencia con otro ID. Migre sus usuarios antes de usar el ID 1000.');
        }
        if (! $existing) {
            $builder->insert(['id' => self::GROUP_ID, 'name' => 'asistencia']);
        }
    }
}

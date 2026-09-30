<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

/** Carga solo navegación, sin ejecutar los catálogos de negocio. */
class NavigationSeeder extends Seeder
{
    public function run()
    {
        $this->db->transStart();
        try {
            $this->call(GroupUserSeeder::class);
            $this->call(MenuSeeder::class);
            $this->call(MenuGroupUserSeeder::class);
            $this->db->transComplete();
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
        if ($this->db->transStatus() === false) {
            throw new \RuntimeException('No se pudo completar la navegación de Asistencia.');
        }
    }
}

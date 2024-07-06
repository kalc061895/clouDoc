<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DiasSeeder extends Seeder
{
    public function run()
    {
        $dias = [
            ['nombre' => 'Lunes', 'abreviatura' => 'Lun', 'numero' => 1],
            ['nombre' => 'Martes', 'abreviatura' => 'Mar', 'numero' => 2],
            ['nombre' => 'Miércoles', 'abreviatura' => 'Mié', 'numero' => 3],
            ['nombre' => 'Jueves', 'abreviatura' => 'Jue', 'numero' => 4],
            ['nombre' => 'Viernes', 'abreviatura' => 'Vie', 'numero' => 5],
            ['nombre' => 'Sábado', 'abreviatura' => 'Sáb', 'numero' => 6],
            ['nombre' => 'Domingo', 'abreviatura' => 'Dom', 'numero' => 7],
        ];

        $this->db->table('dias')->insertBatch($dias);
    }
}

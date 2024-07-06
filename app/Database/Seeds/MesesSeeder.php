<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MesesSeeder extends Seeder
{
    public function run()
    {
        $meses = [
            ['nombre' => 'Enero', 'abreviatura' => 'Ene', 'numero' => 1],
            ['nombre' => 'Febrero', 'abreviatura' => 'Feb', 'numero' => 2],
            ['nombre' => 'Marzo', 'abreviatura' => 'Mar', 'numero' => 3],
            ['nombre' => 'Abril', 'abreviatura' => 'Abr', 'numero' => 4],
            ['nombre' => 'Mayo', 'abreviatura' => 'May', 'numero' => 5],
            ['nombre' => 'Junio', 'abreviatura' => 'Jun', 'numero' => 6],
            ['nombre' => 'Julio', 'abreviatura' => 'Jul', 'numero' => 7],
            ['nombre' => 'Agosto', 'abreviatura' => 'Ago', 'numero' => 8],
            ['nombre' => 'Septiembre', 'abreviatura' => 'Sep', 'numero' => 9],
            ['nombre' => 'Octubre', 'abreviatura' => 'Oct', 'numero' => 10],
            ['nombre' => 'Noviembre', 'abreviatura' => 'Nov', 'numero' => 11],
            ['nombre' => 'Diciembre', 'abreviatura' => 'Dic', 'numero' => 12],
        ];

        $this->db->table('meses')->insertBatch($meses);
    }
}

<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AniosSeeder extends Seeder
{
    public function run()
    {

        // Obtiene el año actual de forma dinámica
        $anioActual = (int) date('Y');
        $data = [];

        for ($year = 2000; $year <= $anioActual; $year++) {
            $data[] = [
                'nombre'      => (string) $year,
                'abreviatura' => (string) $year,
                'numero'      => $year,
            ];
        }

        // Utiliza insertBatch para insertar de golpe todo el array generado
        if (!empty($data)) {
            $this->db->table('anios')->insertBatch($data);
        }
    }
}

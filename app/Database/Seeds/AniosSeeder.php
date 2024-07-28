<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AniosSeeder extends Seeder
{
    public function run()
    {
        $data = [];
        for ($year = 2023; $year <= 2024; $year++) {
            $data[] = [
                'nombre'      => $year,
                'abreviatura' => (string) $year,
                'numero'      => $year,
            ];
        }
        
        $this->db->table('anios')->insertBatch($data);
    }
}

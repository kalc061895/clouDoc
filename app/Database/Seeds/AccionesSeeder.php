<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AccionesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nombre' => 'APROBACIÓN'],
            ['nombre' => 'ATENCIÓN'],
            ['nombre' => 'SU CONOCIMIENTO'],
            ['nombre' => 'OPINIÓN'],
            ['nombre' => 'INFORME'],
            ['nombre' => 'POR CORRESPONDERLE'],
            ['nombre' => 'PARA CONVERSAR'],
            ['nombre' => 'ACOMPAÑAR ANTECEDENTES'],
            ['nombre' => 'SEGÚN SOLICITADO'],
            ['nombre' => 'TOMAR NOTA / DEVOLVER'],
            ['nombre' => 'LEGAJO'],
            ['nombre' => 'ARCHIVAR'],
            ['nombre' => 'ACCIÓN INMEDIATA'],
            ['nombre' => 'PREPARAR CONTESTACIÓN'],
            ['nombre' => 'PROYECTAR RESOLUCIÓN'],
            ['nombre' => 'VER OBSERVACIONES'],
            ['nombre' => 'CONSTANCIA DE PAGO'],
        ];

        // Using Query Builder
        $this->db->table('acciones')->insertBatch($data);
    }
}

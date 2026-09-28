<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ModalidadContratoSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['mco_ide' => 1, 'mco_nombre' => 'DECRETO LEGISLATIVO 276 (NOMBRADO)', 'mco_estado' => 1],
            ['mco_ide' => 2, 'mco_nombre' => 'DECRETO LEGISLATIVO 276 (CONTRATADO)', 'mco_estado' => 1],
            ['mco_ide' => 3, 'mco_nombre' => 'DECRETO LEGISLATIVO 1057 (CAS)', 'mco_estado' => 1],
            ['mco_ide' => 4, 'mco_nombre' => 'LOCACION DE SERVICIOS (TERCEROS)', 'mco_estado' => 1],
        ];

        $this->db->table('casis_modalidad_contrato')->insertBatch($data);
    }
}

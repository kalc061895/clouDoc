<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProfesionSeeder extends Seeder
{
    public function run()
    {
        $data = [

            [
                'pro_nombre' => 'MEDICO',
                'pro_abreviatura' => 'MED',
            ],

            [
                'pro_nombre' => 'BIOLOGO',
                'pro_abreviatura' => 'BIO',
            ],

            [
                'pro_nombre' => 'ENFERMERO',
                'pro_abreviatura' => 'ENF',
            ],

            [
                'pro_nombre' => 'OBSTETRA',
                'pro_abreviatura' => 'OBS',
            ],

            [
                'pro_nombre' => 'ODONTOLOGO',
                'pro_abreviatura' => 'ODO',
            ],

            [
                'pro_nombre' => 'PSICOLOGO',
                'pro_abreviatura' => 'PSI',
            ],

            [
                'pro_nombre' => 'NUTRICIONISTA',
                'pro_abreviatura' => 'NUT',
            ],

            [
                'pro_nombre' => 'QUIMICO FARMACEUTICO',
                'pro_abreviatura' => 'QF',
            ],

            [
                'pro_nombre' => 'TECNOLOGO MEDICO',
                'pro_abreviatura' => 'TM',
            ],

            [
                'pro_nombre' => 'MEDICO VETERINARIO',
                'pro_abreviatura' => 'MV',
            ],

            [
                'pro_nombre' => 'ABOGADO',
                'pro_abreviatura' => 'ABG',
            ],

            [
                'pro_nombre' => 'INGENIERO',
                'pro_abreviatura' => 'ING',
            ],

            [
                'pro_nombre' => 'TRABAJADOR SOCIAL',
                'pro_abreviatura' => 'TS',
            ],

            [
                'pro_nombre' => 'AUXILIAR ASISTENCIAL',
                'pro_abreviatura' => 'AA',
            ],

            [
                'pro_nombre' => 'TECNICO ASISTENCIAL',
                'pro_abreviatura' => 'TA',
            ],
            
            [
                'pro_nombre' => 'TECNICO EN ENFERMERIA',
                'pro_abreviatura' => 'TE',
            ],
            
            [
                'pro_nombre' => 'TECNICO EN LABORATORIO',
                'pro_abreviatura' => 'TL',
            ],
            
            [
                'pro_nombre' => 'TECNICO EN RAYOS X',
                'pro_abreviatura' => 'TRX',
            ],
            
            [
                'pro_nombre' => 'TECNICO EN FARMACIA',
                'pro_abreviatura' => 'TF',
            ],
            
            [
                'pro_nombre' => 'COMUNICADOR SOCIAL',
                'pro_abreviatura' => 'CS',
            ],


        ];

        foreach ($data as &$row) {
            $row['created_at'] = date('Y-m-d H:i:s');
            $row['created_by'] = 1;
        }

        $this->db->table('casis_profesion')->insertBatch($data);
    }
}

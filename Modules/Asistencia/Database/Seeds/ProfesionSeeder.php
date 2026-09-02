<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProfesionSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // --- SECTOR SALUD (Asistenciales directos) ---
            [
                'pro_ide' => 1,
                'pro_codigo' => 'PROF-MED',
                'pro_nombre' => 'MEDICINA HUMANA',
                'pro_abreviatura' => 'MED',
                'pro_estado' => 1
            ],
            [
                'pro_ide' => 2,
                'pro_codigo' => 'PROF-ENF',
                'pro_nombre' => 'ENFERMERIA',
                'pro_abreviatura' => 'ENF',
                'pro_estado' => 1
            ],
            [
                'pro_ide' => 4,
                'pro_codigo' => 'PROF-OBS',
                'pro_nombre' => 'OBSTETRICIA',
                'pro_abreviatura' => 'OBS',
                'pro_estado' => 1
            ],
            [
                'pro_ide' => 5,
                'pro_codigo' => 'PROF-ODO',
                'pro_nombre' => 'ODONTOLOGIA',
                'pro_abreviatura' => 'ODO',
                'pro_estado' => 1
            ],
            [
                'pro_ide' => 6,
                'pro_codigo' => 'PROF-PSI',
                'pro_nombre' => 'PSICOLOGIA',
                'pro_abreviatura' => 'PSI',
                'pro_estado' => 1
            ],
            [
                'pro_ide' => 7,
                'pro_codigo' => 'PROF-TME',
                'pro_nombre' => 'TECNOLOGIA MEDICA',
                'pro_abreviatura' => 'TM',
                'pro_estado' => 1
            ],
            [
                'pro_ide' => 8,
                'pro_codigo' => 'PROF-NUT',
                'pro_nombre' => 'NUTRICION',
                'pro_abreviatura' => 'NUT',
                'pro_estado' => 1
            ],
            [
                'pro_ide' => 9,
                'pro_codigo' => 'PROF-QFA',
                'pro_nombre' => 'QUIMICO FARMACEUTICO',
                'pro_abreviatura' => 'QCE',
                'pro_estado' => 1
            ],
            [
                'pro_ide' => 10,
                'pro_codigo' => 'PROF-TSO',
                'pro_nombre' => 'TRABAJO SOCIAL',
                'pro_abreviatura' => 'TS',
                'pro_estado' => 1
            ],
            [
                'pro_ide' => 11,
                'pro_codigo' => 'PROF-BIO',
                'pro_nombre' => 'BIOLOGIA',
                'pro_abreviatura' => 'BIO',
                'pro_estado' => 1
            ],

            // --- SECTOR INGENIERÍA Y TECNOLOGÍA ---
            [
                'pro_ide' => 3,
                'pro_codigo' => 'PROF-ING',
                'pro_nombre' => 'INGENIERIA DE SISTEMAS',
                'pro_abreviatura' => 'ING-SIS',
                'pro_estado' => 1
            ],

            // --- SECTOR ADMINISTRATIVO, LEGAL Y GESTIÓN ---
            [
                'pro_ide' => 12,
                'pro_codigo' => 'PROF-ADM',
                'pro_nombre' => 'LICENCIADO EN ADMINISTRACION',
                'pro_abreviatura' => 'ADM',
                'pro_estado' => 1
            ],
            [
                'pro_ide' => 13,
                'pro_codigo' => 'PROF-CON',
                'pro_nombre' => 'CONTABILIDAD',
                'pro_abreviatura' => 'CONT',
                'pro_estado' => 1
            ],
            [
                'pro_ide' => 14,
                'pro_codigo' => 'PROF-DER',
                'pro_nombre' => 'DERECHO Y CIENCIAS POLITICAS',
                'pro_abreviatura' => 'ABG',
                'pro_estado' => 1
            ],
            [
                'pro_ide' => 15,
                'pro_codigo' => 'PROF-ECO',
                'pro_nombre' => 'ECONOMIA',
                'pro_abreviatura' => 'ECO',
                'pro_estado' => 1
            ],
            [
                'pro_ide' => 16,
                'pro_codigo' => 'EDU-SEC',
                'pro_nombre' => 'EDUCACION SECUNDARIA',
                'pro_abreviatura' => 'EDU-SEC',
                'pro_estado' => 1
            ],
            
        ];

        // Inserción masiva en la tabla de profesiones
        $this->db->table('casis_profesion')->insertBatch($data);
    }
}

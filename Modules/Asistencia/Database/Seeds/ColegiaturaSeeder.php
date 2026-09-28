<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ColegiaturaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // --- SECTOR SALUD (Asistenciales) ---
            [
                'col_pro_ide' => 1, // ID de Medicina Humana
                'col_nombre' => 'COLEGIO MEDICO DEL PERU',
                'col_abreviatura' => 'CMP',
                'col_estado' => 1
            ],
            [
                'col_pro_ide' => 2, // ID de Enfermería
                'col_nombre' => 'COLEGIO DE ENFERMEROS DEL PERU',
                'col_abreviatura' => 'CEP',
                'col_estado' => 1
            ],
            [
                'col_pro_ide' => 4, // ID de Obstetricia
                'col_nombre' => 'COLEGIO DE OBSTETRAS DEL PERU',
                'col_abreviatura' => 'COP',
                'col_estado' => 1
            ],
            [
                'col_pro_ide' => 5, // ID de Odontología
                'col_nombre' => 'COLEGIO ODONTOLOGICO DEL PERU',
                'col_abreviatura' => 'COP-ODO',
                'col_estado' => 1
            ],
            [
                'col_pro_ide' => 6, // ID de Psicología
                'col_nombre' => 'COLEGIO DE PSICOLOGOS DEL PERU',
                'col_abreviatura' => 'CDR',
                'col_estado' => 1
            ],
            [
                'col_pro_ide' => 7, // ID de Tecnología Médica
                'col_nombre' => 'COLEGIO TECNOLOGO MEDICO DEL PERU',
                'col_abreviatura' => 'CTMP',
                'col_estado' => 1
            ],
            [
                'col_pro_ide' => 8, // ID de Nutrición
                'col_nombre' => 'COLEGIO DE NUTRICIONISTAS DEL PERU',
                'col_abreviatura' => 'CNP',
                'col_estado' => 1
            ],
            [
                'col_pro_ide' => 9, // ID de Químico Farmacéutico
                'col_nombre' => 'COLEGIO QUIMICO FARMACEUTICO DEL PERU',
                'col_abreviatura' => 'CQFP',
                'col_estado' => 1
            ],
            [
                'col_pro_ide' => 10, // ID de Trabajo Social
                'col_nombre' => 'COLEGIO DE TRABAJADORES SOCIALES DEL PERU',
                'col_abreviatura' => 'CTSP',
                'col_estado' => 1
            ],
            [
                'col_pro_ide' => 11, // ID de Biología
                'col_nombre' => 'COLEGIO DE BIOLOGOS DEL PERU',
                'col_abreviatura' => 'CBP',
                'col_estado' => 1
            ],

            // --- SECTOR INGENIERÍA Y TECNOLOGÍA ---
            [
                'col_pro_ide' => 3, // ID de Ingeniería (Sistemas, Civil, etc.)
                'col_nombre' => 'COLEGIO DE INGENIEROS DEL PERU',
                'col_abreviatura' => 'CIP',
                'col_estado' => 1
            ],

            // --- SECTOR ADMINISTRATIVO, LEGAL Y GESTIÓN ---
            [
                'col_pro_ide' => 12, // ID de Administración
                'col_nombre' => 'COLEGIO DE LICENCIADOS EN ADMINISTRACION',
                'col_abreviatura' => 'CORLAD',
                'col_estado' => 1
            ],
            [
                'col_pro_ide' => 13, // ID de Contabilidad
                'col_nombre' => 'COLEGIO DE CONTADORES PUBLICOS',
                'col_abreviatura' => 'CCPP',
                'col_estado' => 1
            ],
            [
                'col_pro_ide' => 14, // ID de Derecho / Abogacía
                'col_nombre' => 'COLEGIO DE ABOGADOS',
                'col_abreviatura' => 'CAA',
                'col_estado' => 1
            ],
            [
                'col_pro_ide' => 15, // ID de Economía
                'col_nombre' => 'COLEGIO DE ECONOMISTAS DEL PERU',
                'col_abreviatura' => 'CEL',
                'col_estado' => 1
            ]
        ];

        // Insertamos masivamente en la tabla que definiste en tu modelo
        $this->db->table('casis_colegiatura')->insertBatch($data);
    }
}

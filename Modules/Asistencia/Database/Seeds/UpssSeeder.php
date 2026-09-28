<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpssSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // --- ATENCIÓN AMBULATORIA (CONSULTA EXTERNA) ---
            [
                'ups_codigo' => '111101',
                'ups_nombre' => 'CONSULTA EXTERNA',
                'ups_agrupacion' => 'ATENCION AMBULATORIA',
                'ups_agrupacion_abreviatura' => 'AMB',
                'ups_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],

            // --- ATENCIÓN DE EMERGENCIAS Y URGENCIAS ---
            [
                'ups_codigo' => '111201',
                'ups_nombre' => 'EMERGENCIA',
                'ups_agrupacion' => 'EMERGENCIA Y CUIDADOS CRITICOS',
                'ups_agrupacion_abreviatura' => 'EMG_CC',
                'ups_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ups_codigo' => '111202',
                'ups_nombre' => 'CUIDADOS INTENSIVOS',
                'ups_agrupacion' => 'EMERGENCIA Y CUIDADOS CRITICOS',
                'ups_agrupacion_abreviatura' => 'EMG_CC',
                'ups_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],

            // --- ATENCIÓN EN INTERNAMIENTO (HOSPITALIZACIÓN) ---
            [
                'ups_codigo' => '111301',
                'ups_nombre' => 'HOSPITALIZACION',
                'ups_agrupacion' => 'INTERNAMIENTO',
                'ups_agrupacion_abreviatura' => 'HOSP',
                'ups_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],

            // --- INTERVENCIONES QUIRÚRGICAS Y MATERNIDAD ---
            [
                'ups_codigo' => '111401',
                'ups_nombre' => 'CENTRO QUIRURGICO',
                'ups_agrupacion' => 'INTERVENCIONES',
                'ups_agrupacion_abreviatura' => 'INT',
                'ups_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ups_codigo' => '111402',
                'ups_nombre' => 'CENTRO OBSTETRICO',
                'ups_agrupacion' => 'INTERVENCIONES',
                'ups_agrupacion_abreviatura' => 'INT',
                'ups_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],

            // --- SERVICIOS DE APOYO AL DIAGNÓSTICO ---
            [
                'ups_codigo' => '111501',
                'ups_nombre' => 'PATOLOGIA CLINICA (LABORATORIO)',
                'ups_agrupacion' => 'APOYO AL DIAGNOSTICO',
                'ups_agrupacion_abreviatura' => 'DIAG',
                'ups_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ups_codigo' => '111502',
                'ups_nombre' => 'DIAGNOSTICO POR IMAGENES (RAYOS X / ECOGRAFIA)',
                'ups_agrupacion' => 'APOYO AL DIAGNOSTICO',
                'ups_agrupacion_abreviatura' => 'DIAG',
                'ups_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ups_codigo' => '111503',
                'ups_nombre' => 'ANATOMIA PATOLOGICA',
                'ups_agrupacion' => 'APOYO AL DIAGNOSTICO',
                'ups_agrupacion_abreviatura' => 'DIAG',
                'ups_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],

            // --- SERVICIOS DE APOYO AL TRATAMIENTO ---
            [
                'ups_codigo' => '111601',
                'ups_nombre' => 'FARMACIA',
                'ups_agrupacion' => 'APOYO AL TRATAMIENTO',
                'ups_agrupacion_abreviatura' => 'TRAT',
                'ups_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ups_codigo' => '111602',
                'ups_nombre' => 'MEDICINA FISICA Y REHABILITACION',
                'ups_agrupacion' => 'APOYO AL TRATAMIENTO',
                'ups_agrupacion_abreviatura' => 'TRAT',
                'ups_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ups_codigo' => '111603',
                'ups_nombre' => 'NUTRICION Y DIETETICA',
                'ups_agrupacion' => 'APOYO AL TRATAMIENTO',
                'ups_agrupacion_abreviatura' => 'TRAT',
                'ups_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ups_codigo' => '111604',
                'ups_nombre' => 'HEMODIALISIS',
                'ups_agrupacion' => 'APOYO AL TRATAMIENTO',
                'ups_agrupacion_abreviatura' => 'TRAT',
                'ups_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ups_codigo' => '111605',
                'ups_nombre' => 'BANCO DE SANGRE / HEMOTERAPIA',
                'ups_agrupacion' => 'APOYO AL TRATAMIENTO',
                'ups_agrupacion_abreviatura' => 'TRAT',
                'ups_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],

            // --- SALUD MENTAL Y ATENCIONES ESPECIALIZADAS ---
            [
                'ups_codigo' => '111701',
                'ups_nombre' => 'SALUD MENTAL Y PSIQUIATRIA',
                'ups_agrupacion' => 'SALUD MENTAL',
                'ups_agrupacion_abreviatura' => 'SM',
                'ups_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],

            // --- SALUD PÚBLICA / PROGRAMAS (Frecuentes en el primer nivel I-1 a I-4) ---
            [
                'ups_codigo' => '111801',
                'ups_nombre' => 'INMUNIZACIONES (VACUNATORIO)',
                'ups_agrupacion' => 'SALUD PUBLICA',
                'ups_agrupacion_abreviatura' => 'SP',
                'ups_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ups_codigo' => '111802',
                'ups_nombre' => 'CRED (CONTROL DE DESARROLLO DEL NIÑO)',
                'ups_agrupacion' => 'SALUD PUBLICA',
                'ups_agrupacion_abreviatura' => 'SP',
                'ups_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ups_codigo' => '111803',
                'ups_nombre' => 'PLANIFICACION FAMILIAR',
                'ups_agrupacion' => 'SALUD PUBLICA',
                'ups_agrupacion_abreviatura' => 'SP',
                'ups_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ups_codigo' => '111804',
                'ups_nombre' => 'PREVENCION Y CONTROL DE TBC',
                'ups_agrupacion' => 'SALUD PUBLICA',
                'ups_agrupacion_abreviatura' => 'SP',
                'ups_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],

            // --- ADMINISTRACIÓN Y GESTIÓN SANITARIA (Para personal de Red / Oficina) ---
            [
                'ups_codigo' => '111901',
                'ups_nombre' => 'ADMINISTRACION Y DIRECCION',
                'ups_agrupacion' => 'GESTION INSTITUCIONAL',
                'ups_agrupacion_abreviatura' => 'ADM_GES',
                'ups_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];

        // Inserción masiva usando el Query Builder apuntando a tu tabla parametrizada
        $this->db->table('casis_upss')->insertBatch($data);
    }
}

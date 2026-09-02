<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CargoSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // --- CARGOS DIRECTIVOS Y JEFATURAS ---
            [
                'car_codigo' => 'DIR-001',
                'car_nombre' => 'DIRECTOR DE RED DE SALUD',
                'car_descripcion' => 'Dirección general técnica y administrativa de la Red de Salud.',
                'car_estado' => 1
            ],
            [
                'car_codigo' => 'JEF-002',
                'car_nombre' => 'JEFE DE LA OFICINA DE RECURSOS HUMANOS',
                'car_descripcion' => 'Gestión, administración y desarrollo del personal de la institución.',
                'car_estado' => 1
            ],
            [
                'car_codigo' => 'JEF-003',
                'car_nombre' => 'JEFE DE LA UNIDAD DE CONTROL DE ASISTENCIA',
                'car_descripcion' => 'Supervisión, validación y control de la asistencia y permanencia del personal.',
                'car_estado' => 1
            ],
            [
                'car_codigo' => 'JEF-004',
                'car_nombre' => 'JEFE DE LA UNIDAD DE REMUNERACIONES Y PLANILLAS',
                'car_descripcion' => 'Procesamiento de planillas, incentivos y obligaciones financieras del personal.',
                'car_estado' => 1
            ],
            [
                'car_codigo' => 'JEF-005',
                'car_nombre' => 'JEFE DE LA OFICINA DE INFORMATICA Y TELECOMUNICACIONES',
                'car_descripcion' => 'Administración de la infraestructura tecnológica, sistemas y redes del hospital y la red.',
                'car_estado' => 1
            ],

            // --- PERSONAL ASISTENCIAL (MÉDICOS Y ESPECIALISTAS) ---
            [
                'car_codigo' => 'MED-006',
                'car_nombre' => 'MEDICO ESPECIALISTA',
                'car_descripcion' => 'Médico cirujano con segunda especialidad registrada.',
                'car_estado' => 1
            ],
            [
                'car_codigo' => 'MED-007',
                'car_nombre' => 'MEDICO GENERAL',
                'car_descripcion' => 'Atención médica integral ambulatoria y de urgencias.',
                'car_estado' => 1
            ],
            [
                'car_codigo' => 'ENF-008',
                'car_nombre' => 'ENFERMERO(A) ESPECIALISTA',
                'car_descripcion' => 'Atención especializada en áreas críticas (UCI, Emergencia, Centro Quirúrgico).',
                'car_estado' => 1
            ],
            [
                'car_codigo' => 'ENF-009',
                'car_nombre' => 'ENFERMERO(A) GENERAL',
                'car_descripcion' => 'Cuidado integral del paciente en hospitalización y consultorios.',
                'car_estado' => 1
            ],
            [
                'car_codigo' => 'OBST-010',
                'car_nombre' => 'OBSTETRA',
                'car_descripcion' => 'Atención de la salud sexual y reproductiva, control prenatal y partos.',
                'car_estado' => 1
            ],
            [
                'car_codigo' => 'ODONT-011',
                'car_nombre' => 'CIRUJANO DENTISTA',
                'car_descripcion' => 'Atención y tratamiento de la salud bucodental.',
                'car_estado' => 1
            ],
            [
                'car_codigo' => 'TEC-012',
                'car_nombre' => 'TECNOLOGO MEDICO',
                'car_descripcion' => 'Especialistas en Laboratorio Clínico, Radiología o Terapia Física.',
                'car_estado' => 1
            ],
            [
                'car_codigo' => 'NUT-013',
                'car_nombre' => 'NUTRICIONISTA',
                'car_descripcion' => 'Evaluación, régimen alimentario y soporte nutricional a pacientes.',
                'car_estado' => 1
            ],

            // --- PERSONAL DE APOYO ASISTENCIAL ---
            [
                'car_codigo' => 'TMA-014',
                'car_nombre' => 'TECNICO EN ENFERMERIA',
                'car_descripcion' => 'Apoyo directo al personal profesional de enfermería y médico.',
                'car_estado' => 1
            ],
            [
                'car_codigo' => 'TMA-015',
                'car_nombre' => 'TECNICO EN LABORATORIO',
                'car_descripcion' => 'Toma, procesamiento y registro de muestras biológicas.',
                'car_estado' => 1
            ],

            // --- PERSONAL ADMINISTRATIVO Y PROFESIONALES DE GESTIÓN ---
            [
                'car_codigo' => 'ADM-016',
                'car_nombre' => 'ADMINISTRADOR',
                'car_descripcion' => 'Gestión operativa, logística o presupuestal de áreas administrativas.',
                'car_estado' => 1
            ],
            [
                'car_codigo' => 'TI-017',
                'car_nombre' => 'INGENIERO DE SISTEMAS / DESARROLLADOR',
                'car_descripcion' => 'Análisis, desarrollo y mantenimiento de sistemas y módulos institucionales.',
                'car_estado' => 1
            ],
            [
                'car_codigo' => 'ADM-018',
                'car_nombre' => 'CONTADOR',
                'car_descripcion' => 'Registro contable, conciliaciones y ejecución del presupuesto institucional.',
                'car_estado' => 1
            ],
            [
                'car_codigo' => 'ADM-019',
                'car_nombre' => 'ASISTENTE ADMINISTRATIVO',
                'car_descripcion' => 'Trámite documentario, archivo digital y apoyo en la gestión de la oficina.',
                'car_estado' => 1
            ],
            [
                'car_codigo' => 'ADM-020',
                'car_nombre' => 'SECRETARIA',
                'car_descripcion' => 'Recepción, agenda, redacción de documentos y atención al usuario.',
                'car_estado' => 1
            ]
        ];

        // Inserción masiva en la tabla indicada en tu modelo
        $this->db->table('casis_cargo')->insertBatch($data);
    }
}

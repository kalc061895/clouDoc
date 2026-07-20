<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PermisoSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // --- PERMISOS CON GOCE DE HABER (REMUNERADOS) ---
            [
                'pero_nombre' => 'PERMISO POR LACTANCIA MATERNA',
                'pero_abreviatura' => 'PER_LACT',
                'pero_descripcion' => 'Una hora diaria de permiso hasta que el hijo cumpla un año de edad. No se compensa.',
                'pero_remunerado' => 1,
                'pero_horas_maximas' => 30, // Estimado referencial mensual (1 hora por día laborable)
                'pero_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'pero_nombre' => 'PERMISO POR CITACIÓN JUDICIAL O POLICIAL',
                'pero_abreviatura' => 'PER_CIT',
                'pero_descripcion' => 'Por el tiempo que dure la diligencia judicial, fiscal o policial, más el término de la distancia.',
                'pero_remunerado' => 1,
                'pero_horas_maximas' => 8, // Suele cubrir la jornada del día del evento según la notificación
                'pero_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'pero_nombre' => 'PERMISO POR ATENCIÓN MÉDICA EN ESSALUD/MINSA',
                'pero_abreviatura' => 'PER_MED',
                'pero_descripcion' => 'Por consultas médicas, citas o exámenes del servidor. Sustentado con constancia de atención.',
                'pero_remunerado' => 1,
                'pero_horas_maximas' => 4, // Límite estándar por cita o jornada, recargable según el ticket
                'pero_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'pero_nombre' => 'PERMISO POR CAPACITACIÓN CON AUSPICIO',
                'pero_abreviatura' => 'PER_CAP_OF',
                'pero_descripcion' => 'Para asistir a cursos o eventos académicos aprobados y declarados de interés institucional.',
                'pero_remunerado' => 1,
                'pero_horas_maximas' => 40, // Límite referencial supeditado a las horas del evento refrendado
                'pero_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'pero_nombre' => 'PERMISO POR ONOMÁSTICO',
                'pero_abreviatura' => 'PER_ONO',
                'pero_descripcion' => 'Descanso remunerado el día del cumpleaños del servidor (o día hábil siguiente si cae feriado/domingo).',
                'pero_remunerado' => 1,
                'pero_horas_maximas' => 8, // Equivalente a la jornada laboral diaria completa
                'pero_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'pero_nombre' => 'PERMISO POR FUNCIÓN SINDICAL (DIRIGENTES)',
                'pero_abreviatura' => 'PER_SIND',
                'pero_descripcion' => 'Facilidades para dirigentes sindicales reconocidos según convenios colectivos o normas vigentes.',
                'pero_remunerado' => 1,
                'pero_horas_maximas' => 40, // Tope mensual regulado de horas sindicales autorizadas
                'pero_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],

            // --- PERMISOS SIN GOCE DE HABER (NO REMUNERADOS O POR COMPENSAR) ---
            [
                'pero_nombre' => 'PERMISO POR PARTICULARES (DEDUCCIÓN DE PLANILLA)',
                'pero_abreviatura' => 'PER_PART',
                'pero_descripcion' => 'Para atender trámites o asuntos netamente personales durante la jornada de trabajo.',
                'pero_remunerado' => 0,
                'pero_horas_maximas' => 16, // Límite estándar acumulado por mes autorizado por la jefatura
                'pero_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'pero_nombre' => 'PERMISO TRÁMITE PARTICULAR COMPENSABLE',
                'pero_abreviatura' => 'PER_COMP',
                'pero_descripcion' => 'Salida por horas particulares con cargo a ser devueltas (recuperadas) fuera del horario normal.',
                'pero_remunerado' => 0, // Se computa inicialmente sin goce hasta que la jefatura valide las horas de recuperación
                'pero_horas_maximas' => 24, // Máximo acumulable para mantener un control de horas pendientes
                'pero_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'pero_nombre' => 'PERMISO POR DOCENCIA UNIVERSITARIA',
                'pero_abreviatura' => 'PER_DOC',
                'pero_descripcion' => 'Permiso para ejercer la docencia (hasta 6 horas semanales), sujeto a compensación obligatoria.',
                'pero_remunerado' => 0, // Es remunerado si se compensa, pero el software de asistencia lo trata como descuento directo si no hay horas de recuperación registradas
                'pero_horas_maximas' => 24, // Tope referencial de 24 horas mensuales (6 semanales)
                'pero_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];

        // Inserción batch orientada a tu tabla de permisos
        $this->db->table('casis_permiso')->insertBatch($data);
    }
}

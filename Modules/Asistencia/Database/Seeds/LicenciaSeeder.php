<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LicenciaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // --- LICENCIAS CON GOCE DE HABER (REMUNERADAS) ---
            [
                'lic_nombre' => 'LICENCIA POR ENFERMEDAD O ACCIDENTE',
                'lic_abreviatura' => 'LIC_ENF',
                'lic_remunerado' => 1,
                'lic_dias_maximos' => 365, // Según EsSalud, hasta 1 año continuo o alternado
                'lic_descripcion' => 'Por incapacidad temporal para el trabajo, sustentado con CITT de EsSalud.',
                'lic_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'lic_nombre' => 'LICENCIA POR MATERNIDAD',
                'lic_abreviatura' => 'LIC_MAT',
                'lic_remunerado' => 1,
                'lic_dias_maximos' => 98, // 49 prenatal y 49 postnatal (puede extenderse 30 días más en parto múltiple)
                'lic_descripcion' => 'Para la servidora gestante por el período de gravidez y postparto.',
                'lic_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'lic_nombre' => 'LICENCIA POR PATERNIDAD',
                'lic_abreviatura' => 'LIC_PAT',
                'lic_remunerado' => 1,
                'lic_dias_maximos' => 10, // Extendible a 20 o 30 en casos complejos o partos prematuros
                'lic_descripcion' => 'Para el servidor varón por el nacimiento de su hijo o hija.',
                'lic_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'lic_nombre' => 'LICENCIA POR FALLECIMIENTO DE FAMILIAR DIRECTO',
                'lic_abreviatura' => 'LIC_FALL',
                'lic_remunerado' => 1,
                'lic_dias_maximos' => 5, // 5 días laborables (se amplía por el término de la distancia si el deceso es en otra provincia)
                'lic_descripcion' => 'Por deceso de cónyuge, padres, hijos o hermanos del servidor público.',
                'lic_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'lic_nombre' => 'LICENCIA POR MATRIMONIO',
                'lic_abreviatura' => 'LIC_MATR',
                'lic_remunerado' => 1,
                'lic_dias_maximos' => 5, // Otorgado habitualmente en regímenes públicos / convenios colectivos
                'lic_descripcion' => 'Concedida al servidor que contrae matrimonio civil.',
                'lic_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'lic_nombre' => 'LICENCIA POR CAPACITACIÓN OFICIALIZADA',
                'lic_abreviatura' => 'LIC_CAP',
                'lic_remunerado' => 1,
                'lic_dias_maximos' => 365, // Sujeto a la duración del evento/estudio aprobado por resolución
                'lic_descripcion' => 'Para perfeccionamiento o especialización institucional aprobada por la entidad.',
                'lic_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'lic_nombre' => 'LICENCIA POR ENFERMEDAD GRAVE O ACCIDENTE DE FAMILIAR DIRECTO',
                'lic_abreviatura' => 'LIC_FAM_GRAVE',
                'lic_remunerado' => 1,
                'lic_dias_maximos' => 7, // Ley N° 30012 (hasta 7 días calendario, prorrogables a cuenta de vacaciones)
                'lic_descripcion' => 'Para asistencia de hijo, padre, cónyuge o conviviente en estado grave o crítico.',
                'lic_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'lic_nombre' => 'LICENCIA POR FUNCIÓN EDIL o DE PRENSA',
                'lic_abreviatura' => 'LIC_EDIL',
                'lic_remunerado' => 1,
                'lic_dias_maximos' => 20, // Horas o días máximos mensuales según Ley Orgánica de Municipalidades
                'lic_descripcion' => 'Concedida a servidores elegidos como regidores para cumplir actividades de su cargo local.',
                'lic_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'lic_nombre' => 'LICENCIA POR ADOPCIÓN',
                'lic_abreviatura' => 'LIC_ADOP',
                'lic_remunerado' => 1,
                'lic_dias_maximos' => 30, // Con goce de haber (Ley N° 27409)
                'lic_descripcion' => 'Para el servidor que adopte un niño menor de 12 años.',
                'lic_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],

            // --- LICENCIAS SIN GOCE DE HABER (NO REMUNERADAS) ---
            [
                'lic_nombre' => 'LICENCIA POR MOTIVOS PARTICULARES',
                'lic_abreviatura' => 'LIC_PART',
                'lic_remunerado' => 0,
                'lic_dias_maximos' => 90, // En el DL 276 se otorgan hasta 90 días en un período de un año
                'lic_descripcion' => 'Por asuntos personales del servidor, supeditado a la necesidad del servicio.',
                'lic_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'lic_nombre' => 'LICENCIA POR CAPACITACIÓN NO OFICIALIZADA',
                'lic_abreviatura' => 'LIC_CAP_NO',
                'lic_remunerado' => 0,
                'lic_dias_maximos' => 730, // Hasta dos años para estudios de postgrado o especialización por cuenta propia
                'lic_descripcion' => 'Por estudios de perfeccionamiento sin el auspicio oficial de la entidad.',
                'lic_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'lic_nombre' => 'LICENCIA POR ENFERMEDAD PROLONGADA (EXCESO ESSALUD)',
                'lic_abreviatura' => 'LIC_ENF_S_GOCE',
                'lic_remunerado' => 0,
                'lic_dias_maximos' => 365, // Periodos que exceden la cobertura económica de EsSalud pero reservan la plaza
                'lic_descripcion' => 'Cuando el servidor continúa con problemas de salud y superó el tope subsidiado.',
                'lic_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];

        // Inserción masiva apuntando a tu tabla de licencias institucional
        $this->db->table('casis_licencia')->insertBatch($data);
    }
}

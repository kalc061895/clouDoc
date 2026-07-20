<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OficinaSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // 1. Mapeo referencial de los IDs de 'casis_tipo_oficina' (Asumiendo IDs del Seeder anterior)
        // Si tus IDs reales varían, puedes hacer un query previo aquí para capturarlos.
        $tofi_direccion = 1;
        $tofi_oficina = 2;
        $tofi_departamento = 3;
        $tofi_unidad = 4;
        $tofi_area = 5;
        $tofi_servicio = 6;

        // ID del Establecimiento por defecto (Red / Hospital Principal)
        $est_ide_defecto = 1;

        // --- NIVEL 1: ÓRGANO DE DIRECCIÓN (RAÍZ) ---
        $direccionData = [
            'ofi_est_ide' => $est_ide_defecto,
            'ofi_tofi_ide' => $tofi_direccion,
            'ofi_nombre' => 'DIRECCIÓN EJECUTIVA',
            'ofi_abreviatura' => 'DE',
            'ofi_codigo_referencia' => '01',
            'ofi_titulo_encargado' => 'M.C.',
            'ofi_nombres_encargado' => 'DIRECTOR GENERAL',
            'ofi_cargo_encargado' => 'DIRECTOR EJECUTIVO',
            'ofi_descripcion' => 'Órgano de la más alta autoridad de la Dirección de Red de Salud y Hospital.',
            'ofi_rango' => 1,
            'ofi_padre_ide' => null, // Raíz del organigrama
            'ofi_estado' => 1,
            'created_by' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $db->table('casis_oficina')->insert($direccionData);
        $de_id = $db->insertID(); // ID Padre Principal

        // --- NIVEL 2: ÓRGANOS DE ASESORAMIENTO Y CONTROL (Dependen de Dirección Ejecutiva) ---
        $asesoramientoYControl = [
            [
                'ofi_est_ide' => $est_ide_defecto,
                'ofi_tofi_ide' => $tofi_oficina,
                'ofi_nombre' => 'ÓRGANO DE CONTROL INSTITUCIONAL',
                'ofi_abreviatura' => 'OCI',
                'ofi_codigo_referencia' => '01.1',
                'ofi_titulo_encargado' => 'CPC.',
                'ofi_nombres_encargado' => 'JEFE DE OCI',
                'ofi_cargo_encargado' => 'JEFE DE ÓRGANO DE CONTROL',
                'ofi_descripcion' => 'Encargado de ejecutar el control gubernamental interno en la institución.',
                'ofi_rango' => 2,
                'ofi_padre_ide' => $de_id,
                'ofi_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ofi_est_ide' => $est_ide_defecto,
                'ofi_tofi_ide' => $tofi_oficina,
                'ofi_nombre' => 'OFICINA DE ASESORÍA JURÍDICA',
                'ofi_abreviatura' => 'OAJ',
                'ofi_codigo_referencia' => '01.2',
                'ofi_titulo_encargado' => 'ABOG.',
                'ofi_nombres_encargado' => 'JEFE DE ASESORÍA JURÍDICA',
                'ofi_cargo_encargado' => 'JEFE DE OFICINA',
                'ofi_descripcion' => 'Asesoramiento legal y normativo a la Dirección y dependencias.',
                'ofi_rango' => 2,
                'ofi_padre_ide' => $de_id,
                'ofi_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ofi_est_ide' => $est_ide_defecto,
                'ofi_tofi_ide' => $tofi_oficina,
                'ofi_nombre' => 'OFICINA DE PLANEAMIENTO COMPLEMENTARIO Y PRESUPUESTO',
                'ofi_abreviatura' => 'OPP',
                'ofi_codigo_referencia' => '01.3',
                'ofi_titulo_encargado' => 'LIC.',
                'ofi_nombres_encargado' => 'JEFE DE PLANEAMIENTO',
                'ofi_cargo_encargado' => 'JEFE DE OFICINA',
                'ofi_descripcion' => 'Planificación estratégica, costos, presupuesto y POI.',
                'ofi_rango' => 2,
                'ofi_padre_ide' => $de_id,
                'ofi_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ofi_est_ide' => $est_ide_defecto,
                'ofi_tofi_ide' => $tofi_oficina,
                'ofi_nombre' => 'OFICINA DE EPIDEMIOLOGÍA Y SALUD AMBIENTAL',
                'ofi_abreviatura' => 'EPI',
                'ofi_codigo_referencia' => '01.4',
                'ofi_titulo_encargado' => 'M.C.',
                'ofi_nombres_encargado' => 'JEFE DE EPIDEMIOLOGÍA',
                'ofi_cargo_encargado' => 'JEFE DE OFICINA',
                'ofi_descripcion' => 'Vigilancia epidemiológica, brotes y salud ambiental.',
                'ofi_rango' => 2,
                'ofi_padre_ide' => $de_id,
                'ofi_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];
        $db->table('casis_oficina')->insertBatch($asesoramientoYControl);


        // --- NIVEL 2: OFICINA DE ADMINISTRACIÓN (ÓRGANO DE APOYO CENTRAL) ---
        $administracionData = [
            'ofi_est_ide' => $est_ide_defecto,
            'ofi_tofi_ide' => $tofi_oficina,
            'ofi_nombre' => 'OFICINA DE ADMINISTRACIÓN',
            'ofi_abreviatura' => 'ADM',
            'ofi_codigo_referencia' => '02',
            'ofi_titulo_encargado' => 'LIC. ADM.',
            'ofi_nombres_encargado' => 'DIRECTOR DE ADMINISTRACIÓN',
            'ofi_cargo_encargado' => 'DIRECTOR DE ADMINISTRACIÓN',
            'ofi_descripcion' => 'Gestión de los recursos financieros, materiales y de personal de la institución.',
            'ofi_rango' => 2,
            'ofi_padre_ide' => $de_id,
            'ofi_estado' => 1,
            'created_by' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $db->table('casis_oficina')->insert($administracionData);
        $adm_id = $db->insertID(); // ID Padre para las Unidades Administrativas


        // --- NIVEL 3: UNIDADES DE LA OFICINA DE ADMINISTRACIÓN ---
        $unidadesAdministrativas = [
            [
                'ofi_est_ide' => $est_ide_defecto,
                'ofi_tofi_ide' => $tofi_unidades = $tofi_unidad,
                'ofi_nombre' => 'UNIDAD DE GESTIÓN DE RECURSOS HUMANOS',
                'ofi_abreviatura' => 'UGRH',
                'ofi_codigo_referencia' => '02.1',
                'ofi_titulo_encargado' => 'LIC.',
                'ofi_nombres_encargado' => 'JEFE DE RECURSOS HUMANOS',
                'ofi_cargo_encargado' => 'JEFE DE UNIDAD',
                'ofi_descripcion' => 'Administración de personal, planillas, legajos, asistencia y bienestar.',
                'ofi_rango' => 3,
                'ofi_padre_ide' => $adm_id,
                'ofi_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ofi_est_ide' => $est_ide_defecto,
                'ofi_tofi_ide' => $tofi_unidad,
                'ofi_nombre' => 'UNIDAD DE LOGÍSTICA',
                'ofi_abreviatura' => 'LOG',
                'ofi_codigo_referencia' => '02.2',
                'ofi_titulo_encargado' => 'ING.',
                'ofi_nombres_encargado' => 'JEFE DE LOGÍSTICA',
                'ofi_cargo_encargado' => 'JEFE DE UNIDAD',
                'ofi_descripcion' => 'Abastecimiento, contrataciones, adquisiciones y patrimonio.',
                'ofi_rango' => 3,
                'ofi_padre_ide' => $adm_id,
                'ofi_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ofi_est_ide' => $est_ide_defecto,
                'ofi_tofi_ide' => $tofi_unidad,
                'ofi_nombre' => 'UNIDAD DE ECONOMÍA',
                'ofi_abreviatura' => 'ECON',
                'ofi_codigo_referencia' => '02.3',
                'ofi_titulo_encargado' => 'CPC.',
                'ofi_nombres_encargado' => 'JEFE DE ECONOMÍA',
                'ofi_cargo_encargado' => 'JEFE DE UNIDAD',
                'ofi_descripcion' => 'Contabilidad, tesorería, devengados e integración financiera.',
                'ofi_rango' => 3,
                'ofi_padre_ide' => $adm_id,
                'ofi_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ofi_est_ide' => $est_ide_defecto,
                'ofi_tofi_ide' => $tofi_unidad,
                'ofi_nombre' => 'UNIDAD DE ESTADÍSTICA E INFORMÁTICA',
                'ofi_abreviatura' => 'UEI',
                'ofi_codigo_referencia' => '02.4',
                'ofi_titulo_encargado' => 'ING.',
                'ofi_nombres_encargado' => 'JEFE DE INFORMÁTICA',
                'ofi_cargo_encargado' => 'JEFE DE UNIDAD',
                'ofi_descripcion' => 'Sistemas de información, soporte tecnológico, procesamiento HIS y servidores.',
                'ofi_rango' => 3,
                'ofi_padre_ide' => $adm_id,
                'ofi_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ofi_est_ide' => $est_ide_defecto,
                'ofi_tofi_ide' => $tofi_unidad,
                'ofi_nombre' => 'UNIDAD DE SEGUROS (SIS/SOAT)',
                'ofi_abreviatura' => 'SEGUROS',
                'ofi_codigo_referencia' => '02.5',
                'ofi_titulo_encargado' => 'M.C.',
                'ofi_nombres_encargado' => 'JEFE DE SEGUROS',
                'ofi_cargo_encargado' => 'JEFE DE UNIDAD',
                'ofi_descripcion' => 'Auditoría médica de FUA, financiamiento de prestaciones SIS y convenios.',
                'ofi_rango' => 3,
                'ofi_padre_ide' => $adm_id,
                'ofi_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];
        $db->table('casis_oficina')->insertBatch($unidadesAdministrativas);

        // Obtenemos el ID de la Unidad de RRHH para colgar áreas de control diarias si es necesario
        $rrhh_id = $db->select('ofi_ide')->where('ofi_abreviatura', 'UGRH')->get('casis_oficina')->getRow()->ofi_ide;

        // --- NIVEL 4: ÁREAS OPERATIVAS INTERNAS (Ejemplo dentro de Recursos Humanos) ---
        $areasRRHH = [
            [
                'ofi_est_ide' => $est_ide_defecto,
                'ofi_tofi_ide' => $tofi_area,
                'ofi_nombre' => 'ÁREA DE CONTROL DE ASISTENCIA Y PERMANENCIA',
                'ofi_abreviatura' => 'CONTROL_ASIS',
                'ofi_codigo_referencia' => '02.1.1',
                'ofi_titulo_encargado' => 'ING.',
                'ofi_nombres_encargado' => 'RESPONSABLE DE ASISTENCIA',
                'ofi_cargo_encargado' => 'JEFE DE ÁREA',
                'ofi_descripcion' => 'Validación de tareos, roles de turno, justificaciones, licencias y marcaciones.',
                'ofi_rango' => 4,
                'ofi_padre_ide' => $rrhh_id,
                'ofi_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ofi_est_ide' => $est_ide_defecto,
                'ofi_tofi_ide' => $tofi_area,
                'ofi_nombre' => 'ÁREA DE REMUNERACIONES (PLANILLAS)',
                'ofi_abreviatura' => 'PLANILLAS',
                'ofi_codigo_referencia' => '02.1.2',
                'ofi_titulo_encargado' => 'CPC.',
                'ofi_nombres_encargado' => 'RESPONSABLE DE PLANILLAS',
                'ofi_cargo_encargado' => 'JEFE DE ÁREA',
                'ofi_descripcion' => 'Cálculo de conceptos remunerativos, incentivos, AIRHSP y retenciones ley.',
                'ofi_rango' => 4,
                'ofi_padre_ide' => $rrhh_id,
                'ofi_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];
        $db->table('casis_oficina')->insertBatch($areasRRHH);


        // --- NIVEL 2: DIRECCIÓN DE SERVICIOS DE SALUD / DIRECCIÓN MÉDICA (ÓRGANOS DE LÍNEA) ---
        $direccionMedicaData = [
            'ofi_est_ide' => $est_ide_defecto,
            'ofi_tofi_ide' => $tofi_oficina,
            'ofi_nombre' => 'DIRECCIÓN MÉDICA / JEFATURA DE SERVICIOS DE SALUD',
            'ofi_abreviatura' => 'DM_JSS',
            'ofi_codigo_referencia' => '03',
            'ofi_titulo_encargado' => 'M.C. ESP.',
            'ofi_nombres_encargado' => 'DIRECTOR MÉDICO',
            'ofi_cargo_encargado' => 'DIRECTOR DE LÍNEA',
            'ofi_descripcion' => 'Gestión, monitoreo y supervisión de las prestaciones de salud hospitalarias y periféricas.',
            'ofi_rango' => 2,
            'ofi_padre_ide' => $de_id,
            'ofi_estado' => 1,
            'created_by' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $db->table('casis_oficina')->insert($direccionMedicaData);
        $dm_id = $db->insertID(); // ID Padre para la rama asistencial masiva


        // --- NIVEL 3: DEPARTAMENTOS ASISTENCIALES HOSPIALARIOS (Dependen de Dirección Médica) ---
        $departamentosAsistenciales = [
            [
                'ofi_est_ide' => $est_ide_defecto,
                'ofi_tofi_ide' => $tofi_departamento,
                'ofi_nombre' => 'DEPARTAMENTO DE MEDICINA',
                'ofi_abreviatura' => 'DEPMED',
                'ofi_codigo_referencia' => '03.1',
                'ofi_titulo_encargado' => 'M.C.',
                'ofi_nombres_encargado' => 'JEFE DE DEPARTAMENTO DE MEDICINA',
                'ofi_cargo_encargado' => 'JEFE DE DEPARTAMENTO',
                'ofi_descripcion' => 'Agrupa los servicios clínicos de atención médica especializada interna.',
                'ofi_rango' => 3,
                'ofi_padre_ide' => $dm_id,
                'ofi_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ofi_est_ide' => $est_ide_defecto,
                'ofi_tofi_ide' => $tofi_departamento,
                'ofi_nombre' => 'DEPARTAMENTO DE CIRUGÍA',
                'ofi_abreviatura' => 'DEPCIR',
                'ofi_codigo_referencia' => '03.2',
                'ofi_titulo_encargado' => 'M.C.',
                'ofi_nombres_encargado' => 'JEFE DE DEPARTAMENTO DE CIRUGÍA',
                'ofi_cargo_encargado' => 'JEFE DE DEPARTAMENTO',
                'ofi_descripcion' => 'Tratamientos e intervenciones quirúrgicas electivas y de urgencia.',
                'ofi_rango' => 3,
                'ofi_padre_ide' => $dm_id,
                'ofi_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ofi_est_ide' => $est_ide_defecto,
                'ofi_tofi_ide' => $tofi_departamento,
                'ofi_nombre' => 'DEPARTAMENTO DE PEDIATRÍA Y NEONATOLOGÍA',
                'ofi_abreviatura' => 'DEPPED',
                'ofi_codigo_referencia' => '03.3',
                'ofi_titulo_encargado' => 'M.C.',
                'ofi_nombres_encargado' => 'JEFE DE DEPARTAMENTO DE PEDIATRÍA',
                'ofi_cargo_encargado' => 'JEFE DE DEPARTAMENTO',
                'ofi_descripcion' => 'Atención integral médica del neonato, lactante y adolescente.',
                'ofi_rango' => 3,
                'ofi_padre_ide' => $dm_id,
                'ofi_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ofi_est_ide' => $est_ide_defecto,
                'ofi_tofi_ide' => $tofi_departamento,
                'ofi_nombre' => 'DEPARTAMENTO DE GINECO-OBSTETRICIA',
                'ofi_abreviatura' => 'DEPGOB',
                'ofi_codigo_referencia' => '03.4',
                'ofi_titulo_encargado' => 'M.C.',
                'ofi_nombres_encargado' => 'JEFE DE GINECO-OBSTETRICIA',
                'ofi_cargo_encargado' => 'JEFE DE DEPARTAMENTO',
                'ofi_descripcion' => 'Patologías del aparato reproductor femenino, gestación, parto y puerperio.',
                'ofi_rango' => 3,
                'ofi_padre_ide' => $dm_id,
                'ofi_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ofi_est_ide' => $est_ide_defecto,
                'ofi_tofi_ide' => $tofi_departamento,
                'ofi_nombre' => 'DEPARTAMENTO DE ENFERMERÍA',
                'ofi_abreviatura' => 'DEPENF',
                'ofi_codigo_referencia' => '03.5',
                'ofi_titulo_encargado' => 'LIC. ENF.',
                'ofi_nombres_encargado' => 'JEFE DE DEPARTAMENTO DE ENFERMERÍA',
                'ofi_cargo_encargado' => 'JEFE DE DEPARTAMENTO',
                'ofi_descripcion' => 'Gestión del cuidado de enfermería en todos los servicios de internamiento y consulta.',
                'ofi_rango' => 3,
                'ofi_padre_ide' => $dm_id,
                'ofi_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ofi_est_ide' => $est_ide_defecto,
                'ofi_tofi_ide' => $tofi_departamento,
                'ofi_nombre' => 'DEPARTAMENTO DE EMERGENCIA Y CUIDADOS CRÍTICOS',
                'ofi_abreviatura' => 'DEPEMG',
                'ofi_codigo_referencia' => '03.6',
                'ofi_titulo_encargado' => 'M.C.',
                'ofi_nombres_encargado' => 'JEFE DE EMERGENCIA',
                'ofi_cargo_encargado' => 'JEFE DE DEPARTAMENTO',
                'ofi_descripcion' => 'Atención inmediata de prioridades de salud I y II, shock trauma e UCI.',
                'ofi_rango' => 3,
                'ofi_padre_ide' => $dm_id,
                'ofi_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];
        $db->table('casis_oficina')->insertBatch($departamentosAsistenciales);

        // Capturamos el Departamento de Medicina y Emergencia para desglosar sus servicios finales
        $dep_medicina_id = $db->select('ofi_ide')->where('ofi_abreviatura', 'DEPMED')->get('casis_oficina')->getRow()->ofi_ide;
        $dep_emergencia_id = $db->select('ofi_ide')->where('ofi_abreviatura', 'DEPEMG')->get('casis_oficina')->getRow()->ofi_ide;


        // --- NIVEL 4: SERVICIOS ASISTENCIALES ESPECÍFICOS ---
        $serviciosFinales = [
            // Servicios de Medicina
            [
                'ofi_est_ide' => $est_ide_defecto,
                'ofi_tofi_ide' => $tofi_servicio,
                'ofi_nombre' => 'SERVICIO DE MEDICINA INTERNA',
                'ofi_abreviatura' => 'SERV_MEDINT',
                'ofi_codigo_referencia' => '03.1.1',
                'ofi_titulo_encargado' => 'M.C.',
                'ofi_nombres_encargado' => 'JEFE DE SERVICIO MEDICINA',
                'ofi_cargo_encargado' => 'JEFE DE SERVICIO',
                'ofi_descripcion' => 'Hospitalización clínica y consultorios de medicina de adultos.',
                'ofi_rango' => 4,
                'ofi_padre_ide' => $dep_medicina_id,
                'ofi_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ofi_est_ide' => $est_ide_defecto,
                'ofi_tofi_ide' => $tofi_servicio,
                'ofi_nombre' => 'SERVICIO DE CARDIOLOGÍA',
                'ofi_abreviatura' => 'SERV_CARDIO',
                'ofi_codigo_referencia' => '03.1.2',
                'ofi_titulo_encargado' => 'M.C. ESP.',
                'ofi_nombres_encargado' => 'RESPONSABLE DE CARDIOLOGÍA',
                'ofi_cargo_encargado' => 'JEFE DE SERVICIO',
                'ofi_descripcion' => 'Consultorios y exámenes especiales cardiológicos (EKG, ecocardiogramas).',
                'ofi_rango' => 4,
                'ofi_padre_ide' => $dep_medicina_id,
                'ofi_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            // Servicios de Emergencia
            [
                'ofi_est_ide' => $est_ide_defecto,
                'ofi_tofi_ide' => $tofi_servicio,
                'ofi_nombre' => 'SERVICIO DE TRIAJE Y TÓPICO DE EMERGENCIA',
                'ofi_abreviatura' => 'SERV_TRIAJE',
                'ofi_codigo_referencia' => '03.6.1',
                'ofi_titulo_encargado' => 'LIC. ENF.',
                'ofi_nombres_encargado' => 'ENCARGADO DE TRIAJE',
                'ofi_cargo_encargado' => 'JEFE DE SERVICIO',
                'ofi_descripcion' => 'Clasificación de la gravedad del paciente según escala de prioridades.',
                'ofi_rango' => 4,
                'ofi_padre_ide' => $dep_emergencia_id,
                'ofi_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'ofi_est_ide' => $est_ide_defecto,
                'ofi_tofi_ide' => $tofi_servicio,
                'ofi_nombre' => 'SERVICIO DE CUIDADOS INTENSIVOS (UCI)',
                'ofi_abreviatura' => 'SERV_UCI',
                'ofi_codigo_referencia' => '03.6.2',
                'ofi_titulo_encargado' => 'M.C. INTENSIVISTA',
                'ofi_nombres_encargado' => 'JEFE DE UCI',
                'ofi_cargo_encargado' => 'JEFE DE SERVICIO',
                'ofi_descripcion' => 'Monitoreo crítico continuo de pacientes inestables de alto riesgo.',
                'ofi_rango' => 4,
                'ofi_padre_ide' => $dep_emergencia_id,
                'ofi_estado' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];
        $db->table('casis_oficina')->insertBatch($serviciosFinales);
    }
}

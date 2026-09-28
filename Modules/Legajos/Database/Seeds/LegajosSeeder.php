<?php

namespace Modules\Legajos\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LegajosSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // ---------------------------------------------------------------
        // 1. Registro en la tabla 'modules'
        // ---------------------------------------------------------------
        if ($db->tableExists('modules')) {
            $existeModulo = $db->table('modules')->where('alias', 'legajos')->countAllResults();
            if ($existeModulo === 0) {
                $db->table('modules')->insert([
                    'alias'       => 'legajos',
                    'nombre'      => 'Legajo Personal',
                    'descripcion' => 'Gestión integral del Legajo Personal y Expediente Digital conforme a directivas de SERVIR',
                    'activo'      => 1,
                    'created_at'  => date('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s'),
                ]);
            }
        }

        // ---------------------------------------------------------------
        // 2. Registro de Menús en la tabla 'menus'
        // ---------------------------------------------------------------
        if ($db->tableExists('menus')) {
            $existeMenu = $db->table('menus')->where('abbr', 'LEGAJOS-MOD')->orWhere('url', 'legajos')->countAllResults();
            if ($existeMenu === 0) {
                // Menú Principal / Agrupador
                $db->table('menus')->insert([
                    'id'        => 600,
                    'type'      => 'primary',
                    'parent_id' => null,
                    'name'      => 'LEGAJO PERSONAL',
                    'abbr'      => 'LEGAJOS-MOD',
                    'url'       => 'legajos',
                    'icon'      => 'solar:folder-with-files-bold-duotone',
                    'order'     => 600,
                    'status'    => 'active',
                    'separator' => 'Recursos Humanos',
                    'created_at'=> date('Y-m-d H:i:s'),
                    'updated_at'=> date('Y-m-d H:i:s'),
                ]);

                // Submenú 1: Servidores Públicos
                $db->table('menus')->insert([
                    'id'        => 601,
                    'type'      => 'secondary',
                    'parent_id' => 600,
                    'name'      => 'Directorio de Servidores',
                    'abbr'      => 'LEG-DIR',
                    'url'       => 'legajos',
                    'icon'      => 'solar:users-group-rounded-bold-duotone',
                    'order'     => 601,
                    'status'    => 'active',
                    'separator' => null,
                    'created_at'=> date('Y-m-d H:i:s'),
                    'updated_at'=> date('Y-m-d H:i:s'),
                ]);
            }
        }

        // ---------------------------------------------------------------
        // 3. Secciones Normativas del Legajo (SERVIR)
        // ---------------------------------------------------------------
        if ($db->tableExists('leg_secciones')) {
            $db->table('leg_secciones')->emptyTable();

            $secciones = [
                [
                    'sec_ide'         => 1,
                    'sec_numero'      => 1,
                    'sec_codigo'      => 'FILIACION',
                    'sec_nombre'      => 'Datos Filiatorios y Familiares',
                    'sec_descripcion' => 'Ficha de datos personales, filiación, derechohabientes (cónyuge, conviviente, hijos), y contactos de emergencia.',
                    'sec_icono'       => 'solar:users-group-two-rounded-bold-duotone',
                    'sec_orden'       => 1,
                    'sec_estado'      => 1,
                    'created_at'      => date('Y-m-d H:i:s'),
                ],
                [
                    'sec_ide'         => 2,
                    'sec_numero'      => 2,
                    'sec_codigo'      => 'ACADEMICO',
                    'sec_nombre'      => 'Formación Académica y Colegiatura',
                    'sec_descripcion' => 'Grados académicos, títulos profesionales, maestrías, doctorados, colegiatura y constancias de habilitación.',
                    'sec_icono'       => 'solar:diploma-verified-bold-duotone',
                    'sec_orden'       => 2,
                    'sec_estado'      => 1,
                    'created_at'      => date('Y-m-d H:i:s'),
                ],
                [
                    'sec_ide'         => 3,
                    'sec_numero'      => 3,
                    'sec_codigo'      => 'EXPERIENCIA',
                    'sec_nombre'      => 'Experiencia Laboral Previa',
                    'sec_descripcion' => 'Trayectoria y récord de servicios en el sector público y privado anteriores a la vinculación actual.',
                    'sec_icono'       => 'solar:case-round-minimalistic-bold-duotone',
                    'sec_orden'       => 3,
                    'sec_estado'      => 1,
                    'created_at'      => date('Y-m-d H:i:s'),
                ],
                [
                    'sec_ide'         => 4,
                    'sec_numero'      => 4,
                    'sec_codigo'      => 'MOVIMIENTOS',
                    'sec_nombre'      => 'Movimientos de Personal y Desplazamientos',
                    'sec_descripcion' => 'Resoluciones de rotación, destaque, reasignación, permuta, encargatura, licencias y descansos vacacionales.',
                    'sec_icono'       => 'solar:transfer-vertical-bold-duotone',
                    'sec_orden'       => 4,
                    'sec_estado'      => 1,
                    'created_at'      => date('Y-m-d H:i:s'),
                ],
                [
                    'sec_ide'         => 5,
                    'sec_numero'      => 5,
                    'sec_codigo'      => 'EVALUACION_CAPACITACION',
                    'sec_nombre'      => 'Evaluaciones del Desempeño y Capacitaciones',
                    'sec_descripcion' => 'Evaluaciones de rendimiento (GDR SERVIR), capacitaciones institucionales, cursos, diplomados y PDP.',
                    'sec_icono'       => 'solar:document-medicine-bold-duotone',
                    'sec_orden'       => 5,
                    'sec_estado'      => 1,
                    'created_at'      => date('Y-m-d H:i:s'),
                ],
                [
                    'sec_ide'         => 6,
                    'sec_numero'      => 6,
                    'sec_codigo'      => 'MERITOS_SANCIONES',
                    'sec_nombre'      => 'Méritos, Reconocimientos y Sanciones (PAD)',
                    'sec_descripcion' => 'Resoluciones de felicitación, reconocimientos institucionales y Procedimientos Administrativos Disciplinarios (PAD).',
                    'sec_icono'       => 'solar:medal-ribbon-star-bold-duotone',
                    'sec_orden'       => 6,
                    'sec_estado'      => 1,
                    'created_at'      => date('Y-m-d H:i:s'),
                ],
            ];

            $db->table('leg_secciones')->insertBatch($secciones);
        }

        // ---------------------------------------------------------------
        // 4. Datos Iniciales de Servidores Públicos (Demostración)
        // ---------------------------------------------------------------
        if ($db->tableExists('leg_servidores')) {
            $existeServidor = $db->table('leg_servidores')->countAllResults();
            if ($existeServidor === 0) {
                // Servidor 1: D.L. 276
                $db->table('leg_servidores')->insert([
                    'ser_ide'                 => 1,
                    'ser_tipo_documento'      => 'DNI',
                    'ser_numero_documento'    => '45892014',
                    'ser_ruc'                 => '10458920141',
                    'ser_nombres'             => 'Carlos Alberto',
                    'ser_apellido_paterno'    => 'Mendoza',
                    'ser_apellido_materno'    => 'Ríos',
                    'ser_sexo'                => 'M',
                    'ser_fecha_nacimiento'    => '1982-04-15',
                    'ser_estado_civil'        => 'CASADO',
                    'ser_grupo_sanguineo'     => 'O+',
                    'ser_celular'             => '984512365',
                    'ser_telefono_fijo'       => '014852369',
                    'ser_email_institucional' => 'cmendoza@institucion.gob.pe',
                    'ser_email_personal'      => 'carlos.mendoza.rios@gmail.com',
                    'ser_direccion'           => 'Av. Los Próceres 1245, San Borja, Lima',
                    'ser_ubigeo'              => '150130',
                    'ser_foto'                => null,
                    'ser_regimen_laboral'     => 'D.L. 276',
                    'ser_condicion_laboral'   => 'NOMBRADO',
                    'ser_cargo'               => 'Especialista en Planificación Pública III',
                    'ser_dependencia'         => 'Oficina de Planeamiento y Presupuesto',
                    'ser_fecha_ingreso'       => '2012-03-01',
                    'ser_numero_legajo'       => 'LEG-2012-0045',
                    'ser_estado'              => 'ACTIVO',
                    'ser_observaciones'       => 'Servidor de carrera con nivel F-2.',
                    'created_at'              => date('Y-m-d H:i:s'),
                ]);

                // Familiares Servidor 1
                $db->table('leg_familiares')->insertBatch([
                    [
                        'fam_ser_ide'                => 1,
                        'fam_parentesco'             => 'CONYUGE',
                        'fam_tipo_documento'         => 'DNI',
                        'fam_numero_documento'       => '47120365',
                        'fam_nombres'                => 'Elena Patricia',
                        'fam_apellido_paterno'       => 'Vargas',
                        'fam_apellido_materno'       => 'Salas',
                        'fam_fecha_nacimiento'       => '1985-07-22',
                        'fam_sexo'                   => 'F',
                        'fam_es_derechohabiente'     => 1,
                        'fam_es_contacto_emergencia' => 1,
                        'fam_telefono'               => '974125896',
                        'fam_direccion'              => 'Av. Los Próceres 1245, San Borja, Lima',
                        'created_at'                 => date('Y-m-d H:i:s'),
                    ],
                    [
                        'fam_ser_ide'                => 1,
                        'fam_parentesco'             => 'HIJO(A)',
                        'fam_tipo_documento'         => 'DNI',
                        'fam_numero_documento'       => '78965412',
                        'fam_nombres'                => 'Mateo',
                        'fam_apellido_paterno'       => 'Mendoza',
                        'fam_apellido_materno'       => 'Vargas',
                        'fam_fecha_nacimiento'       => '2016-11-10',
                        'fam_sexo'                   => 'M',
                        'fam_es_derechohabiente'     => 1,
                        'fam_es_contacto_emergencia' => 0,
                        'fam_telefono'               => null,
                        'fam_direccion'              => 'Av. Los Próceres 1245, San Borja, Lima',
                        'created_at'                 => date('Y-m-d H:i:s'),
                    ]
                ]);

                // Formación Académica Servidor 1
                $db->table('leg_formacion_academica')->insertBatch([
                    [
                        'for_ser_ide'              => 1,
                        'for_nivel_educativo'      => 'TITULADO',
                        'for_institucion'          => 'Universidad Nacional Mayor de San Marcos',
                        'for_carrera_especialidad' => 'Economía',
                        'for_grado_obtenido'       => 'Licenciado en Economía',
                        'for_fecha_expedicion'     => '2007-06-18',
                        'for_colegio_profesional'  => 'Colegio de Economistas de Lima',
                        'for_numero_colegiatura'   => 'CEL-14892',
                        'for_es_habilitado'        => 1,
                        'for_pais'                 => 'PERÚ',
                        'for_registro_sunedu'      => 'UNMSM-ECO-2007-124',
                        'created_at'               => date('Y-m-d H:i:s'),
                    ],
                    [
                        'for_ser_ide'              => 1,
                        'for_nivel_educativo'      => 'MAESTRIA',
                        'for_institucion'          => 'Universidad del Pacífico',
                        'for_carrera_especialidad' => 'Gestión Pública y Políticas Sociales',
                        'for_grado_obtenido'       => 'Magíster en Gestión Pública',
                        'for_fecha_expedicion'     => '2015-12-05',
                        'for_colegio_profesional'  => null,
                        'for_numero_colegiatura'   => null,
                        'for_es_habilitado'        => 1,
                        'for_pais'                 => 'PERÚ',
                        'for_registro_sunedu'      => 'UP-MGP-2015-089',
                        'created_at'               => date('Y-m-d H:i:s'),
                    ]
                ]);

                // Movimientos Servidor 1
                $db->table('leg_movimientos_personal')->insert([
                    'mov_ser_ide'                 => 1,
                    'mov_tipo'                    => 'ROTACION',
                    'mov_tipo_documento_sustento' => 'RESOLUCION DIRECTORAL',
                    'mov_numero_documento'        => 'RD N° 045-2024-MINSA/OGGRH',
                    'mov_fecha_documento'         => '2024-01-15',
                    'mov_dependencia_origen'      => 'Dirección de Presupuesto',
                    'mov_dependencia_destino'     => 'Oficina de Planeamiento y Presupuesto',
                    'mov_cargo_destino'           => 'Especialista en Planificación Pública III',
                    'mov_fecha_inicio'            => '2024-02-01',
                    'mov_dias_computados'         => 0,
                    'mov_motivo_detalle'          => 'Rotación por necesidad del servicio en el marco de la modernización institucional.',
                    'created_at'                  => date('Y-m-d H:i:s'),
                ]);

                // Evaluaciones y Capacitaciones Servidor 1
                $db->table('leg_evaluaciones_capacitaciones')->insert([
                    'evc_ser_ide'                 => 1,
                    'evc_tipo'                    => 'CAPACITACION',
                    'evc_titulo'                  => 'Diplomado en Gestión de Inversión Pública (Invierte.pe)',
                    'evc_institucion_organizadora'=> 'Escuela Nacional de Administración Pública - ENAP / SERVIR',
                    'evc_tipo_evento'             => 'DIPLOMADO',
                    'evc_fecha_inicio'            => '2023-04-10',
                    'evc_fecha_fin'               => '2023-09-20',
                    'evc_horas_academicas'        => 180,
                    'evc_creditos'                => 10.00,
                    'evc_calificacion_obtenida'   => '19 - Sobresaliente',
                    'evc_es_financiado_entidad'   => 1,
                    'created_at'                  => date('Y-m-d H:i:s'),
                ]);

                // Méritos Servidor 1
                $db->table('leg_meritos_sanciones')->insert([
                    'msa_ser_ide'           => 1,
                    'msa_tipo'              => 'MERITO',
                    'msa_subtipo'           => 'FELICITACION',
                    'msa_acto_resolutivo'   => 'Resolución Ministerial N° 312-2023-MINSA',
                    'msa_fecha_acto'        => '2023-12-15',
                    'msa_entidad_emisora'   => 'Ministerio de Salud',
                    'msa_descripcion_motivo'=> 'Reconocimiento y felicitación por su destacada labor y cumplimiento de metas del POI 2023.',
                    'created_at'            => date('Y-m-d H:i:s'),
                ]);

                // Servidor 2: D.L. 1057 (CAS)
                $db->table('leg_servidores')->insert([
                    'ser_ide'                 => 2,
                    'ser_tipo_documento'      => 'DNI',
                    'ser_numero_documento'    => '70852369',
                    'ser_ruc'                 => '10708523694',
                    'ser_nombres'             => 'Lucía Gabriela',
                    'ser_apellido_paterno'    => 'Alvarado',
                    'ser_apellido_materno'    => 'Paredes',
                    'ser_sexo'                => 'F',
                    'ser_fecha_nacimiento'    => '1993-09-28',
                    'ser_estado_civil'        => 'SOLTERO',
                    'ser_grupo_sanguineo'     => 'A+',
                    'ser_celular'             => '991245781',
                    'ser_telefono_fijo'       => null,
                    'ser_email_institucional' => 'lalvarado@institucion.gob.pe',
                    'ser_email_personal'      => 'lucia.alvarado.p@outlook.com',
                    'ser_direccion'           => 'Calle Los Sauces 410, Jesús María, Lima',
                    'ser_ubigeo'              => '150113',
                    'ser_foto'                => null,
                    'ser_regimen_laboral'     => 'D.L. 1057 (CAS)',
                    'ser_condicion_laboral'   => 'CONTRATADO PLAZO DETERMINADO',
                    'ser_cargo'               => 'Analista de Recursos Humanos',
                    'ser_dependencia'         => 'Oficina General de Gestión de Recursos Humanos',
                    'ser_fecha_ingreso'       => '2021-06-15',
                    'ser_numero_legajo'       => 'LEG-2021-0188',
                    'ser_estado'              => 'ACTIVO',
                    'ser_observaciones'       => 'Contrato CAS vinculado a convocatoria pública CAS N° 012-2021.',
                    'created_at'              => date('Y-m-d H:i:s'),
                ]);

                // Formación Académica Servidor 2
                $db->table('leg_formacion_academica')->insert([
                    'for_ser_ide'              => 2,
                    'for_nivel_educativo'      => 'TITULADO',
                    'for_institucion'          => 'Pontificia Universidad Católica del Perú',
                    'for_carrera_especialidad' => 'Psicología Organizacional',
                    'for_grado_obtenido'       => 'Licenciada en Psicología',
                    'for_fecha_expedicion'     => '2017-08-20',
                    'for_colegio_profesional'  => 'Colegio de Psicólogos del Perú',
                    'for_numero_colegiatura'   => 'CPsP-32145',
                    'for_es_habilitado'        => 1,
                    'for_pais'                 => 'PERÚ',
                    'for_registro_sunedu'      => 'PUCP-PSI-2017-567',
                    'created_at'               => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}


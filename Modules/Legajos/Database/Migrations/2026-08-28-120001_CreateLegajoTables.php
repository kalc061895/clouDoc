<?php

namespace Modules\Legajos\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLegajoTables extends Migration
{
    public function up()
    {
        // ---------------------------------------------------------------
        // 0. Tabla: modules (Asegurar existencia si no existe)
        // ---------------------------------------------------------------
        if (!$this->db->tableExists('modules')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'alias' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'unique'     => true,
                ],
                'nombre' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 150,
                ],
                'descripcion' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                ],
                'activo' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 1,
                ],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
                'deleted_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->createTable('modules', true);
        }

        // ---------------------------------------------------------------
        // 1. Tabla: leg_secciones (Catálogo de Secciones Normativas SERVIR)
        // ---------------------------------------------------------------
        $this->forge->addField([
            'sec_ide' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'sec_numero' => [
                'type'       => 'INT',
                'constraint' => 2,
            ],
            'sec_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'sec_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'sec_descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'sec_icono' => [
                'type'       => 'VARCHAR',
                'constraint' => 60,
                'default'    => 'solar:folder-bold-duotone',
            ],
            'sec_orden' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 1,
            ],
            'sec_estado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('sec_ide', true);
        $this->forge->addUniqueKey('sec_codigo');
        $this->forge->createTable('leg_secciones', true);

        // ---------------------------------------------------------------
        // 2. Tabla: leg_servidores (Datos Personales y Vinculación Laboral)
        // ---------------------------------------------------------------
        $this->forge->addField([
            'ser_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ser_tipo_documento' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'DNI', // DNI, CE, PASAPORTE
            ],
            'ser_numero_documento' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'ser_ruc' => [
                'type'       => 'VARCHAR',
                'constraint' => 11,
                'null'       => true,
            ],
            'ser_nombres' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'ser_apellido_paterno' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'ser_apellido_materno' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'ser_sexo' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'default'    => 'M', // M, F
            ],
            'ser_fecha_nacimiento' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'ser_estado_civil' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'SOLTERO', // SOLTERO, CASADO, CONVIVIENTE, DIVORCIADO, VIUDO
            ],
            'ser_grupo_sanguineo' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
            ],
            'ser_celular' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'ser_telefono_fijo' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'ser_email_institucional' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'ser_email_personal' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'ser_direccion' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'ser_ubigeo' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
            ],
            'ser_foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            // Datos Laborales y Vinculación
            'ser_regimen_laboral' => [
                'type'       => 'VARCHAR',
                'constraint' => 60,
                'default'    => 'D.L. 1057 (CAS)', // D.L. 276, D.L. 728, D.L. 1057 (CAS), LEY 30057 (SERVIR), LOCACION DE SERVICIOS
            ],
            'ser_condicion_laboral' => [
                'type'       => 'VARCHAR',
                'constraint' => 60,
                'default'    => 'CONTRATADO', // NOMBRADO, CONTRATADO PLAZO INDETERMINADO, CONTRATADO PLAZO DETERMINADO, DESIGNADO, CONFIANZA
            ],
            'ser_cargo' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'ser_dependencia' => [
                'type'       => 'VARCHAR',
                'constraint' => 150, // Oficina / Unidad Orgánica
            ],
            'ser_fecha_ingreso' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'ser_fecha_cese' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'ser_numero_legajo' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'ser_estado' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'ACTIVO', // ACTIVO, CESADO, SUSPENDIDO, LICENCIA
            ],
            'ser_observaciones' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            // Auditoría
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
            'created_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'updated_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'deleted_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addKey('ser_ide', true);
        $this->forge->addUniqueKey('ser_numero_documento');
        $this->forge->createTable('leg_servidores', true);

        // ---------------------------------------------------------------
        // 3. Tabla: leg_familiares (Sección 1: Filiación y Familiares)
        // ---------------------------------------------------------------
        $this->forge->addField([
            'fam_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'fam_ser_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'fam_parentesco' => [
                'type'       => 'VARCHAR',
                'constraint' => 30, // CONYUGE, CONVIVIENTE, HIJO(A), PADRE, MADRE, HERMANO(A), OTRO
            ],
            'fam_tipo_documento' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'DNI',
            ],
            'fam_numero_documento' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'fam_nombres' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'fam_apellido_paterno' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'fam_apellido_materno' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'fam_fecha_nacimiento' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'fam_sexo' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'default'    => 'M',
            ],
            'fam_es_derechohabiente' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'fam_es_contacto_emergencia' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'fam_telefono' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'fam_direccion' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'fam_adjunto_sustento' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true, // Ruta acta matrimonio/nacimiento en PDF
            ],
            // Auditoría
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
            'created_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'updated_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'deleted_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addKey('fam_ide', true);
        $this->forge->addKey('fam_ser_ide');
        $this->forge->createTable('leg_familiares', true);

        // ---------------------------------------------------------------
        // 4. Tabla: leg_formacion_academica (Sección 2: Formación Académica)
        // ---------------------------------------------------------------
        $this->forge->addField([
            'for_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'for_ser_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'for_nivel_educativo' => [
                'type'       => 'VARCHAR',
                'constraint' => 60, // SECUNDARIA, TECNICO, BACHILLER, TITULADO, MAESTRIA, DOCTORADO, SEGUNDA ESPECIALIDAD
            ],
            'for_institucion' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],
            'for_carrera_especialidad' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],
            'for_grado_obtenido' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'for_fecha_expedicion' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'for_colegio_profesional' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'for_numero_colegiatura' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'for_es_habilitado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'for_fecha_habilitacion_vigencia' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'for_pais' => [
                'type'       => 'VARCHAR',
                'constraint' => 80,
                'default'    => 'PERÚ',
            ],
            'for_registro_sunedu' => [
                'type'       => 'VARCHAR',
                'constraint' => 80,
                'null'       => true,
            ],
            'for_adjunto_sustento' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            // Auditoría
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
            'created_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'updated_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'deleted_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addKey('for_ide', true);
        $this->forge->addKey('for_ser_ide');
        $this->forge->createTable('leg_formacion_academica', true);

        // ---------------------------------------------------------------
        // 5. Tabla: leg_experiencia_laboral (Sección 3: Experiencia Previa)
        // ---------------------------------------------------------------
        $this->forge->addField([
            'exp_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'exp_ser_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'exp_tipo_entidad' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'PUBLICA', // PUBLICA, PRIVADA
            ],
            'exp_entidad_empresa' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],
            'exp_cargo_desempenado' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'exp_unidad_organica' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'exp_fecha_inicio' => [
                'type' => 'DATE',
            ],
            'exp_fecha_fin' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'exp_tiempo_anios' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 0,
            ],
            'exp_tiempo_meses' => [
                'type'       => 'INT',
                'constraint' => 2,
                'default'    => 0,
            ],
            'exp_tiempo_dias' => [
                'type'       => 'INT',
                'constraint' => 2,
                'default'    => 0,
            ],
            'exp_funciones_principales' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'exp_motivo_cese' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'exp_adjunto_sustento' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            // Auditoría
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
            'created_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'updated_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'deleted_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addKey('exp_ide', true);
        $this->forge->addKey('exp_ser_ide');
        $this->forge->createTable('leg_experiencia_laboral', true);

        // ---------------------------------------------------------------
        // 6. Tabla: leg_movimientos_personal (Sección 4: Desplazamientos/Licencias)
        // ---------------------------------------------------------------
        $this->forge->addField([
            'mov_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'mov_ser_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'mov_tipo' => [
                'type'       => 'VARCHAR',
                'constraint' => 60, // ROTACION, REASIGNACION, DESTAQUE, PERMUTA, ENCARGATURA, COMISION_SERVICIO, LICENCIA_CON_GOCE, LICENCIA_SIN_GOCE, VACACIONES, SUSPENSION
            ],
            'mov_tipo_documento_sustento' => [
                'type'       => 'VARCHAR',
                'constraint' => 60, // RESOLUCION DIRECTORAL, RESOLUCION PRESIDENCIAL, MEMORANDO, OFICIO
                'default'    => 'RESOLUCION DIRECTORAL',
            ],
            'mov_numero_documento' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'mov_fecha_documento' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'mov_dependencia_origen' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'mov_dependencia_destino' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'mov_cargo_destino' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'mov_fecha_inicio' => [
                'type' => 'DATE',
            ],
            'mov_fecha_fin' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'mov_dias_computados' => [
                'type'       => 'INT',
                'constraint' => 4,
                'default'    => 0,
            ],
            'mov_motivo_detalle' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'mov_adjunto_sustento' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            // Auditoría
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
            'created_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'updated_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'deleted_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addKey('mov_ide', true);
        $this->forge->addKey('mov_ser_ide');
        $this->forge->createTable('leg_movimientos_personal', true);

        // ---------------------------------------------------------------
        // 7. Tabla: leg_evaluaciones_capacitaciones (Sección 5: Eval/Capacitaciones)
        // ---------------------------------------------------------------
        $this->forge->addField([
            'evc_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'evc_ser_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'evc_tipo' => [
                'type'       => 'VARCHAR',
                'constraint' => 40,
                'default'    => 'CAPACITACION', // CAPACITACION, EVALUACION_DESEMPENO, CERTIFICACION
            ],
            'evc_titulo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'evc_institucion_organizadora' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],
            'evc_tipo_evento' => [
                'type'       => 'VARCHAR',
                'constraint' => 60, // CURSO, TALLER, DIPLOMADO, SEMINARIO, CONGRESO, PASANTIA, GDR
                'default'    => 'CURSO',
            ],
            'evc_fecha_inicio' => [
                'type' => 'DATE',
            ],
            'evc_fecha_fin' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'evc_horas_academicas' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 0,
            ],
            'evc_creditos' => [
                'type'       => 'DECIMAL',
                'constraint' => '4,2',
                'default'    => 0.00,
            ],
            'evc_calificacion_obtenida' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true, // Nota 18, Satisfactorio, Aprobado
            ],
            'evc_es_financiado_entidad' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0, // 1 si pertenece a PDP institucional
            ],
            'evc_adjunto_sustento' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            // Auditoría
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
            'created_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'updated_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'deleted_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addKey('evc_ide', true);
        $this->forge->addKey('evc_ser_ide');
        $this->forge->createTable('leg_evaluaciones_capacitaciones', true);

        // ---------------------------------------------------------------
        // 8. Tabla: leg_meritos_sanciones (Sección 6: Méritos y PAD)
        // ---------------------------------------------------------------
        $this->forge->addField([
            'msa_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'msa_ser_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'msa_tipo' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'MERITO', // MERITO, SANCION
            ],
            'msa_subtipo' => [
                'type'       => 'VARCHAR',
                'constraint' => 80, // FELICITACION, RECONOCIMIENTO, AMONESTACION_VERBAL, AMONESTACION_ESCRITA, SUSPENSION_PAD, DESTITUCION_PAD, INHABILITACION
            ],
            'msa_acto_resolutivo' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
            ],
            'msa_fecha_acto' => [
                'type' => 'DATE',
            ],
            'msa_entidad_emisora' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],
            'msa_numero_expediente_pad' => [
                'type'       => 'VARCHAR',
                'constraint' => 80,
                'null'       => true,
            ],
            'msa_descripcion_motivo' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'msa_periodo_sancion_dias' => [
                'type'       => 'INT',
                'constraint' => 4,
                'default'    => 0,
            ],
            'msa_fecha_inicio_efecto' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'msa_fecha_fin_efecto' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'msa_esta_rehabilitado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'msa_adjunto_sustento' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            // Auditoría
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
            'created_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'updated_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'deleted_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addKey('msa_ide', true);
        $this->forge->addKey('msa_ser_ide');
        $this->forge->createTable('leg_meritos_sanciones', true);

        // ---------------------------------------------------------------
        // 9. Tabla: leg_documentos_digitales (Expediente Digital / Repositorio)
        // ---------------------------------------------------------------
        $this->forge->addField([
            'doc_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'doc_ser_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'doc_sec_ide' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'doc_titulo' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],
            'doc_numero_folio' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'doc_fecha_emision' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'doc_ruta_archivo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'doc_nombre_original' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'doc_mime_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'application/pdf',
            ],
            'doc_peso_kb' => [
                'type'       => 'INT',
                'constraint' => 10,
                'default'    => 0,
            ],
            'doc_observacion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            // Auditoría
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
            'created_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'updated_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'deleted_by' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addKey('doc_ide', true);
        $this->forge->addKey('doc_ser_ide');
        $this->forge->createTable('leg_documentos_digitales', true);
    }

    public function down()
    {
        $this->forge->dropTable('leg_documentos_digitales', true);
        $this->forge->dropTable('leg_meritos_sanciones', true);
        $this->forge->dropTable('leg_evaluaciones_capacitaciones', true);
        $this->forge->dropTable('leg_movimientos_personal', true);
        $this->forge->dropTable('leg_experiencia_laboral', true);
        $this->forge->dropTable('leg_formacion_academica', true);
        $this->forge->dropTable('leg_familiares', true);
        $this->forge->dropTable('leg_servidores', true);
        $this->forge->dropTable('leg_secciones', true);
    }
}


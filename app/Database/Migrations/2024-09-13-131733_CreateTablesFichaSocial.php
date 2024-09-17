<?php


namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTablesFichaSocial extends Migration
{
    public function up()
    {
        // Table for TRABAJO
        $this->forge->addField([

            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'dato_personal_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'fecha_ingreso' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'numero_contrato' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'resolucion_nombramiento' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'unidad' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'nivel' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'establecimiento' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'microred' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'servicio' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'area' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'cargo' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'profesion' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'especialidad' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'modalidad' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'pension' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'pension_cuenta' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'remuneracion' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'numero_historia_sst' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'numero_legajo' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('datos_trabajo');

        // Table for DATOS_PERSONALES
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'apellido_paterno' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'apellido_materno' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'nombres' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'foto' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'sexo' => [
                'type' => 'ENUM',
                'constraint' => ['M', 'F'],
                'null' => true,
            ],
            'nacionalidad' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'fecha_nacimiento' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'edad' => [
                'type' => 'INT',
                'null' => true,
            ],
            'dni' => [
                'type' => 'VARCHAR',
                'constraint' => '15',
                'null' => true,
            ],
            'carne_extranjeria' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
            ],
            'licencia_conducir' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
            ],
            'estado_civil' => [
                'type' => 'ENUM',
                'constraint' => ['soltero', 'convivencia', 'casado', 'separado', 'viudo', 'divorciado'],
                'null' => true,
            ],
            'numero_hijos' => [
                'type' => 'INT',
                'null' => true,
            ],
            'domicilio_actual' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'distrito' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'provincia' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'departamento' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'telefono_celular' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
            ],
            'telefono_fijo' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
            ],
            'correo_electronico' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'RUC' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
            ],
            'banco_nombre' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'numero_cuenta' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'persona_emergencia' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'telefono_emergencia' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('datos_personales');

        // Table for FAMILIA
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'dato_personal_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'fam_apellidos_nombres' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'fam_parentesco' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'fam_fecha_nacimiento' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'fam_estado_civil' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'fam_grado_instruccion' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'fam_profesion' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'fam_vive_con_usted' => [
                'type' => 'ENUM',
                'constraint' => ['si', 'no'],
                'null' => true,
            ],
            'fam_dependencia_economica' => [
                'type' => 'ENUM',
                'constraint' => ['si', 'no'],
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('dato_personal_id', 'datos_personales', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('datos_familia');

        // Table for EDUCACION
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'dato_personal_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'e_centro_estudios' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'e_grado_instruccion' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'e_anios_cursados' => [
                'type' => 'INT',
                'null' => true,
            ],
            'e_titulo_obtenido' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'e_observaciones' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('dato_personal_id', 'datos_personales', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('datos_educacion');

        // Table for VIVIENDA Y COMUNIDAD
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'dato_personal_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'viv_ubicacion' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'viv_tenencia' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'viv_material' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'viv_servicios_luz' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'viv_servicios_agua' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'viv_servicios_telefono' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'viv_servicios_desague' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'viv_servicios_cable' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'viv_servicios_internet' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'viv_servicios_otros' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'viv_problemas_drogadiccion' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'viv_problemas_pandillas' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'viv_problemas_robos' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'viv_problemas_alcoholismo' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'viv_problemas_otros' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('dato_personal_id', 'datos_personales', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('datos_vivienda_comunidad');

        // Table for DATOS DE SALUD
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'dato_personal_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'grupo_sanguineo' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'discapacidad' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'carnet_conadis' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'gestante' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'aler_betalactamicos' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'aler_penicilina' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'aler_analgesicos' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'aler_ketorolaco' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'aler_otras' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'enf_tbc' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'enf_cancer' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'enf_ets' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'enf_vih' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'enf_alteraciones_mentales' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'enf_diabetes' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'enf_hipertension' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'enf_asma' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'enf_epilepsia' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'enf_otras' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'med_actuales' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('dato_personal_id', 'datos_personales', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('datos_salud');

        // Table for DATOS ECONOMICOS
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'dato_personal_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'ingreso_mensual_trabajador' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'ingreso_mensual_conyugue' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'ingreso_mensual_otros' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'egreso_servicios_agua' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'egreso_servicios_luz' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'egreso_servicios_telefono' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'egreso_servicios_cable' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'egreso_servicios_internet' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'egreso_servicios_otros' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'egreso_servicios_total' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'egreso_familiar_alimentacion' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'egreso_familiar_medicamento' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'egreso_familiar_vestimenta' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'egreso_familiar_educacion' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'egreso_familiar_movilidad' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'egreso_familiar_otros' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'egreso_familiar_total' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'egreso_otros_deudas' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'egreso_otros_seguro_salud' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'egreso_otros_seguro_vida' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'egreso_otros_seguro_sepelio' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'egreso_otros_vehiculo' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'egreso_otros_otros' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'egreso_otros_total' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('dato_personal_id', 'datos_personales', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('datos_economicos');
    }

    public function down()
    {
        $this->forge->dropTable('datos_economicos');
        $this->forge->dropTable('datos_salud');
        $this->forge->dropTable('datos_vivienda_comunidad');
        $this->forge->dropTable('datos_educacion');
        $this->forge->dropTable('datos_familia');
        $this->forge->dropTable('datos_trabajo');
        $this->forge->dropTable('datos_personales');
    }
}

<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePersonaHistorial extends Migration
{
    public function up()
    {
        // 1. HISTORIAL: casis_persona
        $this->forge->addField([
            'hist_per_ide' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'per_ide' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'per_tdi_ide' => ['type' => 'VARCHAR', 'constraint' => 5, 'null' => true],
            'per_numero_documento' => ['type' => 'VARCHAR', 'constraint' => 15, 'null' => true],
            'per_paterno' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'per_materno' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'per_nombre' => ['type' => 'VARCHAR', 'constraint' => 15, 'null' => true],
            'per_foto' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'per_sexo' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'per_lugar_nacimiento' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'per_fecha_nacimiento' => ['type' => 'DATE', 'null' => true],
            'per_residencia' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'per_ruc' => ['type' => 'VARCHAR', 'constraint' => 11, 'null' => true],
            'per_telefono' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'per_email' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'per_estadocivil' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'per_ingreso' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            // Meta Historial
            'hist_accion' => ['type' => 'ENUM', 'constraint' => ['INSERT', 'UPDATE', 'DELETE']],
            'hist_hecho_por' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'hist_creado_en' => ['type' => 'DATETIME', 'null' => true],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'deleted_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true]

        ]);
        $this->forge->addKey('hist_per_ide', true);
        $this->forge->addKey('per_ide');
        $this->forge->createTable('casis_persona_historial', true);

        // 2. HISTORIAL: casis_personal
        $this->forge->addField([
            'hist_perl_ide' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'perl_ide' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'perl_per_ide' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'perl_est_ide' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'perl_ofi_ide' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'perl_car_ide' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'perl_mco_ide' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'perl_se_ide' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'perl_codigo' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'perl_fecha_inicio' => ['type' => 'DATE', 'null' => true],
            'perl_fecha_termino' => ['type' => 'DATE', 'null' => true],
            'perl_fecha_cese' => ['type' => 'DATE','null' => true,],
            'perl_numero_colegiatura' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'perl_plaza' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'perl_nivel' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'perl_estado' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'perl_regimen_laboral' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'perl_observacion' => ['type' => 'TEXT', 'null' => true],
            // Meta Historial
            'hist_accion' => ['type' => 'ENUM', 'constraint' => ['INSERT', 'UPDATE', 'DELETE']],
            'hist_hecho_por' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'hist_creado_en' => ['type' => 'DATETIME', 'null' => true],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'deleted_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true]
        ]);
        $this->forge->addKey('hist_perl_ide', true);
        $this->forge->addKey('perl_ide');
        $this->forge->createTable('casis_personal_historial', true);

        // 3. HISTORIAL: casis_personal_profesion
        $this->forge->addField([
            'hist_pp_ide' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'pp_ide' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'pp_perl_ide' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'pp_principal' => ['type' => 'TINYINT', 'constraint' => 1, 'null' => true],
            'pp_pro_ide' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'pp_col_ide' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'pp_se_ide' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'pp_numero_colegiatura' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'pp_rne' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'pp_habilitado' => ['type' => 'TINYINT', 'constraint' => 1, 'null' => true],
            'pp_fecha_habilitacion' => ['type' => 'DATE', 'null' => true],
            'pp_fecha_vencimiento' => ['type' => 'DATE', 'null' => true],
            'pp_observacion' => ['type' => 'TEXT', 'null' => true],
            // Meta Historial
            'hist_accion' => ['type' => 'ENUM', 'constraint' => ['INSERT', 'UPDATE', 'DELETE']],
            'hist_hecho_por' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'hist_creado_en' => ['type' => 'DATETIME', 'null' => true],
            'created_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'deleted_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true]
        ]);
        $this->forge->addKey('hist_pp_ide', true);
        $this->forge->addKey('pp_ide');
        $this->forge->createTable('casis_personal_profesion_historial', true);
    }

    public function down()
    {
        $this->forge->dropTable('casis_personal_profesion_historial', true);
        $this->forge->dropTable('casis_personal_historial', true);
        $this->forge->dropTable('casis_persona_historial', true);
    }
}

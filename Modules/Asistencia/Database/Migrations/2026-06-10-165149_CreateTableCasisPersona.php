<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableCasisPersona extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'per_ide' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'per_tdi_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                
            ],

            'per_numero_documento' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
            ],

            'per_paterno' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'per_materno' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'per_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'per_foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],

            'per_sexo' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],

            'per_lugar_nacimiento' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => true,
            ],

            'per_fecha_nacimiento' => [
                'type' => 'DATE',
                'null' => true,
            ],

            'per_residencia' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => true,
            ],

            'per_ruc' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
                'null'       => true,
            ],

            'per_telefono' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'per_email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'per_estadocivil' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'per_ingreso' => [
                'type' => 'DATE',
                'null' => true,
            ],

            'per_user' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],

            'per_pass' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],

            'per_fecha_registro' => [
                'type'    => 'TIMESTAMP',
                'null'    => true,
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

        $this->forge->addKey('per_ide', true);
        $this->forge->addKey('per_tdi_ide');
        
        $this->forge->addForeignKey(
            'per_tdi_ide',
            'casis_tipo_documento_identidad',
            'tdi_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->addUniqueKey(
            'per_numero_documento', 
            'uk_per_numero_documento'
            );

        $this->forge->createTable('casis_persona');
    }

    public function down()
    {
        $this->forge->dropTable('casis_persona', true);
    }
}

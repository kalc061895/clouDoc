<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableRegistroLicencia extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'rl_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'rl_lic_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'rl_perl_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'rl_motivo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],

            'rl_fecha_inicio' => [
                'type' => 'DATE',
                'null' => false,
            ],

            'rl_fecha_fin' => [
                'type' => 'DATE',
                'null' => false,
            ],

            'rl_justificacion' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'rl_numero_documento' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'rl_fecha_documento' => [
                'type' => 'DATE',
                'null' => true,
            ],

            'rl_archivo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],

            'rl_estado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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

            'created_by' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'updated_by' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'deleted_by' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
        ]);

        $this->forge->addKey('rl_ide', true);

        $this->forge->addKey('rl_lic_ide');
        $this->forge->addKey('rl_perl_ide');

        $this->forge->addForeignKey(
            'rl_lic_ide',
            'casis_licencia',
            'lic_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'rl_perl_ide',
            'casis_personal',
            'perl_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->createTable('casis_registro_licencia');
    }

    public function down()
    {
        $this->forge->dropTable('casis_registro_licencia', true);
    }
}

<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableUpssServicio extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'uss_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'uss_ups_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'uss_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],

            'uss_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],

            'uss_abreviatura' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],

            'uss_estado' => [
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
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],

            'updated_by' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],

            'deleted_by' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],
        ]);

        $this->forge->addKey('uss_ide', true);

        $this->forge->addKey('uss_ups_ide');

        $this->forge->addForeignKey(
            'uss_ups_ide',
            'casis_upss',
            'ups_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->createTable('casis_upss_servicio');
    }

    public function down()
    {
        $this->forge->dropTable('casis_upss_servicio', true);
    }
}

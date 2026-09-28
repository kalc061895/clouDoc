<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableCasisRed extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'red_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'red_dir_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'red_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],

            'red_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],

            'red_director' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],

            'red_ubicacion' => [
                'type'       => 'VARCHAR',
                'constraint' => 250,
                'null'       => true,
            ],

            'red_telefono' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],

            'red_email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
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

        $this->forge->addKey('red_ide', true);

        $this->forge->addKey('red_dir_ide');

        $this->forge->addUniqueKey(
            ['red_dir_ide', 'red_nombre'],
            'uk_red_diresa_nombre'
        );

        $this->forge->addForeignKey(
            'red_dir_ide',
            'casis_diresa',
            'dir_ide',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('casis_red');
    }

    public function down()
    {
        $this->forge->dropTable('casis_red');
    }
}

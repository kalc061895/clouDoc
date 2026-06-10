<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableCasisMicroRed extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'mic_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'mic_red_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'mic_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],

            'mic_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],

            'mic_director' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],

            'mic_ubicacion' => [
                'type'       => 'VARCHAR',
                'constraint' => 250,
                'null'       => true,
            ],

            'mic_telefono' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],

            'mic_email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            // Auditoría
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

        $this->forge->addKey('mic_ide', true);

        $this->forge->addKey('mic_red_ide');

        $this->forge->addUniqueKey(
            ['mic_red_ide', 'mic_nombre'],
            'uk_microred_red_nombre'
        );

        $this->forge->addForeignKey(
            'mic_red_ide',
            'casis_red',
            'red_ide',
            'RESTRICT',
            'RESTRICT'
        );

        $this->forge->createTable('casis_microred');
    }

    public function down()
    {
        $this->forge->dropTable('casis_microred');
    }
}

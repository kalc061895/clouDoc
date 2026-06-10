<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableTurno extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'tur_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'tur_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],

            'tur_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],

            'tur_color' => [
                'type'       => 'VARCHAR',
                'constraint' => 7,
                'null'       => true,
            ],

            'tur_estado' => [
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
        ]);

        $this->forge->addKey('tur_ide', true);
        $this->forge->addUniqueKey('tur_codigo', 'uk_tur_codigo');

        $this->forge->createTable('casis_turno');
    }

    public function down()
    {
        $this->forge->dropTable('casis_turno');
    }
}

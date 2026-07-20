<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableCasisEstablecimiento extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'est_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'est_mic_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'est_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],

            'est_ipress' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],

            'est_tipo' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],

            'est_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],

            'est_categoria' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],

            'est_ubigeo' => [
                'type'       => 'VARCHAR',
                'constraint' => 6,
                'null'       => true,
            ],

            'est_latitud' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,8',
                'null'       => true,
            ],

            'est_longitud' => [
                'type'       => 'DECIMAL',
                'constraint' => '11,8',
                'null'       => true,
            ],

            'est_radio' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 100,
            ],

            'est_foto_principal' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
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

        $this->forge->addKey('est_ide', true);

        $this->forge->addKey('est_mic_ide');

        $this->forge->addUniqueKey('est_codigo');

        $this->forge->addForeignKey(
            'est_mic_ide',
            'casis_microred',
            'mic_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->createTable('casis_establecimiento');
    }

    public function down()
    {
        $this->forge->dropTable('casis_establecimiento');
    }
}

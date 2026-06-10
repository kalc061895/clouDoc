<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableEstablecimientoUpss extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'eup_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'eup_est_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'eup_ups_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'eup_estado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],

            'eup_observacion' => [
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

        $this->forge->addKey('eup_ide', true);

        $this->forge->addUniqueKey(
            ['eup_est_ide', 'eup_ups_ide'],
            'uk_establecimiento_upss'
        );

        $this->forge->addForeignKey(
            'eup_est_ide',
            'casis_establecimiento',
            'est_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'eup_ups_ide',
            'casis_upss',
            'ups_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->createTable('casis_establecimiento_upss');
    }

    public function down()
    {
        $this->forge->dropTable('casis_establecimiento_upss', true);
    }
}

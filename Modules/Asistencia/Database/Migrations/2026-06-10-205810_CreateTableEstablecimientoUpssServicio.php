<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableEstablecimientoUpssServicio extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'eus_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'eus_eup_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'eus_uss_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'eus_estado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],

            'eus_observacion' => [
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

        $this->forge->addKey('eus_ide', true);

        $this->forge->addUniqueKey(
            ['eus_eup_ide', 'eus_uss_ide'],
            'uk_est_upss_servicio'
        );

        $this->forge->addForeignKey(
            'eus_eup_ide',
            'casis_establecimiento_upss',
            'eup_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'eus_uss_ide',
            'casis_upss_servicio',
            'uss_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->createTable('casis_establecimiento_upss_servicio');
    }

    public function down()
    {
        $this->forge->dropTable('casis_establecimiento_upss_servicio', true);
    }
}

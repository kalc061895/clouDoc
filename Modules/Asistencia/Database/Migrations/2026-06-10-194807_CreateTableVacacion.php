<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableVacacion extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'vac_ide' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],

            'vac_perl_ide' => [
                'type' => 'INT',
                'unsigned' => true,
            ],

            'vac_periodo_inicio' => [
                'type' => 'DATE',
            ],

            'vac_periodo_fin' => [
                'type' => 'DATE',
            ],

            'vac_dias_ganados' => [
                'type' => 'INT',
                'default' => 30,
            ],

            'vac_dias_gozados' => [
                'type' => 'INT',
                'default' => 0,
            ],

            'vac_dias_pendientes' => [
                'type' => 'INT',
                'default' => 30,
            ],

            'vac_estado' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
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

        $this->forge->addKey('vac_ide', true);

        $this->forge->addForeignKey(
            'vac_perl_ide',
            'casis_personal',
            'perl_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->createTable('casis_vacacion');
    }

    public function down()
    {
        $this->forge->dropTable('casis_vacacion', true);
    }
}

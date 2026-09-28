<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableProgramacion extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'prog_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'prog_perl_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'prog_fecha' => [
                'type' => 'DATE',
            ],

            'prog_th_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'prog_eup_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'prog_eus_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'prog_estado' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'PROGRAMADO',
            ],

            'prog_observacion' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'prog_es_cambio' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],

            'prog_origen_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'prog_reg' => [
                'type'    => 'TIMESTAMP',
                'null'    => true,
                'default' => null,
            ],
        ]);

        $this->forge->addKey('prog_ide', true);

        $this->forge->addKey('prog_fecha');
        $this->forge->addKey('prog_perl_ide');
        $this->forge->addKey('prog_th_ide');

        // FK personal
        $this->forge->addForeignKey(
            'prog_perl_ide',
            'casis_personal',
            'perl_ide',
            'CASCADE',
            'CASCADE'
        );

        // FK turno horario
        $this->forge->addForeignKey(
            'prog_th_ide',
            'casis_turno_horario',
            'th_ide',
            'RESTRICT',
            'CASCADE'
        );

        // FK establecimiento_upss
        $this->forge->addForeignKey(
            'prog_eup_ide',
            'casis_establecimiento_upss',
            'eup_ide',
            'SET NULL',
            'CASCADE'
        );

        // FK establecimiento_upss_servicio
        $this->forge->addForeignKey(
            'prog_eus_ide',
            'casis_establecimiento_upss_servicio',
            'eus_ide',
            'SET NULL',
            'CASCADE'
        );

        $this->forge->createTable('casis_programacion');
    }

    public function down()
    {
        $this->forge->dropTable('casis_programacion');
    }
}

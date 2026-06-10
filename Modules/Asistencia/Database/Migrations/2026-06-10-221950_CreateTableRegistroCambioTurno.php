<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableRegistroCambioTurno extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'rc_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'rc_sol_perl_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'rc_ace_perl_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'rc_fecha_sol' => [
                'type' => 'DATE',
            ],

            'rc_fecha_ace' => [
                'type' => 'DATE',
            ],

            'rc_turno_sol_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'rc_turno_ace_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'rc_justificacion' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'rc_observacion' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'rc_estado' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'PENDIENTE',
            ],

            'rc_fecha_aprobacion' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'rc_aprobado_por' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
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

        $this->forge->addKey('rc_ide', true);

        $this->forge->addForeignKey(
            'rc_sol_perl_ide',
            'casis_personal',
            'perl_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'rc_ace_perl_ide',
            'casis_personal',
            'perl_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->createTable('casis_registro_cambio_turno');
    }

    public function down()
    {
        $this->forge->dropTable('casis_registro_cambio_turno', true);
    }
}

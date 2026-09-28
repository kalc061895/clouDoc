<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableCalificacionDetalle extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'cad_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'cad_cal_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'cad_perl_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'cad_asistencia' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'cad_guardias' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],

            'cad_guardias_monto' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0,
            ],

            'cad_faltas' => [
                'type'    => 'INT',
                'default' => 0,
            ],

            'cad_faltas_monto' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0,
            ],

            'cad_tardanzas' => [
                'type'    => 'INT',
                'default' => 0,
            ],

            'cad_tardanzas_monto' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0,
            ],

            'cad_observacion' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'cad_estado' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'PENDIENTE',
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

        $this->forge->addKey('cad_ide', true);

        $this->forge->addKey('cad_cal_ide');
        $this->forge->addKey('cad_perl_ide');

        $this->forge->addForeignKey(
            'cad_cal_ide',
            'casis_calificacion',
            'cal_ide',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'cad_perl_ide',
            'casis_personal',
            'perl_ide',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('casis_calificacion_detalle');
    }

    public function down()
    {
        $this->forge->dropTable('casis_calificacion_detalle');
    }
}

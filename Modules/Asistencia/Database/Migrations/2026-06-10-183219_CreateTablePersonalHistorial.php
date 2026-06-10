<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTablePersonalHistorial extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'phis_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'phis_perl_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'phis_tmp_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'phis_est_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'phis_ofi_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'phis_car_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'phis_mco_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'phis_fecha_inicio' => [
                'type' => 'DATE',
                'null' => false,
            ],

            'phis_fecha_fin' => [
                'type' => 'DATE',
                'null' => true,
            ],

            'phis_resolucion' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'phis_fecha_resolucion' => [
                'type' => 'DATE',
                'null' => true,
            ],

            'phis_motivo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],

            'phis_observacion' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'phis_estado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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

        $this->forge->addKey('phis_ide', true);

        $this->forge->addKey('phis_perl_ide');
        $this->forge->addKey('phis_tmp_ide');
        $this->forge->addKey('phis_est_ide');
        $this->forge->addKey('phis_ofi_ide');
        $this->forge->addKey('phis_car_ide');
        $this->forge->addKey('phis_mco_ide');

        $this->forge->addForeignKey(
            'phis_perl_ide',
            'casis_personal',
            'perl_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'phis_tmp_ide',
            'casis_tipo_movimiento_personal',
            'tmp_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'phis_est_ide',
            'casis_establecimiento',
            'est_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'phis_ofi_ide',
            'casis_oficina',
            'ofi_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'phis_car_ide',
            'casis_cargo',
            'car_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'phis_mco_ide',
            'casis_modalidad_contrato',
            'mco_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->createTable('casis_personal_historial');
    }

    public function down()
    {
        $this->forge->dropTable('casis_personal_historial', true);
    }
}

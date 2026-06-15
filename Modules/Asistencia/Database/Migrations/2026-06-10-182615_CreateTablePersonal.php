<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTablePersonal extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'perl_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'perl_per_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'perl_est_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'perl_ofi_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'perl_car_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'perl_mco_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'perl_se_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'perl_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],

            'perl_fecha_inicio' => [
                'type' => 'DATE',
                'null' => true,
            ],

            'perl_fecha_termino' => [
                'type' => 'DATE',
                'null' => true,
            ],

            'perl_numero_colegiatura' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
            ],

            'perl_plaza' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'perl_nivel' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],

            'perl_estado' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'ACTIVO',
            ],

            'perl_regimen_laboral' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'perl_observacion' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'perl_uo_ide' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'perl_ser_ide' // Se coloca después del campo de servicio
            ],
            // Auditoría
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],

            'created_by' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'updated_by' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'deleted_by' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
        ]);

        $this->forge->addKey('perl_ide', true);

        $this->forge->addKey('perl_per_ide');
        $this->forge->addKey('perl_est_ide');
        $this->forge->addKey('perl_ofi_ide');
        $this->forge->addKey('perl_car_ide');
        $this->forge->addKey('perl_mco_ide');
        $this->forge->addKey('perl_se_ide');

        $this->forge->addForeignKey(
            'perl_per_ide',
            'casis_persona',
            'per_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'perl_est_ide',
            'casis_establecimiento',
            'est_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'perl_ofi_ide',
            'casis_oficina',
            'ofi_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'perl_car_ide',
            'casis_cargo',
            'car_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'perl_mco_ide',
            'casis_modalidad_contrato',
            'mco_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'perl_se_ide',
            'casis_segunda_especialidad',
            'se_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'perl_uo_ide',
            'casis_unidades_organizacionales',
            'uo_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->createTable('casis_personal');
    }

    public function down()
    {
        $this->forge->dropTable('casis_personal', true);
    }
}

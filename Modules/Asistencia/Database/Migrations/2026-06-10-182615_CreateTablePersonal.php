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
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | Relaciones
            |--------------------------------------------------------------------------
            */

            'perl_per_ide' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],

            'perl_est_ide' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],

            'perl_uo_ide' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],

            'perl_ofi_ide' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],

            'perl_car_ide' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],

            'perl_mco_ide' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | Datos laborales
            |--------------------------------------------------------------------------
            */

            'perl_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],

            'perl_fecha_inicio' => [
                'type' => 'DATE',
                'null' => false,
            ],

            'perl_fecha_termino' => [
                'type' => 'DATE',
                'null' => true,
            ],

            'perl_fecha_cese' => [
                'type' => 'DATE',
                'null' => true,
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

            /*
            |--------------------------------------------------------------------------
            | Estado laboral
            |--------------------------------------------------------------------------
            */

            'perl_estado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => '1=Activo,2=Licencia,3=Vacaciones,4=Suspendido,5=Cesado',
            ],

            'perl_observacion' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | Auditoría
            |--------------------------------------------------------------------------
            */

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
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],

            'updated_by' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],

            'deleted_by' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Primary Key
        |--------------------------------------------------------------------------
        */

        $this->forge->addKey('perl_ide', true);

        /*
        |--------------------------------------------------------------------------
        | Índices
        |--------------------------------------------------------------------------
        */

        $this->forge->addUniqueKey('perl_codigo');

        $this->forge->addKey('perl_per_ide');
        $this->forge->addKey('perl_est_ide');
        $this->forge->addKey('perl_uo_ide');
        $this->forge->addKey('perl_ofi_ide');
        $this->forge->addKey('perl_car_ide');
        $this->forge->addKey('perl_mco_ide');

        // Índices compuestos para consultas frecuentes

        $this->forge->addKey(['perl_est_ide', 'perl_estado']);
        $this->forge->addKey(['perl_ofi_ide', 'perl_estado']);
        $this->forge->addKey(['perl_car_ide', 'perl_estado']);

        /*
        |--------------------------------------------------------------------------
        | Relaciones
        |--------------------------------------------------------------------------
        */

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
            'perl_uo_ide',
            'casis_unidades_organizacionales',
            'uo_ide',
            'SET NULL',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'perl_ofi_ide',
            'casis_oficina',
            'ofi_ide',
            'SET NULL',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'perl_car_ide',
            'casis_cargo',
            'car_ide',
            'SET NULL',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'perl_mco_ide',
            'casis_modalidad_contrato',
            'mco_ide',
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

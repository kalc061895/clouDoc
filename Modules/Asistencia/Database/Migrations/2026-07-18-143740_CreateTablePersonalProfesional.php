<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePersonalProfesional extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'pp_ide' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'pp_perl_ide' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'pp_principal' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'unsigned'   => true,
                'default'    => 1
            ],

            'pp_pro_ide' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],

            'pp_col_ide' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],

            'pp_se_ide' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],

            'pp_numero_colegiatura' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
            ],

            'pp_rne' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
            ],

            'pp_habilitado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => '1=Habilitado,0=No habilitado',
            ],

            'pp_fecha_habilitacion' => [
                'type' => 'DATE',
                'null' => true,
            ],

            'pp_fecha_vencimiento' => [
                'type' => 'DATE',
                'null' => true,
            ],

            'pp_observacion' => [
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

        $this->forge->addKey('pp_ide', true);


        $this->forge->addKey('pp_perl_ide');
        $this->forge->addKey('pp_pro_ide');
        $this->forge->addKey('pp_col_ide');
        $this->forge->addKey('pp_se_ide');

        $this->forge->addForeignKey(
            'pp_perl_ide',
            'casis_personal',
            'perl_ide',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'pp_pro_ide',
            'casis_profesion',
            'pro_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'pp_col_ide',
            'casis_colegiatura',
            'col_ide',
            'SET NULL',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'pp_se_ide',
            'casis_segunda_especialidad',
            'se_ide',
            'SET NULL',
            'CASCADE'
        );

        $this->forge->createTable('casis_personal_profesion');
    }

    public function down()
    {
        $this->forge->dropTable('casis_personal_profesion', true);
    }
}
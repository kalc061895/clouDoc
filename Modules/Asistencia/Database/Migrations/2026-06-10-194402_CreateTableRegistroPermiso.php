<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableRegistroPermiso extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'rp_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'rp_pero_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'rp_perl_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'rp_fecha' => [
                'type' => 'DATE',
            ],

            'rp_hora_salida' => [
                'type' => 'TIME',
            ],

            'rp_hora_retorno' => [
                'type' => 'TIME',
            ],

            'rp_total_horas' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
            ],

            'rp_motivo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],

            'rp_justificacion' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'rp_numero_documento' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'rp_fecha_documento' => [
                'type' => 'DATE',
                'null' => true,
            ],

            'rp_archivo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],

            'rp_estado' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'PENDIENTE',
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

        $this->forge->addKey('rp_ide', true);

        $this->forge->addKey('rp_pero_ide');
        $this->forge->addKey('rp_perl_ide');

        $this->forge->addForeignKey(
            'rp_pero_ide',
            'casis_permiso',
            'pero_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'rp_perl_ide',
            'casis_personal',
            'perl_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->createTable('casis_registro_permiso');
    }

    public function down()
    {
        $this->forge->dropTable('casis_registro_permiso', true);
    }
}

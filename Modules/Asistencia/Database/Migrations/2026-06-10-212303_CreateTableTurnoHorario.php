<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableTurnoHorario extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'th_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'th_tur_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'th_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],

            'th_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],

            'th_hora_ingreso' => [
                'type' => 'TIME',
            ],

            'th_hora_salida' => [
                'type' => 'TIME',
            ],

            'th_tolerancia_ingreso' => [
                'type' => 'TIME',
                'null' => true,
            ],

            'th_tolerancia_salida' => [
                'type' => 'TIME',
                'null' => true,
            ],

            'th_refrigerio_salida' => [
                'type' => 'TIME',
                'null' => true,
            ],

            'th_refrigerio_retorno' => [
                'type' => 'TIME',
                'null' => true,
            ],

            'th_estado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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
        ]);

        $this->forge->addKey('th_ide', true);
        $this->forge->addKey('th_tur_ide');

        $this->forge->addForeignKey(
            'th_tur_ide',
            'casis_turno',
            'tur_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->createTable('casis_turno_horario');
    }

    public function down()
    {
        $this->forge->dropTable('casis_turno_horario');
    }
}

<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableProfesionEspecilidad extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'pes_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'pes_pro_ide' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],

            'pes_se_ide' => [
                'type'       => 'INT',
                'unsigned'   => true,
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
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],

            'updated_by' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],

            'deleted_by' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('pes_ide', true);

        $this->forge->addKey('pes_pro_ide');
        $this->forge->addKey('pes_se_ide');

        // Evita registros duplicados
        $this->forge->addUniqueKey(
            ['pes_pro_ide', 'pes_se_ide'],
            'uk_profesion_especialidad'
        );

        $this->forge->addForeignKey(
            'pes_pro_ide',
            'casis_profesion',
            'pro_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'pes_se_ide',
            'casis_segunda_especialidad',
            'se_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->createTable('casis_profesion_especialidad');
    }

    public function down()
    {
        $this->forge->dropTable('casis_profesion_especialidad', true);
    }
}

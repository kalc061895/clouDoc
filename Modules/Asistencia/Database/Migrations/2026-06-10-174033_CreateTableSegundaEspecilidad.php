<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableSegundaEspecilidad extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'se_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'se_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => false,
            ],

            'se_abreviatura' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],

            'se_estado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => '1=Activo,0=Inactivo',
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

        $this->forge->addKey('se_ide', true);

        $this->forge->addUniqueKey(
            'se_nombre',
            'uk_se_nombre'
        );

        $this->forge->createTable('casis_segunda_especialidad');
    }

    public function down()
    {
        $this->forge->dropTable('casis_segunda_especialidad');
    }
}

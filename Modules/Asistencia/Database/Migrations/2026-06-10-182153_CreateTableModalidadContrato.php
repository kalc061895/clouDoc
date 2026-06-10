<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableModalidadContrato extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'mco_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'mco_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],

            'mco_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],

            'mco_descripcion' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],

            'mco_base_legal' => [
                'type'       => 'VARCHAR',
                'constraint' => 250,
                'null'       => true,
            ],

            'mco_estado' => [
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

        $this->forge->addKey('mco_ide', true);

        $this->forge->addUniqueKey(
            'mco_nombre',
            'uk_mco_nombre'
        );

        $this->forge->createTable('casis_modalidad_contrato');
    }

    public function down()
    {
        $this->forge->dropTable('casis_modalidad_contrato', true);
    }
}

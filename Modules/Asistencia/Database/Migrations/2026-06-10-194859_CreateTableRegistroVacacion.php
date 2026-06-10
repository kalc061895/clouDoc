<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableRegistroVacacion extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'rv_ide' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],

            'rv_vac_ide' => [
                'type' => 'INT',
                'unsigned' => true,
            ],

            'rv_fecha_inicio' => [
                'type' => 'DATE',
            ],

            'rv_fecha_fin' => [
                'type' => 'DATE',
            ],

            'rv_dias' => [
                'type' => 'INT',
            ],

            'rv_numero_documento' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],

            'rv_fecha_documento' => [
                'type' => 'DATE',
                'null' => true,
            ],

            'rv_observacion' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'rv_estado' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
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

        $this->forge->addKey('rv_ide', true);

        $this->forge->addForeignKey(
            'rv_vac_ide',
            'casis_vacacion',
            'vac_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->createTable('casis_registro_vacacion');
    }

    public function down()
    {
        $this->forge->dropTable('casis_registro_vacacion', true);
    }
    
}

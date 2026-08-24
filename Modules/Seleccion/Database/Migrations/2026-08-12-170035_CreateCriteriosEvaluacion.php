<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCriteriosEvaluacion extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'cri_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'cri_fie_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'cri_codigo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'cri_nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'cri_descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'cri_tipo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'cri_puntaje_maximo' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'cri_obligatorio' => [
                'type' => 'BOOLEAN',
                'null' => true,
                'default' => true,
            ],
            'cri_orden' => [
                'type' => 'INT',
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
    'type' => 'BIGINT',
    'unsigned' => true,
    'null' => true,
],
            'updated_by' => [
    'type' => 'BIGINT',
    'unsigned' => true,
    'null' => true,
],
            'deleted_by' => [
    'type' => 'BIGINT',
    'unsigned' => true,
    'null' => true,
],
        ]);

        $this->forge->addKey('cri_ide', true);
        $this->forge->addForeignKey('cri_fie_ide', 'selec_fichas_evaluacion', 'fie_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_criterios_evaluacion');
    }

    public function down()
    {
        $this->forge->dropTable('selec_criterios_evaluacion', true);
    }
}

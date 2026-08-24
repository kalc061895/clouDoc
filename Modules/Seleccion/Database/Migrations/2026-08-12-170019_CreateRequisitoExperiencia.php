<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRequisitoExperiencia extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'rex_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'rex_req_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'rex_anios' => [
                'type' => 'INT',
                'null' => true,
            ],
            'rex_meses' => [
                'type' => 'INT',
                'null' => true,
            ],
            'rex_dias' => [
                'type' => 'INT',
                'null' => true,
            ],
            'rex_tipo_experiencia' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'rex_especifica' => [
                'type' => 'BOOLEAN',
                'null' => true,
                'default' => false,
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

        $this->forge->addKey('rex_ide', true);
        $this->forge->addForeignKey('rex_req_ide', 'selec_requisitos', 'req_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_requisito_experiencia');
    }

    public function down()
    {
        $this->forge->dropTable('selec_requisito_experiencia', true);
    }
}

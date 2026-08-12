<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRequisitoEvaluaciones extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'reqe_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'reqe_eva_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'reqe_req_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'reqe_resultado' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => true,
            ],
            'reqe_cumple' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'reqe_observacion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
        ]);

        $this->forge->addKey('reqe_ide', true);
        $this->forge->addForeignKey('reqe_eva_ide', 'selec_evaluaciones', 'eva_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('reqe_req_ide', 'selec_requisitos', 'req_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_requisito_evaluaciones');
    }

    public function down()
    {
        $this->forge->dropTable('selec_requisito_evaluaciones', true);
    }
}

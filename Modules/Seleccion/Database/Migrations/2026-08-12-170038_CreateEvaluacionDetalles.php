<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEvaluacionDetalles extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'evd_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'evd_eva_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'evd_cri_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'evd_resultado' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'evd_cumple' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'evd_puntaje' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'evd_observacion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('evd_ide', true);
        $this->forge->addForeignKey('evd_eva_ide', 'selec_evaluaciones', 'eva_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('evd_cri_ide', 'selec_criterios_evaluacion', 'cri_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_evaluacion_detalles');
    }

    public function down()
    {
        $this->forge->dropTable('selec_evaluacion_detalles', true);
    }
}

<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEvaluaciones extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'eva_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'eva_pto_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'eva_fie_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'eva_com_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'eva_usu_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'eva_tipo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'eva_estado' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => true,
            ],
            'eva_puntaje_total' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'eva_fecha_inicio' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'eva_fecha_fin' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'eva_observacion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('eva_ide', true);
        $this->forge->addForeignKey('eva_pto_ide', 'selec_postulaciones', 'pto_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('eva_fie_ide', 'selec_fichas_evaluacion', 'fie_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('eva_com_ide', 'selec_comisiones', 'com_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_evaluaciones');
    }

    public function down()
    {
        $this->forge->dropTable('selec_evaluaciones', true);
    }
}

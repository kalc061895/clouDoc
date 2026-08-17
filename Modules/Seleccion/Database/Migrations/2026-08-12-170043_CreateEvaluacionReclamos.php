<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEvaluacionReclamos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'ere_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'ere_rec_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'ere_com_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'ere_decision' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'ere_fundamento' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'ere_observacion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'ere_fecha' => [
                'type' => 'DATETIME',
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
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_by' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('ere_ide', true);
        $this->forge->addForeignKey('ere_rec_ide', 'selec_reclamos', 'rec_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('ere_com_ide', 'selec_comisiones', 'com_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_evaluacion_reclamos');
    }

    public function down()
    {
        $this->forge->dropTable('selec_evaluacion_reclamos', true);
    }
}

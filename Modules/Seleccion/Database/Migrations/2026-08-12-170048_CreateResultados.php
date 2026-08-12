<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateResultados extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'res_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'res_pto_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'res_version' => [
                'type' => 'INT',
                'null' => false,
                'default' => 1,
            ],
            'res_tipo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'res_puntaje_curricular' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'res_puntaje_entrevista' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'res_puntaje_bonificacion' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'res_puntaje_total' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'res_orden_merito' => [
                'type' => 'INT',
                'null' => true,
            ],
            'res_condicion' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'res_publicado' => [
                'type' => 'BOOLEAN',
                'null' => true,
                'default' => false,
            ],
            'res_fecha' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'res_anterior_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
        ]);

        $this->forge->addKey('res_ide', true);
        $this->forge->addForeignKey('res_pto_ide', 'selec_postulaciones', 'pto_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('res_anterior_ide', 'selec_resultados', 'res_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_resultados');
    }

    public function down()
    {
        $this->forge->dropTable('selec_resultados', true);
    }
}

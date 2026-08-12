<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateConvocatoriaCargos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'cco_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'cco_con_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'cco_car_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'cco_numero_plazas' => [
                'type' => 'INT',
                'null' => false,
            ],
            'cco_dependencia' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'null' => true,
            ],
            'cco_area' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'null' => true,
            ],
            'cco_est_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'cco_remuneracion' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'null' => true,
            ],
            'cco_observacion' => [
                'type' => 'TEXT',
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
        ]);

        $this->forge->addKey('cco_ide', true);
        $this->forge->addUniqueKey(['cco_con_ide', 'cco_car_ide']);
        $this->forge->addForeignKey('cco_con_ide', 'selec_convocatorias', 'con_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('cco_car_ide', 'selec_cargos', 'car_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_convocatoria_cargos');
    }

    public function down()
    {
        $this->forge->dropTable('selec_convocatoria_cargos', true);
    }
}

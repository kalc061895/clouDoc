<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCargos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'car_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'car_codigo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'car_denominacion' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'null' => false,
            ],
            'car_especialidad' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],
            'car_tca_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'car_gru_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'car_niv_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'car_pro_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'car_descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'car_estado' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
                'default' => 'ACTIVO',
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

        $this->forge->addKey('car_ide', true);
        $this->forge->addForeignKey('car_tca_ide', 'selec_tipos_cargo', 'tca_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('car_gru_ide', 'selec_grupos_ocupacionales', 'gru_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('car_niv_ide', 'selec_niveles', 'niv_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('car_pro_ide', 'selec_profesiones', 'pro_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_cargos');
    }

    public function down()
    {
        $this->forge->dropTable('selec_cargos', true);
    }
}

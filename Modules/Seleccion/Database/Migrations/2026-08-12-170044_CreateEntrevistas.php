<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEntrevistas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'ent_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'ent_con_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'ent_nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'ent_fie_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'ent_puntaje_maximo' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'ent_estado' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
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

        $this->forge->addKey('ent_ide', true);
        $this->forge->addForeignKey('ent_con_ide', 'selec_convocatorias', 'con_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('ent_fie_ide', 'selec_fichas_evaluacion', 'fie_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_entrevistas');
    }

    public function down()
    {
        $this->forge->dropTable('selec_entrevistas', true);
    }
}

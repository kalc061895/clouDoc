<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFichasEvaluacion extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'fie_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'fie_con_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'fie_nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'fie_tipo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'fie_version' => [
                'type' => 'INT',
                'null' => true,
                'default' => 1,
            ],
            'fie_estado' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'default' => 'ACTIVA',
            ],
            'fie_puntaje_maximo' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('fie_ide', true);
        $this->forge->addForeignKey('fie_con_ide', 'selec_convocatorias', 'con_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_fichas_evaluacion');
    }

    public function down()
    {
        $this->forge->dropTable('selec_fichas_evaluacion', true);
    }
}

<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateExperienciaEvaluaciones extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'exe_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'exe_eva_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'exe_pex_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'exe_resultado' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'exe_dias_declarados' => [
                'type' => 'INT',
                'null' => true,
            ],
            'exe_dias_validados' => [
                'type' => 'INT',
                'null' => true,
            ],
            'exe_puntaje' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'exe_observacion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
        ]);

        $this->forge->addKey('exe_ide', true);
        $this->forge->addForeignKey('exe_eva_ide', 'selec_evaluaciones', 'eva_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('exe_pex_ide', 'selec_postulante_experiencias', 'pex_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_experiencia_evaluaciones');
    }

    public function down()
    {
        $this->forge->dropTable('selec_experiencia_evaluaciones', true);
    }
}

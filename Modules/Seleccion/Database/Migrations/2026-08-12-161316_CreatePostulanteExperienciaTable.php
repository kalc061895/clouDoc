<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePostulanteExperienciaTable extends Migration
{
    public function up()
    {
        // --------------------------------------------------------------------
        // 1. selec_postulante_experiencias
        // --------------------------------------------------------------------
        $this->forge->addField([
            'pex_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pex_pos_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
            ],
            'pex_institucion' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'pex_cargo' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
                'null'       => true,
            ],
            'pex_area' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
                'null'       => true,
            ],
            'pex_mvi_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'pex_fecha_inicio' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'pex_fecha_termino' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'pex_dias_declarados' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'pex_descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'pex_documento_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
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

        $this->forge->addKey('pex_ide', true);
        $this->forge->addForeignKey('pex_pos_ide', 'selec_postulantes', 'pos_ide', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('pex_mvi_ide', 'selec_modalidades_vinculo', 'mvi_ide', 'CASCADE', 'SET NULL');

        $this->forge->createTable('selec_postulante_experiencias', true);
    }

    public function down()
    {
        $this->forge->dropTable('selec_postulante_experiencias', true);
    }
}

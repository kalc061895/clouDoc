<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePostulanteExperiencias extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'pex_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'pex_pos_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'pex_institucion' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'pex_cargo' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'null' => true,
            ],
            'pex_area' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'null' => true,
            ],
            'pex_mvi_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
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
                'type' => 'INT',
                'null' => true,
            ],
            'pex_descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'pex_documento_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
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

        $this->forge->addKey('pex_ide', true);
        $this->forge->addForeignKey('pex_pos_ide', 'selec_postulantes', 'pos_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('pex_mvi_ide', 'selec_modalidades_vinculo', 'mvi_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('pex_documento_ide', 'selec_expediente_documentos', 'exd_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_postulante_experiencias');
    }

    public function down()
    {
        $this->forge->dropTable('selec_postulante_experiencias', true);
    }
}

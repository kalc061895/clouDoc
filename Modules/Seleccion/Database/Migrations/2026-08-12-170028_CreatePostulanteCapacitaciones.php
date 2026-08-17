<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePostulanteCapacitaciones extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'pca_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'pca_pos_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'pca_nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'pca_institucion' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'pca_tipo' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'pca_fecha' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'pca_horas' => [
                'type' => 'DECIMAL',
                'constraint' => '8,2',
                'null' => true,
            ],
            'pca_modalidad' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'pca_documento_ide' => [
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

        $this->forge->addKey('pca_ide', true);
        $this->forge->addForeignKey('pca_pos_ide', 'selec_postulantes', 'pos_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('pca_documento_ide', 'selec_expediente_documentos', 'exd_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_postulante_capacitaciones');
    }

    public function down()
    {
        $this->forge->dropTable('selec_postulante_capacitaciones', true);
    }
}

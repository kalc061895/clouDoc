<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePostulacionAnexosTable extends Migration
{
    public function up()
    {
        // --------------------------------------------------------------------
        // 1. selec_postulacion_anexos
        // --------------------------------------------------------------------
        $this->forge->addField([
            'pan_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pan_pto_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
            ],
            'pan_ane_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
            ],
            'pan_exd_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'pan_presentado' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'pan_observacion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('pan_ide', true);

        // Índice único compuesto para evitar duplicidad del mismo anexo en la misma postulación
        $this->forge->addUniqueKey(['pan_pto_ide', 'pan_ane_ide']);

        // Claves Foráneas
        $this->forge->addForeignKey('pan_pto_ide', 'selec_postulaciones', 'pto_ide', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('pan_ane_ide', 'selec_anexos', 'ane_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('pan_exd_ide', 'selec_expediente_documentos', 'exd_ide', 'CASCADE', 'SET NULL');

        $this->forge->createTable('selec_postulacion_anexos', true);
    }

    public function down()
    {
        $this->forge->dropTable('selec_postulacion_anexos', true);
    }
}

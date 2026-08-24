<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePostulacionAnexos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'pan_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'pan_pto_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'pan_ane_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'pan_exd_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'pan_presentado' => [
                'type' => 'BOOLEAN',
                'null' => true,
                'default' => false,
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

        $this->forge->addKey('pan_ide', true);
        $this->forge->addForeignKey('pan_pto_ide', 'selec_postulaciones', 'pto_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('pan_ane_ide', 'selec_anexos', 'ane_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('pan_exd_ide', 'selec_expediente_documentos', 'exd_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_postulacion_anexos');
    }

    public function down()
    {
        $this->forge->dropTable('selec_postulacion_anexos', true);
    }
}

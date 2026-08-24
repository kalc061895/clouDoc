<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBonificaciones extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'bon_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'bon_pto_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'bon_tbo_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'bon_porcentaje' => [
                'type' => 'DECIMAL',
                'constraint' => '10,4',
                'null' => true,
            ],
            'bon_puntaje' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'bon_documento_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'bon_resultado' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'bon_fundamento' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'bon_fecha' => [
                'type' => 'DATETIME',
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

        $this->forge->addKey('bon_ide', true);
        $this->forge->addForeignKey('bon_pto_ide', 'selec_postulaciones', 'pto_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('bon_tbo_ide', 'selec_tipos_bonificacion', 'tbo_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('bon_documento_ide', 'selec_expediente_documentos', 'exd_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_bonificaciones');
    }

    public function down()
    {
        $this->forge->dropTable('selec_bonificaciones', true);
    }
}

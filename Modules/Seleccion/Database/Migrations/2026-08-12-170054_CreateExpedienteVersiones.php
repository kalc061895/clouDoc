<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateExpedienteVersiones extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'exv_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'exv_pto_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'exv_version' => [
                'type' => 'INT',
                'null' => false,
            ],
            'exv_hash' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'exv_fecha' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'exv_motivo' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'exv_estado' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
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

        $this->forge->addKey('exv_ide', true);
        $this->forge->addForeignKey('exv_pto_ide', 'selec_postulaciones', 'pto_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_expediente_versiones');
    }

    public function down()
    {
        $this->forge->dropTable('selec_expediente_versiones', true);
    }
}

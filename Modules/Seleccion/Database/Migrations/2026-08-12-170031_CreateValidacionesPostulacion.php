<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateValidacionesPostulacion extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'vpo_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'vpo_pto_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'vpo_codigo' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'vpo_nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'vpo_resultado' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'vpo_observacion' => [
                'type' => 'TEXT',
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

        $this->forge->addKey('vpo_ide', true);
        $this->forge->addForeignKey('vpo_pto_ide', 'selec_postulaciones', 'pto_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_validaciones_postulacion');
    }

    public function down()
    {
        $this->forge->dropTable('selec_validaciones_postulacion', true);
    }
}

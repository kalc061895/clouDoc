<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateValidacionPostulacionTable extends Migration
{
    public function up()
    {
        // --------------------------------------------------------------------
        // 1. selec_validaciones_postulacion
        // --------------------------------------------------------------------
        $this->forge->addField([
            'vpo_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'vpo_pto_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
            ],
            'vpo_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'vpo_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'vpo_resultado' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ],
            'vpo_observacion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'vpo_fecha' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('vpo_ide', true);

        // Claves Foráneas e Índices
        $this->forge->addForeignKey('vpo_pto_ide', 'selec_postulaciones', 'pto_ide', 'CASCADE', 'CASCADE');

        $this->forge->createTable('selec_validaciones_postulacion', true);
    }

    public function down()
    {
        $this->forge->dropTable('selec_validaciones_postulacion', true);
    }
}

<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePostulantePostulacionTable extends Migration
{
    public function up()
    {
        // --------------------------------------------------------------------
        // 1. selec_postulaciones
        // --------------------------------------------------------------------
        $this->forge->addField([
            'pto_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pto_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'pto_pos_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
            ],
            'pto_cco_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
            ],
            'pto_epo_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
            ],
            'pto_eex_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'pto_fecha_presentacion' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'pto_confirmado' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'pto_ip' => [
                'type'       => 'VARCHAR',
                'constraint' => '45',
                'null'       => true,
            ],
            'pto_hash_expediente' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'pto_observacion' => [
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
        ]);

        $this->forge->addKey('pto_ide', true);
        $this->forge->addUniqueKey('pto_codigo');

        // Claves Foráneas
        $this->forge->addForeignKey('pto_pos_ide', 'selec_postulantes', 'pos_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('pto_cco_ide', 'selec_convocatoria_cargos', 'cco_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('pto_epo_ide', 'selec_estados_postulacion', 'epo_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('pto_eex_ide', 'selec_estados_expediente', 'eex_ide', 'CASCADE', 'SET NULL');

        $this->forge->createTable('selec_postulaciones', true);
    }

    public function down()
    {
        $this->forge->dropTable('selec_postulaciones', true);
    }
}

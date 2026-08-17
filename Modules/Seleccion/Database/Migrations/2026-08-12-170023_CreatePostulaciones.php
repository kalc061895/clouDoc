<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePostulaciones extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'pto_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'pto_codigo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'pto_pos_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'pto_cco_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'pto_epo_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'pto_eex_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'pto_fecha_presentacion' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'pto_confirmado' => [
                'type' => 'BOOLEAN',
                'null' => true,
                'default' => false,
            ],
            'pto_ip' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
            ],
            'pto_hash_expediente' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
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

        $this->forge->addKey('pto_ide', true);
        $this->forge->addUniqueKey('pto_codigo');
        $this->forge->addForeignKey('pto_pos_ide', 'selec_postulantes', 'pos_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('pto_cco_ide', 'selec_convocatoria_cargos', 'cco_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('pto_epo_ide', 'selec_estados_postulacion', 'epo_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('pto_eex_ide', 'selec_estados_expediente', 'eex_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_postulaciones');
    }

    public function down()
    {
        $this->forge->dropTable('selec_postulaciones', true);
    }
}

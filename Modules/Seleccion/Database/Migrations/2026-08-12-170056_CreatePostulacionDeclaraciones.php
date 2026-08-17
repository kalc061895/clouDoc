<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePostulacionDeclaraciones extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'pde_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'pde_pto_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'pde_tde_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'pde_exd_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'pde_acepta' => [
                'type' => 'BOOLEAN',
                'null' => false,
            ],
            'pde_fecha' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'pde_ip' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
            ],
            'pde_hash' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
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

        $this->forge->addKey('pde_ide', true);
        $this->forge->addForeignKey('pde_pto_ide', 'selec_postulaciones', 'pto_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('pde_tde_ide', 'selec_tipos_declaracion', 'tde_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('pde_exd_ide', 'selec_expediente_documentos', 'exd_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_postulacion_declaraciones');
    }

    public function down()
    {
        $this->forge->dropTable('selec_postulacion_declaraciones', true);
    }
}

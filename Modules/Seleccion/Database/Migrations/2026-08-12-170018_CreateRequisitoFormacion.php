<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRequisitoFormacion extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'rfo_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'rfo_req_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'rfo_nfo_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'rfo_pro_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'rfo_grado' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
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

        $this->forge->addKey('rfo_ide', true);
        $this->forge->addForeignKey('rfo_req_ide', 'selec_requisitos', 'req_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('rfo_nfo_ide', 'selec_niveles_formacion', 'nfo_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('rfo_pro_ide', 'selec_profesiones', 'pro_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_requisito_formacion');
    }

    public function down()
    {
        $this->forge->dropTable('selec_requisito_formacion', true);
    }
}

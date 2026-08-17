<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRequisitos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'req_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'req_cco_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'req_codigo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'req_nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'req_descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'req_tipo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'req_obligatorio' => [
                'type' => 'BOOLEAN',
                'null' => true,
                'default' => true,
            ],
            'req_puntaje' => [
                'type' => 'BOOLEAN',
                'null' => true,
                'default' => false,
            ],
            'req_orden' => [
                'type' => 'INT',
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

        $this->forge->addKey('req_ide', true);
        $this->forge->addForeignKey('req_cco_ide', 'selec_convocatoria_cargos', 'cco_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_requisitos');
    }

    public function down()
    {
        $this->forge->dropTable('selec_requisitos', true);
    }
}

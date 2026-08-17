<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEstadosExpediente extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'eex_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'eex_codigo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'eex_nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
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

        $this->forge->addKey('eex_ide', true);
        $this->forge->addUniqueKey('eex_codigo');

        $this->forge->createTable('selec_estados_expediente');
    }

    public function down()
    {
        $this->forge->dropTable('selec_estados_expediente', true);
    }
}

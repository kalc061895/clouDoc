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

<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEtapas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'eta_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'eta_codigo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'eta_nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false,
            ],
            'eta_descripcion' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'eta_orden' => [
                'type' => 'INT',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('eta_ide', true);
        $this->forge->addUniqueKey('eta_codigo');

        $this->forge->createTable('selec_etapas');
    }

    public function down()
    {
        $this->forge->dropTable('selec_etapas', true);
    }
}

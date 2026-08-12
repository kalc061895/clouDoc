<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTiposCargo extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'tca_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'tca_codigo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'tca_nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
        ]);

        $this->forge->addKey('tca_ide', true);
        $this->forge->addUniqueKey('tca_codigo');

        $this->forge->createTable('selec_tipos_cargo');
    }

    public function down()
    {
        $this->forge->dropTable('selec_tipos_cargo', true);
    }
}

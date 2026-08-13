<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTiposDocumento extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'tdo_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'tdo_codigo' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => false,
            ],
            'tdo_nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'tdo_estado' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false,
            ],
        ]);

        $this->forge->addKey('tdo_ide', true);
        $this->forge->addUniqueKey('tdo_codigo');

        $this->forge->createTable('selec_tipos_documento');
    }

    public function down()
    {
        $this->forge->dropTable('selec_tipos_documento', true);
    }
}

<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNiveles extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'niv_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'niv_codigo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'niv_nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
        ]);

        $this->forge->addKey('niv_ide', true);
        $this->forge->addUniqueKey('niv_codigo');

        $this->forge->createTable('selec_niveles');
    }

    public function down()
    {
        $this->forge->dropTable('selec_niveles', true);
    }
}

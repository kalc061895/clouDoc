<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProfesiones extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'pro_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'pro_codigo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'pro_nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false,
            ],
        ]);

        $this->forge->addKey('pro_ide', true);

        $this->forge->createTable('selec_profesiones');
    }

    public function down()
    {
        $this->forge->dropTable('selec_profesiones', true);
    }
}

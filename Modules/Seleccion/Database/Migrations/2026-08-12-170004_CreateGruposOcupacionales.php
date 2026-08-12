<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGruposOcupacionales extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'gru_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'gru_codigo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'gru_nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
        ]);

        $this->forge->addKey('gru_ide', true);
        $this->forge->addUniqueKey('gru_codigo');

        $this->forge->createTable('selec_grupos_ocupacionales');
    }

    public function down()
    {
        $this->forge->dropTable('selec_grupos_ocupacionales', true);
    }
}

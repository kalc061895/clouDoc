<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEstadosConvocatoria extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'eco_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'eco_codigo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'eco_nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'eco_descripcion' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'eco_orden' => [
                'type' => 'INT',
                'null' => true,
            ],
            'eco_estado' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false,
            ],
        ]);

        $this->forge->addKey('eco_ide', true);
        $this->forge->addUniqueKey('eco_codigo');

        $this->forge->createTable('selec_estados_convocatoria');
    }

    public function down()
    {
        $this->forge->dropTable('selec_estados_convocatoria', true);
    }
}

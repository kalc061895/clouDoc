<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTiposConvocatoria extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'tco_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'tco_codigo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'tco_nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false,
            ],
            'tco_descripcion' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'tco_estado' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
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

        $this->forge->addKey('tco_ide', true);
        $this->forge->addUniqueKey('tco_codigo');

        $this->forge->createTable('selec_tipos_convocatoria');
    }

    public function down()
    {
        $this->forge->dropTable('selec_tipos_convocatoria', true);
    }
}

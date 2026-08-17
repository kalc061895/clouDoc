<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNivelesFormacion extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'nfo_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'nfo_codigo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'nfo_nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'nfo_estado' => [
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

        $this->forge->addKey('nfo_ide', true);
        $this->forge->addUniqueKey('nfo_codigo');

        $this->forge->createTable('selec_niveles_formacion');
    }

    public function down()
    {
        $this->forge->dropTable('selec_niveles_formacion', true);
    }
}

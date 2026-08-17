<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTiposDeclaracion extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'tde_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'tde_codigo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'tde_nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false,
            ],
            'tde_contenido' => [
                'type' => 'TEXT',
                'null' => true,
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

        $this->forge->addKey('tde_ide', true);
        $this->forge->addUniqueKey('tde_codigo');

        $this->forge->createTable('selec_tipos_declaracion');
    }

    public function down()
    {
        $this->forge->dropTable('selec_tipos_declaracion', true);
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTipoDocumentoTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'tamano' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'activo' => [
                'type' => 'BOOLEAN',
                'default' => true, // Valor por defecto activo
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('tipo_documento');
    }

    public function down()
    {
        $this->forge->dropTable('tipo_documento');
    }
}

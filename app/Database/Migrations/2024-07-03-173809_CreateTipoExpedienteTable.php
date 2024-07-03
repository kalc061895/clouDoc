<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTipoExpedienteTable extends Migration
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
            'descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('tipo_expediente');
    }

    public function down()
    {
        $this->forge->dropTable('tipo_expediente');
    }
}

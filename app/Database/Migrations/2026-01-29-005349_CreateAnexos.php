<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAnexos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_anexo' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'tipo' => [
                'type'       => 'ENUM',
                'constraint' => ['FORMACION', 'EXPERIENCIA', 'PERSONAL'],
            ],

            'nombre_original' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],

            'ruta' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],

            'mime' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],

            'extension' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],

            'size' => [
                'type'       => 'INT',
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_anexo', true);
        $this->forge->createTable('anexos', true);
    }

    public function down()
    {
        $this->forge->dropTable('anexos', true);
    }
}

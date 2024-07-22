<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;
class CreateAniosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'nombre'      => [
                'type'           => 'VARCHAR',
                'constraint'     => '255'
            ],
            'abreviatura' => [
                'type'           => 'VARCHAR',
                'constraint'     => '10'
            ],
            'numero'      => [
                'type'           => 'INT',
                'unsigned'       => true
            ],
            'created_at'  => [
                'type'           => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at'  => [
                'type'           => 'DATETIME',
                'null'           => true
            ],
            'deleted_at'  => [
                'type'           => 'DATETIME',
                'null'           => true
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('anios');
    }

    public function down()
    {
        $this->forge->dropTable('anios');
    }
}


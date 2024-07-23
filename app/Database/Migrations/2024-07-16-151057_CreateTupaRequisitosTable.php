<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;
class CreateTupaRequisitosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tupa_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'numero_requisito' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'denominacion' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'formato' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('tupa_id', 'tupa', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tupa_requisitos');
    }

    public function down()
    {
        $this->forge->dropTable('tupa_requisitos');
    }
}

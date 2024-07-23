<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;
class CreateTupaTable extends Migration
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
            'numero_orden' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'denominacion_procedimiento' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'derecho_admision_uit' => [
                'type' => 'FLOAT',
            ],
            'derecho_admision_soles' => [
                'type' => 'FLOAT',
            ],
            'calificacion' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'plazo' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'oficina_inicio' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'oficina_competencia' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'oficina_reconsideracion' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'oficina_apelacion' => [
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
        $this->forge->createTable('tupa');
    }

    public function down()
    {
        $this->forge->dropTable('tupa');
    }
}

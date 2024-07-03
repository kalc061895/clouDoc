<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateExpedientesTable extends Migration
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
            'numero_expediente' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'procedencia' => [
                'type' => 'ENUM',
                'constraint' => ['Interno', 'Externo'],
                'default' => 'Externo',
            ],
            'fecha_recepcion' => [
                'type' => 'DATETIME',
            ],
            'folios' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'tipo_expediente_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'tipo_documento' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'numero_documento' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'entidad_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'asunto' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'atencion_oficina_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'observacion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'activo' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
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
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('tipo_expediente_id', 'tipo_expediente', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('entidad_id', 'entidades', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('atencion_oficina_id', 'oficinas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('expedientes');
    }

    public function down()
    {
        $this->forge->dropTable('expedientes');
    }
}

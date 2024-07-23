<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;
class CreateOficinasTable extends Migration
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
                'constraint' => 255,
            ],
            'tipo' => [
                'type' => 'ENUM',
                'constraint' => ['Departamento', 'Oficina', 'Área'], // Define los tipos necesarios
                'default' => 'Oficina', // Tipo por defecto
            ],
            'descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'rango' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'default' => 1, // Rango por defecto
            ],
            'oficina_padre_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'null' => true, // Puede ser nulo si no depende de otra oficina
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
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('oficina_padre_id', 'oficinas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('oficinas');
    }

    public function down()
    {
        $this->forge->dropTable('oficinas');
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;
class CreateEntidadTable extends Migration
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
                'constraint' => ['Persona', 'Empresa', 'Entidad Pública'],
                'default' => 'Persona',
            ],
            'tipo_documento_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'num_documento' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'direccion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'telefono' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'correo_electronico' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
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
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->db->disableForeignKeyChecks();
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('tipo_documento_id', 'tipo_documento', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('entidad');
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('entidad');
    }
}

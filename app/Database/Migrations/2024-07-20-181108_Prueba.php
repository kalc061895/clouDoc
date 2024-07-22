<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;
class Prueba extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'numero_expediente' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'collation' => 'utf8mb4_general_ci'
            ],
            'procedencia' => [
                'type' => 'ENUM',
                'constraint' => ['Interno', 'Externo'],
                'default' => 'Externo',
                'collation' => 'utf8mb4_general_ci'
            ],
            'fecha_recepcion' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'folios' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'tipo_expediente_id' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'tipo_documento' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'collation' => 'utf8mb4_general_ci'
            ],
            'numero_documento' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'collation' => 'utf8mb4_general_ci'
            ],
            'entidad_id' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'asunto' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'collation' => 'utf8mb4_general_ci'
            ],
            'descripcion' => [
                'type' => 'TEXT',
                'null' => true,
                'collation' => 'utf8mb4_general_ci'
            ],
            'atencion_oficina_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true
            ],
            'observacion' => [
                'type' => 'TEXT',
                'null' => true,
                'collation' => 'utf8mb4_general_ci'
            ],
            'activo' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => '1'
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('tipo_expediente_id', 'tipo_expediente', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('entidad_id', 'entidad', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('atencion_oficina_id', 'oficinas', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('prueba');
    }

    public function down()
    {
        $this->forge->dropTable('prueba');
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMovimientosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'expediente_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'numero_movimiento' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => false,
            ],
            'oficina_procedencia_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => false,
            ],
            'oficina_destino_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => false,
            ],
            'estado' => [
                'type'       => 'ENUM',
                'constraint' => ['espera', 'recibido', 'derivado', 'en proceso', 'finalizado', 'rechazado'],
                'default'    => 'espera',
                'null'       => false,
            ],
            'fecha_envio' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'fecha_recepcion' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'fecha_culminacion' => [
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

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('expediente_id', 'expedientes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('movimientos');
    }

    public function down()
    {
        $this->forge->dropTable('movimientos');
    }
}

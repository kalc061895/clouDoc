<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;
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
            'prioridad' => [
                'type'       => 'ENUM',
                'constraint' => ['NORMAL', 'URGENTE', 'MUY  URGENTE'],
                'default'    => 'NORMAL',
                'null'       => false,
            ],
            'estado' => [
                'type'       => 'ENUM',
                'constraint' => ['ESPERA', 'RECIBIDO', 'DERIVADO', 'EN POROCESO', 'FINALIZADO', 'ATENDIDO'],
                'default'    => 'ESPERA',
                'null'       => false,
            ],
            'fecha_envio' => [
                'type' => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'observacion' => [
                'type' => 'TEXT',
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
                'default' => new RawSql('CURRENT_TIMESTAMP'),
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

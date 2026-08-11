<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CapacitacionCreate extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_capacitacion' => [
                'type' => 'INT',
                'auto_increment' => true,
                'unsigned' => true
            ],

            'id_postulante' => [
                'type' => 'INT',
                'unsigned' => true
            ],

            'nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 200
            ],

            'institucion' => [
                'type' => 'VARCHAR',
                'constraint' => 200
            ],

            'horas' => [
                'type' => 'INT',
                'null' => true
            ],

            'fecha_inicio' => [
                'type' => 'DATE'
            ],

            'fecha_fin' => [
                'type' => 'DATE'
            ],
            'id_anexo' => [
                'type' => 'INT',
                'null' => true
            ]
        ]);

        $this->forge->addKey('id_capacitacion', true);
        $this->forge->addForeignKey('id_postulante', 'postulantes', 'id_postulante', 'CASCADE', 'CASCADE');
        $this->forge->createTable('capacitaciones');
    }

    public function down()
    {
        $this->forge->dropTable('postulante_capacitaciones');
    }
}

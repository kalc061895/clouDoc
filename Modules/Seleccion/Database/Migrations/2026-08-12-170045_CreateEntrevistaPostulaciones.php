<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEntrevistaPostulaciones extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'enp_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'enp_ent_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'enp_pto_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'enp_fecha' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'enp_puntaje' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'enp_estado' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => true,
            ],
            'enp_observacion' => [
                'type' => 'TEXT',
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
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_by' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('enp_ide', true);
        $this->forge->addForeignKey('enp_ent_ide', 'selec_entrevistas', 'ent_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('enp_pto_ide', 'selec_postulaciones', 'pto_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_entrevista_postulaciones');
    }

    public function down()
    {
        $this->forge->dropTable('selec_entrevista_postulaciones', true);
    }
}

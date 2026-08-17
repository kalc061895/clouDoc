<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEstadosPostulacion extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'epo_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'epo_codigo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'epo_nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'epo_descripcion' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'epo_orden' => [
                'type' => 'INT',
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

        $this->forge->addKey('epo_ide', true);
        $this->forge->addUniqueKey('epo_codigo');

        $this->forge->createTable('selec_estados_postulacion');
    }

    public function down()
    {
        $this->forge->dropTable('selec_estados_postulacion', true);
    }
}

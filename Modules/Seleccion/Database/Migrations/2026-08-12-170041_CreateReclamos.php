<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReclamos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'rec_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'rec_codigo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'rec_pto_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'rec_fecha' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'rec_motivo' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'rec_estado' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'created_by' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('rec_ide', true);
        $this->forge->addUniqueKey('rec_codigo');
        $this->forge->addForeignKey('rec_pto_ide', 'selec_postulaciones', 'pto_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_reclamos');
    }

    public function down()
    {
        $this->forge->dropTable('selec_reclamos', true);
    }
}

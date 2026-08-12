<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateActaParticipantes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'acp_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'acp_act_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'acp_usu_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'acp_nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'acp_cargo' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],
        ]);

        $this->forge->addKey('acp_ide', true);
        $this->forge->addForeignKey('acp_act_ide', 'selec_actas', 'act_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_acta_participantes');
    }

    public function down()
    {
        $this->forge->dropTable('selec_acta_participantes', true);
    }
}

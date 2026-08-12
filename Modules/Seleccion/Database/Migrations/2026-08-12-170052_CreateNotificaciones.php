<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotificaciones extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'not_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'not_tno_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'not_usu_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'not_pos_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'not_email' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],
            'not_asunto' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'not_mensaje' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'not_fecha_envio' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'not_estado' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => true,
            ],
        ]);

        $this->forge->addKey('not_ide', true);
        $this->forge->addForeignKey('not_tno_ide', 'selec_tipos_notificacion', 'tno_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('not_pos_ide', 'selec_postulantes', 'pos_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_notificaciones');
    }

    public function down()
    {
        $this->forge->dropTable('selec_notificaciones', true);
    }
}

<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTiposNotificacion extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'tno_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'tno_codigo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'tno_nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],
            'tno_asunto' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'tno_plantilla' => [
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

        $this->forge->addKey('tno_ide', true);
        $this->forge->addUniqueKey('tno_codigo');

        $this->forge->createTable('selec_tipos_notificacion');
    }

    public function down()
    {
        $this->forge->dropTable('selec_tipos_notificacion', true);
    }
}

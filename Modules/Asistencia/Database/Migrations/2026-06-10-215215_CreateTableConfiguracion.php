<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableConfiguracion extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'cfg_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'cfg_red_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'cfg_user_ide' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'cfg_key' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],

            'cfg_value' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'cfg_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'string',
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

        $this->forge->addKey('cfg_ide', true);

        $this->forge->addKey('cfg_red_ide');
        $this->forge->addKey('cfg_user_ide');
        $this->forge->addKey('cfg_key');

        $this->forge->addUniqueKey(
            ['cfg_red_ide', 'cfg_user_ide', 'cfg_key'],
            'uq_configuracion'
        );

        $this->forge->createTable('casis_configuracion');
    }

    public function down()
    {
        $this->forge->dropTable('casis_configuracion');
    }
}

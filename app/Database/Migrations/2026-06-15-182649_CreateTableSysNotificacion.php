<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableSysNotificacion extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'not_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'not_user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],

            'not_tipo' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],

            'not_titulo' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],

            'not_mensaje' => [
                'type' => 'TEXT',
            ],

            'not_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],

            'not_prioridad' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'NORMAL',
            ],
            'not_icono' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'info',
            ],

            'not_leido' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],

            'not_mostrado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],

            'not_fecha' => [
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
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'deleted_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],

        ]);

        $this->forge->addKey('not_id', true);

        $this->forge->addKey('not_user_id');
        $this->forge->addKey('not_leido');
        $this->forge->addKey('not_mostrado');
        $this->forge->addKey('not_fecha');

        // Relación con tabla users de Shield
        $this->forge->addForeignKey(
            'not_user_id',
            'users',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('sys_notificaciones');
    }

    public function down()
    {
        $this->forge->dropTable('sys_notificaciones');
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMenuGroupUserTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'menu_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'group_user_id' => [
                'type' => 'INT',
                'unsigned' => true,
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
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('menu_id', 'menus', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('group_user_id', 'group_user', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('menu_group_user');
    }

    public function down()
    {
        $this->forge->dropTable('menu_group_user');
    }
}

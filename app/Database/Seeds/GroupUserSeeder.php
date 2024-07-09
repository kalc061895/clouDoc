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
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'menu_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'group_user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('menu_id', 'menus', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('group_user_id', 'group_user', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('menu_group_user', true);
    }

    public function down()
    {
        $this->forge->dropTable('menu_group_user', true);
    }
}

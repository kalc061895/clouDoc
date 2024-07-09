<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGroupUserTable extends Migration
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
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('group_user', true);
    }

    public function down()
    {
        $this->forge->dropTable('group_user', true);
    }
}

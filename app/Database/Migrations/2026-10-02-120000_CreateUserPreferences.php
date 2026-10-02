<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserPreferences extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'settings_json' => ['type' => 'TEXT'],
            'updated_at' => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('user_id', true);
        $this->forge->addForeignKey('user_id', config('Auth')->tables['users'], 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_preferences', true);
    }

    public function down()
    {
        $this->forge->dropTable('user_preferences', true);
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateFileUploadControlTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'oficina_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'collation'  => 'utf8mb4_general_ci',
            ],
            'folder_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'collation'  => 'utf8mb4_general_ci',
            ],
            'storage' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'collation'  => 'utf8mb4_general_ci',
            ],
            'count' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'collation'  => 'utf8mb4_general_ci',
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'value'       => new RawSql('CURRENT_TIMESTAMP'),
                'null'       => false,
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'deleted_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('file_upload_control', true, [
            'ENGINE' => 'MyISAM',
            'DEFAULT COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('file_upload_control');
    }
}

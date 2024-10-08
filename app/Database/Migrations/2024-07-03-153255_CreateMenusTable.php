<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;
class CreateMenusTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
                
            ],
            'type'        => [
                'type'           => 'ENUM',
                'constraint'     => ['separator', 'primary', 'secondary', 'ternary'],
                'default'        => 'primary',
                
            ],
            'parent_id'   => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
                
            ],
            'name'        => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                
            ],
            'abbr'        => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => true,
                
            ],
            'url'         => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => true,
                
            ],
            'icon'        => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => true,
                
            ],
            'status'      => [
                'type'           => 'ENUM',
                'constraint'     => ['active', 'inactive'],
                'default'        => 'active',
                
            ],
            'order'   => [
                'type'           => 'INT',
                'constraint'     => '10',
                
            ],
            'separator'   => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => true,
                
            ],
            'created_at'  => [
                'type'           => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
                
            ],
            'updated_at'  => [
                'type'           => 'DATETIME',
                'null'           => true,
                
            ],
            'deleted_at'  => [
                'type'           => 'DATETIME',
                'null'           => true,
                
            ],
        ]);
        $this->db->disableForeignKeyChecks();
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('parent_id', 'menus', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('menus');
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('menus');
    }
}

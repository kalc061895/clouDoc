<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableColegiatura extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'col_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'col_pro_ide' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],

            'col_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => false,
            ],

            'col_abreviatura' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],

            'col_estado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],

            // Auditoría
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
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],

            'updated_by' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],

            'deleted_by' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('col_ide', true);

        $this->forge->addKey('col_pro_ide');

        $this->forge->addUniqueKey(
            'col_abreviatura',
            'uk_col_abreviatura'
        );

        $this->forge->addForeignKey(
            'col_pro_ide',
            'casis_profesion',
            'pro_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->createTable('casis_colegiatura');
    }

    public function down()
    {
        $this->forge->dropTable('casis_colegiatura', true);
    }
}

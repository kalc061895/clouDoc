<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableProfesion extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'pro_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'pro_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],

            'pro_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => false,
            ],

            'pro_abreviatura' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],

            'pro_estado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => '1=Activo,0=Inactivo',
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

        $this->forge->addKey('pro_ide', true);

        $this->forge->addUniqueKey(
            'pro_nombre',
            'uk_pro_nombre'
        );

        $this->forge->createTable('casis_profesion');
    }

    public function down()
    {
        $this->forge->dropTable('casis_profesion');
    }
}

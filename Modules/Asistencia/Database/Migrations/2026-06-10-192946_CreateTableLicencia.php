<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableLicencia extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'lic_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'lic_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],

            'lic_abreviatura' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],

            'lic_remunerado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => '1=Si,0=No',
            ],

            'lic_dias_maximos' => [
                'type'       => 'INT',
                'constraint' => 5,
                'null'       => true,
            ],

            'lic_descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'lic_estado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'updated_by' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'deleted_by' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
        ]);

        $this->forge->addKey('lic_ide', true);

        $this->forge->addUniqueKey(
            'lic_nombre',
            'uk_lic_nombre'
        );

        $this->forge->createTable('casis_licencia');
    }

    public function down()
    {
        $this->forge->dropTable('casis_licencia', true);
    }
}

<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableOficina extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'ofi_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'ofi_est_ide' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],

            'ofi_tofi_ide' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],

            'ofi_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],

            'ofi_abreviatura' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],

            'ofi_codigo_referencia' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'ofi_titulo_encargado' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'ofi_nombres_encargado' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],

            'ofi_cargo_encargado' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],

            'ofi_descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'ofi_rango' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 1,
            ],

            'ofi_padre_ide' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],

            'ofi_estado' => [
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

        $this->forge->addKey('ofi_ide', true);

        $this->forge->addKey('ofi_est_ide');
        $this->forge->addKey('ofi_tofi_ide');
        $this->forge->addKey('ofi_padre_ide');

        $this->forge->addForeignKey(
            'ofi_est_ide',
            'casis_establecimiento',
            'est_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'ofi_tofi_ide',
            'casis_tipo_oficina',
            'tofi_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'ofi_padre_ide',
            'casis_oficina',
            'ofi_ide',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->createTable('casis_oficina');
    }

    public function down()
    {
        $this->forge->dropTable('casis_oficina', true);
    }
}

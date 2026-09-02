<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableTipoDocumentoIdentidad extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'tdi_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'tdi_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],

            'tdi_abreviatura' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],

            'tdi_longitud' => [
                'type'       => 'INT',
                'constraint' => 2,
                'null'       => true,
            ],

            'tdi_estado' => [
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

        $this->forge->addKey('tdi_ide', true);

        $this->forge->addUniqueKey(
            'tdi_nombre',
            'uk_tdi_nombre'
        );

        $this->forge->createTable('casis_tipo_documento_identidad');
    }

    public function down()
    {
        $this->forge->dropTable('casis_tipo_documento_identidad', true);
    }
}

<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableTipoMovimientoPersonal extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'tmp_ide' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'tmp_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
            ],

            'tmp_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],

            'tmp_descripcion' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],

            'tmp_estado' => [
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

        $this->forge->addKey('tmp_ide', true);

        $this->forge->addUniqueKey(
            'tmp_codigo',
            'uk_tmp_codigo'
        );

        $this->forge->addUniqueKey(
            'tmp_nombre',
            'uk_tmp_nombre'
        );

        $this->forge->createTable('casis_tipo_movimiento_personal');
    }

    public function down()
    {
        $this->forge->dropTable('casis_tipo_movimiento_personal', true);
    }
}

<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTiposBonificacion extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'tbo_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'tbo_codigo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'tbo_nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false,
            ],
            'tbo_descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tbo_tipo_calculo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'tbo_estado' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
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
    'type' => 'BIGINT',
    'unsigned' => true,
    'null' => true,
],
            'updated_by' => [
    'type' => 'BIGINT',
    'unsigned' => true,
    'null' => true,
],
            'deleted_by' => [
    'type' => 'BIGINT',
    'unsigned' => true,
    'null' => true,
],
        ]);

        $this->forge->addKey('tbo_ide', true);
        $this->forge->addUniqueKey('tbo_codigo');

        $this->forge->createTable('selec_tipos_bonificacion');
    }

    public function down()
    {
        $this->forge->dropTable('selec_tipos_bonificacion', true);
    }
}

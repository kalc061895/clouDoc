<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateComisiones extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'com_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'com_con_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'com_numero' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'com_fecha_designacion' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'com_documento_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'com_estado' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'default' => 'ACTIVA',
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
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_by' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('com_ide', true);
        $this->forge->addForeignKey('com_con_ide', 'selec_convocatorias', 'con_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('com_documento_ide', 'selec_convocatoria_documentos', 'cod_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_comisiones');
    }

    public function down()
    {
        $this->forge->dropTable('selec_comisiones', true);
    }
}

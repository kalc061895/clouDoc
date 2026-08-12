<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAnexosTable extends Migration
{
    public function up()
    {
        // --------------------------------------------------------------------
        // 1. selec_anexos
        // --------------------------------------------------------------------
        $this->forge->addField([
            'ane_ide' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ane_con_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false,
            ],
            'ane_codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'ane_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'ane_descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'ane_obligatorio' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'ane_condicion' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'ane_archivo_ide' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
            ],
            'ane_estado' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'ACTIVO',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('ane_ide', true);

        // Relaciones clave
        $this->forge->addForeignKey('ane_con_ide', 'selec_convocatorias', 'con_ide', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('ane_archivo_ide', 'selec_expediente_documentos', 'exd_ide', 'CASCADE', 'SET NULL');

        $this->forge->createTable('selec_anexos', true);
    }

    public function down()
    {
        $this->forge->dropTable('selec_anexos', true);
    }
}

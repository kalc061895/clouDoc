<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateConvocatoriaDocumentos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'cod_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'cod_con_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'cod_nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'cod_descripcion' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'cod_tipo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'cod_ruta' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
            ],
            'cod_nombre_interno' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'cod_mime' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],
            'cod_tamanio' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'cod_hash' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'cod_version' => [
                'type' => 'INT',
                'null' => true,
                'default' => 1,
            ],
            'cod_obligatorio' => [
                'type' => 'BOOLEAN',
                'null' => true,
                'default' => false,
            ],
            'created_by' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('cod_ide', true);
        $this->forge->addForeignKey('cod_con_ide', 'selec_convocatorias', 'con_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_convocatoria_documentos');
    }

    public function down()
    {
        $this->forge->dropTable('selec_convocatoria_documentos', true);
    }
}

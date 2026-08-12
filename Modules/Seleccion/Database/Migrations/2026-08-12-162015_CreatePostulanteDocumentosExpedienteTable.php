<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePostulanteDocumentosExpedienteTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'exd_ide' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'exd_pto_ide' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'exd_tipo_documento' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'exd_nombre_original' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'exd_nombre_interno' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'exd_ruta' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'null'       => true,
            ],
            'exd_mime' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true,
            ],
            'exd_tamanio' => [
                'type' => 'BIGINT',
                'null' => true,
            ],
            'exd_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'exd_version' => [
                'type'       => 'INT',
                'default'    => 1,
            ],
            'exd_estado' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'null'       => true,
            ],
            'created_by' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'exd_fecha_carga' => [
                'type' => 'DATETIME',
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
        ]);

        $this->forge->addKey('exd_ide', true);
        $this->forge->addKey('exd_pto_ide');
        $this->forge->createTable('selec_expediente_documentos', true);
    }

    public function down()
    {
        $this->forge->dropTable('selec_expediente_documentos', true);
    }
}

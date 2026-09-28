<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSelecPostulantesOtrosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'otr_ide' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
                'null'           => false,
            ],
            'otr_pos_ide' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => false,
            ],
            'otr_tipo' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'comment'    => 'SERUMS, CONADIS, FFAA, FELICITACION, COLEGIATURA, OTRO',
            ],
            'otr_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'otr_institucion' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'otr_descripcion' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'otr_fecha_expedicion' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'otr_fecha_inicio' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'otr_fecha_fin' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'otr_folios' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 1,
                'null'       => true,
            ],
            'otr_documento_ide' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],

            /* --- TIMESTAMPS --- */
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

            /* --- AUDITORÍA DE USUARIOS --- */
            'created_by' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'updated_by' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'deleted_by' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
        ]);

        // Llave Primaria
        $this->forge->addKey('otr_ide', true);

        // Claves Foráneas de la arquitectura
        $this->forge->addForeignKey('otr_pos_ide', 'selec_postulantes', 'pos_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('otr_documento_ide', 'selec_expediente_documentos', 'exd_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_postulante_otros');
    }

    public function down()
    {
        $this->forge->dropTable('selec_postulante_otros', true);
    }
}

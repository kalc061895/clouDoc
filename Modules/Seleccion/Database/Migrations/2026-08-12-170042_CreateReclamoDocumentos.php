<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReclamoDocumentos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'red_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'red_rec_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
            'red_exd_ide' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
        ]);

        $this->forge->addKey('red_ide', true);
        $this->forge->addForeignKey('red_rec_ide', 'selec_reclamos', 'rec_ide', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('red_exd_ide', 'selec_expediente_documentos', 'exd_ide', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('selec_reclamo_documentos');
    }

    public function down()
    {
        $this->forge->dropTable('selec_reclamo_documentos', true);
    }
}

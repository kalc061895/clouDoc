<?php

namespace Modules\Seleccion\Database\Migrations;

use CodeIgniter\Database\Migration;

/** Las declaraciones pueden aceptarse sin que se adjunte un archivo. */
class MakeDeclaracionDocumentoOptional extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('selec_postulacion_declaraciones', [
            'pde_exd_ide' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
        ]);
    }

    public function down()
    {
        // No se revierte: volverla obligatoria invalidaría declaraciones existentes sin archivo.
    }
}

<?php

namespace Modules\Firma\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAsuntoFirma extends Migration
{
    public function up()
    {
        foreach (['firma_operaciones', 'firma_versiones'] as $table) {
            $this->forge->addColumn($table, ['asunto' => ['type' => 'VARCHAR', 'constraint' => 200, 'default' => '']]);
        }
    }

    public function down()
    {
        foreach (['firma_operaciones', 'firma_versiones'] as $table) {
            $this->forge->dropColumn($table, 'asunto');
        }
    }
}

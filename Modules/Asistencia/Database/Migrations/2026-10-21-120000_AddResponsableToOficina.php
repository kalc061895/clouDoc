<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddResponsableToOficina extends Migration
{
    public function up()
    {
        $this->forge->addColumn('casis_oficina', [
            'ofi_responsable_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'default' => null,
            ],
        ]);
        $this->forge->addKey('ofi_responsable_id');
        $this->forge->addForeignKey(
            'ofi_responsable_id', config('Auth')->tables['users'], 'id',
            'CASCADE', 'SET NULL', 'casis_oficina_responsable_fk'
        );
        $this->forge->processIndexes('casis_oficina');
    }

    public function down()
    {
        $this->forge->dropForeignKey('casis_oficina', 'casis_oficina_responsable_fk');
        $this->forge->dropColumn('casis_oficina', 'ofi_responsable_id');
    }
}

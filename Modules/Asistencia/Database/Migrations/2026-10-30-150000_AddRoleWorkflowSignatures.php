<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

/** Estado de las tres firmas secuenciales del envío aprobado. */
class AddRoleWorkflowSignatures extends Migration
{
    public function up()
    {
        $columns = [
            'firma_elaborador_at' => ['type' => 'DATETIME', 'null' => true],
            'firma_rrhh_at' => ['type' => 'DATETIME', 'null' => true],
            'firma_direccion_at' => ['type' => 'DATETIME', 'null' => true],
            'firma_completada_at' => ['type' => 'DATETIME', 'null' => true],
        ];
        foreach ($columns as $name => $definition) {
            if (! $this->db->fieldExists($name, 'casis_rol_envio')) $this->forge->addColumn('casis_rol_envio', [$name => $definition]);
        }
    }

    public function down()
    {
        foreach (['firma_completada_at', 'firma_direccion_at', 'firma_rrhh_at', 'firma_elaborador_at'] as $name) {
            if ($this->db->fieldExists($name, 'casis_rol_envio')) $this->forge->dropColumn('casis_rol_envio', $name);
        }
    }
}

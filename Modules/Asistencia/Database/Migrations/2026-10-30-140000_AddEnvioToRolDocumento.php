<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

/** Vincula una única versión de PDF oficial a su envío aprobado. */
class AddEnvioToRolDocumento extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('envio_id', 'casis_rol_documento')) {
            $this->forge->addColumn('casis_rol_documento', [
                'envio_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            ]);
            $this->db->query('ALTER TABLE casis_rol_documento ADD UNIQUE KEY uk_rol_documento_envio (envio_id)');
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('envio_id', 'casis_rol_documento')) {
            $this->db->query('ALTER TABLE casis_rol_documento DROP INDEX uk_rol_documento_envio');
            $this->forge->dropColumn('casis_rol_documento', 'envio_id');
        }
    }
}

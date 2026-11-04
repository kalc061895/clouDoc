<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAccesoAuditLog extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'actor_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'accion' => ['type' => 'VARCHAR', 'constraint' => 40],
            'entidad_tipo' => ['type' => 'VARCHAR', 'constraint' => 40],
            'entidad_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'detalle' => ['type' => 'JSON', 'null' => true],
            'ip_address' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => true],
            'created_at' => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['entidad_tipo', 'entidad_id']);
        $this->forge->addKey(['actor_id', 'created_at']);
        $this->forge->createTable('casis_acceso_auditoria', true);
    }

    public function down()
    {
        $this->forge->dropTable('casis_acceso_auditoria', true);
    }
}

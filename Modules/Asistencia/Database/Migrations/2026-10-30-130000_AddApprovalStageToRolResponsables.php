<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

/** Mantiene permisos existentes como aprobación final y permite asignar RR. HH. por etapa. */
class AddApprovalStageToRolResponsables extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('etapa', 'casis_plan_responsable')) {
            $this->forge->addColumn('casis_plan_responsable', [
                'etapa' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'DIRECCION'],
            ]);
        }
        if (! $this->db->fieldExists('programar', 'casis_plan_responsable')) {
            $this->forge->addColumn('casis_plan_responsable', [
                'programar' => ['type' => 'TINYINT', 'default' => 1],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('etapa', 'casis_plan_responsable')) {
            $this->forge->dropColumn('casis_plan_responsable', 'etapa');
        }
        if ($this->db->fieldExists('programar', 'casis_plan_responsable')) {
            $this->forge->dropColumn('casis_plan_responsable', 'programar');
        }
    }
}

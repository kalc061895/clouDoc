<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEstablecimientoResponsable extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('casis_plan_responsable', ['oficina_id' => ['type' => 'INT', 'null' => true]]);
        $this->forge->addColumn('casis_plan_responsable', ['establecimiento_id' => ['type' => 'INT', 'null' => true]]);
    }

    public function down()
    {
        if ($this->db->table('casis_plan_responsable')->where('oficina_id', null)->countAllResults()) {
            throw new \RuntimeException('Retire las designaciones por establecimiento antes de revertir.');
        }
        $this->forge->dropColumn('casis_plan_responsable', 'establecimiento_id');
        $this->forge->modifyColumn('casis_plan_responsable', ['oficina_id' => ['type' => 'INT', 'null' => false]]);
    }
}

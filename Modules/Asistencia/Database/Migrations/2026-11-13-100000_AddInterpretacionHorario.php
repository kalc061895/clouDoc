<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddInterpretacionHorario extends Migration
{
    public function up()
    {
        $this->forge->addColumn('casis_turno_horario', [
            'th_interpretacion' => ['type' => 'VARCHAR', 'constraint' => 10, 'default' => 'MANUAL'],
            'th_tipo_jornada' => ['type' => 'VARCHAR', 'constraint' => 25, 'default' => 'ORDINARIA'],
            'th_refrigerio_marcacion' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'NO_DEFINIDO'],
            'th_margen_marcacion' => ['type' => 'INT', 'default' => 120],
            'th_margen_refrigerio' => ['type' => 'INT', 'default' => 30],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('casis_turno_horario', array_keys(\Modules\Asistencia\Services\InterpretacionHorario::DEFAULTS));
    }
}

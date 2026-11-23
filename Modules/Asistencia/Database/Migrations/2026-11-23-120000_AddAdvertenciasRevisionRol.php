<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdvertenciasRevisionRol extends Migration
{
    public function up()
    {
        $this->forge->addColumn('casis_plan_periodo', [
            'max_dias_sin_turno' => ['type' => 'INT', 'null' => true, 'default' => 2],
            'max_horas_semanales' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true],
        ]);
        $this->forge->addColumn('casis_rol_envio', [
            'observaciones_rrhh' => ['type' => 'MEDIUMTEXT', 'null' => true],
            'observaciones_direccion' => ['type' => 'MEDIUMTEXT', 'null' => true],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('casis_rol_envio', ['observaciones_rrhh', 'observaciones_direccion']);
        $this->forge->dropColumn('casis_plan_periodo', ['max_dias_sin_turno', 'max_horas_semanales']);
    }
}

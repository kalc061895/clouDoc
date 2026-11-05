<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveTurnoModalidad extends Migration
{
    public function up()
    {
        if ($this->db->fieldExists('tur_modalidad', 'casis_turno')) {
            $this->forge->dropColumn('casis_turno', 'tur_modalidad');
        }
        if ($this->db->tableExists('menus')) {
            $this->db->table('menus')->where('id', 1085)->update(['url' => null, 'status' => 'inactive']);
        }
    }

    public function down()
    {
        if (!$this->db->fieldExists('tur_modalidad', 'casis_turno')) {
            $this->forge->addColumn('casis_turno', [
                'tur_modalidad' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'ASISTENCIAL'],
            ]);
        }
        if ($this->db->tableExists('menus')) {
            $this->db->table('menus')->where('id', 1085)->update(['url' => 'asistencia/gestion-roles/turnos', 'status' => 'active']);
        }
    }
}

<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class RetireLegacyRoleScreens extends Migration
{
    public function up()
    {
        foreach (['asistencia/roles/generacion', 'asistencia/roles/historial'] as $url) {
            $this->db->table('menus')->where('url', $url)->update(['status'=>'inactive']);
        }
    }

    public function down()
    {
        // Las URL conservadas redirigen al flujo nuevo; no se restauran generadores antiguos.
        foreach (['asistencia/roles/generacion', 'asistencia/roles/historial'] as $url) {
            $this->db->table('menus')->where('url', $url)->update(['status'=>'active']);
        }
    }
}

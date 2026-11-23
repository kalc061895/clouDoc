<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;
use Modules\Asistencia\Database\Seeds\MenuGroupUserSeeder;
use Modules\Asistencia\Database\Seeds\MenuSeeder;

class CreatePresupuestoGuardias extends Migration
{
    public function up()
    {
        $menu = array_column(MenuSeeder::menus(), null, 'id')[1104];
        $actual = $this->db->table('menus')->where('id', 1104)->get()->getRowArray();
        if ($actual && $actual['url'] !== $menu['url']) throw new \RuntimeException('El identificador del menú de presupuesto ya está ocupado.');

        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'codigo' => ['type' => 'VARCHAR', 'constraint' => 32],
            'periodo_id' => ['type' => 'INT', 'unsigned' => true],
            'usuario_id' => ['type' => 'INT', 'unsigned' => true],
            'formato' => ['type' => 'VARCHAR', 'constraint' => 4],
            'generado_at' => ['type' => 'DATETIME'],
            'sha256' => ['type' => 'CHAR', 'constraint' => 64],
            'snapshot' => ['type' => 'LONGTEXT'],
            'contenido' => ['type' => 'LONGTEXT'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('codigo');
        $this->forge->addKey(['usuario_id', 'id']);
        $this->forge->createTable('casis_presupuesto_guardia_exportacion', true);
        $this->db->transStart();
        if (!$actual) $this->db->table('menus')->insert($menu);
        $asignaciones = MenuGroupUserSeeder::asignaciones();
        foreach ($this->db->table('group_user')->select('id, name')->get()->getResultArray() as $grupo) {
            if (!in_array(1104, $asignaciones[$grupo['name']] ?? [], true)) continue;
            foreach ([1080, 1104] as $id) {
                $relacion = ['group_user_id' => $grupo['id'], 'menu_id' => $id];
                if (!$this->db->table('menu_group_user')->where($relacion)->countAllResults()) {
                    $this->db->table('menu_group_user')->insert($relacion);
                }
            }
        }
        $this->db->transComplete();
        if (!$this->db->transStatus()) throw new \RuntimeException('No se pudo registrar el menú de presupuesto.');
    }

    public function down()
    {
        $this->db->table('menu_group_user')->where('menu_id', 1104)->delete();
        $this->db->table('menus')->where('id', 1104)->delete();
        $this->forge->dropTable('casis_presupuesto_guardia_exportacion', true);
    }
}

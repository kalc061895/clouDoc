<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

/** Instala solamente Reportes, preservando las personalizaciones del resto del menú. */
class ReportesMenuSeeder extends Seeder
{
    public function run()
    {
        $menus = array_filter(MenuSeeder::menus(), static fn($m) => in_array($m['id'], [1050, 1051], true));
        foreach ($menus as $menu) {
            $existing = $this->db->table('menus')->where('id', $menu['id'])->get()->getRowArray();
            if ($existing && ($existing['url'] !== $menu['url'] || ($menu['url'] === null && $existing['name'] !== $menu['name']))) throw new \RuntimeException('El ID de menú ' . $menu['id'] . ' está ocupado por otra opción.');
        }
        $this->db->transStart();
        foreach ($menus as $menu) {
            $q = $this->db->table('menus');
            if ($q->where('id', $menu['id'])->countAllResults()) $q->where('id', $menu['id'])->update($menu);
            else $q->insert($menu);
        }
        $this->db->transComplete();
        if (! $this->db->transStatus()) throw new \RuntimeException('No se pudo registrar el menú de reportes.');
    }
}

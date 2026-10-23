<?php
namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

/** Instalación aditiva: no modifica las otras opciones ni las membresías. */
class CalificacionMenuSeeder extends Seeder
{
    public function run()
    {
        $menus=array_filter(MenuSeeder::menus(),static fn($m)=>in_array($m['id'],[1090,1091,1092,1093],true));
        foreach ($menus as $m) {
            $old=$this->db->table('menus')->where('id',$m['id'])->get()->getRowArray();
            if ($old && ($old['url']!==$m['url'] || $old['name']!==$m['name'])) throw new \RuntimeException('ID de menú ocupado: '.$m['id']);
        }
        $this->db->transStart();
        foreach ($menus as $m) {
            $q=$this->db->table('menus');
            if (!$q->where('id',$m['id'])->countAllResults()) $q->insert($m);
            foreach ($this->db->table('group_user')->whereIn('name',['asistencia','asi_sua','asi_adm'])->get()->getResultArray() as $g) {
                $rel=['menu_id'=>$m['id'],'group_user_id'=>$g['id']];
                if (!$this->db->table('menu_group_user')->where($rel)->countAllResults()) $this->db->table('menu_group_user')->insert($rel);
            }
        }
        $this->db->transComplete();
        if (!$this->db->transStatus()) throw new \RuntimeException('No se pudo instalar el menú.');
    }
}

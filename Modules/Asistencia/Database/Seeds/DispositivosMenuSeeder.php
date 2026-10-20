<?php
namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

/** Instala únicamente dispositivos, conservando los demás menús personalizados. */
class DispositivosMenuSeeder extends Seeder
{
    public function run()
    {
        $menus=array_filter(MenuSeeder::menus(),fn($m)=>in_array($m['id'],[1070,1071,1072],true));
        $grupos=[];
        foreach (['asistencia','asi_sua','asi_adm'] as $nombre) {
            $grupo=$this->db->table('group_user')->where('name',$nombre)->get()->getRowArray();
            if (!$grupo) throw new \RuntimeException('Falta el perfil '.$nombre.'. Ejecute GroupUserSeeder de Asistencia.');
            $grupos[$nombre]=(int)$grupo['id'];
        }
        foreach ($menus as $menu) {
            $actual=$this->db->table('menus')->where('id',$menu['id'])->get()->getRowArray();
            if ($actual && ($actual['url']!==$menu['url'] || ($menu['url']===null && $actual['name']!==$menu['name']))) throw new \RuntimeException('El menú '.$menu['id'].' pertenece a otra opción.');
        }
        $this->db->transStart();
        foreach ($menus as $menu) {
            $q=$this->db->table('menus');
            if ($q->where('id',$menu['id'])->countAllResults()) $q->where('id',$menu['id'])->update($menu);
            else $q->insert($menu);
            foreach ($grupos as $nombre=>$grupoId) {
                if (!in_array($menu['id'],MenuGroupUserSeeder::asignaciones()[$nombre],true)) continue;
                $rel=['group_user_id'=>$grupoId,'menu_id'=>$menu['id']];
                if (!$this->db->table('menu_group_user')->where($rel)->countAllResults()) $this->db->table('menu_group_user')->insert($rel);
            }
        }
        $this->db->transComplete();
        if (!$this->db->transStatus()) throw new \RuntimeException('No se pudo instalar el menú de dispositivos.');
    }
}

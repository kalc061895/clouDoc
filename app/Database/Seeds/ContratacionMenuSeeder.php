<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ContratacionMenuSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        /*
        |--------------------------------------------------------------------------
        | MENÚS PADRE
        |--------------------------------------------------------------------------
        */
        $menusPadre = [
            ['name'=>'POSTULACIÓN','icon'=>'fa-file-signature','order'=>100],
            ['name'=>'CONFIGURACIÓN','icon'=>'fa-gear','order'=>200],
            ['name'=>'EVALUACIÓN','icon'=>'fa-check-square','order'=>300],
            ['name'=>'RESULTADOS','icon'=>'fa-trophy','order'=>400],
        ];

        $menuIds = [];

        foreach ($menusPadre as $m) {
            $this->db->table('menus')->insert([
                'type'=>'primary',
                'parent_id'=>null,
                'name'=>$m['name'],
                'abbr'=>null,
                'url'=>null,
                'icon'=>$m['icon'],
                'status'=>'active',
                'separator'=>null,
                'order'=>$m['order'],
                'created_at'=>$now,
                'updated_at'=>$now,
            ]);
            $menuIds[$m['name']] = $this->db->insertID();
        }

        /*
        |--------------------------------------------------------------------------
        | SUBMENÚS
        |--------------------------------------------------------------------------
        */
        $submenus = [

            // POSTULACIÓN
            ['POSTULACIÓN','Convocatorias vigentes','/convocatorias','fa-bullhorn',101],
            ['POSTULACIÓN','Mi postulación','/postulacion','fa-user-check',102],
            ['POSTULACIÓN','Mis documentos','/postulacion/documentos','fa-folder-open',103],
            ['POSTULACIÓN','Estado de postulación','/postulacion/estado','fa-info-circle',104],
            ['POSTULACIÓN','Historial de postulaciones','/postulacion/historial','fa-clock',105],

            // CONFIGURACIÓN
            ['CONFIGURACIÓN','Convocatorias','/admin/convocatorias','fa-bullhorn',201],
            ['CONFIGURACIÓN','Plazas','/admin/plazas','fa-layer-group',202],
            ['CONFIGURACIÓN','Perfiles de puesto','/admin/perfiles','fa-id-card',203],
            ['CONFIGURACIÓN','Requisitos por perfil','/admin/requisitos','fa-list-check',204],
            ['CONFIGURACIÓN','Cronograma','/admin/cronograma','fa-calendar',205],
            ['CONFIGURACIÓN','Etapas','/admin/etapas','fa-diagram-project',206],
            ['CONFIGURACIÓN','Usuarios / Comisión','/admin/usuarios','fa-users-cog',207],
            ['CONFIGURACIÓN','Puntajes y criterios','/admin/criterios','fa-percent',208],

            // EVALUACIÓN
            ['EVALUACIÓN','Postulantes por plaza','/evaluacion/postulantes','fa-users',301],
            ['EVALUACIÓN','Evaluación curricular','/evaluacion/curricular','fa-check',302],
            ['EVALUACIÓN','Entrevistas','/evaluacion/entrevistas','fa-microphone',303],
            ['EVALUACIÓN','Observaciones','/evaluacion/observaciones','fa-comment-dots',304],

            // RESULTADOS
            ['RESULTADOS','Resultados preliminares','/resultados/preliminares','fa-clipboard-list',401],
            ['RESULTADOS','Cuadro de mérito','/resultados/merito','fa-table',402],
            ['RESULTADOS','Resultados finales','/resultados/finales','fa-award',403],
            ['RESULTADOS','Actas','/resultados/actas','fa-file-pdf',404],
        ];

        $menuRol = [];

        foreach ($submenus as $s) {
            [$padre,$name,$url,$icon,$order] = $s;

            $this->db->table('menus')->insert([
                'type'=>'secondary',
                'parent_id'=>$menuIds[$padre],
                'name'=>$name,
                'abbr'=>null,
                'url'=>$url,
                'icon'=>$icon,
                'status'=>'active',
                'separator'=>null,
                'order'=>$order,
                'created_at'=>$now,
                'updated_at'=>$now,
            ]);

            $menuId = $this->db->insertID();

            /*
            |--------------------------------------------------------------------------
            | ASIGNACIÓN POR ROL
            |--------------------------------------------------------------------------
            */

            // SUPERADMIN → todo
            $menuRol[] = ['group_user_id'=>1,'menu_id'=>$menuId];

            // POSTULANTE
            if ($padre === 'POSTULACIÓN' || $padre === 'RESULTADOS') {
                $menuRol[] = ['group_user_id'=>6,'menu_id'=>$menuId];
            }

            // COMISIÓN
            if ($padre === 'EVALUACIÓN' || $padre === 'RESULTADOS') {
                $menuRol[] = ['group_user_id'=>7,'menu_id'=>$menuId];
            }
        }

        // padres también visibles
        foreach ($menuIds as $id) {
            $menuRol[] = ['group_user_id'=>1,'menu_id'=>$id];
            $menuRol[] = ['group_user_id'=>6,'menu_id'=>$id];
            $menuRol[] = ['group_user_id'=>7,'menu_id'=>$id];
        }

        $this->db->table('menu_group_user')->insertBatch($menuRol);
    }
}

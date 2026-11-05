<?php

namespace App\Controllers;

use App\Models\ExpedientesModel;
use App\Models\MenuModel;


class Home extends BaseController
{
    public function index()
    {
        /*
        $expediente = new ExpedientesModel();
        $dashboard = [
            "expediente"=> [
                "actual"=> $expediente->getActual(),
                "totalHoy"=> $expediente->getTotalHoy(),
                "totalAtendidos"=> $expediente->getTotalAtendidos(),
                "totalEspera"=> $expediente->getTotalEspera(),
            ],
            "drive"=> [
                "almacenamientoTotal"=> '20.5 GB',
            ],
            "grafica"=> [

            ]

        ];
        */
        // menu filtardo por grupo de usuario usuario
        $groups = auth()->user()->getGroups();
        if (!in_array('superadmin', $groups, true) && array_intersect($groups, ['asistencia', 'asi_sua', 'asi_adm', 'asi_apo'])) {
            return redirect()->to(base_url('asistencia'));
        }
        switch ($groups[0] ?? '') {
            case 'superadmin':
            case 'tramite':
            case 'oficina':
            case 'area':
                $menu = new MenuModel();
                $set = array(
                    //"menu"=> $menu->findAll(),
                    "menu" => $menu->getMenuTree(),
                    //"dasboard"=> $dashboard,
                    //"menu"=> $menu->getMenuUsuario(),
                );

                return view('dashboard', $set);

                break;
            case 'asistencia':
                case 'asi_sua':
                case 'asi_apo': case'asi_dm': case'asi_apoyo':                
                case 'asi_diresa': case'asi_diresa_apoyo': case'asi_diresa_registro': case'asi_diresa_consulta': 
                case 'asi_red': case'asi_red_apoyo': case'asi_red_registro': case'asi_red_consulta': 
                case 'asi_mred': case'asi_mred_apoyo': case'asi_mred_registro': case'asi_mred_consulta':
                case 'asi_est': case'asi_est_apoyo': case'asi_est_registro': case'asi_est_consulta': 
                case 'asi_auditoria':
                return redirect()->to(base_url('asistencia'));
            case 'postulante':
                return redirect()->to(base_url('seleccion/postulacion'));
                break;
            case 'comision':
                return redirect()->to(base_url('seleccion/comision'));
                break;
            default:
                return redirect()->to(base_url('login'));
        }
    }
}

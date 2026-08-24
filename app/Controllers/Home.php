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
        switch (auth()->user()->getGroups()[0]) {
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
                break;
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

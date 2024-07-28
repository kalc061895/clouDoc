<?php

namespace App\Controllers;
use App\Models\ExpedientesModel;
use App\Models\MenuModel;


class Home extends BaseController
{
    public function index(): string
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
        $menu = new MenuModel();
        $set = array(
            //"menu"=> $menu->findAll(),
            "menu"=> $menu->getMenusByRole(),
            //"dasboard"=> $dashboard,
            //"menu"=> $menu->getMenuUsuario(),
        );

        return view('dashboard',$set);
    }
}

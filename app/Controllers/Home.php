<?php

namespace App\Controllers;
use App\Models\MenuModel;

class Home extends BaseController
{
    public function index(): string
    {

        $menu = new MenuModel();
        $set = array(
            "menu"=> $menu->getAll(),
        );
        return view('welcome_message',$set);
    }
}

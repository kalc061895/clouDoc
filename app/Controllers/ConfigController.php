<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ConfigController extends BaseController
{
    public function index()
    {
        //
    }
    public function menus(){

        return view("config/menus");
    }


}

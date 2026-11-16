<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;

class PortalController extends BaseController
{
    public function index()
    {
        return view('portal/inicio', ['portal'=>config('Portal'), 'moduloActual'=>null]);
    }

    public function modulo(string $slug)
    {
        $portal = config('Portal');
        if (!isset($portal->modulos[$slug])) throw PageNotFoundException::forPageNotFound();
        return view('portal/modulo', ['portal'=>$portal, 'modulo'=>$portal->modulos[$slug], 'moduloActual'=>$slug]);
    }
}

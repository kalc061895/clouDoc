<?php

namespace Modules\Seleccion\Controllers;

use App\Controllers\BaseController;

class ConvocatoriaWorkflowController extends BaseController
{
    public function index()
    {
        //return view('convocatorias/index');
        return view('Modules\Seleccion\Views\convocatorias\index');
    }
    public function detalle(int $id)
    {
        return view('convocatorias/detalle', ['convocatoriaId' => $id]);
    }
}

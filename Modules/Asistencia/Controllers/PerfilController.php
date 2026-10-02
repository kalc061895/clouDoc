<?php

namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;
use App\Libraries\AsistenciaLayoutData;

class PerfilController extends BaseModuleController
{
    public function index()
    {
        return view('Modules\Asistencia\Views\perfil\index', ['perfilAsistencia' => AsistenciaLayoutData::perfil(auth()->user())]);
    }
}

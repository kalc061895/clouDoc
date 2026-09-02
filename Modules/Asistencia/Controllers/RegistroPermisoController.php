<?php

namespace Modules\Asistencia\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\AnioModel;
use App\Models\MesesModel;

class RegistroPermisoController extends BaseController
{
    public function index()
    {
        //
    }

    public function getPanePermisos($id)
    {
        $anioModel = new AnioModel();
        $mesModel = new MesesModel();


        // Traemos los datos ordenados
        $data['anios'] = $anioModel->orderBy('numero', 'DESC')->findAll();
        $data['meses'] = $mesModel->orderBy('numero', 'ASC')->findAll();

        $data['perl_ide'] = $id;

        return view('Modules\Asistencia\Views\personal\modals\pane_permisos_view', $data);
    }
}

<?php

namespace Modules\Asistencia\Controllers;

use App\Controllers\BaseController;
use App\Models\AnioModel;
use App\Models\MesesModel;
use CodeIgniter\HTTP\ResponseInterface;

class RegistroLicenciaController extends BaseController
{
    public function index()
    {
        //
    }

    public function getPaneIncidencias($id)
    {
        $anioModel = new AnioModel();
        $mesModel = new MesesModel();


        // Traemos los datos ordenados
        $data['anios'] = $anioModel->orderBy('numero', 'DESC')->findAll();
        $data['meses'] = $mesModel->orderBy('numero', 'ASC')->findAll();

        $data['perl_ide'] = $id;

        return view('Modules\Asistencia\Views\personal\modals\pane_licencias_view', $data);
    }

}

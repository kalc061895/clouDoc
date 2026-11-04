<?php

namespace Modules\Asistencia\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\AnioModel;
use App\Models\MesesModel;
use Modules\Asistencia\Services\AccesoAsistenciaService;

class RegistroPermisoController extends BaseController
{
    public function index()
    {
        //
    }

    public function getPanePermisos($id)
    {
        $acceso = new AccesoAsistenciaService();
        if (!$acceso->permitir('lectura_personal') || !$acceso->personalDentroAmbito((int) $id)) return $this->response->setStatusCode(404)->setBody('Trabajador no encontrado.');
        $anioModel = new AnioModel();
        $mesModel = new MesesModel();


        // Traemos los datos ordenados
        $data['anios'] = $anioModel->orderBy('numero', 'DESC')->findAll();
        $data['meses'] = $mesModel->orderBy('numero', 'ASC')->findAll();

        $data['perl_ide'] = $id;

        return view('Modules\Asistencia\Views\personal\modals\pane_permisos_view', $data);
    }
}

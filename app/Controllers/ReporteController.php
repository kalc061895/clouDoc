<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ExpedientesModel;
use CodeIgniter\HTTP\ResponseInterface;

class ReporteController extends BaseController
{
    public function index()
    {
        //
    }

    public function getPlanillaTramite() {

        $_expedientesModel = new ExpedientesModel();
        $set = array(
            'expedientes' => $_expedientesModel->getExpedientesTodo(),
        );

        return view('reporte/planilla_tramite',$set );
    }
}

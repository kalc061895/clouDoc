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

    public function getPlanillaTramite()
    {

        $_expedientesModel = new ExpedientesModel();
        $where = [

            [
                'key' => 'expedientes.numero_expediente',
                'value' => '015104',
            ],
        ];
        $set = array(
            //'expedientes' => $_expedientesModel->getExpedientesTodo(),
            'expedientes' => $_expedientesModel->getReporteExpedientesFiltrado($where),
        );

        return view('reporte/planilla_tramite', $set);
    }
}

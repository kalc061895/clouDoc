<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ExpedientesModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Database\RawSql;

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
    public function postPlanillaTramiteFiltrado()
    {

        $_post = $this->request->getPost();
        $_expedientesModel = new ExpedientesModel();

        $where = [];

        if ($_post['numExpediente'] != '') {
            $where[] = [
                'key' => 'expedientes.numero_expediente LIKE',
                'value' => "%".$_post['numExpediente']."%",
            ];
        }
        if ($_post['fechaInicio'] != '') {
            $where[] = [
                'key' => 'expedientes.fecha_recepcion >=',
                'value' => $_post['fechaInicio'],
            ];
        }

        if ($_post['fechaFin'] != '') {
            $where[] = [
                'key' => 'expedientes.fecha_recepcion <=',
                'value' => $_post['fechaFin'],
            ];
        }
        if ($_post['nroDocumento'] != '') {
            $where[] = [
                'key' => 'expedientes.numero_documento ',
                'value' => $_post['nroDocumento'],
            ];
        }
        if ($_post['nombres'] != '') {
            $where[] = [
                'key' => 'entidad.nombre LIKE',
                'value' => "%".$_post['nombres']."%",
            ];
        }
        if ($_post['asunto'] != '') {
            $where[] = [
                'key' => 'expedientes.asunto LIKE',
                'value' => "%".$_post['asunto']."%",
            ];
        }

        $data = $_expedientesModel->getReporteExpedientesFiltrado($where);
        return $this->response->setJSON($data);
    }
}

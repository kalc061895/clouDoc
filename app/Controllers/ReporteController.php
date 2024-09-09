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

        $set = array(
            //'expedientes' => $_expedientesModel->getExpedientesTodo(),
            'expedientes' => $_expedientesModel->getReporteExpedientesFiltrado(),
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
                'value' => "%" . $_post['numExpediente'] . "%",
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
                'value' => "%" . $_post['nombres'] . "%",
            ];
        }
        if ($_post['asunto'] != '') {
            $where[] = [
                'key' => 'expedientes.asunto LIKE',
                'value' => "%" . $_post['asunto'] . "%",
            ];
        }

        $data = $_expedientesModel->getReporteExpedientesFiltrado($where);
        return $this->response->setJSON($data);
    }
    public function getHojaRuta()
    {

        $_expedientesModel = new ExpedientesModel();

        $set = array(
            //'expedientes' => $_expedientesModel->getExpedientesTodo(),
            'expedientes' => $_expedientesModel->getReporteExpedientesFiltrado(),
        );

        return view('reporte/hoja_ruta', $set);
    }
    public function postHojaRutaFiltrado()
    {

        $_post = $this->request->getPost();
        $_expedientesModel = new ExpedientesModel();

        $where = [];

        if ($_post['numExpediente'] != '') {
            $where[] = [
                'key' => 'expedientes.numero_expediente LIKE',
                'value' => "%" . $_post['numExpediente'] . "%",
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
                'value' => "%" . $_post['nombres'] . "%",
            ];
        }
        if ($_post['asunto'] != '') {
            $where[] = [
                'key' => 'expedientes.asunto LIKE',
                'value' => "%" . $_post['asunto'] . "%",
            ];
        }

        $data = $_expedientesModel->getReporteExpedientesFiltrado($where);
        foreach ($data as $key => $value) {
            $data[$key]->movimientos = [
                [
                    'oficina_destino' => 'Unidad de Recursos Humanos',
                    'column2' => 'Dataasdasd 2',
                    'column3' => 'Datasadasd 3',
                    'column4' => 'Dataasdasd 4',
                    'column5' => 'Daasdasdta 5'
                ],
                ['column1' => 'Dataasdas 6', 'column2' => 'Datasdasda 7', 'column3' => 'Datadasdasd 8', 'column4' => 'Daasdasdasdta 9', 'column5' => 'Data sdadas10'],
                ['column1' => 'Dataasdas 6', 'column2' => 'Datasdasda 7', 'column3' => 'Datadasdasd 8', 'column4' => 'Daasdasdasdta 9', 'column5' => 'Data sdadas10'],
                ['column1' => 'Dataasdas 6', 'column2' => 'Datasdasda 7', 'column3' => 'Datadasdasd 8', 'column4' => 'Daasdasdasdta 9', 'column5' => 'Data sdadas10'],
                ['column1' => 'Dataasdas 6', 'column2' => 'Datasdasda 7', 'column3' => 'Datadasdasd 8', 'column4' => 'Daasdasdasdta 9', 'column5' => 'Data sdadas10'],
                ['column1' => 'Dataasdas 6', 'column2' => 'Datasdasda 7', 'column3' => 'Datadasdasd 8', 'column4' => 'Daasdasdasdta 9', 'column5' => 'Data sdadas10'],
                // Agrega más filas según sea necesario
            ];
        }
        return $this->response->setJSON($data);
    }
}

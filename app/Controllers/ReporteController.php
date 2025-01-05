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
            if ($_post['numExpedienteFin'] != '') {
                $where[] = [
                    'key' => 'expedientes.numero_expediente >=',
                    'value' => "" . $_post['numExpediente'] . "",
                ];
                $where[] = [
                    'key' => 'expedientes.numero_expediente <=',
                    'value' => "" . $_post['numExpedienteFin'] . "",
                ];
            }else{
              $where[] = [
                'key' => 'expedientes.numero_expediente LIKE',
                'value' => "%" . $_post['numExpediente'] . "%",
                ];
            }

        }
        if ($_post['fechaInicio'] != '') {
            $where[] = [
                'key' => 'expedientes.fecha_recepcion >=',
                'value' => $_post['fechaInicio'],
            ];
        }

        if ($_post['fechaInicio'] != '') {
            $where[] = [
                'key' => 'expedientes.fecha_recepcion >=',
                'value' => $_post['fechaInicio'].' 00:00:00',
            ];
        }

        if ($_post['fechaFin'] != '') {
            $where[] = [
                'key' => 'expedientes.fecha_recepcion <=',
                'value' => $_post['fechaFin'].' 23:59:59',
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
            if ($_post['numExpedienteFin'] != '') {
                $where[] = [
                    'key' => 'expedientes.numero_expediente >=',
                    'value' => "" . $_post['numExpediente'] . "",
                ];
                $where[] = [
                    'key' => 'expedientes.numero_expediente <=',
                    'value' => "" . $_post['numExpedienteFin'] . "",
                ];
            }else{
              $where[] = [
                'key' => 'expedientes.numero_expediente LIKE',
                'value' => "%" . $_post['numExpediente'] . "%",
                ];
            }
        }
        if ($_post['fechaInicio'] != '') {
            $where[] = [
                'key' => 'expedientes.fecha_recepcion >=',
                'value' => $_post['fechaInicio'].' 00:00:00',
            ];
        }

        if ($_post['fechaFin'] != '') {
            $where[] = [
                'key' => 'expedientes.fecha_recepcion <=',
                'value' => $_post['fechaFin'].' 23:59:59',
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
            $_movimientos = $_expedientesModel->getMovimientos($value->id);
            $_setMovimientos =[];
            foreach ($_movimientos as $keyVal => $val) {
                $_setMovimientos[] = [
                    'oficina_destino' => $val->oficina_destino_abreviatura,
                    'accion' => $val->accion,
                    'fecha' => $val->fecha_envio,
                    'folios' => $value->folios,
                    'firma' => $value->estado
                ];
            }
            $data[$key]->movimientos = $_setMovimientos;
        }
        return $this->response->setJSON($data);
    }

    public function getBuscarTramite()
    {
        $_expedientesModel = new ExpedientesModel();
        $set = [
            'anios'  =>  $_expedientesModel->getAnios(),
            'exp_id'  =>  $this->request->getGet('id') ?? '',
        ];
        return view('tramite/busqueda_expediente', $set);
    }
    public function getBuscarAvanzadoTramite()
    {

        $_expedientesModel = new ExpedientesModel();

        $set = array(
            //'expedientes' => $_expedientesModel->getExpedientesTodo(),
            'expedientes' => $_expedientesModel->getReporteExpedientesFiltrado(),
        );

        return view('reporte/planilla_tramite', $set);
    }
    public function postBuscarTramiteFiltrado()
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

        if ($_post['fechaInicio'] != '') {
            $where[] = [
                'key' => 'expedientes.fecha_recepcion >=',
                'value' => $_post['fechaInicio'].' 00:00:00',
            ];
        }

        if ($_post['fechaFin'] != '') {
            $where[] = [
                'key' => 'expedientes.fecha_recepcion <=',
                'value' => $_post['fechaFin'].' 23:59:59',
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

    public function getGraficoSemanal() {

        $arrayName = array(
            'sales' => [intval(rand(180,400)),intval(rand(180,400)),intval(rand(180,400)),intval(rand(180,400)),intval(rand(180,400)),intval(rand(180,400)),intval(rand(180,400))],
            'orders' => [intval(rand(50,150)),intval(rand(50,150)),intval(rand(50,150)),intval(rand(50,150)),intval(rand(50,150)),intval(rand(50,150)),intval(rand(50,150))],
            'deliveries' => [intval(rand(181,320)),intval(rand(181,320)),intval(rand(181,320)),intval(rand(181,320)),intval(rand(181,320)),intval(rand(181,320)),intval(rand(181,320))],
            'categories' => ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"],
            'params'=>[
                'totalSemanal'=>rand(500,2000),
                'diferenciaSemanal'=>rand(100,200),
                'totalHoy'=>rand(500,2000),
                'diferenciaHoy'=>rand(100,200),
                'totalAlmacenamiento'=>rand(500,2000),
                'diferenciaAlmacenamiento'=>rand(100,200),
                'totalAtendidos'=>rand(1810,3200),
                'diferenciaAtendidos'=>rand(100,300),
                'hoyFisicos'=>rand(100,300),
                'hoyVirtual'=>rand(100,300),
                'hoyAtendidos'=>rand(100,300),
                'hoyEmitidos'=>rand(100,300),
                
            ]
        );

        return $this->response->setJSON($arrayName);
    } 
}

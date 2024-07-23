<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ExpedientesModel;
use App\Models\AccionModel;
use App\Models\OficinaModel;

class TramiteController extends BaseController
{
    public function index()
    {
        //
    }
    public function getNuevosExpedientes(): String
    {

        $tramiteModel = new ExpedientesModel();
        $set = [
            'expediente' => $tramiteModel->getNuevosExpedientesExternos(),
        ];
        return view('tramite/listar_nuevos_expedientes', $set);
    }

    public function getDetallesExpedientes()
    {
        $id = $this->request->getGet('id');
        $expedienteModel = new ExpedientesModel();
        $expediente = $expedienteModel->detalleExpediente($id);

        $oficinaModel = new OficinaModel();
        $accionModel = new AccionModel();

        if ($expediente) {
            $response = [
                'title' => 'Detalle del Expediente: ' . $expediente[0]->numero_expediente,
                'body' => view(
                    'tramite/detalle_expediente',
                    [
                        'expediente' => $expediente,
                        'oficina' => $oficinaModel->findAll(),
                        'accion' => $accionModel->findAll(),

                    ]
                )
            ];
        } else {
            $response = [
                'title' => 'Error',
                'body' => 'Expediente no encontrado'
            ];
        }

        return $this->response->setJSON($response);
    }
}

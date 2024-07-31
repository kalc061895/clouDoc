<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MovimientosModel;
use App\Models\UsuarioModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ExpedientesModel;
use App\Models\AccionModel;
use App\Models\OficinaModel;
use Config\Auth;

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
    public function postDerivarExpediente()
    {
        $userModel = new UsuarioModel();
        $_usuario = $userModel->find(auth()->user()->id);
        $movimientoModel = new MovimientosModel();

        // Obtener el número de movimiento
        $ultimoMovimiento = $movimientoModel->where('expediente_id', $this->request->getPost('id'))->orderBy('numero_movimiento', 'DESC')->first();

        $set = [
            'expediente_id' => $this->request->getPost('id'),
            //'accion'=> $this->request->getPost('accionDerivar'),
            'oficina_procedencia_id' => $_usuario->oficina_id,
            'oficina_destino_id' => $this->request->getPost('oficinaDerivar'),
            'numero_movimiento' => $ultimoMovimiento ? $ultimoMovimiento['numero_movimiento'] + 1 : 1,
            'estado' => 'ESPERA',
        ];
        $nuevoMovimiento = new MovimientosModel(); 
        $nuevoMovimiento->insert($set);
        echo $nuevoMovimiento->insertID();
        return 0;
        
        //guardar archivo

        $set_adjunto =[
            'movimiento_id'=> $nuevoMovimiento->insertID(),
         ];
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

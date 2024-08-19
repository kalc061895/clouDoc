<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MovimientosModel;
use App\Models\UsuarioModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ExpedientesModel;
use App\Models\AccionModel;
use App\Models\OficinaModel;
use App\Models\AdjuntoModel;
use Config\Auth;
use App\Libraries\GoogleDrive;

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
        $movimientoArray = $expedienteModel->getMovimientos($id);
        // Manejo del archivo
        $_adjunto = new AdjuntoModel();
        $adjunto = $_adjunto->where('expediente_id', $id)->get()->getResultObject();


        $oficinaModel = new OficinaModel();
        $accionModel = new AccionModel();

        if ($expediente) {
            $response = [
                'title' => 'Detalle del Expediente: ' . $expediente[0]->numero_expediente,
                'body' => view(
                    'tramite/detalle_expediente',
                    [
                        'expediente' => $expediente,
                        'movimiento' => $movimientoArray,
                        'adjunto' => $adjunto,
                        'oficina' => $oficinaModel->orderBy('oficina_padre_id ASC')->findAll(),
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

        $_ultimoMovimiento = new MovimientosModel();
        $movimiento = $_ultimoMovimiento->find($this->request->getPost('idDerivar'));

        if ($movimiento != null) {
            $movimiento['estado'] = 'DERIVADO';
            $_ultimoMovimiento->save($movimiento);
        } else {
        }

        // Obtener el número de movimiento
        $ultimoMovimiento = $movimientoModel->where('expediente_id', $this->request->getPost('id'))->orderBy('numero_movimiento', 'DESC')->first();

        $set = [
            'expediente_id' => $this->request->getPost('idDerivar'),
            'observacion' => $this->request->getPost('observacionDerivar'),
            'accion' => $this->request->getPost('accionDerivar'),
            'oficina_procedencia_id' => $_usuario->oficina_id,
            'oficina_destino_id' => $this->request->getPost('oficinaDerivar'),
            'numero_movimiento' => $ultimoMovimiento ? $ultimoMovimiento['numero_movimiento'] + 1 : 1,
            'estado' => 'ESPERA',
        ];
        $nuevoMovimiento = new MovimientosModel();
        $insertResult = $nuevoMovimiento->insert($set);

        $insertID = $nuevoMovimiento->insertID();
        if ($insertResult !== false) {
            $insertID = $nuevoMovimiento->insertID();

            //guardar archivo
            /**
             * Aqui se debe llamar a la funcion que guardara el archivo adjunto si se adjunta algun archivo o archivos
             */
            // Uso de la función
            $response = $this->handleFileUpload(
                'adjuntoDerivar',
                $this->request->getPost('idDerivar'),
                $insertID
            );
        } else {
            // Manejar el error de inserción
            // Podrías lanzar una excepción o manejar el error de otra manera adecuada
            // throw new \RuntimeException('Error al insertar el nuevo movimiento');
            $response = 'Error al insertar el nuevo movimiento';
        }

        $response = [
            'title' => 'success',
            'body' => 'Expediente no encontrado',
            'idexpediente' => $this->request->getPost('idDerivar')
        ];


        return $this->response->setJSON($response);
    }
    public function postAtenderExpediente()
    {
        $userModel = new UsuarioModel();
        $_usuario = $userModel->find(auth()->user()->id);
        $movimientoModel = new MovimientosModel();

        $_ultimoMovimiento = new MovimientosModel();
        $movimiento = $_ultimoMovimiento->find($this->request->getPost('idAtender'));

        if ($movimiento != null) {
            $movimiento['estado'] = 'DERIVADO';
            $_ultimoMovimiento->save($movimiento);
        } else {
        }

        // Obtener el número de movimiento
        $ultimoMovimiento = $movimientoModel->where('expediente_id', $this->request->getPost('id'))->orderBy('numero_movimiento', 'DESC')->first();

        $set = [
            'expediente_id' => $this->request->getPost('idAtender'),
            'observacion' => $this->request->getPost('observacionAtender'),
            'accion' => $this->request->getPost('accionAtender'),
            'oficina_procedencia_id' => $_usuario->oficina_id,
            'oficina_destino_id' => $_usuario->oficina_id,
            'numero_movimiento' => ($ultimoMovimiento != false) ? $ultimoMovimiento['numero_movimiento'] + 1 : 1,
            'estado' => 'ATENDIDO',
        ];
        $nuevoMovimiento = new MovimientosModel();
        $insertResult = $nuevoMovimiento->insert($set);

        $insertID = $nuevoMovimiento->insertID();
        if ($insertResult !== false) {
            $insertID = $nuevoMovimiento->insertID();

            //guardar archivo
            /**
             * Aqui se debe llamar a la funcion que guardara el archivo adjunto si se adjunta algun archivo o archivos
             */
            // Uso de la función
            $response = $this->handleFileUpload(
                'adjuntoAtender',
                $this->request->getPost('idAtender'),
                $insertID
            );
        } else {
            // Manejar el error de inserción
            // Podrías lanzar una excepción o manejar el error de otra manera adecuada
            // throw new \RuntimeException('Error al insertar el nuevo movimiento');
            $response = 'Error al insertar el nuevo movimiento';
        }

        $response = [
            'title' => 'success',
            'body' => 'Expediente no encontrado',
            'num_mov' => $ultimoMovimiento,
            'idexpediente' => $this->request->getPost('idAtender'),
        ];

        /**
         * Remitir correo de confirmacion de atencion de expediente 
         */

         
        return $this->response->setJSON($response);
    }

    function handleFileUpload($inputName, $expedienteId, $movimientoId = null)
    {

        $_adjunto = new AdjuntoModel();
        $files = $this->request->getFileMultiple($inputName);

        if (empty($files)) {
            $files = [$this->request->getFile($inputName)];
        }

        foreach ($files as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                try {
                    $newName = $file->getRandomName();
                    $drivePath = '-';
                    if (false) {
                        $googleDrive = new GoogleDrive();

                        $folderId = '15WeczEPwYK534xeyX3BOswRsjBLl67G0'; // ID de tu carpeta
                        $fileId = $googleDrive->uploadFile($file->getTempName(), $newName, $folderId);
                        $drivePath = $fileId;
                    }
                    $file->move('uploads', $newName);


                    $localPath = 'uploads/' . $newName;
                    // Obtener el número de orden para el nuevo adjunto
                    $orden = $_adjunto->where('expediente_id', $expedienteId)
                        ->countAllResults() + 1;

                    // Guardar la información en la base de datos
                    $data = [
                        'expediente_id' => $expedienteId,
                        'movimiento_id' => $movimientoId,
                        'local_path' => $localPath,
                        'drive_path' => $drivePath,
                        'orden' => $orden,
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                    $_adjunto->insert($data);
                } catch (\Throwable $th) {
                    return [
                        'status' => 'error',
                        'message' => 'Error al subir el archivo: ' . $th->getMessage(),
                    ];
                }
            }
        }

        return [
            'status' => 'success',
            'message' => 'Archivo(s) subido(s) exitosamente.',
        ];
    }

    public function getExpedienteTodo(): String
    {
        $_userModel = new UsuarioModel();
        $_user = $_userModel->find(auth()->user()->id);
        $idOficina = $_user->oficina_id;
        $expedientesModel = new ExpedientesModel();
        $set = [
            'expediente' => $expedientesModel->getExpedientesTodo(),
        ];
        return view('tramite/listar_expedientes_observacion', $set);
    }
    public function getExpedientePorOficina(): String
    {
        $_userModel = new UsuarioModel();
        $_user = $_userModel->find(auth()->user()->id);
        $idOficina = $_user->oficina_id;
        $expedientesModel = new ExpedientesModel();
        $set = [
            'expediente' => $expedientesModel->getExpedientesOficina($idOficina),
        ];
        return view('tramite/listar_nuevos_expedientes', $set);
    }

    public function getDerivados(): String
    {
        $estado = 'DERIVADO';
        $_userModel = new UsuarioModel();
        $_user = $_userModel->find(auth()->user()->id);
        $idOficina = $_user->oficina_id;
        $expedientesModel = new ExpedientesModel();
        $set = [
            'expediente' => $expedientesModel->getExpedientesDerivados($idOficina,$estado),
        ];

        return view('tramite/listar_nuevos_expedientes', $set);
    }
    public function getObservados(): String
    {
        $estado = 'OBSERVADO';
        $_userModel = new UsuarioModel();
        $_user = $_userModel->find(auth()->user()->id);
        $idOficina = $_user->oficina_id;
        $expedientesModel = new ExpedientesModel();
        $set = [
            'expediente' => $expedientesModel->getExpedientesDerivados($idOficina,$estado),
        ];

        return view('tramite/listar_nuevos_expedientes', $set);
    }

    public function fetch_expedientes()
    {
        $expedienteModel = new ExpedientesModel();

        // Fetch filtered data from the model
        $data = $expedienteModel->getFilteredData(
            $this->request->getPost('start'),
            $this->request->getPost('length'),
            $this->request->getPost('search')['value'],
            $this->request->getPost('order')
        );

        // Total records count before applying filters
        $totalRecords = $expedienteModel->getTotalRecords();

        // Total records count after applying filters
        $totalFiltered = $expedienteModel->getTotalFilteredRecords(
            $this->request->getPost('search')['value']
        );

        $response = [
            'draw' => intval($this->request->getPost('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ];

        return $this->response->setJSON($response);
    }
}

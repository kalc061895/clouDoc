<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\ExpedienteForm;
use App\Models\EntidadModel;
use CodeIgniter\Files\File;
use App\Models\ExpedientesModel;
class ExpedienteController extends BaseController
{
    protected $expedienteModel;
    protected $entidadModel;
    protected $anexoModel;

    public function __construct()
    {
        $this->expedienteModel = new ExpedientesModel();
        $this->entidadModel = new EntidadModel();
        //$this->anexoModel = new AnexoModel();
    }

    public function index()
    {
        //$data['expedientes'] = $this->expedienteModel->findAll();

        return view('external/homepage');
    }
    public function nuevoExpediente()
    {
        //$data['expedientes'] = $this->expedienteModel->findAll();
        $expedienteForm = new ExpedienteForm();
        $set = array(
            'tipoDocumento' => [
                ['id' => 1, 'nombre' => 'DNI'],
                ['id' => 2, 'nombre' => 'RUC'],
                ['id' => 3, 'nombre' => 'Carnet de Extranjería']
            ],
            'tipoExpediente' => [
                ['id' => 1, 'nombre' => 'SOLICITUD'],
                ['id' => 2, 'nombre' => 'INFORME'],
                ['id' => 3, 'nombre' => 'OFICIO'],
                ['id' => 4, 'nombre' => 'OTRO'],
            ],
            'content' => $expedienteForm->render()
        );
        return view('external/formulario_expediente', $set);
    }

    public function buscarExpediente()
    {
        //$data['expedientes'] = $this->expedienteModel->findAll();


        return view('external/busqueda_expediente');
    }



    // resultado de la busqueda de un Expediente
    public function infoExpediente()
    {
        $set = array(
            'expediente' => $this->request->getPost(),
        );
        //$data['expedientes'] = $this->expedienteModel->findAll();
        return view('external/info_expediente', $set);
    }

    public function tupaExpediente()
    {
        //$data['expedientes'] = $this->expedienteModel->findAll();
        return view('external/lista_tupa_expediente');
    }

    public function create()
    {
        return view('external/create');
    }

    public function store()
    {

        $entidadData = [
            'tipo' => $this->request->getPost('tipoNew'),
            'tipo_documento_id' => $this->request->getPost('tipoDocNew'),
            'num_documento' => $this->request->getPost('numDocNew'),
            'nombre' => $this->request->getPost('nombreNew'),
            'telefono' => $this->request->getPost('telefonoNew'),
            'correo_electronico' => $this->request->getPost('correoNew'),
            'direccion' => $this->request->getPost('direccionNew'),
        ];
        $this->entidadModel->save($entidadData);
        $entidadId = $this->entidadModel->insertID();
        
        // Manejo del archivo
        $anexoExp = $this->request->getFile('anexoExp');
        if ($anexoExp->isValid() && !$anexoExp->hasMoved()) {
            $newName = $anexoExp->getRandomName();
            $anexoExp->move(WRITEPATH . 'uploads', $newName);
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Error al subir el archivo.',
            ];
            return $this->response->setJSON($response);
        }

        $expedienteData = [
            'tipo_documento' => $this->request->getPost('tipoDocExp'),
            'numero_documento' => $this->request->getPost('numDocExp'),
            'folio' => $this->request->getPost('folioDocExp'),
            'asunto' => $this->request->getPost('asuntoDocExp'),
            'anexo' => $newName,
            'entidad_id' => $entidadId,
        ];

        $this->expedienteModel->save($expedienteData);
        print_r( $this->expedienteModel->find($this->expedienteModel->insertID()));
        //return json_encode($this->expedienteModel->toArray(), JSON_UNESCAPED_UNICODE);
    }
}

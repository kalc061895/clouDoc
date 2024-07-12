<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\ExpedienteForm;
use App\Models\EntidadModel;
use CodeIgniter\Files\File;
use App\Models\ExpedientesModel;
use App\Models\TipoExpedienteModel;

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
        $tipo_expediente = new TipoExpedienteModel();



        //$data['expedientes'] = $this->expedienteModel->findAll();
        $expedienteForm = new ExpedienteForm();
        $set = array(
            'tipoDocumento' => [
                ['id' => 1, 'nombre' => 'DNI'],
                ['id' => 2, 'nombre' => 'RUC'],
                ['id' => 3, 'nombre' => 'Carnet de Extranjería']
            ],
            'tipoExpediente' => $tipo_expediente->findAll(),
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
        $id = $this->entidadModel->insert($entidadData);
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
            'tipo_expediente_id' => $this->request->getPost('tipoDocExp'),
            'numero_documento' => $this->request->getPost('numDocExp'),
            'numero_expediente' => 'temp',
            'folios' => $this->request->getPost('folioDocExp'),
            'asunto' => $this->request->getPost('asuntoDocExp'),
            'anexo' => $newName,
            'entidad_id' => $entidadId,
        ];

        $this->expedienteModel->save($expedienteData);
        $expedienteArray = $this->expedienteModel->find($this->expedienteModel->insertID());
        $entidadArray = $this->entidadModel->find($expedienteArray['entidad_id']);
        
        //return json_encode($this->expedienteModel->toArray(), JSON_UNESCAPED_UNICODE);
        $set = array(
            'status' => 'success',
            'html' => view(
                'external/resumen_expediente',
                [
                    'entidad' => $entidadArray,
                    'expediente' => $expedienteArray,
                    'documento' => '',
                ]
            ),
        );
        return json_encode($set);
    }
}

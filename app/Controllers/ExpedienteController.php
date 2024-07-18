<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\ExpedienteForm;
use App\Models\EntidadModel;
use CodeIgniter\Files\File;
use App\Models\ExpedientesModel;
use App\Models\TipoExpedienteModel;
use App\Models\TipoDocumentoModel;
use App\Models\AdjuntoModel;
use Dompdf\Dompdf;
use Dompdf\Options;

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
        $tipo_documento = new TipoDocumentoModel();
        $expedienteForm = new ExpedienteForm();

        $set = array(
            'tipoDocumento' => $tipo_documento->findAll(),
            'tipoExpediente' => $tipo_expediente->findAll(),
            'content' => $expedienteForm->render()
        );

        return view('external/formulario_expediente', $set);
    }

    public function buscarExpediente()
    {   
        return view('external/busqueda_expediente');
    }



    // resultado de la busqueda de un Expediente
    public function infoExpediente()
    {
        //inputcode
        //inputanio
        $expedienteArray = $this->expedienteModel->find($this->request->getPost('id'));

        $entidadArray = $this->entidadModel->find($expedienteArray['entidad_id']);

        // Manejo del archivo
        $_adjunto = new AdjuntoModel();

        $adjunto = $_adjunto->where($expedienteArray['entidad_id']);
        
        $_documento = new TipoExpedienteModel();
        $documento = $_documento->find($expedienteArray['tipo_expediente_id']);
        $data = [
            'entidad' => $entidadArray,
            'expediente' => $expedienteArray,
            'documento' => $documento,
            'adjunto' => $adjunto,
        ];

        return view('external/info_expediente', $data);
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



        $expedienteData = [
            'tipo_expediente_id' => $this->request->getPost('tipoDocExp'),
            'numero_documento' => $this->request->getPost('numDocExp'),
            'numero_expediente' => 'temp',
            'folios' => $this->request->getPost('folioDocExp'),
            'asunto' => $this->request->getPost('asuntoDocExp'),
            'anexo' => '',
            'entidad_id' => $entidadId,
        ];

        $this->expedienteModel->save($expedienteData);
        $expedienteArray = $this->expedienteModel->find($this->expedienteModel->insertID());
        $entidadArray = $this->entidadModel->find($expedienteArray['entidad_id']);

        // Manejo del archivo
        $_adjunto = new AdjuntoModel();
        $anexoExp = $this->request->getFile('anexoExp');

        if ($anexoExp->isValid() && !$anexoExp->hasMoved()) {
            $newName = $anexoExp->getRandomName();
            $anexoExp->move(WRITEPATH . 'uploads', $newName);

            $localPath = WRITEPATH . 'uploads/' . $newName;
            // Obtener el número de orden para el nuevo adjunto
            $orden = $_adjunto->where('expediente_id', $expedienteArray['id'])
                ->countAllResults() + 1;

            $drivePath = 'algundirreccion de google';
            // Guardar la información en la base de datos
            $data = [
                'expediente_id' => $expedienteArray['id'],
                'local_path' => $localPath,
                'drive_path' => $drivePath,
                'orden' => $orden,
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $_adjunto->insert($data);
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Error al subir el archivo.',
            ];
            return $this->response->setJSON($response);
        }

        $adjunto = $_adjunto->find($_adjunto->insertID());

        $_documento = new TipoExpedienteModel();
        $documento = $_documento->find($expedienteArray['tipo_expediente_id']);
        $set = array(
            'status' => 'success',
            'html' => view(
                'external/resumen_expediente',
                [
                    'entidad' => $entidadArray,
                    'expediente' => $expedienteArray,
                    'documento' => $documento,
                    'adjunto' => $adjunto,
                ]
            ),
            //'pdf' => $this->generateReceiptPDF($expedienteArray['id'])
        );
        return json_encode($set);
    }

    public function generateReceiptPDF($expedienteId)
    {
        $dompdf = new Dompdf();
        $expediente = $this->expedienteModel->find($expedienteId);
        $dompdf->loadHtml(view('pdf/pdf_template',['expediente'=> $expediente]));
        $dompdf->setPaper('A5', 'portrait');
        $dompdf->render();
        $dompdf->stream('cargo_' . $expedienteId . '.pdf', ['Attachment' => 0]);
    }

    public function sendReceiptEmail($expedienteId)
    {
        // Obtener la información del expediente desde la base de datos
        $expediente = $this->expedienteModel->find($expedienteId);

        // Generar el PDF
        $this->generateReceiptPDF($expedienteId);

        // Configuración del email
        $email = \Config\Services::email();
        $email->setFrom('tuemail@ejemplo.com', 'Tu Nombre');
        $email->setTo($expediente['correo']);
        $email->setSubject('Constancia de Presentación');
        $email->setMessage('Adjunto encontrarás la constancia de presentación de tu documento.');

        // Adjuntar el PDF
        $email->attach('receipts/' . $expedienteId . '.pdf');

        // Enviar el email
        if ($email->send()) {
            return true;
        } else {
            return false;
        }
    }
}

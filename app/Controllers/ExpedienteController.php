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
            $anexoExp->move('uploads', $newName);

            $localPath = 'uploads/' . $newName;
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
        $this->sendReceiptEmail($expedienteArray, $entidadArray);
        return json_encode($set);
    }


    public function generateReceiptPDF($expedienteId)
    {
        $dompdf = new Dompdf();
        $expediente = $this->expedienteModel->find($expedienteId);
        $dompdf->loadHtml(view('pdf/pdf_template', ['expediente' => $expediente]));
        $dompdf->setPaper('A5', 'portrait');
        $dompdf->render();
        $dompdf->stream('cargo_' . $expedienteId . '.pdf', ['Attachment' => 0]);
    }

    public function sendReceiptEmail($_expediente, $_entidad)
    {

        // Generar el PDF
        //$this->generateReceiptPDF($expedienteId);

        // Configuración del email
        $email = \Config\Services::email();
        $email->setFrom('mesadepartes.rssr@gmail.com', 'ClouDoc - Trámite Documentario Virtual');
        $email->setTo($_entidad['correo_electronico']);
        $email->setSubject('Cargo de Trámite Virtual - Exp:' . $_expediente['numero_expediente']);
        $message = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Envío de Expediente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
        }
        .content {
            font-size: 16px;
            line-height: 1.5;
        }
        .content p {
            margin: 10px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #888;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Confirmación de Envío de Expediente</h1>
        </div>
        <div class="content">
            <p>Estimado/a,</p>
            <p>Hemos recibido correctamente su expediente.</p>
            <p><strong>Número de Expediente:</strong> ' . $_expediente['numero_expediente'] . '</p>
            <p>Adjunto encontrará la constancia de presentación de su documento.</p>
            <p>Para seguir el estado de su expediente, haga clic en el siguiente enlace:</p>
            <p><a href="https://example.com/seguimiento?expediente=' . $_expediente['numero_expediente'] . '" class="btn">Seguir Expediente</a></p>
        </div>
        <div class="footer">
            <p>Gracias por utilizar nuestro sistema de trámite virtual.</p>
        </div>
    </div>
</body>
</html>';

        $email->setMessage($message);
        $email->setMailType('html');
        // Adjuntar el PDF
        //$email->attach('receipts/' . $expedienteId . '.pdf');

        // Enviar el email
        if ($email->send()) {
            return true;
        } else {
            return false;
        }
    }
}

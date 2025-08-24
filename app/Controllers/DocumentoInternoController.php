<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\TipoExpedienteModel;
use App\Models\TipoDocumentoModel;
use App\Libraries\ExpedienteForm;
use App\Models\ExpedientesModel;
use App\Libraries\EmailLibrary;
use App\Models\EntidadModel;
use App\Models\AdjuntoModel;
use App\Libraries\GoogleDrive;
use App\Models\EmpresaConfiguracionModel;
use App\Models\OficinaModel;
use App\Models\MovimientosModel;

class DocumentoInternoController extends BaseController
{
    public function index()
    {
        //
    }

    public function getNuevoDocumento()
    {
        $tipo_expediente = new TipoExpedienteModel();
        $tipo_documento = new TipoDocumentoModel();
        $expedienteForm = new ExpedienteForm();

        $userModel = new UsuarioModel();
        $_usuario = $userModel->find(auth()->user()->id);

        $oficinasModel = new OficinaModel();

        $_oficinas = $oficinasModel->findAll();
        $set = array(
            'infoUsuario' => $_usuario,
            'oficina' => $_oficinas,
            'tipoDocumento' => $tipo_documento->findAll(),
            'tipoExpediente' => $tipo_expediente->findAll(),
            'content' => $expedienteForm->render()
        );

        return view('internal/nuevo_documento', $set);
    }
    public function getNuevoDocumentoFisico()
    {
        $tipo_expediente = new TipoExpedienteModel();
        $tipo_documento = new TipoDocumentoModel();
        $expedienteForm = new ExpedienteForm();

        $userModel = new UsuarioModel();
        $_usuario = $userModel->find(auth()->user()->id);

        $oficinasModel = new OficinaModel();

        $_oficinas = $oficinasModel->findAll();
        $set = array(
            'infoUsuario' => $_usuario,
            'oficina' => $_oficinas,
            'tipoDocumento' => $tipo_documento->findAll(),
            'tipoExpediente' => $tipo_expediente->findAll(),
            'content' => $expedienteForm->render()
        );

        return view('internal/nuevo_documento_fisico', $set);
    }

    public function getEmitidos()
    {

        $_userModel = new UsuarioModel();
        $_user = $_userModel->find(auth()->user()->id);
        $idOficina = $_user->oficina_id;
        $expedientesModel = new ExpedientesModel();
        $set = [
            'expediente' => $expedientesModel->getEmitidos($idOficina),
            'observacion' => true

        ];
        return view('tramite/listar_emitidos', $set);
    }
    public function numeracion()
    {
        $userModel = new UsuarioModel();
        $_usuario = $userModel->find(auth()->user()->id);
        // Obtener el ID del tipo de documento desde la solicitud
        $tipoDocExp = $this->request->getPost('tipoDocExp');
        // Instanciar el modelo
        $expedienteModel = new ExpedientesModel();

        // Obtener el número de documento basado en el tipo (esto depende de tu lógica)
        $numeroDocumento = $expedienteModel
            ->where('tipo_expediente_id', $tipoDocExp)
            ->where('procedencia', 'Interno')
            ->where('atencion_oficina_id', $_usuario->oficina_id)
            ->orderBy('numero_documento', 'DESC')->get()->getResultObject();
        // Retornar el número de documento en formato JSON
        return $this->response->setJSON(
            [
                'numDocExp' => ($numeroDocumento != null) ? intval($numeroDocumento[0]->numero_documento) + 1 : 1,
                'tipo' => $tipoDocExp,

            ]
        );
    }

    public function generarformato()
    {
        $userModel = new UsuarioModel();
        $_usuario = $userModel->find(auth()->user()->id);

        $empresaModel = new EmpresaConfiguracionModel();
        $_post = $this->request->getPost();

        $oficinaModel = new OficinaModel();
        $documentoModel = new TipoExpedienteModel();
        $empresaModel = new EmpresaConfiguracionModel();

        $_oficinaRemitente = $oficinaModel->find($_usuario->oficina_id);
        $_oficinaDestino = $oficinaModel->find($_post['oficinaDestino']);
        $_tipoDocumento = $documentoModel->find($_post['tipoDocExp']);

        $referencia = (isset($_post['documentoReferencia'])) ? implode("\n", $_post['documentoReferencia']) : '';
        $cCopia = (isset($_post['oficinaConCopia'])) ? implode("\n", $_post['oficinaConCopia']) : '';
        $setInfo = [
            'NOMBRE_DE_ANIO' => $empresaModel->getConfig('anio_nombre'),
            'LUGAR_EMISION' => 'Juliaca',
            'TIPO_DOCUMENTO' => $_tipoDocumento['nombre'],
            'NUMERO_DOCUMENTO' => $_post['numDocExp'],
            'FECHA_ACTUAL' => date('d') . ' de ' . date('F',) . '' . date('Y'),
            'ANIO' => date('Y'),

            'TITULO_TRATAMIENTO_DESTINATARIO' => $_oficinaDestino['titulo_encargado'],
            'NOMBRE_DESTINATARIO' => $_oficinaDestino['nombres_encargado'],
            'CARGO_DESTINATARIO' => $_oficinaDestino['cargo_encargado'],
            'OFICINA_DESTINATARIO' => $_oficinaDestino['nombre'],
            'DIRECCION_DESTINATARIO' => 'Av. Huancane km2',
            'LUGAR_DESTINATARIO' => 'Juliaca',

            'ASUNTO' => $_post['asuntoDocExp'],
            'REFERENCIA' => $referencia,
            'CCOPIA' => $cCopia,
            'CUERPO_DOCUMENTO' => 'Ingrese aqui el cuerpo ...',

            'TITULO_TRATAMIENTO_EMISARIO' => $_oficinaRemitente['titulo_encargado'],
            'NOMBRE_EMISARIO' => $_oficinaRemitente['nombres_encargado'],
            'OFICINA_SIGLAS' => $_oficinaRemitente['codigo_referencia'],
            'CARGO_EMISARIO' => $_oficinaRemitente['cargo_encargado'],
            'OFICINA_EMISARIO' => $_oficinaRemitente['nombre'],
            'CODIGO_DOCUMENTO' => '',
            'DIRECCION_EMISARIO' => 'Av. Huancane 224',
            'LUGAR_EMISARIO' => 'Juliaca',
            'TELEFONO_EMISARIO' => '',
            'CORREO_EMISARIO' => '',
        ];

        $insert = (object)[
            'key' => 'info_docTemp',
            'value' => $this->request->getPost('formData'),
        ];
        $insert = (object)[
            'key' => 'info_docTemp',
            'value' => json_encode($setInfo),
        ];

        $_tempData = $empresaModel->insert($insert);

        return $this->response->setJSON(
            ['id' => $empresaModel->insertID()]
        );
    }


    public function store()
    {

        $entidadModel = new EntidadModel();
        $expedienteModel = new ExpedientesModel();
        $drivePath = '-';

        $entidadData = [
            'tipo' => 'Persona',
            'tipo_documento_id' => $this->request->getPost('tipoDocNew'),
            'num_documento' => $this->request->getPost('numDocNew'),
            'nombre' => $this->request->getPost('nombreNew'),
            'telefono' => $this->request->getPost('telefonoNew'),
            'correo_electronico' => $this->request->getPost('correoNew'),
            'direccion' => $this->request->getPost('direccionNew'),
        ];


        $entidadExistente = $entidadModel->where($entidadData)->first();

        if ($entidadExistente) {
            // Si ya existe, obtener la ID
            $entidadId = $entidadExistente['id'];
        } else {
            // Si no existe, insertar el nuevo registro
            $id = $entidadModel->insert($entidadData);
            $entidadId = $entidadModel->insertID();
        }
        $userModel = new UsuarioModel();
        $_usuario = $userModel->find(auth()->user()->id);
        $expedienteData = [
            'procedencia' => 'Interno',
            'tipo_expediente_id' => $this->request->getPost('tipoDocExp'),
            'numero_documento' => $this->request->getPost('numDocExp'),
            //'numero_expediente' => 'temp',
            'folios' => $this->request->getPost('folioDocExp'),
            'asunto' => $this->request->getPost('asuntoDocExp'),
            'entidad_id' => $entidadId,
            'atencion_oficina_id' => $_usuario->oficina_id,
        ];

        $expedienteModel->save($expedienteData);
        $expedienteArray = $expedienteModel->find($expedienteModel->insertID());
        $entidadArray = $entidadModel->find($expedienteArray['entidad_id']);

        // Manejo del archivo
        $_adjunto = new AdjuntoModel();
        $anexoExp = $this->request->getFile('anexoExp');
        $adjunto_id ='';

        if ($anexoExp->isValid() && !$anexoExp->hasMoved()) {
            $newName = $anexoExp->getRandomName();

            /**
             * Subir al google drive acorde a la configuracion
             */
            $_driveModel = new EmpresaConfiguracionModel();
            if ($_driveModel->getDriveConfig()) {
                $googleDrive = new GoogleDrive();

                $folderId = $_driveModel->getConfig('google_drive_folder'); // ID de tu carpeta
                $fileId = $googleDrive->uploadFile($anexoExp->getTempName(), $newName, $folderId);
                $drivePath = $fileId;
            }

            $anexoExp->move('uploads', $newName);

            $localPath = 'uploads/' . $newName;
            // Obtener el número de orden para el nuevo adjunto
            $orden = $_adjunto->where('expediente_id', $expedienteArray['id'])
                ->countAllResults() + 1;

            // Guardar la información en la base de datos
            $data = [
                'expediente_id' => $expedienteArray['id'],
                'local_path' => $localPath,
                'drive_path' => $drivePath,
                'orden' => $orden,
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $adjunto_id = $_adjunto->insert($data);

            $_referencia = $this->request->getPost('documentoReferencia');
            $_concopia = $this->request->getPost('oficinaConCopia');
            $setMovimiento = [
                'expediente_id' => $expedienteArray['id'],
                'observacion' => '',
                'accion' => 2,
                'oficina_procedencia_id' => $_usuario->oficina_id,
                'oficina_destino_id' => $this->request->getPost('oficinaDestino'),
                'numero_movimiento' => 1,
                'estado' => 'ESPERA',
                'referencia' => ($_referencia == '') ? '' : implode(',', $_referencia),
                'concopia' => ($_concopia == '') ? '' : implode(',', $_concopia),
            ];

            // aqui guardamos las referencias
            // y las con copia
            //
            $nuevoMovimiento = new MovimientosModel();
            $insertResult = $nuevoMovimiento->insert($setMovimiento);
            $response = $this->handleFileUpload(
                'adjuntoDerivar',
                $expedienteArray['id'],
                $nuevoMovimiento->insertID(),
            );
        } else {
            $_expediente  = new ExpedientesModel();
            $_expediente->delete($expedienteArray['id'], true);
            $response = [
                'status' => 'error',
                'message' => 'Error al subir el archivo.',
            ];
            return $response;
        }


        //$adjunto = $_adjunto->find($_adjunto->insertID()); // obtine el valor de 0 - por tano un retorno de null
        $adjunto = $_adjunto->find($adjunto_id);

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

        $email = new EmailLibrary();
        $email->sendReceiptEmail($expedienteArray, $entidadArray);
        return json_encode($set);
    }


    public function nuevoDocumentoDesdePlantilla()
    {
        // Instanciar la biblioteca GoogleDrive
        $googleDrive = new GoogleDrive();

        // Datos para el nuevo documento
        $templateFileId = '1ncPrlfNZC_mjcsl3AF7GDztMKsYNpWXQ'; // Reemplaza con el ID de tu plantilla

        $documentName = 'Nuevo Documento Generado';
        $replacements = [
            '[NOMBRE_DE_ANIO]' => 'Año del Bicentenario de la consolidacion de nuestra independencia y de la consolidación de las heroícas batallas de Junín y Ayacucho',
            '[NUMERO_CARTA]' => '001',
            '[ANIO]' => '2024',
            '[OFICINA_REMITENTE]' => 'Oficina Principal',
            '[AREA_REMITENTE]' => 'Área de Gestión',
            '[NOMBRE_REMITENTE]' => 'Juan Pérez',
            '[CARGO_REMITENTE]' => 'Director General',
            '[PERSONA_DESTINO]' => 'María López',
            '[OFICINA_DESTINO]' => 'Oficina de Recursos Humanos'
        ];

        // Crear el nuevo documento a partir de la plantilla
        try {
            $newDocumentId = $googleDrive->createDocumentFromTemplate($templateFileId, $documentName, $replacements);
            return "Nuevo documento creado con ID: $newDocumentId";
        } catch (\Exception $e) {
            return "Error al crear el documento: " . $e->getMessage();
        }
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
                    $_driveModel = new EmpresaConfiguracionModel();
                    if ($_driveModel->getDriveConfig()) {
                        $googleDrive = new GoogleDrive();

                        $folderId = $_driveModel->getConfig('google_drive_fodler'); // ID de tu carpeta
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

    public function referencia()
    {

        $expedienteModel =  new ExpedientesModel();

        $items = [
            ['id' => 1, 'text' => 'Opción 1'],
            ['id' => 2, 'text' => 'Opción 2'],
            ['id' => 3, 'text' => 'Opción 3'],
            ['id' => 4, 'text' => 'Opción 4'],
            ['id' => 5, 'text' => 'Opción 5']
        ];
        $set = [
            'term' => 'q',
            'items' => $expedienteModel->getExpedienteSelect($this->request->getGET('q'), false),
        ];
        return $this->response->setJSON($set);
    }
}

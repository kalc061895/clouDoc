<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\ExpedienteForm;
//use App\Models\ExpedienteModel;
//use App\Models\AnexoModel;
class ExpedienteController extends BaseController
{
    protected $expedienteModel;
    protected $anexoModel;

    public function __construct()
    {
        //$this->expedienteModel = new ExpedienteModel();
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
            'tipoDocumento'=> [
                ['id'=>1,'nombre'=>'DNI'],
                ['id'=>2,'nombre'=>'RUC'],
                ['id'=>3,'nombre'=>'Carnet de Extranjería']
            ],
            'tipoExpediente'=> [
                ['id'=>1,'nombre'=>'SOLICITUD'],
                ['id'=>2,'nombre'=>'INFORME'],
                ['id'=>3,'nombre'=>'OFICIO'],
                ['id'=>4,'nombre'=>'OTRO'],
            ],
            'content'=>$expedienteForm->render()
        );
        return view('external/formulario_expediente', $set);
    }
    
    public function buscarExpediente()
    {
        //$data['expedientes'] = $this->expedienteModel->findAll();
        
        
        return view('external/busqueda_expediente');
    }

    public function nuevoTramite()
    {
        helper(['form', 'url']);
        
        $validation = \Config\Services::validation();

        $input = $this->validate([
            'tipoNew' => 'required',
            'tipoDocNew' => 'required',
            'numDocNew' => 'required',
            'input1' => 'required',
            'telefonoNew' => 'required',
            'correoNew' => 'required|valid_email',
            'direccionNew' => 'required',
            'tipoDocExp' => 'required',
            'numDocExp' => 'required',
            'folioDocExp' => 'required',
            'asuntoDocExp' => 'required',
            'anexoExp' => [
                'uploaded[anexoExp]',
                'mime_in[anexoExp,image/jpg,image/jpeg,image/png,application/pdf]',
                'max_size[anexoExp,4096]',
            ],
        ]);

        if (!$input) {
            return view('external_form', [
                'validation' => $this->validator,
            ]);
        } else {
            // Subir el archivo
            $file = $this->request->getFile('anexoExp');
            if ($file->isValid() && !$file->hasMoved()) {
                $filePath = $this->uploadToGoogleDrive($file);
                if (!$filePath) {
                    // Si no hay conexión, guardar localmente
                    $file->move(WRITEPATH . 'uploads');
                    $filePath = WRITEPATH . 'uploads/' . $file->getName();
                }
            }
            // Aquí puedes guardar los datos en la base de datos
        }
    }

    private function uploadToGoogleDrive($file)
    {
        try {
            $client = new Client();
            $client->setAuthConfig(APPPATH . 'Config/credentials.json');
            $client->addScope(Drive::DRIVE_FILE);

            $service = new Drive($client);

            $fileMetadata = new DriveFile([
                'name' => $file->getName()
            ]);

            $content = file_get_contents($file->getTempName());

            $driveFile = $service->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => $file->getMimeType(),
                'uploadType' => 'multipart',
                'fields' => 'id',
            ]);

            return 'https://drive.google.com/uc?export=view&id=' . $driveFile->id;
        } catch (\Exception $e) {
            // Manejar errores de conexión
            return false;
        }
    }
    public function infoExpediente()
    {
        $set = array(
            'expediente'=>$this->request->getPost(),
        );
        //$data['expedientes'] = $this->expedienteModel->findAll();
        return view('external/info_expediente',$set);
    }

    public function tupaExpediente()
    {
        //$data['expedientes'] = $this->expedienteModel->findAll();
        return view('external/lista_tupa_expediente');
    }

    public function create()
    {
        return view('expedientes/create');
    }

    public function store()
    {
        $data = [
            'numero_expediente' => $this->request->getPost('numero_expediente'),
            'procedencia' => $this->request->getPost('procedencia'),
            'fecha_recepcion' => $this->request->getPost('fecha_recepcion'),
            'hora_recepcion' => $this->request->getPost('hora_recepcion'),
            'folios' => $this->request->getPost('folios'),
            'tipo_documento' => $this->request->getPost('tipo_documento'),
            'numero_documento' => $this->request->getPost('numero_documento'),
            'entidad_id' => $this->request->getPost('entidad_id'),
            'asunto' => $this->request->getPost('asunto'),
            'descripcion' => $this->request->getPost('descripcion'),
            'atencion_oficina_id' => $this->request->getPost('atencion_oficina_id'),
            'observacion' => $this->request->getPost('observacion')
        ];

        $this->expedienteModel->save($data);
        return redirect()->to('/expedientes');
    }

    public function show($id)
    {
        $data['expediente'] = $this->expedienteModel->find($id);
        $data['anexos'] = $this->anexoModel->where('expediente_id', $id)->findAll();
        return view('expedientes/show', $data);
    }

    public function edit($id)
    {
        $data['expediente'] = $this->expedienteModel->find($id);
        return view('expedientes/edit', $data);
    }

    public function update($id)
    {
        $data = [
            'id' => $id,
            'numero_expediente' => $this->request->getPost('numero_expediente'),
            'procedencia' => $this->request->getPost('procedencia'),
            'fecha_recepcion' => $this->request->getPost('fecha_recepcion'),
            'hora_recepcion' => $this->request->getPost('hora_recepcion'),
            'folios' => $this->request->getPost('folios'),
            'tipo_documento' => $this->request->getPost('tipo_documento'),
            'numero_documento' => $this->request->getPost('numero_documento'),
            'entidad_id' => $this->request->getPost('entidad_id'),
            'asunto' => $this->request->getPost('asunto'),
            'descripcion' => $this->request->getPost('descripcion'),
            'atencion_oficina_id' => $this->request->getPost('atencion_oficina_id'),
            'observacion' => $this->request->getPost('observacion')
        ];

        $this->expedienteModel->save($data);
        return redirect()->to('/expedientes');
    }

    public function delete($id)
    {
        $this->expedienteModel->delete($id);
        return redirect()->to('/expedientes');
    }

    public function addAnexo($expediente_id)
    {
        $data = [
            'expediente_id' => $expediente_id,
            'nombre_anexo' => $this->request->getPost('nombre_anexo'),
            'archivo' => $this->request->getFile('archivo')->store()
        ];

        $this->anexoModel->save($data);
        return redirect()->to('/expedientes/' . $expediente_id);
    }
}

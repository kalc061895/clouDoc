<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
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

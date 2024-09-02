<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\TipoExpedienteModel;
use App\Models\TipoDocumentoModel;
use App\Libraries\ExpedienteForm;

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

        $set = array(
            'infoUsuario' => $_usuario,
            'tipoDocumento' => $tipo_documento->findAll(),
            'tipoExpediente' => $tipo_expediente->findAll(),
            'content' => $expedienteForm->render()
        );

        return view('internal/nuevo_documento', $set);
    }


}

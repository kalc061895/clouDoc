<?php

namespace App\Controllers\Contratacion;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class AnexoController extends BaseController
{
    public function index()
    {
        //
    }

    public function subir()
    {
        $file = $this->request->getFile('archivo');

        if (!$file->isValid()) {
            return $this->response->setJSON(['status' => 'error']);
        }

        $newName = $file->getRandomName();
        $file->move(WRITEPATH . 'uploads/anexos', $newName);

        // Guardar en BD
        // id_postulacion, tipo, archivo, estado

        return $this->response->setJSON(['status' => 'ok']);
    }
}

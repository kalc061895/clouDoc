<?php

namespace App\Controllers\Contratacion;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Contratacion\ExperienciaModel;
use App\Models\Contratacion\AnexoModel;
use App\Services\Contratacion\ExperienciaService;

class ExperienciaController extends BaseController
{
    protected $experienciaService;

    public function __construct()
    {
        $this->experienciaService = new ExperienciaService();
    }

    // 📄 Listar experiencias del postulante
    public function listar()
    {
        return $this->response->setJSON(
            $this->experienciaService->listar()
        );
    }

    // 💾 Guardar experiencia
    public function guardar()
    {

        return $this->response->setJSON(
            $this->experienciaService->guardar(
                auth()->id(),
                $this->request
            )

        );
    }

    // 🗑 Eliminar experiencia
    public function eliminar($id)
    {
        return $this->response->setJSON(
            $this->experienciaService->eliminar(
                $id
            )
        );
    }
}

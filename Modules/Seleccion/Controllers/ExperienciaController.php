<?php

namespace Modules\Seleccion\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Seleccion\Models\ExperienciaModel;
use Modules\Seleccion\Models\AnexoModel;
use Modules\Seleccion\Services\ExperienciaService;

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

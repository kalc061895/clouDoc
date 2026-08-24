<?php

namespace Modules\Seleccion\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Seleccion\Services\AnexoService;

class AnexoController extends BaseController
{
    protected AnexoService $service;

    public function __construct()
    {
        $this->service = new AnexoService();
    }

    /**
     * Listar anexos de una convocatoria en formato JSON.
     * GET /seleccion/admin/anexos/listar/{convocatoriaId}
     */
    public function listar(int $convocatoriaId): ResponseInterface
    {
        $data = $this->service->obtenerPorConvocatoria($convocatoriaId);
        return $this->response->setJSON([
            'ok' => true,
            'data' => $data
        ]);
    }

    /**
     * Guardar o actualizar anexo de convocatoria.
     * POST /seleccion/admin/anexos/guardar
     */
    public function guardar(): ResponseInterface
    {
        $postData = $this->request->getPost();

        // Inyectar auditoría si se cuenta con sesión
        $postData['created_by'] = session()->get('user_id') ?? null;
        $postData['updated_by'] = session()->get('user_id') ?? null;

        $res = $this->service->guardar($postData);

        return $this->response->setStatusCode($res['code'])->setJSON($res);
    }

    /**
     * Eliminar registro de anexo.
     * POST /seleccion/admin/anexos/eliminar/{id}
     */
    public function eliminar(int $id): ResponseInterface
    {
        $res = $this->service->eliminar($id);
        return $this->response->setStatusCode($res['code'])->setJSON($res);
    }
}

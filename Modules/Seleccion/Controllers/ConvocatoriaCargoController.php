<?php

namespace Modules\Seleccion\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Seleccion\Services\ConvocatoriaCargoService;

class ConvocatoriaCargoController extends BaseController
{
    protected ConvocatoriaCargoService $service;

    public function __construct()
    {
        $this->service = new ConvocatoriaCargoService();
    }

    /**
     * Listar cargos de una convocatoria en formato JSON.
     * GET /seleccion/admin/cargos/listar/{convocatoriaId}
     */
    public function listar(int $convocatoriaId): ResponseInterface
    {
        $data = $this->service->obtenerPorConvocatoria($convocatoriaId);
        return $this->response->setJSON([
            'ok'   => true,
            'data' => $data
        ]);
    }

    /**
     * Guardar o actualizar plaza de convocatoria.
     * POST /seleccion/admin/cargos/guardar
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
     * Eliminar registro de plaza.
     * POST /seleccion/admin/cargos/eliminar/{id}
     */
    public function eliminar(int $id): ResponseInterface
    {
        $res = $this->service->eliminar($id);
        return $this->response->setStatusCode($res['code'])->setJSON($res);
    }
}

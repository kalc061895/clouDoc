<?php

namespace Modules\Seleccion\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Seleccion\Services\ConvocatoriaEtapaService;

class ConvocatoriaEtapaController extends BaseController
{
    protected ConvocatoriaEtapaService $service;

    public function __construct()
    {
        $this->service = new ConvocatoriaEtapaService();
    }

    public function listar(int $convocatoriaId): ResponseInterface
    {
        return $this->response->setJSON([
            'ok'   => true,
            'data' => $this->service->obtenerPorConvocatoria($convocatoriaId)
        ]);
    }

    public function guardar(): ResponseInterface
    {
        $postData = $this->request->getPost();
        $postData['created_by'] = session()->get('user_id') ?? null;
        $postData['updated_by'] = session()->get('user_id') ?? null;

        $res = $this->service->guardar($postData);
        return $this->response->setStatusCode($res['code'])->setJSON($res);
    }

    public function eliminar(int $id): ResponseInterface
    {
        $res = $this->service->eliminar($id);
        return $this->response->setStatusCode($res['code'])->setJSON($res);
    }
}

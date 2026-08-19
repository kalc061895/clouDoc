<?php

namespace Modules\Seleccion\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Seleccion\Services\ConvocatoriaDocumentoService;

class ConvocatoriaDocumentoController extends BaseController
{
    protected ConvocatoriaDocumentoService $service;

    public function __construct()
    {
        $this->service = new ConvocatoriaDocumentoService();
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
        $file = $this->request->getFile('archivo_adjunto');

        $postData['created_by'] = session()->get('user_id') ?? null;
        $postData['updated_by'] = session()->get('user_id') ?? null;

        $res = $this->service->guardar($postData, $file);
        return $this->response->setStatusCode($res['code'])->setJSON($res);
    }

    public function eliminar(int $id): ResponseInterface
    {
        $res = $this->service->eliminar($id);
        return $this->response->setStatusCode($res['code'])->setJSON($res);
    }
}

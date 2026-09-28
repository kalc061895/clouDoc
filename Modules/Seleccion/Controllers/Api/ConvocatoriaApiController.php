<?php

namespace Modules\Seleccion\Controllers\Api;

use App\Controllers\BaseController;
use Modules\Seleccion\Services\ConvocatoriaWorkflowService;

class ConvocatoriaApiController extends BaseController
{
    public function __construct(private readonly ConvocatoriaWorkflowService $service = new ConvocatoriaWorkflowService())
    {
    }
    public function index()
    {
        return $this->respond(['ok' => true, 'code' => 200, 'data' => $this->service->listar()], 'Convocatorias obtenidas correctamente');
    }
    public function show(int $id)
    {
        $data = $this->service->obtener($id);
        return $data ? $this->respond(['ok' => true, 'code' => 200, 'data' => $data], 'Convocatoria obtenida correctamente') : $this->respond(['ok' => false, 'code' => 404], 'Convocatoria no encontrada');
    }
    public function create()
    {
        return $this->result($this->service->guardar($this->payload()), 'Convocatoria creada correctamente');
    }
    public function update(int $id)
    {
        return $this->result($this->service->guardar($this->payload(), $id), 'Convocatoria actualizada correctamente');
    }
    public function publish(int $id)
    {
        return $this->result($this->service->publicar($id), 'Convocatoria publicada correctamente');
    }
    private function payload(): array
    {
        $json = $this->request->getJSON(true);
        return is_array($json) ? $json : $this->request->getRawInput();
    }
    private function result(array $result, string $message)
    {
        return $this->respond($result, $result['ok'] ? $message : ($result['message'] ?? ($result['code'] === 404 ? 'Convocatoria no encontrada' : 'No se pudo procesar la solicitud')));
    }
    private function respond(array $result, string $message)
    {
        $body = ['status' => $result['ok'], 'code' => $result['code'], 'message' => $message, 'data' => $result['data'] ?? null];
        if (isset($result['errors']))
            $body['errors'] = $result['errors'];
        return $this->response->setStatusCode($result['code'])->setJSON($body);
    }
}

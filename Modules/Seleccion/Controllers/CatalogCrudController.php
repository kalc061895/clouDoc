<?php

namespace Modules\Seleccion\Controllers;

use App\Controllers\BaseController;
use Modules\Seleccion\Services\CatalogCrudService;

abstract class CatalogCrudController extends BaseController
{
    protected CatalogCrudService $service;
    public function index()
    {
        return $this->json($this->service->index($this->request->getGet()), 'Registros obtenidos correctamente');
    }
    public function show(int $id)
    {
        return $this->json($this->service->find($id), 'Registro obtenido correctamente');
    }
    public function create()
    {
        return $this->json($this->service->create($this->payload()), 'Registro creado correctamente');
    }
    public function update(int $id)
    {
        return $this->json($this->service->update($id, $this->payload()), 'Registro actualizado correctamente');
    }
    public function delete(int $id)
    {
        return $this->json($this->service->delete($id), 'Registro eliminado correctamente');
    }
    private function payload(): array
    {
        $json = $this->request->getJSON(true);
        return is_array($json) ? $json : $this->request->getRawInput();
    }
    private function json(array $result, string $successMessage)
    {
        $body = ['status' => $result['ok'], 'code' => $result['code'], 'message' => $result['ok'] ? $successMessage : $result['message']];
        if ($result['ok'] || $result['code'] === 404)
            $body['data'] = $result['data'] ?? null;
        if (isset($result['meta']))
            $body['meta'] = $result['meta'];
        if (isset($result['errors']))
            $body['errors'] = $result['errors'];
        return $this->response->setStatusCode($result['code'])->setJSON($body);
    }
}

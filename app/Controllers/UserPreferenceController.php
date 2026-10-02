<?php

namespace App\Controllers;

use App\Services\UserPreferenceService;

class UserPreferenceController extends BaseController
{
    public function index()
    {
        return $this->procesar(false);
    }

    public function guardar()
    {
        return $this->procesar(true);
    }

    private function procesar(bool $guardar)
    {
        $this->response->setHeader('Cache-Control', 'private, no-store');
        if (!auth()->loggedIn()) return $this->response->setStatusCode(401)->setJSON(['message' => 'Inicia sesión para guardar tu apariencia.']);
        try {
            $service = new UserPreferenceService();
            $userId = (int) auth()->id();
            if ($guardar) {
                $input = $this->request->getJSON(true);
                if (!is_array($input)) throw new \InvalidArgumentException('Configuración inválida.');
                $settings = $service->guardar($userId, $input);
            } else {
                $settings = $service->obtener($userId);
            }
            return $this->response->setJSON(['settings' => $settings, 'csrf' => ['header' => csrf_header(), 'name' => csrf_token(), 'hash' => csrf_hash()]]);
        } catch (\InvalidArgumentException $e) {
            return $this->response->setStatusCode(422)->setJSON(['message' => $e->getMessage(), 'csrf' => ['header' => csrf_header(), 'name' => csrf_token(), 'hash' => csrf_hash()]]);
        } catch (\Throwable $e) {
            log_message('error', 'Preferencias de usuario: {message}', ['message' => $e->getMessage()]);
            return $this->response->setStatusCode(503)->setJSON(['message' => 'No se pudo guardar o cargar tu apariencia. Inténtalo nuevamente.', 'csrf' => ['header' => csrf_header(), 'name' => csrf_token(), 'hash' => csrf_hash()]]);
        }
    }
}

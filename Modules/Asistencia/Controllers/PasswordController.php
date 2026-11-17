<?php

declare(strict_types=1);

namespace Modules\Asistencia\Controllers;

use App\Controllers\BaseController;
use App\Exceptions\PasswordChangeException;
use App\Services\PasswordChangeService;
use CodeIgniter\HTTP\ResponseInterface;

class PasswordController extends BaseController
{
    public function token(): ResponseInterface
    {
        return auth()->loggedIn()
            ? $this->respond(200, true, '')
            : $this->respond(401, false, 'Su sesión terminó. Vuelva a iniciar sesión.');
    }

    public function guardar(): ResponseInterface
    {
        if (! auth()->loggedIn()) {
            return $this->respond(401, false, 'Su sesión terminó. Vuelva a iniciar sesión.');
        }
        $actorId = (int) auth()->id();
        if (! service('throttler')->check('password-change-' . $actorId, 5, MINUTE)) {
            return $this->respond(429, false, 'Demasiados intentos. Espere un minuto para volver a intentar.');
        }
        $data = $this->request->getPost(['current_password', 'password', 'password_confirm']);
        $rules = array_fill_keys(['current_password', 'password', 'password_confirm'], 'required|string|max_length[255]');
        if (! $this->validateData($data, $rules)) {
            return $this->respond(422, false, 'Revise los campos del formulario.', $this->validator->getErrors());
        }
        try {
            $this->passwordService()->cambiar($actorId, $data['current_password'], $data['password'], $data['password_confirm']);
            return $this->respond(200, true, 'Su contraseña se actualizó correctamente.');
        } catch (PasswordChangeException $e) {
            return $this->respond(422, false, $e->getMessage(), $e->errors);
        } catch (\Throwable $e) {
            // Do not log request bodies, passwords or exception arguments.
            log_message('error', 'Cambio de contraseña fallido para usuario {id}; tipo {type}.', ['id' => $actorId, 'type' => $e::class]);
            return $this->respond(500, false, 'No se pudo cambiar la contraseña. Intente nuevamente.');
        }
    }

    protected function passwordService(): PasswordChangeService
    {
        return new PasswordChangeService();
    }

    private function respond(int $status, bool $success, string $message, array $errors = []): ResponseInterface
    {
        return $this->response->setHeader('Cache-Control', 'private, no-store')->setStatusCode($status)->setJSON([
            'success' => $success, 'message' => $message, 'data' => null, 'errors' => (object) $errors,
            'meta' => ['csrf' => ['header' => csrf_header(), 'name' => csrf_token(), 'hash' => csrf_hash()]],
        ]);
    }
}

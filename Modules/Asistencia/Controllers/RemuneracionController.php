<?php

namespace Modules\Asistencia\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use Modules\Asistencia\Models\CargoModel;
use Modules\Asistencia\Services\RemuneracionService;

class RemuneracionController extends BaseController
{
    use ResponseTrait;

    private function acceso()
    {
        if (!auth()->loggedIn()) return $this->failUnauthorized('Debe iniciar sesión.');
        if (!auth()->user()->inGroup('superadmin', 'asistencia', 'asi_sua')) return $this->failForbidden('No tiene permiso para administrar remuneraciones.');
        return null;
    }

    public function index()
    {
        if ($error = $this->acceso()) return $error;
        return view('Modules\Asistencia\Views\database\remuneraciones_view', [
            'cargos' => (new CargoModel())->orderBy('car_nombre')->findAll(),
            'campos' => RemuneracionService::CAMPOS,
        ]);
    }

    public function listar()
    {
        if ($error = $this->acceso()) return $error;
        return $this->respond(['status' => 'success', 'data' => (new RemuneracionService())->listar()]);
    }

    public function guardar($id = null)
    {
        return $this->mutar(function () use ($id) {
            $datos = $this->request->getJSON(true);
            if (!is_array($datos)) throw new \DomainException('Datos no válidos.');
            (new RemuneracionService())->guardar($datos, (int)auth()->id(), $id === null ? null : (int)$id);
        }, 'Remuneración guardada correctamente.', $id === null ? 201 : 200);
    }

    public function eliminar($id)
    {
        return $this->mutar(fn() => (new RemuneracionService())->eliminar((int)$id), 'Remuneración eliminada.');
    }

    private function mutar(callable $accion, string $mensaje, int $status = 200)
    {
        $this->response->setHeader(csrf_header(), csrf_hash());
        if ($error = $this->acceso()) return $error;
        try {
            $accion();
            return $this->respond(['status' => 'success', 'message' => $mensaje], $status);
        } catch (\DomainException $e) {
            return $this->fail($e->getMessage(), in_array($e->getCode(), [404, 409], true) ? $e->getCode() : 422);
        } catch (\Throwable $e) {
            log_message('error', 'CRUD remuneraciones: {message}', ['message' => $e->getMessage()]);
            return $this->failServerError('No se pudo completar la operación. Inténtelo nuevamente.');
        }
    }
}

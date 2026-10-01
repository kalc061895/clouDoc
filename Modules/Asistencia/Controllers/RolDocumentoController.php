<?php

namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Asistencia\Services\RolDocumentoData;
use Modules\Asistencia\Services\RolDocumentoService;

class RolDocumentoController extends BaseModuleController
{
    protected function jsonResponse(string $status, string $message, $data = [], int $httpCode = 200): \CodeIgniter\HTTP\ResponseInterface
    {
        $data['csrf'] = ['name' => csrf_token(), 'hash' => csrf_hash()];
        return parent::jsonResponse($status, $message, $data, $httpCode);
    }

    public function index()
    {
        return view('Modules\Asistencia\Views\roles\index', ['catalogos' => (new RolDocumentoService())->catalogos()]);
    }

    public function historial()
    {
        return view('Modules\Asistencia\Views\roles\historial', ['catalogos' => (new RolDocumentoService())->catalogos()]);
    }

    public function firmar()
    {
        return redirect()->to(base_url('firma'));
    }

    public function consultar()
    {
        try {
            $doc = (new RolDocumentoService())->consultar($this->request->getGet());
            return $this->jsonResponse('success', 'Consulta completada.', [
                'html' => view('Modules\Asistencia\Views\roles\tabla', ['documento' => $doc]),
                'cabecera' => $doc['cabecera'],
                'personal' => count($doc['personal']),
                'horas' => $doc['total_horas'],
                'huella' => RolDocumentoData::huella($doc),
            ]);
        } catch (\Throwable $e) {
            return $this->error($e);
        }
    }

    public function generar()
    {
        try {
            $rol = (new RolDocumentoService())->generar($this->request->getPost(), $this->usuario());
            $rol['url'] = base_url('asistencia/roles/' . $rol['id'] . '/pdf');
            return $this->jsonResponse('success', 'PDF generado y guardado en el historial.', $rol, 201);
        } catch (\Throwable $e) {
            return $this->error($e);
        }
    }

    public function listar()
    {
        try {
            return $this->jsonResponse('success', 'Historial de roles.', (new RolDocumentoService())->historial($this->request->getGet()));
        } catch (\Throwable $e) {
            return $this->error($e);
        }
    }

    public function pdf(int $id)
    {
        try {
            $archivo = (new RolDocumentoService())->archivo($id);


            return $this->response
            ->setContentType('application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $archivo['nombre'] . '"')
            ->setHeader('Cache-Control', 'private, no-store')
            ->setHeader('X-Rol-Estado', $archivo['estado'])
            ->setBody(file_get_contents($archivo['ruta']));

               /**
                * 
                return $this->response
                ->download($archivo['ruta'], null)
                ->setFileName($archivo['nombre'])
                ->inline()
                ->setHeader('Cache-Control', 'private, no-store')
                ->setHeader('X-Rol-Estado', $archivo['estado']);

                */
              

        } catch (\Throwable $e) {
            return $this->error($e);
        }
    }

    public function anular(int $id)
    {
        try {
            $motivo = $this->request->getPost('motivo');
            if (! is_string($motivo)) throw new \InvalidArgumentException('Ingrese un motivo de anulación.');
            (new RolDocumentoService())->anular($id, $motivo, $this->usuario());
            return $this->jsonResponse('success', 'Rol anulado. El archivo original se conserva en el historial.');
        } catch (\Throwable $e) {
            return $this->error($e);
        }
    }

    private function usuario(): int
    {
        $id = function_exists('auth') && auth()->loggedIn() ? auth()->id() : (session()->get('user_id') ?? session()->get('usu_ide'));
        if (! $id || (int) $id < 1) throw new \UnexpectedValueException('La sesión de usuario no está disponible. Inicie sesión nuevamente.');
        return (int) $id;
    }

    private function error(\Throwable $e)
    {
        $status = $e instanceof \InvalidArgumentException ? 422 : ($e instanceof \DomainException ? 409 : ($e instanceof \OutOfBoundsException ? 404 : ($e instanceof \UnexpectedValueException ? 401 : 500)));
        if ($status === 500) log_message('error', 'Roles PDF: {message}', ['message' => $e->getMessage()]);
        return $this->jsonResponse('error', $status === 500 ? 'No se pudo completar la operación. Revise la migración de roles y el registro del servidor.' : $e->getMessage(), [], $status);
    }
}

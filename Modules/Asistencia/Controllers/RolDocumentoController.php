<?php

namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Asistencia\Services\RolEnvioService;
use Modules\Asistencia\Services\GestionRolService;
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
        return redirect()->to(site_url('asistencia/gestion-roles/envios'));
    }

    public function historial()
    {
        return redirect()->to(site_url('asistencia/gestion-roles/envios'));
    }

    public function firmar()
    {
        return redirect()->to(base_url('firma'));
    }

    /** Compatibilidad para clientes antiguos: nunca generar documentos fuera del flujo aprobado. */
    public function consultar() { return $this->retirado(); }
    public function generar() { return $this->retirado(); }
    public function listar() { return $this->retirado(); }
    public function anular(int $id) { return $this->retirado(); }

    private function retirado()
    {
        return $this->jsonResponse('error', 'Esta operación fue retirada. Use Envíos y aprobaciones para consultar el rol y generar su PDF oficial.', ['url'=>site_url('asistencia/gestion-roles/envios')], 410);
    }

    public function pdf(int $id)
    {
        try {
            $usuario = $this->usuario();
            $documento = db_connect()->table('casis_rol_documento')->where('id', $id)->get()->getRowArray();
            if (!$documento) throw new \OutOfBoundsException('El rol no existe.');
            if (!empty($documento['envio_id'])) {
                (new RolEnvioService())->oficial((int)$documento['envio_id']);
            } elseif (!(new GestionRolService())->administrador() && (int)$documento['created_by'] !== $usuario) {
                throw new \DomainException('No tiene acceso a este documento.');
            }
            $archivo = (new RolDocumentoService())->archivo($id);


            return $this->response
            ->setContentType('application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $archivo['nombre'] . '"')
            ->setHeader('Cache-Control', 'private, no-store')
            ->setHeader('X-Rol-Estado', $archivo['estado'])
            ->setBody(file_get_contents($archivo['ruta']));

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

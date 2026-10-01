<?php

namespace Modules\Firma\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Firma\Services\FirmaService;
use Modules\Firma\Config\Firma;

class FirmaController extends BaseModuleController
{
    private function usuario(): int
    {
        $id = auth()->loggedIn() ? (int) auth()->id() : 0;
        if ($id < 1) throw new \UnexpectedValueException('Inicie sesión nuevamente.');
        return $id;
    }

    public function index()
    {
        return view('Modules\Firma\Views\index', ['config' => config(Firma::class)]);
    }

    private function ejecutar(callable $action)
    {
        try {
            $data = $action(new FirmaService(), $this->usuario());
            return $this->jsonResponse('success', 'Operación completada.', ['resultado' => $data, 'csrf' => ['name' => csrf_token(), 'hash' => csrf_hash()]]);
        } catch (\Throwable $e) {
            $code = $e instanceof \InvalidArgumentException ? 422 : ($e instanceof \OutOfBoundsException ? 404 : ($e instanceof \DomainException ? 409 : ($e instanceof \UnexpectedValueException ? 401 : 500)));
            if ($code === 500) log_message('error', 'Firma: {message}', ['message' => $e->getMessage()]);
            return $this->jsonResponse('error', $code === 500 ? 'No se pudo completar la operación. Revise la configuración y el registro del servidor.' : $e->getMessage(), ['csrf' => ['name' => csrf_token(), 'hash' => csrf_hash()]], $code);
        }
    }

    public function listar()
    {
        return $this->ejecutar(fn($s, $u) => $s->listar($u, max(1, min(100000, (int) $this->request->getGet('pagina')))));
    }

    public function subir()
    {
        return $this->ejecutar(function ($s, $u) {
            $file = $this->request->getFile('documento');
            if (! $file || ! $file->isValid()) throw new \InvalidArgumentException('No se recibió el PDF. Revise el tamaño máximo de carga del servidor.');
            return ['id' => $s->registrar($file->getTempName(), $file->getClientName(), $u)];
        });
    }

    public function importarRol(int $id)
    {
        return $this->ejecutar(function ($s, $u) use ($id) {
            $db = db_connect();
            $rol = $db->table('casis_rol_documento')->where('id', $id)->where('created_by', $u)->get()->getRowArray();
            if (! $rol) throw new \OutOfBoundsException('Solo puede incorporar roles generados por usted.');
            if ($rol['estado'] !== 'GENERADO') throw new \DomainException('No se puede firmar un rol anulado.');
            $existing = $db->table('firma_documentos')->where('origen', 'asistencia.roles')->where('referencia', (string) $id)->where('created_by', $u)->get()->getRowArray();
            if ($existing) return ['id' => (int) $existing['id']];
            $archivo = (new \Modules\Asistencia\Services\RolDocumentoService())->archivo($id);
            return ['id' => $s->registrar($archivo['ruta'], $archivo['nombre'], $u, 'asistencia.roles', (string) $id)];
        });
    }

    public function versiones(int $id)
    {
        return $this->ejecutar(fn($s, $u) => $s->versiones($id, $u));
    }

    public function pdf(int $id, int $numero)
    {
        try {
            $s = new FirmaService();
            $s->documento($id, $this->usuario());
            $file = $s->archivo($id, $numero);
            
            $nombre = 'documento-' . $id . '-v' . $numero . '.pdf';
            return $this->response->download($file['ruta'], null, true)
                ->setFileName($nombre)
                ->setContentType('application/pdf', '')
                ->setHeader('Content-Disposition', 'inline; filename="' . $nombre . '"')
                ->setHeader('Cache-Control', 'private, no-store')
                ->setHeader('X-Content-Type-Options', 'nosniff');
        } catch (\Throwable $e) {
            return $this->ejecutar(static function () use ($e) { throw $e; });
        }
    }

    public function iniciar(int $id)
    {
        return $this->ejecutar(function ($s, $u) use ($id) {
            $cfg = config(Firma::class);
            if (($cfg->clientId ?: env('FIRMAPERU_CLIENT_ID', '')) === '' || ($cfg->clientSecret ?: env('FIRMAPERU_CLIENT_SECRET', '')) === '') {
                throw new \DomainException('La firma está pendiente de configuración institucional. Contacte al administrador.');
            }
            return $s->iniciar($id, $u, $this->request->getPost());
        });
    }

    public function estado(int $id)
    {
        return $this->ejecutar(fn($s, $u) => $s->estado($id, $u));
    }

    public function cancelar(int $id)
    {
        return $this->ejecutar(function ($s, $u) use ($id) { $s->cancelar($id, $u); return []; });
    }
}

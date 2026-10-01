<?php

namespace Modules\Firma\Controllers;

use App\Controllers\BaseController;
use Modules\Firma\Services\FirmaService;
use Modules\Firma\Services\ClienteService;

class ClienteController extends BaseController
{
    private function ejecutar(callable $action)
    {
        $this->response->setHeader('Cache-Control', 'no-store')->setHeader('X-Content-Type-Options', 'nosniff');
        try {
            return $action(new FirmaService());
        } catch (\Throwable $e) {
            $code = $e instanceof \InvalidArgumentException ? 422 : ($e instanceof \OutOfBoundsException ? 404 : ($e instanceof \DomainException ? 409 : 500));
            if ($code === 500) log_message('error', 'Cliente Firma Perú: {message}', ['message' => $e->getMessage()]);
            return $this->response->setStatusCode($code)->setContentType('text/plain')->setBody($code === 500 ? 'No se pudo procesar la firma. Revise la configuración del servidor.' : $e->getMessage());
        }
    }

    public function parametros()
    {
        return $this->ejecutar(function () {
            $token = $this->request->getPost('param_token');
            if (! is_string($token)) throw new \InvalidArgumentException('Falta param_token.');
            return $this->response->setContentType('text/plain')->setBody((new ClienteService())->parametros($token));
        });
    }

    public function documento(string $token)
    {
        return $this->ejecutar(function ($s) use ($token) {
            $op = $s->porToken($token);
            $file = $s->archivo((int) $op['documento_id'], (int) $op['version_base']);
            return $this->response->download($file['ruta'], null)->setFileName('documento.pdf')->setHeader('Cache-Control', 'no-store');
        });
    }

    public function recibir(string $token)
    {
        return $this->ejecutar(function ($s) use ($token) {
            $s->porToken($token);
            $files = $this->request->getFiles();
            $file = count($files) === 1 ? reset($files) : null;
            if (! $file instanceof \CodeIgniter\HTTP\Files\UploadedFile || ! $file->isValid()) throw new \InvalidArgumentException('Se requiere exactamente un archivo PDF.');
            $s->recibir($token, $file->getTempName());
            return $this->response->setContentType('text/plain')->setBody('OK');
        });
    }
}

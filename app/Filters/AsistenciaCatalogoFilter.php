<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Asistencia\Services\AccesoAsistenciaService;

class AsistenciaCatalogoFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $uri = strtolower(service('uri')->getPath());
        $esPeriodo = str_contains($uri, '/periodos') || str_contains($uri, '/grupo-corte') || str_contains($uri, '/grupos-corte');
        $metodo = strtoupper($request->getMethod());
        $acceso = new AccesoAsistenciaService();

        if ($esPeriodo) {
            $permitido = $metodo === 'GET'
                ? $acceso->permitir('lectura_periodos')
                : $acceso->permitir(str_contains($uri, 'grupos-corte') || str_contains($uri, 'grupo-corte') ? 'gestionar_grupos_corte' : 'gestionar_periodos');
        } else {
            $perfil = $acceso->perfilTerritorial();
            $territorial = $perfil && !($perfil['global'] ?? false);
            $esEstablecimientos = str_contains($uri, '/establecimientos') || str_ends_with($uri, '/establecimiento');
            if ($territorial && $metodo !== 'GET') {
                $permitido = $esEstablecimientos && $acceso->permitir('gestionar_catalogos');
            } elseif ($territorial) {
                $permitido = $acceso->permitir('lectura_catalogos');
            } else {
                $permitido = $metodo === 'GET'
                    ? $acceso->permitir('lectura_catalogos')
                    : $acceso->permitir('gestionar_catalogos');
            }
        }

        if ($permitido) return null;
        return service('response')->setStatusCode(403)->setBody('No tiene permisos para acceder a este recurso de Asistencia.');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}

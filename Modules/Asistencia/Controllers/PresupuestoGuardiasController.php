<?php

namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Asistencia\Services\GestionRolService;
use Modules\Asistencia\Services\PresupuestoGuardiasExportacion;
use Modules\Asistencia\Services\PresupuestoGuardiasService;

class PresupuestoGuardiasController extends BaseModuleController
{
    private const RUTA = 'asistencia/gestion-roles/presupuesto-guardias';

    public function index()
    {
        try {
            [$usuario, $ambito] = $this->acceso();
            $filtros = $this->filtros($this->request->getGet());
            $servicio = new PresupuestoGuardiasService(db_connect());
            $error = session()->getFlashdata('error');
            try {
                $reporte = $servicio->consultar($filtros, $ambito);
            } catch (\DomainException $e) {
                $error = $e->getMessage();
                $filtros = [];
                $reporte = $servicio->consultar([], $ambito);
            }
            return view('Modules\Asistencia\Views\presupuesto_guardias\index', [
                'reporte' => $reporte, 'filtros' => $filtros, 'error' => $error, 'ruta' => self::RUTA,
                'historial' => (new PresupuestoGuardiasExportacion(db_connect()))->historial($ambito === null ? null : $usuario),
            ]);
        } catch (\DomainException $e) {
            return $this->response->setStatusCode($e->getCode() ?: 400)->setContentType('text/plain')->setBody($e->getMessage());
        }
    }

    public function exportar()
    {
        $filtros = [];
        try {
            [$usuario, $ambito] = $this->acceso();
            $filtros = $this->filtros($this->request->getPost());
            $formato = $this->request->getPost('formato');
            if (!is_string($formato)) throw new \DomainException('Formato de exportación inválido.');
            $db = db_connect();
            $reporte = (new PresupuestoGuardiasService($db))->consultar($filtros, $ambito);
            return $this->descarga((new PresupuestoGuardiasExportacion($db))->generar($reporte, $formato, $usuario));
        } catch (\Throwable $e) {
            if ($e instanceof \DomainException && in_array($e->getCode(), [401, 403], true)) {
                return $this->response->setStatusCode($e->getCode())->setContentType('text/plain')->setBody($e->getMessage());
            }
            if (!$e instanceof \DomainException) log_message('error', 'Exportación de presupuesto de guardias: {mensaje}', ['mensaje' => $e->getMessage()]);
            return redirect()->to(site_url(self::RUTA) . ($filtros ? '?' . http_build_query($filtros) : ''))
                ->with('error', $e instanceof \DomainException ? $e->getMessage() : 'No se pudo generar y registrar la exportación.');
        }
    }

    public function archivo(string $codigo)
    {
        try {
            [$usuario, $ambito] = $this->acceso();
            if (!preg_match('/^PG-\d{6}-[0-9A-F]{16}$/D', $codigo)) throw new \DomainException('Exportación no disponible.', 404);
            return $this->descarga((new PresupuestoGuardiasExportacion(db_connect()))->archivo($codigo, $ambito === null ? null : $usuario, $ambito));
        } catch (\DomainException $e) {
            return $this->response->setStatusCode($e->getCode())->setContentType('text/plain')->setBody($e->getMessage());
        }
    }

    private function acceso(): array
    {
        if (!auth()->loggedIn()) throw new \DomainException('Debe iniciar sesión.', 401);
        $gestion = new GestionRolService();
        $usuario = $gestion->usuario();
        $rrhh = $gestion->oficinas(true, 'RRHH');
        $direccion = $gestion->oficinas(true, 'DIRECCION');
        if ($rrhh === null || $direccion === null) return [$usuario, null];
        $oficinas = array_values(array_unique(array_merge($rrhh, $direccion)));
        if (!$oficinas) throw new \DomainException('El presupuesto requiere una designación vigente en RR. HH. o Dirección.', 403);
        return [$usuario, $oficinas];
    }

    private function filtros(array $entrada): array
    {
        $filtros = [];
        foreach (['periodo', ...array_keys(PresupuestoGuardiasService::FILTROS)] as $campo) {
            $valor = $entrada[$campo] ?? '';
            if ($valor === '') continue;
            if (!is_string($valor) || !ctype_digit($valor) || (int) $valor < 1 || (int) $valor > 2147483647) {
                throw new \DomainException('Seleccione filtros válidos.');
            }
            $filtros[$campo] = (int) $valor;
        }
        return $filtros;
    }

    private function descarga(array $archivo)
    {
        $mime = $archivo['formato'] === 'pdf' ? 'application/pdf' : 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        return $this->response->setContentType($mime)->setHeader('Cache-Control', 'private, no-store')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $archivo['codigo'] . '.' . $archivo['formato'] . '"')
            ->setBody($archivo['contenido']);
    }
}

<?php

namespace Modules\Asistencia\Controllers;

use Modules\Asistencia\Services\PeriodoService;


use CodeIgniter\RESTful\ResourceController;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Asistencia\Services\AccesoAsistenciaService;

class PeriodoController extends ResourceController
{
    /**
     * Vista Principal de Periodos
     * GET /asistencia/gestordb/periodos
     */
    public function periodos()
    {
        if (!(new AccesoAsistenciaService())->permitir('lectura_periodos')) return $this->failForbidden('No tiene acceso a la gestión de periodos.');
        $acceso = new AccesoAsistenciaService();
        return view('Modules\Asistencia\Views\database\periodos_view', ['puedeGestionar' => $acceso->permitir('gestionar_periodos')]);
    }

    /**
     * API: Listar periodos
     * GET /asistencia/gestordb/api/periodos
     */
    public function apiListarPeriodos()
    {
        if (!(new AccesoAsistenciaService())->permitir('lectura_periodos')) return $this->failForbidden('No tiene acceso a la gestión de periodos.');
        $service = new PeriodoService();
        $search = $this->request->getVar('search');
        $grupoIde = $this->request->getVar('gco_ide');
        $estado = $this->request->getVar('per_estado');

        $data = $service->listarPeriodos($search, $grupoIde, $estado);

        return $this->respond([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * API: Obtener periodos por grupo de corte
     * GET /asistencia/gestordb/api/periodos-grupo/(:num)
     */
    public function apiPeriodosPorGrupo($grupoIde = null)
    {
        $acceso = new AccesoAsistenciaService();
        if (!$acceso->permitir('lectura_periodos') || !$acceso->grupoCorteDentroAmbito((int) $grupoIde)) return $this->failNotFound('Grupo de corte no encontrado en su ámbito.');
        $service = new PeriodoService();
        $data = $service->listarPorGrupoCorte((int) $grupoIde);
        return $this->respond([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * API: Calcular fechas por grupo de corte
     * GET /asistencia/gestordb/api/periodos/calcular-fechas
     */
    public function apiCalcularFechas()
    {
        $acceso = new AccesoAsistenciaService();
        if (!$acceso->permitir('lectura_periodos')) return $this->failForbidden('No tiene acceso a la gestión de periodos.');
        $grupoIde = $this->request->getVar('gco_ide');
        $anio = $this->request->getVar('anio');
        $mes = $this->request->getVar('mes');

        if (!$grupoIde || !$anio || !$mes) {
            return $this->fail('Faltan parámetros obligatorios.', 400);
        }
        if (!$acceso->grupoCorteDentroAmbito((int) $grupoIde)) return $this->failNotFound('Grupo de corte no encontrado en su ámbito.');

        try {
            $service = new PeriodoService();
            $fechas = $service->calcularFechasPeriodo((int) $grupoIde, (int) $anio, (int) $mes);
            return $this->respond(['status' => 'success', 'data' => $fechas]);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 400);
        }
    }

    /**
     * API: Crear periodo
     * POST /asistencia/gestordb/api/periodos
     */
    public function apiCrearPeriodo()
    {
        $acceso = new AccesoAsistenciaService();
        if (!$acceso->permitir('gestionar_periodos')) return $this->failForbidden('No tiene permiso para crear o aperturar periodos.');
        $grupoId = (int) $this->request->getVar('gco_ide');
        if (!$grupoId || !$acceso->grupoCorteDentroAmbito($grupoId)) return $this->failForbidden('El grupo de corte está fuera de su ámbito.');
        $service = new PeriodoService();
        $datos = [
            'gco_ide' => $this->request->getVar('gco_ide') ?: null,
            'per_anio' => $this->request->getVar('per_anio'),
            'per_mes' => $this->request->getVar('per_mes'),
            'per_nombre' => $this->request->getVar('per_nombre'),
            'per_fecha_inicio' => $this->request->getVar('per_fecha_inicio'),
            'per_fecha_fin' => $this->request->getVar('per_fecha_fin'),
            'per_estado' => $this->request->getVar('per_estado') ?? 'PROGRAMADO',
            'per_observacion' => $this->request->getVar('per_observacion') ?: null,
        ];

        $resultado = $service->crearPeriodo($datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respondCreated(['status' => 'success', 'message' => 'Período registrado con éxito.']);
    }

    /**
     * API: Actualizar periodo (Con RawInput)
     * PUT /asistencia/gestordb/api/periodos/(:num)
     */
    public function apiActualizarPeriodo($id = null)
    {
        $acceso = new AccesoAsistenciaService();
        if (!$acceso->permitir('gestionar_periodos')) return $this->failForbidden('No tiene permiso para modificar o aperturar periodos.');
        $service = new PeriodoService();
        $rawDatos = $this->request->getRawInput();
        if (!$this->periodoDentroAmbito((int) $id, $acceso) || empty($rawDatos['gco_ide']) || !$acceso->grupoCorteDentroAmbito((int) $rawDatos['gco_ide'])) return $this->failForbidden('El periodo o grupo de corte está fuera de su ámbito.');

        $datos = [
            'gco_ide' => !empty($rawDatos['gco_ide']) ? $rawDatos['gco_ide'] : null,
            'per_anio' => $rawDatos['per_anio'] ?? null,
            'per_mes' => $rawDatos['per_mes'] ?? null,
            'per_nombre' => $rawDatos['per_nombre'] ?? null,
            'per_fecha_inicio' => $rawDatos['per_fecha_inicio'] ?? null,
            'per_fecha_fin' => $rawDatos['per_fecha_fin'] ?? null,
            'per_estado' => $rawDatos['per_estado'] ?? 'PROGRAMADO',
            'per_observacion' => $rawDatos['per_observacion'] ?? null,
        ];

        $resultado = $service->actualizarPeriodo((int) $id, $datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respond(['status' => 'success', 'message' => 'Período actualizado correctamente.']);
    }

    /**
     * API: Eliminar periodo
     * DELETE /asistencia/gestordb/api/periodos/(:num)
     */
    public function apiEliminarPeriodo($id = null)
    {
        $acceso = new AccesoAsistenciaService();
        if (!$acceso->permitir('gestionar_periodos')) return $this->failForbidden('No tiene permiso para eliminar periodos.');
        if (!$this->periodoDentroAmbito((int) $id, $acceso)) return $this->failNotFound('Periodo no encontrado en su ámbito.');
        $service = new PeriodoService();

        if ($service->eliminarPeriodo((int) $id)) {
            return $this->respondDeleted(['status' => 'success', 'message' => 'Período dado de baja con éxito.']);
        }
        return $this->fail('No se pudo eliminar el registro.', 400);
    }



    // =========================================================================
    // API ENDPOINTS: GRUPOS DE CORTE
    // =========================================================================
    public function gruposCorte()
    {
        if (!(new AccesoAsistenciaService())->permitir('lectura_periodos')) return $this->failForbidden('No tiene acceso a grupos de corte.');
        $acceso = new AccesoAsistenciaService();
        return view('Modules\Asistencia\Views\database\grupo_corte_view', ['puedeGestionar' => $acceso->permitir('gestionar_grupos_corte')]);
    }
    /**
     * GET /asistencia/gestordb/api/grupos-corte
     */
    public function apiListarGruposCorte()
    {
        if (!(new AccesoAsistenciaService())->permitir('lectura_periodos')) return $this->failForbidden('No tiene acceso a grupos de corte.');
        $periodoService = new PeriodoService();
        $search = $this->request->getVar('search');
        $estado = $this->request->getVar('estado');

        $data = $periodoService->listarGruposCorte($search, $estado);

        return $this->respond([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * POST /asistencia/gestordb/api/grupos-corte
     */
    public function apiCrearGrupoCorte()
    {
        $acceso = new AccesoAsistenciaService();
        if (!$acceso->permitir('gestionar_grupos_corte')) return $this->failForbidden('No tiene permiso para crear grupos de corte.');
        $periodoService = new PeriodoService();

        $datos = [
            'diresa_id' => $this->request->getVar('diresa_id') ?: null,
            'red_id' => $this->request->getVar('red_id') ?: null,
            'microred_id' => $this->request->getVar('microred_id') ?: null,
            'establecimiento_id' => $this->request->getVar('establecimiento_id') ?: null,
            'gco_nombre' => $this->request->getVar('gco_nombre'),
            'gco_mco_ide' => $this->request->getVar('gco_mco_ide') ?: null,
            'gco_dia_inicio' => $this->request->getVar('gco_dia_inicio'),
            'gco_dia_fin' => $this->request->getVar('gco_dia_fin'),
            'gco_mes_desfasado' => $this->request->getVar('gco_mes_desfasado') ?? 0,
            'gco_estado' => $this->request->getVar('gco_estado') ?? 'ACTIVO',
        ];
        if (!$acceso->validarUbicacion($datos)) return $this->failForbidden('La ubicación del grupo de corte está fuera de su ámbito.');

        $resultado = $periodoService->crearGrupoCorte($datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respondCreated(['status' => 'success', 'message' => 'Grupo de corte registrado correctamente.']);
    }

    /**
     * PUT /asistencia/gestordb/api/grupos-corte/(:num)
     */
    public function apiActualizarGrupoCorte($id = null)
    {
        $acceso = new AccesoAsistenciaService();
        if (!$acceso->permitir('gestionar_grupos_corte')) return $this->failForbidden('No tiene permiso para modificar grupos de corte.');
        $periodoService = new PeriodoService();
        $rawDatos = $this->request->getRawInput();
        if (!$acceso->grupoCorteDentroAmbito((int) $id)) return $this->failNotFound('Grupo de corte no encontrado en su ámbito.');

        $datos = [
            'diresa_id' => !empty($rawDatos['diresa_id']) ? $rawDatos['diresa_id'] : null,
            'red_id' => !empty($rawDatos['red_id']) ? $rawDatos['red_id'] : null,
            'microred_id' => !empty($rawDatos['microred_id']) ? $rawDatos['microred_id'] : null,
            'establecimiento_id' => !empty($rawDatos['establecimiento_id']) ? $rawDatos['establecimiento_id'] : null,
            'gco_nombre' => $rawDatos['gco_nombre'] ?? null,
            'gco_mco_ide' => !empty($rawDatos['gco_mco_ide']) ? $rawDatos['gco_mco_ide'] : null,
            'gco_dia_inicio' => $rawDatos['gco_dia_inicio'] ?? null,
            'gco_dia_fin' => $rawDatos['gco_dia_fin'] ?? null,
            'gco_mes_desfasado' => isset($rawDatos['gco_mes_desfasado']) ? (int) $rawDatos['gco_mes_desfasado'] : 0,
            'gco_estado' => $rawDatos['gco_estado'] ?? 'ACTIVO',
        ];
        if (!$acceso->validarUbicacion($datos)) return $this->failForbidden('La ubicación del grupo de corte está fuera de su ámbito.');

        $resultado = $periodoService->actualizarGrupoCorte((int) $id, $datos);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400);
        }

        return $this->respond(['status' => 'success', 'message' => 'Grupo de corte actualizado correctamente.']);
    }

    /**
     * DELETE /asistencia/gestordb/api/grupos-corte/(:num)
     */
    public function apiEliminarGrupoCorte($id = null)
    {
        $acceso = new AccesoAsistenciaService();
        if (!$acceso->permitir('gestionar_grupos_corte')) return $this->failForbidden('No tiene permiso para eliminar grupos de corte.');
        if (!$acceso->grupoCorteDentroAmbito((int) $id)) return $this->failNotFound('Grupo de corte no encontrado en su ámbito.');
        $periodoService = new PeriodoService();
        if ($periodoService->eliminarGrupoCorte((int) $id)) {
            return $this->respondDeleted(['status' => 'success', 'message' => 'Grupo de corte eliminado con éxito.']);
        }
        return $this->fail('No se pudo eliminar el registro.', 400);
    }

    private function periodoDentroAmbito(int $id, AccesoAsistenciaService $acceso): bool
    {
        $row = \Config\Database::connect()->table('casis_periodos p')->select('p.gco_ide')
            ->where('p.per_ide', $id)->where('p.deleted_at', null)->get()->getRowArray();
        return $row && $acceso->grupoCorteDentroAmbito((int) $row['gco_ide']);
    }
}

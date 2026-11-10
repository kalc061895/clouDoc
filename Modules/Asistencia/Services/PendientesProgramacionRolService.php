<?php

namespace Modules\Asistencia\Services;

/** Detecta personal activo sin turnos durante el mes de su período aplicable. */
class PendientesProgramacionRolService
{
    private $db;
    private GrupoProgramacionRolService $grupos;

    public function __construct($db = null)
    {
        $this->db = $db ?? \Config\Database::connect();
        $this->grupos = new GrupoProgramacionRolService($this->db);
    }

    public function reporte(?int $periodoId = null): array
    {
        $gestion = new GestionRolService();
        $gestion->usuario();
        $oficinaIds = $gestion->oficinas();
        if ($oficinaIds !== null) {
            $oficinaIds = array_values(array_unique(array_map('intval', $oficinaIds)));
            if (!$oficinaIds) return ['periodos' => [], 'periodo' => null, 'oficinas' => [], 'resumen' => $this->resumen([])];
        }

        $periodos = $this->periodosDisponibles($oficinaIds);
        if ($periodoId === null && $periodos) $periodoId = (int) $periodos[0]['id'];
        $periodo = null;
        foreach ($periodos as $item) if ((int) $item['id'] === $periodoId) $periodo = $item;
        if ($periodoId !== null && !$periodo) throw new \DomainException('El período no existe o está fuera de su ámbito de acceso.');
        if (!$periodo) return ['periodos' => $periodos, 'periodo' => null, 'oficinas' => [], 'resumen' => $this->resumen([])];

        $desde = $periodo['mes'] . '-01';
        $hasta = (new \DateTimeImmutable($desde))->modify('+1 month')->format('Y-m-d');
        $q = $this->db->table('casis_personal p')
            ->select('p.perl_ide, p.perl_codigo, p.perl_ofi_ide, p.perl_est_ide, pe.per_numero_documento, pe.per_paterno, pe.per_materno, pe.per_nombre, o.ofi_nombre, e.est_nombre, m.mic_nombre, r.red_nombre, d.dir_nombre, c.car_nombre')
            ->join('casis_cargo c', 'c.car_ide = p.perl_car_ide AND c.deleted_at IS NULL', 'left')
            ->join('casis_persona pe', 'pe.per_ide = p.perl_per_ide AND pe.deleted_at IS NULL', 'inner')
            ->join('casis_oficina o', 'o.ofi_ide = p.perl_ofi_ide AND o.deleted_at IS NULL', 'inner')
            ->join('casis_establecimiento e', 'e.est_ide = p.perl_est_ide AND e.deleted_at IS NULL', 'inner')
            ->join('casis_microred m', 'm.mic_ide = e.est_mic_ide AND m.deleted_at IS NULL', 'left')
            ->join('casis_red r', 'r.red_ide = m.mic_red_ide AND r.deleted_at IS NULL', 'left')
            ->join('casis_diresa d', 'd.dir_ide = r.red_dir_ide AND d.deleted_at IS NULL', 'left')
            ->where('p.perl_estado', 1)->where('p.deleted_at', null)
            ->whereIn('p.perl_est_ide', $periodo['establecimientos']);
        if ($oficinaIds !== null) $q->whereIn('p.perl_ofi_ide', $oficinaIds);
        $personal = $q->orderBy('d.dir_nombre')->orderBy('r.red_nombre')->orderBy('m.mic_nombre')->orderBy('e.est_nombre')->orderBy('o.ofi_nombre')->orderBy('pe.per_paterno')->orderBy('pe.per_materno')->orderBy('pe.per_nombre')->get()->getResultArray();
        $personalIds = array_map('intval', array_column($personal, 'perl_ide'));
        $programados = [];
        if ($personalIds) {
            $programados = array_fill_keys(array_map('intval', array_unique(array_column(
                $this->db->table('casis_programacion')->select('prog_perl_ide')->whereIn('prog_perl_ide', $personalIds)
                    ->where('prog_fecha >=', $desde)->where('prog_fecha <', $hasta)->where('deleted_at', null)->get()->getResultArray(),
                'prog_perl_ide'
            ))), true);
        }

        $oficinas = [];
        foreach ($personal as $persona) {
            $key = (int) $persona['perl_ofi_ide'];
            if (!isset($oficinas[$key])) {
                $oficinas[$key] = [
                    'oficina_id' => $key,
                    'oficina' => $persona['ofi_nombre'],
                    'establecimiento' => $persona['est_nombre'],
                    'microred' => $persona['mic_nombre'] ?? '',
                    'red' => $persona['red_nombre'] ?? '',
                    'diresa' => $persona['dir_nombre'] ?? '',
                    'total' => 0,
                    'programados' => 0,
                    'pendientes' => [],
                ];
            }
            $oficinas[$key]['total']++;
            if (isset($programados[(int) $persona['perl_ide']])) {
                $oficinas[$key]['programados']++;
                continue;
            }
            $oficinas[$key]['pendientes'][] = [
                'id' => (int) $persona['perl_ide'],
                'codigo' => $persona['perl_codigo'],
                'documento' => $persona['per_numero_documento'],
                'nombre' => trim(implode(' ', array_filter([$persona['per_paterno'], $persona['per_materno'], $persona['per_nombre']]))),
                'cargo' => $persona['car_nombre'] ?? '',
            ];
        }
        $oficinas = array_values(array_filter($oficinas, static fn(array $o): bool => count($o['pendientes']) > 0));
        return ['periodos' => $periodos, 'periodo' => $periodo, 'oficinas' => $oficinas, 'resumen' => $this->resumen($oficinas)];
    }

    private function periodosDisponibles(?array $oficinaIds): array
    {
        $rows = $this->db->table('casis_plan_periodo p')
            ->select('p.*, g.nivel, g.entidad_id, g.nombre AS grupo_nombre, g.estado AS grupo_estado')
            ->join('casis_plan_grupo g', 'g.id = p.grupo_id', 'left')
            ->orderBy('p.mes', 'DESC')->orderBy('p.id', 'DESC')->get()->getResultArray();
        $resolverCache = [];
        $periodoAplicableCache = [];
        $resultados = [];
        foreach ($rows as $periodo) {
            if (!empty($periodo['grupo_id']) && ($periodo['grupo_estado'] ?? null) !== 'ACTIVO') continue;
            $candidatos = $this->grupos->establecimientosDePeriodo($periodo);
            $establecimientos = [];
            foreach ($candidatos as $establecimientoId) {
                $establecimientoId = (int) $establecimientoId;
                if ($oficinaIds !== null && !isset($resolverCache[$establecimientoId])) {
                    $oficinasEst = array_map('intval', array_column($this->db->table('casis_oficina')->select('ofi_ide')->where('ofi_est_ide', $establecimientoId)->where('deleted_at', null)->get()->getResultArray(), 'ofi_ide'));
                    $resolverCache[$establecimientoId] = (bool) array_intersect($oficinasEst, $oficinaIds);
                }
                if ($oficinaIds !== null && !$resolverCache[$establecimientoId]) continue;
                if (!isset($periodoAplicableCache[$periodo['mes']][$establecimientoId])) {
                    $periodoAplicableCache[$periodo['mes']][$establecimientoId] = $this->grupos->resolverPeriodo($establecimientoId, $periodo['mes']);
                }
                $aplicable = $periodoAplicableCache[$periodo['mes']][$establecimientoId];
                if ($aplicable && (int) $aplicable['id'] === (int) $periodo['id']) $establecimientos[] = $establecimientoId;
            }
            if (!$establecimientos) continue;
            $periodo['establecimientos'] = $establecimientos;
            $periodo['alcance'] = $periodo['grupo_nombre'] ?: ('Establecimiento ' . $periodo['est_id']);
            $resultados[] = $periodo;
        }
        return $resultados;
    }

    private function resumen(array $oficinas): array
    {
        $resumen = ['oficinas' => count($oficinas), 'personal' => 0, 'programados' => 0, 'pendientes' => 0];
        foreach ($oficinas as $oficina) {
            $resumen['personal'] += $oficina['total'];
            $resumen['programados'] += $oficina['programados'];
            $resumen['pendientes'] += count($oficina['pendientes']);
        }
        return $resumen;
    }
}

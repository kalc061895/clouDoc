<?php

namespace Modules\Asistencia\Services;

class ReporteMensualService
{
    private $db;
    public function __construct($db = null) { $this->db = $db ?? db_connect(); }

    public function catalogos(): array
    {
        $definitions = [
            'dir_ide' => ['casis_diresa', 'dir_ide', 'dir_nombre'],
            'red_ide' => ['casis_red', 'red_ide', 'red_nombre', 'red_dir_ide'],
            'mic_ide' => ['casis_microred', 'mic_ide', 'mic_nombre', 'mic_red_ide'],
            'est_ide' => ['casis_establecimiento', 'est_ide', 'est_nombre', 'est_mic_ide'],
            'ups_ide' => ['casis_upss', 'ups_ide', 'ups_nombre'],
            'uss_ide' => ['casis_upss_servicio', 'uss_ide', 'uss_nombre'],
            'tofi_ide' => ['casis_tipo_oficina', 'tofi_ide', 'tofi_nombre'],
            'ofi_ide' => ['casis_oficina', 'ofi_ide', 'ofi_nombre', 'ofi_est_ide', 'ofi_tofi_ide', 'ofi_padre_ide'],
        ];
        $result = [];
        foreach ($definitions as $key => $def) {
            $table = array_shift($def);
            $result[$key] = $this->db->table($table)->select(implode(', ', $def))->where('deleted_at', null)->orderBy($def[1])->get()->getResultArray();
        }
        $result['eup'] = $this->db->table('casis_establecimiento_upss')->select('eup_ide, eup_est_ide, eup_ups_ide')->where('deleted_at', null)->get()->getResultArray();
        $result['eus'] = $this->db->table('casis_establecimiento_upss_servicio')->select('eus_eup_ide, eus_uss_ide')->where('deleted_at', null)->get()->getResultArray();
        return $result;
    }

    public function consultar(array $input): array
    {
        $f = ReporteMensualData::filtros($input);
        $c = $this->catalogos(); $labels = [];
        foreach (['dir_ide' => 'dir_nombre', 'red_ide' => 'red_nombre', 'mic_ide' => 'mic_nombre', 'est_ide' => 'est_nombre', 'ups_ide' => 'ups_nombre', 'uss_ide' => 'uss_nombre', 'tofi_ide' => 'tofi_nombre', 'ofi_ide' => 'ofi_nombre'] as $key => $name) {
            $index = array_column($c[$key], null, $key);
            if ($f[$key] && ! isset($index[$f[$key]])) throw new \InvalidArgumentException('No existe la selección: ' . $key);
            if ($f[$key]) $labels[] = $index[$f[$key]][$name];
        }
        $reds = array_column($c['red_ide'], null, 'red_ide');
        $mics = array_column($c['mic_ide'], null, 'mic_ide');
        if ($f['red_ide'] && $f['dir_ide'] && (int) $reds[$f['red_ide']]['red_dir_ide'] !== $f['dir_ide']) throw new \InvalidArgumentException('La red no pertenece a la DIRESA.');
        if ($f['mic_ide']) {
            $red = $mics[$f['mic_ide']]['mic_red_ide'];
            if (($f['red_ide'] && (int) $red !== $f['red_ide']) || ($f['dir_ide'] && (int) ($reds[$red]['red_dir_ide'] ?? 0) !== $f['dir_ide'])) throw new \InvalidArgumentException('La microred no corresponde a los filtros.');
        }
        $estIds = [];
        foreach ($c['est_ide'] as $est) {
            $mic = $mics[$est['est_mic_ide']] ?? [];
            $red = $reds[$mic['mic_red_ide'] ?? 0] ?? [];
            if ((! $f['dir_ide'] || (int) ($red['red_dir_ide'] ?? 0) === $f['dir_ide']) && (! $f['red_ide'] || (int) ($mic['mic_red_ide'] ?? 0) === $f['red_ide']) && (! $f['mic_ide'] || (int) $est['est_mic_ide'] === $f['mic_ide']) && (! $f['est_ide'] || (int) $est['est_ide'] === $f['est_ide'])) $estIds[] = (int) $est['est_ide'];
        }
        if ($f['est_ide'] && ! $estIds) throw new \InvalidArgumentException('El establecimiento no corresponde a los filtros.');
        $oficinas = array_values(array_filter($c['ofi_ide'], static fn($o) => in_array((int) $o['ofi_est_ide'], $estIds, true)));
        $officeIds = RolDocumentoData::oficinasIncluidas($oficinas, $f['ofi_ide'], $f['tofi_ide'], $f['incluir_hijos']);
        $inicio = sprintf('%04d-%02d-01', $f['anio'], $f['mes']); $fin = date('Y-m-t', strtotime($inicio));
        $end = date('Y-m-d', strtotime($fin . ' +1 day'));
        $empty = fn() => ReporteMensualData::resumir([], [], [], [], [], [], $f) + ['ambito' => implode(' / ', $labels) ?: 'Todos los ámbitos'];
        if (! $estIds || $officeIds === []) return $empty();
        $p = $this->db->table('casis_personal p')->select('p.perl_ide, pe.per_numero_documento, pe.per_paterno, pe.per_materno, pe.per_nombre, e.est_nombre, o.ofi_nombre, d.dir_nombre')
            ->join('casis_persona pe', 'pe.per_ide = p.perl_per_ide')
            ->join('casis_establecimiento e', 'e.est_ide = p.perl_est_ide')
            ->join('casis_microred m', 'm.mic_ide = e.est_mic_ide', 'left')->join('casis_red r', 'r.red_ide = m.mic_red_ide', 'left')->join('casis_diresa d', 'd.dir_ide = r.red_dir_ide', 'left')
            ->join('casis_oficina o', 'o.ofi_ide = p.perl_ofi_ide', 'left')
            ->where('p.deleted_at', null)->where('pe.deleted_at', null)->whereIn('p.perl_est_ide', $estIds)
            ->groupStart()->where('p.perl_fecha_inicio <=', $fin)->orWhere('p.perl_fecha_inicio', null)->groupEnd()
            ->groupStart()->where('p.perl_fecha_cese >=', $inicio)->orWhere('p.perl_fecha_cese', null)->groupEnd();
        if ($officeIds !== null) $p->whereIn('p.perl_ofi_ide', $officeIds);
        if ($f['dni'] !== '') $p->where('pe.per_numero_documento', $f['dni']);
        $t = $this->db->table('casis_programacion pr')->select('pr.*, th.th_hora_ingreso, th.th_hora_salida, t.tur_codigo, u.ups_nombre, s.uss_nombre')
            ->join('casis_turno_horario th', 'th.th_ide = pr.prog_th_ide')->join('casis_turno t', 't.tur_ide = th.th_tur_ide')
            ->join('casis_establecimiento_upss eu', 'eu.eup_ide = pr.prog_eup_ide', 'left')
            ->join('casis_establecimiento_upss_servicio es', 'es.eus_ide = pr.prog_eus_ide', 'left')
            ->join('casis_upss u', 'u.ups_ide = eu.eup_ups_ide', 'left')->join('casis_upss_servicio s', 's.uss_ide = es.eus_uss_ide', 'left')
            ->where('pr.deleted_at', null)->where('pr.prog_fecha >=', $inicio)->where('pr.prog_fecha <=', $fin)
            ->whereNotIn('pr.prog_estado', ['ANULADO', 'CANCELADO']);
        if ($f['ups_ide']) $t->where('eu.eup_ups_ide', $f['ups_ide']);
        if ($f['uss_ide']) $t->where('es.eus_uss_ide', $f['uss_ide']);
        if ($f['ups_ide'] || $f['uss_ide']) {
            $eligible = clone $t;
            $eligible->select('pr.prog_perl_ide');
            $ids = array_unique(array_column($eligible->get()->getResultArray(), 'prog_perl_ide'));
            if (! $ids) return $empty();
            $p->whereIn('p.perl_ide', $ids);
        }
        $personal = $p->orderBy('e.est_nombre')->orderBy('pe.per_paterno')->orderBy('pe.per_materno')->orderBy('p.perl_ide')->limit(501)->get()->getResultArray();
        if (count($personal) > 500) throw new \InvalidArgumentException('El reporte supera 500 trabajadores. Seleccione un establecimiento, oficina u otro filtro para reducir el ámbito.');
        if (! $personal) return $empty();
        $ids = array_column($personal, 'perl_ide');
        $turnos = $t->whereIn('pr.prog_perl_ide', $ids)->orderBy('pr.prog_fecha')->orderBy('th.th_hora_ingreso')->get()->getResultArray();
        $marcas = $this->db->table('casis_asistencia')->whereIn('asi_perl_ide', $ids)->where('deleted_at', null)->where('asi_fecha_hora >=', $inicio . ' 00:00:00')->where('asi_fecha_hora <', $end . ' 00:00:00')->orderBy('asi_fecha_hora')->get()->getResultArray();
        $licencias = $this->db->table('casis_registro_licencia r')->select('r.*, l.lic_nombre')->join('casis_licencia l', 'l.lic_ide = r.rl_lic_ide', 'left')->whereIn('r.rl_perl_ide', $ids)->where('r.deleted_at', null)->where('r.rl_estado', 1)->where('r.rl_fecha_inicio <=', $fin)->where('r.rl_fecha_fin >=', $inicio)->get()->getResultArray();
        $permisos = $this->db->table('casis_registro_permiso r')->select('r.*, p.pero_nombre')->join('casis_permiso p', 'p.pero_ide = r.rp_pero_ide', 'left')->whereIn('r.rp_perl_ide', $ids)->where('r.deleted_at', null)->whereNotIn('r.rp_estado', ['ANULADO', 'RECHAZADO', 'CANCELADO'])->where('r.rp_fecha >=', $inicio)->where('r.rp_fecha <=', $fin)->get()->getResultArray();
        $vacaciones = $this->db->table('casis_registro_vacacion')->whereIn('rv_perl_ide', $ids)->where('deleted_at', null)->where('rv_estado', 1)->where('rv_fecha_inicio <=', $fin)->where('rv_fecha_fin >=', $inicio)->get()->getResultArray();
        return ReporteMensualData::resumir($personal, $turnos, $marcas, $licencias, $permisos, $vacaciones, $f) + ['ambito' => (implode(' / ', $labels) ?: 'Todos los ámbitos') . ($f['dni'] ? ' / Documento: ' . $f['dni'] : '') . ($f['incluir_hijos'] ? ' / Incluye oficinas dependientes' : '')];
    }
}

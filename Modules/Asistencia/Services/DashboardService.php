<?php

namespace Modules\Asistencia\Services;

class DashboardService
{
    private $db;
    public function __construct($db = null) { $this->db = $db ?? db_connect(); }

    public function establecimientos(): array
    {
        return $this->db->table('casis_establecimiento')->select('est_ide, est_nombre')->where('deleted_at', null)->orderBy('est_nombre')->get()->getResultArray();
    }

    public function consultar(array $input): array
    {
        $now = new \DateTimeImmutable('now', new \DateTimeZone('America/Lima'));
        $fecha = $input['fecha'] ?? $now->format('Y-m-d');
        if (!is_string($fecha) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha) || !($date = \DateTimeImmutable::createFromFormat('!Y-m-d', $fecha, $now->getTimezone())) || $date->format('Y-m-d') !== $fecha || $fecha > $now->format('Y-m-d')) {
            throw new \InvalidArgumentException('Seleccione una fecha válida, hasta hoy.');
        }
        $est = $input['est_ide'] ?? '';
        if (!is_scalar($est) || ($est !== '' && (!ctype_digit((string) $est) || (int) $est < 1))) throw new \InvalidArgumentException('Establecimiento inválido.');
        if ($est !== '' && !in_array((int) $est, array_map('intval', array_column($this->establecimientos(), 'est_ide')), true)) throw new \InvalidArgumentException('El establecimiento no existe.');
        $p = $this->db->table('casis_personal p')->select('p.*, pe.per_paterno, pe.per_materno, pe.per_nombre, pe.per_sexo, pe.per_fecha_nacimiento, e.est_nombre, o.ofi_nombre, m.mco_nombre')
            ->join('casis_persona pe', 'pe.per_ide = p.perl_per_ide')->join('casis_establecimiento e', 'e.est_ide = p.perl_est_ide', 'left')
            ->join('casis_oficina o', 'o.ofi_ide = p.perl_ofi_ide', 'left')->join('casis_modalidad_contrato m', 'm.mco_ide = p.perl_mco_ide', 'left')
            ->where('p.deleted_at', null)->where('pe.deleted_at', null)->where('p.perl_estado', 1)
            ->groupStart()->where('p.perl_fecha_inicio <=', $fecha)->orWhere('p.perl_fecha_inicio', null)->groupEnd()
            ->groupStart()->where('p.perl_fecha_cese >=', $fecha)->orWhere('p.perl_fecha_cese', null)->groupEnd();
        if ($est !== '') $p->where('p.perl_est_ide', (int) $est);
        $personal = $p->orderBy('pe.per_paterno')->get()->getResultArray();
        $ids = array_column($personal, 'perl_ide');
        $turnos = $marcas = $licencias = $permisos = $vacaciones = $profesiones = [];
        if ($ids) {
            $turnos = $this->db->table('casis_programacion pr')->select('pr.*, th.th_hora_ingreso, th.th_hora_salida, t.tur_codigo, u.ups_nombre, s.uss_nombre')
                ->join('casis_turno_horario th', 'th.th_ide = pr.prog_th_ide')->join('casis_turno t', 't.tur_ide = th.th_tur_ide')
                ->join('casis_establecimiento_upss eu', 'eu.eup_ide = pr.prog_eup_ide', 'left')->join('casis_upss u', 'u.ups_ide = eu.eup_ups_ide', 'left')
                ->join('casis_establecimiento_upss_servicio es', 'es.eus_ide = pr.prog_eus_ide', 'left')->join('casis_upss_servicio s', 's.uss_ide = es.eus_uss_ide', 'left')
                ->whereIn('pr.prog_perl_ide', $ids)->where('pr.prog_fecha', $fecha)->where('pr.deleted_at', null)->whereNotIn('pr.prog_estado', ['ANULADO', 'CANCELADO'])->get()->getResultArray();
            $marcas = $this->db->table('casis_asistencia')->whereIn('asi_perl_ide', $ids)->where('deleted_at', null)->where('asi_fecha_hora >=', $date->modify('-1 day')->format('Y-m-d') . ' 22:00:00')->where('asi_fecha_hora <', $date->modify('+2 days')->format('Y-m-d') . ' 00:00:00')->get()->getResultArray();
            $licencias = $this->db->table('casis_registro_licencia r')->select('r.*, l.lic_nombre')->join('casis_licencia l', 'l.lic_ide = r.rl_lic_ide', 'left')->whereIn('r.rl_perl_ide', $ids)->where('r.deleted_at', null)->where('r.rl_estado', 1)->where('r.rl_fecha_inicio <=', $fecha)->where('r.rl_fecha_fin >=', $fecha)->get()->getResultArray();
            $vacaciones = $this->db->table('casis_registro_vacacion')->whereIn('rv_perl_ide', $ids)->where('deleted_at', null)->where('rv_estado', 1)->where('rv_fecha_inicio <=', $fecha)->where('rv_fecha_fin >=', $fecha)->get()->getResultArray();
            $permisos = $this->db->table('casis_registro_permiso r')->select('r.*, p.pero_nombre')->join('casis_permiso p', 'p.pero_ide = r.rp_pero_ide', 'left')->whereIn('r.rp_perl_ide', $ids)->where('r.deleted_at', null)->whereNotIn('r.rp_estado', ['ANULADO', 'RECHAZADO', 'CANCELADO'])->where('r.rp_fecha', $fecha)->get()->getResultArray();
            $profesiones = $this->db->table('casis_personal_profesion pp')->select('pp.pp_perl_ide, pro.pro_nombre')->join('casis_profesion pro', 'pro.pro_ide = pp.pp_pro_ide', 'left')->whereIn('pp.pp_perl_ide', $ids)->where('pp.deleted_at', null)->orderBy('pp.pp_principal', 'DESC')->orderBy('pp.pp_ide')->get()->getResultArray();
        }
        return DashboardData::resumir($personal, $turnos, $marcas, $licencias, $permisos, $vacaciones, $profesiones, $fecha, $now) + ['est' => $est];
    }
}

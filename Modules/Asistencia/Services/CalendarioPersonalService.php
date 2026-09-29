<?php

namespace Modules\Asistencia\Services;

class CalendarioPersonalService
{
    public function periodo(?string $fecha): array
    {
        $fecha = $fecha ?: date('Y-m-d');
        $inicio = \DateTimeImmutable::createFromFormat('!Y-m-d', $fecha);
        if (!$inicio || $inicio->format('Y-m-d') !== $fecha) {
            throw new \InvalidArgumentException('La fecha de consulta no es válida.');
        }
        return [$inicio->format('Y-m-01'), $inicio->format('Y-m-t')];
    }

    public function dias(int $personalId, string $inicio, string $fin, array $registros): array
    {
        $db = \Config\Database::connect();
        $licencias = $db->table('casis_registro_licencia r')
            ->select('r.*, l.lic_abreviatura, l.lic_nombre')
            ->join('casis_licencia l', 'l.lic_ide = r.rl_lic_ide')
            ->where('r.rl_perl_ide', $personalId)->where('r.deleted_at', null)
            ->where('r.rl_fecha_inicio <=', $fin)->where('r.rl_fecha_fin >=', $inicio)
            ->get()->getResultArray();
        $permisos = $db->table('casis_registro_permiso r')
            ->select('r.*, p.pero_abreviatura, p.pero_nombre')
            ->join('casis_permiso p', 'p.pero_ide = r.rp_pero_ide')
            ->where('r.rp_perl_ide', $personalId)->where('r.deleted_at', null)
            ->where('r.rp_fecha >=', $inicio)->where('r.rp_fecha <=', $fin)
            ->get()->getResultArray();
        $porFecha = array_column($registros, null, 'fecha');
        $dias = [];
        $nombres = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
        for ($fecha = new \DateTimeImmutable($inicio); $fecha->format('Y-m-d') <= $fin; $fecha = $fecha->modify('+1 day')) {
            $key = $fecha->format('Y-m-d');
            $dia = $porFecha[$key] ?? ['fecha' => $key, 'turnos' => [], 'marcaciones' => []];
            $dia['dia_nombre'] = $nombres[(int) $fecha->format('w')];
            $dia['es_finde'] = (int) $fecha->format('N') >= 6;
            $dia['licencias'] = array_values(array_filter($licencias, static fn($r) => $r['rl_fecha_inicio'] <= $key && $r['rl_fecha_fin'] >= $key));
            $dia['permisos'] = array_values(array_filter($permisos, static fn($r) => $r['rp_fecha'] === $key));
            $dias[] = $dia;
        }
        return $dias;
    }
}

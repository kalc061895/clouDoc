<?php

namespace Modules\Asistencia\Services;

use DateTimeImmutable;

class AusenciasRolService
{
    public function __construct(private \CodeIgniter\Database\BaseConnection $db)
    {
    }

    public function porPersonal(array $ids, string $mes): array
    {
        if (!$ids) return [];
        $inicio = $mes . '-01';
        $fin = (new DateTimeImmutable($inicio))->format('Y-m-t');
        $licencias = $this->db->table('casis_registro_licencia r')
            ->select('r.rl_perl_ide AS personal, r.rl_fecha_inicio AS inicio, r.rl_fecha_fin AS fin, r.rl_numero_documento AS documento, l.lic_nombre AS nombre')
            ->join('casis_licencia l', 'l.lic_ide = r.rl_lic_ide', 'left')
            ->whereIn('r.rl_perl_ide', $ids)->where('r.deleted_at', null)->where('r.rl_estado', 1)
            ->where('r.rl_fecha_inicio <=', $fin)->where('r.rl_fecha_fin >=', $inicio)
            ->orderBy('r.rl_fecha_inicio')->orderBy('r.rl_ide')->get()->getResultArray();
        $vacaciones = $this->db->table('casis_registro_vacacion')
            ->select('rv_perl_ide AS personal, rv_fecha_inicio AS inicio, rv_fecha_fin AS fin, rv_numero_documento AS documento')
            ->whereIn('rv_perl_ide', $ids)->where('deleted_at', null)->where('rv_estado', 1)
            ->where('rv_fecha_inicio <=', $fin)->where('rv_fecha_fin >=', $inicio)
            ->orderBy('rv_fecha_inicio')->orderBy('rv_ide')->get()->getResultArray();
        $permisos = $this->db->table('casis_registro_permiso r')
            ->select('r.rp_perl_ide AS personal, r.rp_fecha AS inicio, r.rp_fecha AS fin, r.rp_numero_documento AS documento, r.rp_hora_salida, r.rp_hora_retorno, r.rp_estado, p.pero_nombre AS nombre')
            ->join('casis_permiso p', 'p.pero_ide = r.rp_pero_ide', 'left')
            ->whereIn('r.rp_perl_ide', $ids)->where('r.deleted_at', null)
            ->whereNotIn('r.rp_estado', ['0', 'ANULADO', 'RECHAZADO', 'CANCELADO'])
            ->where('r.rp_fecha >=', $inicio)->where('r.rp_fecha <=', $fin)
            ->orderBy('r.rp_fecha')->orderBy('r.rp_ide')->get()->getResultArray();

        $resultado = [];
        foreach (['Licencia' => $licencias, 'Vacaciones' => $vacaciones, 'Permiso' => $permisos] as $tipo => $filas) {
            foreach ($filas as $fila) {
                $resultado[(int) $fila['personal']][] = [
                    'nombre' => trim($fila['nombre'] ?? '') ?: $tipo,
                    'inicio' => max($inicio, $fila['inicio']),
                    'fin' => min($fin, $fila['fin']),
                    'documento' => trim($fila['documento'] ?? ''),
                    'por_horas' => $tipo === 'Permiso',
                    'horario' => $tipo === 'Permiso' ? substr($fila['rp_hora_salida'], 0, 5) . '–' . substr($fila['rp_hora_retorno'], 0, 5) : '',
                    'estado' => ($fila['rp_estado'] ?? '') === 'PENDIENTE' ? 'Pendiente' : '',
                ];
            }
        }
        return $resultado;
    }

    /** Mantiene la lectura de versiones emitidas antes de capturar ausencias en el envío. */
    public function completarSnapshot(array $miembros, string $mes): array
    {
        $ids = [];
        foreach ($miembros as $miembro) {
            if (!array_key_exists('ausencias', $miembro)) {
                $ids[] = (int) $miembro['personal']['perl_ide'];
            }
        }
        if (!$ids) return $miembros;
        $ausencias = $this->porPersonal($ids, $mes);
        foreach ($miembros as &$miembro) {
            $miembro['ausencias'] ??= $ausencias[(int) $miembro['personal']['perl_ide']] ?? [];
        }
        unset($miembro);
        return $miembros;
    }

    public static function calendario(array $ausencias, array $turnosPorDia, int $diasMes): array
    {
        $dias = [];
        $tramos = [];
        $cubiertos = [];
        $alertas = [];
        foreach ($ausencias as $ausencia) {
            $desde = (int) substr($ausencia['inicio'], 8, 2);
            $hasta = (int) substr($ausencia['fin'], 8, 2);
            $detalle = $ausencia['nombre'];
            foreach ([$ausencia['horario'], $ausencia['estado'], $ausencia['documento']] as $dato) {
                if ($dato !== '') $detalle .= ' · ' . $dato;
            }
            $coincidencias = [];
            for ($dia = $desde; $dia <= $hasta; $dia++) {
                $dias[$dia][] = $detalle;
                if (!empty($turnosPorDia[$dia])) $coincidencias[] = $dia;
                if (!$ausencia['por_horas']) $cubiertos[$dia] = true;
            }
            if ($coincidencias) {
                $fechas = (count($coincidencias) === 1 ? 'el día ' : 'los días ') . implode(', ', $coincidencias);
                $alertas[] = 'Turno y ausencia registrada ' . $fechas . '. ' . $detalle;
            }
        }

        for ($dia = 1; $dia <= $diasMes; $dia++) {
            if (empty($dias[$dia]) || !empty($turnosPorDia[$dia])) continue;
            $fin = $dia;
            while ($fin < $diasMes && empty($turnosPorDia[$fin + 1]) && ($dias[$fin + 1] ?? []) === $dias[$dia]) {
                $fin++;
            }
            $tramos[$dia] = ['dias' => $fin - $dia + 1, 'detalle' => implode(' / ', $dias[$dia])];
            $dia = $fin;
        }

        return ['dias' => $dias, 'tramos' => $tramos, 'alertas' => $alertas, 'cubiertos' => $cubiertos];
    }
}

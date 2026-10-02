<?php

namespace Modules\Asistencia\Services;

class DashboardData
{
    public static function resumir(array $personal, array $turnos, array $marcas, array $licencias, array $permisos, array $vacaciones, array $profesiones, string $fecha, \DateTimeImmutable $now): array
    {
        $listas = array_fill_keys(['pendientes', 'licencias', 'vacaciones', 'comisiones', 'cumpleanos', 'papeletas'], []);
        $grupos = array_fill_keys(['unidad', 'servicio', 'profesion', 'sexo', 'modalidad'], []);
        $prof = $just = $byTurno = $byMarca = [];
        $stats = array_fill_keys(['personal', 'programados', 'ingresaron', 'pendientes', 'futuro', 'posibles_faltas', 'justificados', 'sin_programacion', 'marcaciones_sin_tipo'], 0);
        $personas = array_column($personal, null, 'perl_ide');
        $cutoff = $now->format('Y-m-d H:i:s');
        $nombre = static fn($p) => trim(($p['per_paterno'] ?? '') . ' ' . ($p['per_materno'] ?? '') . ' ' . ($p['per_nombre'] ?? ''));
        foreach ($profesiones as $r) $prof[$r['pp_perl_ide']] ??= $r['pro_nombre'] ?: 'Sin profesión';
        foreach ($turnos as $r) $byTurno[$r['prog_perl_ide']][] = $r;
        foreach ($marcas as $r) {
            if ($r['asi_fecha_hora'] > $now->format('Y-m-d H:i:s')) continue;
            $byMarca[$r['asi_perl_ide']][] = $r;
            if (substr($r['asi_fecha_hora'], 0, 10) === $fecha && empty($r['asi_tipo'])) $stats['marcaciones_sin_tipo']++;
        }
        foreach ([['licencias', $licencias, 'rl'], ['vacaciones', $vacaciones, 'rv']] as [$key, $rows, $prefix]) {
            foreach ($rows as $r) {
                $id = $r[$prefix . '_perl_ide'];
                if (!isset($personas[$id])) continue;
                $just[$id] = true;
                $listas[$key][$id] = ['nombre' => $nombre($personas[$id]), 'detalle' => ($r['lic_nombre'] ?? 'Vacaciones') . ' · hasta ' . $r[$prefix . '_fecha_fin']];
                if ($key === 'licencias' && stripos(str_replace('ó', 'o', mb_strtolower($r['lic_nombre'] ?? '')), 'comision') !== false) $listas['comisiones'][$id] = $listas[$key][$id];
            }
        }
        foreach ($permisos as $r) {
            $id = $r['rp_perl_ide'];
            if (!isset($personas[$id])) continue;
            $estado = (string) $r['rp_estado'] === '1' ? 'Registrado' : $r['rp_estado'];
            $item = ['nombre' => $nombre($personas[$id]), 'detalle' => ($r['pero_nombre'] ?? 'Permiso') . ' · ' . ($r['rp_hora_salida'] ?? '') . '–' . ($r['rp_hora_retorno'] ?? '') . ' · ' . $estado];
            $listas['papeletas'][] = $item;
            if (stripos(str_replace('ó', 'o', mb_strtolower($r['pero_nombre'] ?? '')), 'comision') !== false && in_array((string) $r['rp_estado'], ['1', 'APROBADO'], true)) $listas['comisiones'][$id] = $item;
        }
        foreach ($personal as $p) {
            $id = $p['perl_ide']; $stats['personal']++;
            $ts = $byTurno[$id] ?? []; $entered = false; $missing = []; $future = false; $ended = false;
            foreach ($ts as $t) {
                $start = $fecha . ' ' . ($t['th_hora_ingreso'] ?: '00:00:00');
                $end = $fecha . ' ' . ($t['th_hora_salida'] ?: '23:59:59');
                if ($end <= $start) $end = date('Y-m-d H:i:s', strtotime($end . ' +1 day'));
                $matched = false;
                // Una entrada explícita dentro del intervalo del turno, con hasta dos horas de anticipación.
                $early = date('Y-m-d H:i:s', strtotime($start . ' -2 hours'));
                foreach ($byMarca[$id] ?? [] as $m) {
                    if (in_array(strtoupper(trim($m['asi_tipo'] ?? '')), ['ENTRADA', 'INGRESO'], true) && $m['asi_fecha_hora'] >= $early && $m['asi_fecha_hora'] <= min($end, $cutoff)) $matched = true;
                }
                $entered = $entered || $matched;
                if (!$matched && !isset($just[$id])) {
                    if ($start > $cutoff) $future = true;
                    else { $missing[] = ($t['tur_codigo'] ?? '') . ' ' . substr($start, 11, 5) . ' · ' . ($t['uss_nombre'] ?: 'Sin servicio'); $ended = $ended || $end < $cutoff; }
                }
                $key = ($p['est_nombre'] ?: 'Sin establecimiento') . ' / ' . ($t['ups_nombre'] ?: 'Sin UPSS') . ' / ' . ($t['uss_nombre'] ?: 'Sin servicio');
                $grupos['servicio'][$key]['ids'][$id] = true;
                if ($matched) $grupos['servicio'][$key]['entradas'][$id] = true;
            }
            if ($ts) $stats['programados']++;
            if ($entered && $ts) $stats['ingresaron']++;
            if ($ts && isset($just[$id])) $stats['justificados']++;
            if ($missing) {
                $stats['pendientes']++; $stats['posibles_faltas'] += (int) $ended;
                $listas['pendientes'][] = ['nombre' => $nombre($p), 'detalle' => implode(' / ', $missing) . ($ended ? ' · Turno concluido: revisar posible falta' : ' · Sin entrada registrada')];
            } elseif ($future) $stats['futuro']++;
            if (!$ts) {
                foreach ($byMarca[$id] ?? [] as $m) if (substr($m['asi_fecha_hora'], 0, 10) === $fecha && in_array(strtoupper($m['asi_tipo'] ?? ''), ['ENTRADA', 'INGRESO'], true)) { $stats['sin_programacion']++; break; }
            }
            $sexo = mb_strtoupper(trim($p['per_sexo'] ?? ''));
            $labels = ['unidad' => ($p['est_nombre'] ?: 'Sin establecimiento') . ' / ' . ($p['ofi_nombre'] ?: 'Sin unidad'), 'profesion' => $prof[$id] ?? 'Sin profesión', 'modalidad' => $p['mco_nombre'] ?: 'Sin modalidad', 'sexo' => in_array($sexo, ['M', 'MASCULINO', 'HOMBRE'], true) ? 'Hombres' : (in_array($sexo, ['F', 'FEMENINO', 'MUJER'], true) ? 'Mujeres' : 'Sin especificar')];
            foreach ($labels as $key => $label) { $grupos[$key][$label]['ids'][$id] = true; if ($entered) $grupos[$key][$label]['entradas'][$id] = true; }
            if (substr($p['per_fecha_nacimiento'] ?? '', 5, 5) === substr($fecha, 5)) $listas['cumpleanos'][] = ['nombre' => $nombre($p), 'detalle' => $p['ofi_nombre'] ?: 'Sin unidad'];
        }
        foreach ($grupos as &$group) {
            foreach ($group as &$r) $r = ['total' => count($r['ids']), 'ingresos' => count($r['entradas'] ?? [])];
            unset($r); uasort($group, static fn($a, $b) => $b['total'] <=> $a['total']);
        }
        unset($group);
        $stats['cobertura'] = $stats['programados'] ? round(100 * $stats['ingresaron'] / $stats['programados'], 1) : null;
        return ['fecha' => $fecha, 'generado' => $now->format('d/m/Y H:i'), 'stats' => $stats, 'listas' => $listas, 'grupos' => $grupos];
    }
}

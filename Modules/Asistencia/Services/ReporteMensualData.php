<?php

namespace Modules\Asistencia\Services;

class ReporteMensualData
{
    public const NOTA = 'Resumen informativo, no liquidación de asistencia. Marcaciones por fecha calendario (incluye origen manual); no se infieren horas trabajadas, faltas ni tardanzas. Los turnos nocturnos se cuentan en su fecha de inicio. Licencias y vacaciones: días calendario únicos dentro del mes, por categoría. Permisos: registros no anulados/rechazados, incluidos pendientes; sus horas son la suma de los intervalos registrados. La ubicación institucional es la actual. UPSS/servicio filtran turnos y trabajadores con programación en ese ámbito; las marcaciones e incidencias corresponden al mes completo de esos trabajadores.';

    public static function filtros(array $input): array
    {
        $f = [];
        foreach (['anio' => [2000, 2100, date('Y')], 'mes' => [1, 12, date('n')], 'dir_ide' => [1, PHP_INT_MAX, null], 'red_ide' => [1, PHP_INT_MAX, null], 'mic_ide' => [1, PHP_INT_MAX, null], 'est_ide' => [1, PHP_INT_MAX, null], 'ups_ide' => [1, PHP_INT_MAX, null], 'uss_ide' => [1, PHP_INT_MAX, null], 'tofi_ide' => [1, PHP_INT_MAX, null], 'ofi_ide' => [1, PHP_INT_MAX, null]] as $key => [$min, $max, $default]) {
            $v = $input[$key] ?? $default;
            if ($v === '' || $v === null) { $f[$key] = $default === null ? null : (int) $default; continue; }
            if (! is_scalar($v) || ! ctype_digit((string) $v) || (int) $v < $min || (int) $v > $max) throw new \InvalidArgumentException('Filtro inválido: ' . $key);
            $f[$key] = (int) $v;
        }
        $dni = $input['dni'] ?? '';
        if (! is_string($dni) || mb_strlen($dni) > 20) throw new \InvalidArgumentException('Documento de identidad inválido.');
        $f['dni'] = trim($dni);
        $f['incluir_hijos'] = in_array($input['incluir_hijos'] ?? '', ['1', 1, true], true);
        $f['detalle'] = in_array($input['detalle'] ?? '', ['1', 1, true], true);
        return $f;
    }

    public static function horas(?string $inicio, ?string $fin): float
    {
        if (! $inicio || ! $fin) return 0;
        $a = strtotime('2000-01-01 ' . $inicio);
        $b = strtotime('2000-01-01 ' . $fin);
        if ($a === false || $b === false) return 0;
        if ($b < $a) $b += 86400;
        return ($b - $a) / 3600;
    }

    public static function resumir(array $personal, array $turnos, array $marcas, array $licencias, array $permisos, array $vacaciones, array $f): array
    {
        $inicio = sprintf('%04d-%02d-01', $f['anio'], $f['mes']);
        $fin = date('Y-m-t', strtotime($inicio));
        $filas = [];
        foreach ($personal as $p) {
            $p['trabajador'] = trim($p['per_paterno'] . ' ' . $p['per_materno'] . ' ' . $p['per_nombre']);
            $p['dias'] = []; $p['conteos'] = [];
            foreach (['turnos', 'horas', 'marcaciones', 'dias_marcados', 'dias_licencia', 'permisos', 'horas_permiso', 'dias_vacacion'] as $key) $p[$key] = 0;
            for ($d = 1; $d <= (int) substr($fin, 8); $d++) {
                $fecha = sprintf('%04d-%02d-%02d', $f['anio'], $f['mes'], $d);
                $p['dias'][$fecha] = ['turnos' => [], 'marcaciones' => [], 'licencias' => [], 'permisos' => [], 'vacaciones' => []];
            }
            $filas[$p['perl_ide']] = $p;
        }
        foreach ($turnos as $t) {
            $id = $t['prog_perl_ide']; $fecha = $t['prog_fecha'];
            if (! isset($filas[$id]['dias'][$fecha])) continue;
            $filas[$id]['turnos']++;
            $filas[$id]['horas'] += self::horas($t['th_hora_ingreso'], $t['th_hora_salida']);
            $code = $t['tur_codigo'];
            $filas[$id]['conteos'][$code] = ($filas[$id]['conteos'][$code] ?? 0) + 1;
            $filas[$id]['dias'][$fecha]['turnos'][] = $code . ' ' . substr($t['th_hora_ingreso'] ?? '', 0, 5) . '–' . substr($t['th_hora_salida'] ?? '', 0, 5)
                . ' [' . ($t['ups_nombre'] ?? 'Sin UPSS') . ' / ' . ($t['uss_nombre'] ?? 'Sin servicio') . '] (' . $t['prog_estado'] . ')';
        }
        foreach ($marcas as $m) {
            $id = $m['asi_perl_ide']; $fecha = substr($m['asi_fecha_hora'], 0, 10);
            if (! isset($filas[$id]['dias'][$fecha])) continue;
            $filas[$id]['dias'][$fecha]['marcaciones'][] = substr($m['asi_fecha_hora'], 11) . ' ' . ($m['asi_tipo'] ?? '') . ' [' . ($m['asi_dispositivo'] ?: 'Sin reloj') . ' / ' . $m['asi_origen'] . ']';
            $filas[$id]['marcaciones']++;
        }
        foreach ([['licencias', $licencias, 'rl'], ['vacaciones', $vacaciones, 'rv']] as [$kind, $items, $prefix]) {
            foreach ($items as $r) {
                $id = $r[$prefix . '_perl_ide'];
                if (! isset($filas[$id])) continue;
                $from = max($inicio, $r[$prefix . '_fecha_inicio']); $to = min($fin, $r[$prefix . '_fecha_fin']);
                for ($fecha = $from; $fecha <= $to; $fecha = date('Y-m-d', strtotime($fecha . ' +1 day'))) {
                    $filas[$id]['dias'][$fecha][$kind][] = ($r['lic_nombre'] ?? 'Vacaciones') . ' [' . ($r[$prefix . '_numero_documento'] ?? '') . ']';
                }
            }
        }
        foreach ($permisos as $r) {
            $id = $r['rp_perl_ide']; $fecha = $r['rp_fecha'];
            if (! isset($filas[$id]['dias'][$fecha])) continue;
            $filas[$id]['permisos']++;
            $filas[$id]['horas_permiso'] += self::horas($r['rp_hora_salida'], $r['rp_hora_retorno']);
            $filas[$id]['dias'][$fecha]['permisos'][] = $r['pero_nombre'] . ' ' . substr($r['rp_hora_salida'] ?? '', 0, 5) . '–' . substr($r['rp_hora_retorno'] ?? '', 0, 5) . ' (' . $r['rp_estado'] . ')';
        }
        foreach ($filas as &$p) {
            foreach ($p['dias'] as $dia) {
                $p['dias_marcados'] += (int) ! empty($dia['marcaciones']);
                $p['dias_licencia'] += (int) ! empty($dia['licencias']);
                $p['dias_vacacion'] += (int) ! empty($dia['vacaciones']);
            }
            $p['horas'] = round($p['horas'], 2); $p['horas_permiso'] = round($p['horas_permiso'], 2);
            ksort($p['conteos']);
            $p['turnos_resumen'] = implode(', ', array_map(static fn($code, $count) => $code . ': ' . $count, array_keys($p['conteos']), $p['conteos']));
        }
        unset($p);
        return ['filas' => array_values($filas), 'filtros' => $f, 'inicio' => $inicio, 'fin' => $fin, 'generado' => date('Y-m-d H:i:s'), 'nota' => self::NOTA];
    }
}

<?php

namespace Modules\Asistencia\Services;

/** Reglas de identificación; no contiene tarifas ni reglas de remuneración. */
class InterpretacionHorario
{
    public const DEFAULTS = [
        'th_interpretacion' => 'MANUAL', 'th_tipo_jornada' => 'ORDINARIA',
        'th_refrigerio_marcacion' => 'NO_DEFINIDO',
        'th_margen_marcacion' => 120, 'th_margen_refrigerio' => 30,
    ];

    public static function validar(array $datos): array
    {
        $errores = [];
        foreach ([
            'th_interpretacion' => ['MANUAL', 'AUTO'],
            'th_tipo_jornada' => ['ORDINARIA', 'GUARDIA_DIURNA', 'GUARDIA_NOCTURNA', 'OTRA'],
            'th_refrigerio_marcacion' => ['NO_DEFINIDO', 'SIN_REFRIGERIO', 'SIN_MARCAS', 'CON_MARCAS'],
        ] as $campo => $opciones) {
            if (!in_array($datos[$campo] ?? self::DEFAULTS[$campo], $opciones, true)) $errores[$campo] = 'Seleccione una opción válida.';
        }
        foreach (['th_margen_marcacion' => 360, 'th_margen_refrigerio' => 120, 'th_tolerancia_ingreso' => 60, 'th_tolerancia_salida' => 60] as $campo => $max) {
            $v = $datos[$campo] ?? self::DEFAULTS[$campo] ?? 0;
            if (filter_var($v, FILTER_VALIDATE_INT) === false || $v < 0 || $v > $max) $errores[$campo] = "Indique minutos entre 0 y $max.";
        }
        foreach (['th_hora_ingreso', 'th_hora_salida', 'th_refrigerio_salida', 'th_refrigerio_retorno'] as $campo) {
            $v = $datos[$campo] ?? '';
            if ($v === '' || $v === null) {
                if (in_array($campo, ['th_hora_ingreso', 'th_hora_salida'], true)) $errores[$campo] = 'La hora es obligatoria.';
            } elseif (!is_string($v) || !preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/D', $v)) $errores[$campo] = 'Hora inválida.';
        }
        if ($errores) return $errores;
        $a = strtotime('2000-01-01 ' . $datos['th_hora_ingreso']);
        $b = strtotime('2000-01-01 ' . $datos['th_hora_salida']);
        if ($a === $b) $errores['th_hora_salida'] = 'Ingreso y salida deben ser distintos.';
        if ($b < $a) $b += 86400;
        $salida = $datos['th_refrigerio_salida'] ?? null;
        $retorno = $datos['th_refrigerio_retorno'] ?? null;
        $modo = $datos['th_refrigerio_marcacion'] ?? 'NO_DEFINIDO';
        if ((bool)$salida !== (bool)$retorno || (in_array($modo, ['CON_MARCAS', 'SIN_MARCAS'], true) && !$salida)) {
            $errores['th_refrigerio_salida'] = 'Complete ambas horas del refrigerio.';
        } elseif ($salida && $retorno) {
            [$s, $r] = self::pausa($datos, $a);
            if ($s <= $a || $r <= $s || $r >= $b) $errores['th_refrigerio_salida'] = 'El refrigerio debe estar dentro de la jornada y su retorno después de la salida.';
            if ($modo === 'SIN_REFRIGERIO') $errores['th_refrigerio_marcacion'] = 'Quite las horas de refrigerio o seleccione su modalidad.';
        }
        if (($datos['th_interpretacion'] ?? 'MANUAL') === 'AUTO' && $modo === 'NO_DEFINIDO') $errores['th_refrigerio_marcacion'] = 'Defina el refrigerio antes de activar la interpretación automática.';
        return $errores;
    }

    private static function pausa(array $horario, int $inicio): array
    {
        $s = strtotime(date('Y-m-d', $inicio) . ' ' . $horario['th_refrigerio_salida']);
        $r = strtotime(date('Y-m-d', $inicio) . ' ' . $horario['th_refrigerio_retorno']);
        if ($s < $inicio) $s += 86400;
        if ($r < $inicio) $r += 86400;
        return [$s, $r];
    }

    /** Solo asigna cuando todas las marcas tienen una correspondencia única. */
    public static function interpretar(array $marcas, array $horario, int $inicio, int $fin, bool $ambigua): array
    {
        $horario += self::DEFAULTS;
        $salida = ['aplicada' => false, 'razon' => '', 'marcas' => $marcas];
        if (($horario['th_interpretacion'] ?? 'MANUAL') !== 'AUTO') return $salida;
        if ($ambigua || self::validar($horario)) {
            $salida['razon'] = 'Horario inválido o marcaciones compartidas entre turnos.';
            return $salida;
        }
        $margen = (int)$horario['th_margen_marcacion'] * 60;
        if ($horario['th_tipo_jornada'] === 'ORDINARIA' && $horario['th_refrigerio_marcacion'] === 'SIN_REFRIGERIO' && count($marcas) > 2) {
            return self::agruparExtremos($marcas, $inicio, $fin, $margen);
        }
        $eventos = [[$inicio, 'ENTRADA', 'Ingreso al turno', $margen]];
        if ($horario['th_refrigerio_marcacion'] === 'CON_MARCAS') {
            [$s, $r] = self::pausa($horario, $inicio);
            $m = (int)$horario['th_margen_refrigerio'] * 60;
            $eventos[] = [$s, 'SALIDA', 'Salida a refrigerio', $m];
            $eventos[] = [$r, 'ENTRADA', 'Retorno de refrigerio', $m];
        }
        $eventos[] = [$fin, 'SALIDA', 'Salida del turno', $margen];
        if (count($marcas) !== count($eventos)) {
            $salida['razon'] = 'La cantidad de marcas no coincide con los eventos del horario.';
            return $salida;
        }
        $resultado = [];
        foreach (array_values($marcas) as $i => $marca) {
            $ts = strtotime($marca['asi_fecha_hora']);
            $candidatos = [];
            foreach ($eventos as $j => [$hora, $tipo, $evento, $m]) {
                if (abs($ts - $hora) <= $m && (empty($marca['asi_tipo']) || $marca['asi_tipo'] === $tipo)) $candidatos[] = $j;
            }
            if ($candidatos !== [$i] || ($i > 0 && $ts <= strtotime($resultado[$i - 1]['asi_fecha_hora']))) {
                $salida['razon'] = 'Hay eventos ambiguos, tipos contradictorios o marcas fuera de los márgenes.';
                return $salida;
            }
            $marca['asi_tipo'] = $eventos[$i][1];
            $marca['interpretacion_automatica'] = ['tipo' => $eventos[$i][1], 'evento' => $eventos[$i][2], 'regla' => 'HORARIO_V1'];
            $resultado[] = $marca;
        }
        return ['aplicada' => true, 'razon' => '', 'marcas' => $resultado];
    }

    private static function agruparExtremos(array $marcas, int $inicio, int $fin, int $margen): array
    {
        $pendiente = ['aplicada' => false, 'razon' => 'No se pueden agrupar las marcas: faltan grupos, hay tipos contradictorios o marcas fuera de margen o ambiguas.', 'marcas' => $marcas];
        $grupos = ['ENTRADA' => [], 'SALIDA' => []];
        usort($marcas, static fn($a, $b) => strcmp($a['asi_fecha_hora'], $b['asi_fecha_hora']) ?: ((int)($a['asi_ide'] ?? 0) <=> (int)($b['asi_ide'] ?? 0)));
        foreach ($marcas as $marca) {
            $ts = strtotime($marca['asi_fecha_hora']);
            $ingreso = abs($ts - $inicio) <= $margen;
            $salida = abs($ts - $fin) <= $margen;
            if ($ingreso === $salida) return $pendiente;
            $tipo = $ingreso ? 'ENTRADA' : 'SALIDA';
            if (!empty($marca['asi_tipo']) && $marca['asi_tipo'] !== $tipo) return $pendiente;
            $grupos[$tipo][] = $marca;
        }
        if (!$grupos['ENTRADA'] || !$grupos['SALIDA']) return $pendiente;
        $entrada = array_shift($grupos['ENTRADA']);
        $salida = array_pop($grupos['SALIDA']);
        if ($entrada['asi_fecha_hora'] >= $salida['asi_fecha_hora']) return $pendiente;
        $elegidas = [];
        $agrupadas = [];
        foreach (['ENTRADA' => $entrada, 'SALIDA' => $salida] as $tipo => $elegida) {
            $elegida['asi_tipo'] = $tipo;
            $elegida['interpretacion_automatica'] = ['tipo' => $tipo,
                'evento' => $tipo === 'ENTRADA' ? 'Primer ingreso del grupo' : 'Última salida del grupo',
                'regla' => 'EXTREMOS_SIN_REFRIGERIO_V1'];
            $elegidas[] = $elegida;
            foreach ($grupos[$tipo] as $marca) {
                $marca['grupo'] = $tipo;
                $marca['marca_elegida_id'] = $elegida['asi_ide'] ?? null;
                $marca['hora_elegida'] = $elegida['asi_fecha_hora'];
                $marca['regla'] = 'EXTREMOS_SIN_REFRIGERIO_V1';
                $agrupadas[] = $marca;
            }
        }
        return ['aplicada' => true, 'razon' => '', 'marcas' => $elegidas, 'agrupadas' => $agrupadas];
    }
}

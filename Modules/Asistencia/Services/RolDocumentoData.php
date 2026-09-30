<?php

namespace Modules\Asistencia\Services;

/** Cálculos del documento independientes de la base de datos y del motor PDF. */
class RolDocumentoData
{
    public const MESES = [1 => 'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];

    public static function filtros(array $input): array
    {
        $result = [];
        foreach (['anio', 'mes', 'est_ide', 'tofi_ide', 'ofi_ide', 'ups_ide', 'uss_ide'] as $key) {
            $value = $input[$key] ?? '';
            if ($value === '' || $value === null) {
                $result[$key] = null;
            } elseif (! is_scalar($value) || ! ctype_digit((string) $value) || (int) $value < 1 || (int) $value > 2147483647) {
                throw new \InvalidArgumentException('Filtro inválido: ' . $key);
            } else {
                $result[$key] = (int) $value;
            }
        }
        if ($result['anio'] < 2000 || $result['anio'] > 2100 || $result['mes'] < 1 || $result['mes'] > 12 || ! $result['est_ide']) {
            throw new \InvalidArgumentException('Seleccione año (2000–2100), mes y establecimiento.');
        }
        $result['incluir_hijos'] = in_array($input['incluir_hijos'] ?? '0', ['1', 1, true], true);
        return $result;
    }

    /** La oficina seleccionada y sus descendientes, sin salir del catálogo del establecimiento. */
    public static function oficinasIncluidas(array $oficinas, ?int $oficina, ?int $tipo, bool $hijos): ?array
    {
        $index = array_column($oficinas, null, 'ofi_ide');
        if ($oficina && (! isset($index[$oficina]) || ($tipo && (int) $index[$oficina]['ofi_tofi_ide'] !== $tipo))) {
            throw new \InvalidArgumentException('La oficina no corresponde al establecimiento o al tipo seleccionado.');
        }
        if (! $oficina && ! $tipo) {
            return null;
        }
        $ids = $oficina ? [$oficina] : array_map('intval', array_column(array_filter($oficinas, static fn ($o) => (int) $o['ofi_tofi_ide'] === $tipo), 'ofi_ide'));
        if ($hijos) {
            do {
                $before = count($ids);
                foreach ($oficinas as $o) {
                    if (in_array((int) $o['ofi_padre_ide'], $ids, true) && ! in_array((int) $o['ofi_ide'], $ids, true)) {
                        $ids[] = (int) $o['ofi_ide'];
                    }
                }
            } while (count($ids) !== $before);
        }
        return $ids;
    }

    public static function color(?string $color): string
    {
        $color = trim($color ?? '');
        if (preg_match('/^#[0-9a-f]{3}$/i', $color)) {
            return '#' . $color[1] . $color[1] . $color[2] . $color[2] . $color[3] . $color[3];
        }
        return preg_match('/^#[0-9a-f]{6}$/i', $color) ? strtoupper($color) : '#FFFFFF';
    }

    public static function tinta(string $color): string
    {
        [$r, $g, $b] = sscanf(self::color($color), '#%02x%02x%02x');
        return ($r * 299 + $g * 587 + $b * 114) / 1000 > 145 ? '#111827' : '#FFFFFF';
    }

    public static function preparar(array $matriz, array $cabecera, array $filtros): array
    {
        $codigos = ['GD', 'GN', 'M', 'T', 'N'];
        $leyenda = [];
        $total = 0.0;
        foreach ($matriz['matriz'] as &$persona) {
            $persona['conteos'] = [];
            $persona['total_horas'] = 0.0;
            foreach ($persona['dias'] as &$turnos) {
                foreach ($turnos as &$turno) {
                    $codigo = trim((string) $turno['tur_codigo']);
                    $turno['tur_codigo'] = $codigo;
                    $turno['tur_color'] = self::color($turno['tur_color'] ?? null);
                    $turno['tinta'] = self::tinta($turno['tur_color']);
                    $persona['conteos'][$codigo] = ($persona['conteos'][$codigo] ?? 0) + 1;
                    $persona['total_horas'] += (float) $turno['duracion_horas'];
                    if (! in_array($codigo, $codigos, true)) {
                        $codigos[] = $codigo;
                    }
                    $key = $codigo . '|' . $turno['th_hora_ingreso'] . '|' . $turno['th_hora_salida'];
                    $leyenda[$key] = $turno;
                }
                unset($turno);
            }
            unset($turnos);
            $persona['total_horas'] = round($persona['total_horas'], 2);
            $total += $persona['total_horas'];
        }
        unset($persona);
        ksort($leyenda);
        return [
            'cabecera' => $cabecera, 'filtros' => $filtros,
            'mes_nombre' => self::MESES[$filtros['mes']],
            'dias' => $matriz['dias'], 'dias_mes' => $matriz['dias_mes'],
            'personal' => $matriz['matriz'], 'codigos' => $codigos,
            'leyenda' => array_values($leyenda), 'total_horas' => round($total, 2),
        ];
    }

    public static function huella(array $documento): string
    {
        return hash('sha256', json_encode($documento, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
    }

    /** Reserva espacio para cabecera, leyenda y firmas; considera turnos apilados. */
    public static function paginas(array $documento): array
    {
        $paginas = [];
        $filas = [];
        $alto = 0;
        $offset = 0;
        $presupuesto = max(170, 350 - max(0, count($documento['leyenda']) - 6) * 8);
        foreach ($documento['personal'] as $persona) {
            $turnos = max(array_map('count', $persona['dias']) ?: [0]);
            $altoFila = max(30, ceil(mb_strlen($persona['trabajador']) / 28) * 13 + 8, ceil(mb_strlen($persona['cargo']) / 13) * 12 + 8, $turnos * 23 + 6);
            if ($filas && $alto + $altoFila > $presupuesto) {
                $paginas[] = ['filas' => $filas, 'offset' => $offset];
                $offset += count($filas);
                $filas = [];
                $alto = 0;
            }
            $filas[] = $persona;
            $alto += $altoFila;
        }
        if ($filas) $paginas[] = ['filas' => $filas, 'offset' => $offset];
        return $paginas;
    }
}

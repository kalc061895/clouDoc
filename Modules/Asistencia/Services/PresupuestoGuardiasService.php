<?php

namespace Modules\Asistencia\Services;

use CodeIgniter\Database\BaseConnection;
use DateTimeImmutable;
use Modules\Asistencia\Models\RemuneracionModel;

class PresupuestoGuardiasService
{
    public const FILTROS = ['red' => 'Red', 'microred' => 'Microred', 'establecimiento' => 'Establecimiento', 'oficina' => 'Oficina', 'regimen' => 'Régimen laboral'];
    public const COLUMNAS = ['N.º', 'Documento', 'Apellidos y nombres', 'Cargo', 'Nivel', 'GD', 'GN', 'GDF', 'GNF', 'Total guardias', 'Monto total (S/)'];

    public function __construct(private BaseConnection $db)
    {
    }

    public function consultar(array $filtros, ?array $ambito): array
    {
        $base = $this->db->table('casis_rol_envio e')
            ->join('casis_plan_rol r', 'r.id = e.rol_id')
            ->join('casis_plan_periodo p', 'p.id = r.periodo_id')
            ->join('casis_plan_grupo g', 'g.id = p.grupo_id', 'left')
            ->where('e.estado', 'APROBADO_DIRECCION');
        if ($ambito !== null) $base->whereIn('e.id_oficina', $ambito ?: [0]);
        $periodos = (clone $base)->distinct()->select('p.id, p.mes, g.nombre AS grupo')
            ->orderBy('p.mes', 'DESC')->orderBy('p.id', 'DESC')->get()->getResultArray();
        $periodo = array_column($periodos, null, 'id')[$filtros['periodo'] ?? 0] ?? null;
        if (isset($filtros['periodo']) && !$periodo) throw new \DomainException('Seleccione un período aprobado dentro de su ámbito.');
        $opciones = array_fill_keys(array_keys(self::FILTROS), []);
        $resultado = ['periodos' => $periodos, 'periodo' => $periodo, 'opciones' => $opciones, 'filas' => [], 'fuentes' => [], 'filtros' => [], 'totales' => [0, 0, 0, 0, 0, 0], 'pendientes' => 0];
        if (!$periodo) return $resultado;

        $envios = $base->select('e.id, e.rol_id, e.version, e.hash_sha256, e.datos_json, e.id_oficina, e.id_establecimiento,
            o.ofi_nombre, es.est_nombre, m.mic_ide, m.mic_nombre, red.red_ide, red.red_nombre')
            ->join('casis_oficina o', 'o.ofi_ide = e.id_oficina', 'left')
            ->join('casis_establecimiento es', 'es.est_ide = e.id_establecimiento', 'left')
            ->join('casis_microred m', 'm.mic_ide = es.est_mic_ide', 'left')
            ->join('casis_red red', 'red.red_ide = m.mic_red_ide', 'left')
            ->where('p.id', $periodo['id'])->orderBy('e.id', 'DESC')->get()->getResultArray();
        $columnas = [
            'red' => ['red_ide', 'red_nombre'], 'microred' => ['mic_ide', 'mic_nombre'],
            'establecimiento' => ['id_establecimiento', 'est_nombre'], 'oficina' => ['id_oficina', 'ofi_nombre'],
        ];
        $miembros = $fuentes = $revisados = $niveles = $horarios = $regimenes = [];
        foreach ($envios as $envio) {
            if (isset($revisados[$envio['rol_id']])) continue;
            $revisados[$envio['rol_id']] = true;
            $coincide = true;
            foreach ($columnas as $campo => [$id, $nombre]) {
                if ($envio[$id]) $opciones[$campo][$envio[$id]] = $envio[$nombre] ?? ('#' . $envio[$id]);
                if (isset($filtros[$campo]) && $filtros[$campo] !== (int) $envio[$id]) $coincide = false;
            }
            if (!hash_equals($envio['hash_sha256'], hash('sha256', $envio['datos_json']))) {
                throw new \DomainException('Un envío aprobado no superó la verificación de integridad.');
            }
            foreach (json_decode($envio['datos_json'], true, 512, JSON_THROW_ON_ERROR) as $miembro) {
                $perfil = $miembro['personal'];
                $regimen = (int) ($perfil['perl_mco_ide'] ?? 0);
                if ($regimen) $regimenes[$regimen] = true;
                if (!$coincide || (isset($filtros['regimen']) && $filtros['regimen'] !== $regimen)) continue;
                if (!array_key_exists('perl_nivel', $perfil)) $niveles[(int) $perfil['perl_ide']] = null;
                foreach ($miembro['turnos'] as $turno) {
                    if (!isset($turno['th_tipo_jornada']) && !empty($turno['prog_th_ide'])) {
                        $horarios[(int) ($turno['prog_th_ide'] ?? 0)] = null;
                    }
                }
                $miembros[] = $miembro;
                $fuentes[$envio['id']] = array_intersect_key($envio, array_flip(['id', 'version', 'hash_sha256', 'id_oficina']));
            }
        }
        if ($regimenes) $opciones['regimen'] = array_column($this->db->table('casis_modalidad_contrato')
            ->select('mco_ide, mco_nombre')->whereIn('mco_ide', array_keys($regimenes))->get()->getResultArray(), 'mco_nombre', 'mco_ide');
        foreach (array_keys($regimenes) as $id) $opciones['regimen'][$id] ??= '#' . $id;
        if ($niveles) $niveles = array_column($this->db->table('casis_personal')->select('perl_ide, perl_car_ide, perl_nivel')
            ->whereIn('perl_ide', array_keys($niveles))->get()->getResultArray(), null, 'perl_ide');
        if ($horarios) $horarios = array_column($this->db->table('casis_turno_horario')->select('th_ide, th_tipo_jornada')
            ->whereIn('th_ide', array_keys($horarios))->get()->getResultArray(), 'th_tipo_jornada', 'th_ide');
        foreach ($opciones as $campo => &$lista) {
            asort($lista, SORT_NATURAL | SORT_FLAG_CASE);
            if (isset($filtros[$campo])) {
                if (!isset($lista[$filtros[$campo]])) throw new \DomainException('Un filtro está fuera del período o de su ámbito.');
                $resultado['filtros'][$campo] = $lista[$filtros[$campo]];
            }
        }
        unset($lista);

        $inicio = $periodo['mes'] . '-01';
        $fin = (new DateTimeImmutable($inicio))->format('Y-m-t');
        $feriados = $this->db->table('casis_diaferiado')->select('df_fecha, df_repetitivo')->where('deleted_at', null)
            ->groupStart()->where('df_repetitivo', 1)->orGroupStart()->where('df_fecha >=', $inicio)->where('df_fecha <=', $fin)->groupEnd()->groupEnd()
            ->get()->getResultArray();
        $grupos = [];
        $programaciones = [];
        foreach ($miembros as $miembro) {
            $perfil = $miembro['personal'];
            $cargo = (int) ($perfil['perl_car_ide'] ?? 0);
            $actual = $niveles[$perfil['perl_ide']] ?? [];
            $nivelAnterior = (int) ($actual['perl_car_ide'] ?? 0) === $cargo ? ($actual['perl_nivel'] ?? '') : '';
            $nivel = trim((string) (array_key_exists('perl_nivel', $perfil) ? $perfil['perl_nivel'] : $nivelAnterior));
            $clave = (int) $perfil['perl_ide'] . '|' . $cargo . '|' . $nivel;
            $grupos[$clave] ??= ['perfil' => $perfil, 'cargo' => $cargo, 'nivel' => $nivel, 'turnos' => []];
            foreach ($miembro['turnos'] as $turno) {
                $id = (int) ($turno['prog_ide'] ?? 0);
                if ($id && isset($programaciones[$id])) continue;
                if ($id) $programaciones[$id] = true;
                $grupos[$clave]['turnos'][] = $turno;
            }
        }
        $tarifario = new RemuneracionModel($this->db);
        $tarifas = [];
        foreach ($grupos as $grupo) {
            ['perfil' => $perfil, 'cargo' => $cargo, 'nivel' => $nivel, 'turnos' => $turnos] = $grupo;
            $conteo = self::contar($turnos, $feriados, $horarios, $periodo['mes']);
            $clave = $cargo . '|' . $nivel;
            if ($conteo && !array_key_exists($clave, $tarifas)) {
                $tarifas[$clave] = $cargo && $nivel !== '' ? $tarifario->obtenerPorCargoNivel($cargo, $nivel) : null;
            }
            $tarifa = $tarifas[$clave] ?? null;
            $guardias = $tarifa['rem_configuracion']['guardias'] ?? [];
            $monto = self::valorizar($conteo, $guardias);
            $cantidades = [
                $conteo['diurna'] ?? 0, $conteo['nocturna'] ?? 0,
                ($conteo['diurna_domingo'] ?? 0) + ($conteo['diurna_feriado'] ?? 0),
                ($conteo['nocturna_domingo'] ?? 0) + ($conteo['nocturna_feriado'] ?? 0),
                array_sum($conteo), $monto,
            ];
            if ($monto === null) $resultado['pendientes']++;
            foreach ($cantidades as $i => $cantidad) $resultado['totales'][$i] += $cantidad ?? 0;
            $resultado['filas'][] = [
                'personal_id' => (int) $perfil['perl_ide'],
                'valores' => array_merge([$perfil['documento_snapshot'], $perfil['nombre_snapshot'], $perfil['cargo_snapshot'], $nivel], $cantidades),
                'conteo' => $conteo, 'tarifa_id' => $tarifa['rem_ide'] ?? null, 'tarifas' => array_intersect_key($guardias, $conteo),
            ];
        }
        usort($resultado['filas'], static fn(array $a, array $b): int => strnatcasecmp(
            mb_strtolower(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $a['valores'][1]) ?: $a['valores'][1]),
            mb_strtolower(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $b['valores'][1]) ?: $b['valores'][1])
        ) ?: ($a['personal_id'] <=> $b['personal_id']));
        $resultado['opciones'] = $opciones;
        $resultado['fuentes'] = array_values($fuentes);
        $resultado['feriados'] = $feriados;
        $resultado['filtros_ids'] = $filtros;
        return $resultado;
    }

    public static function contar(array $turnos, array $feriados, array $horarios, string $mes): array
    {
        $especiales = [];
        $anuales = [];
        foreach ($feriados as $feriado) {
            $especiales[$feriado['df_fecha']] = true;
            if ($feriado['df_repetitivo']) $anuales[substr($feriado['df_fecha'], 5)] = true;
        }
        $conteo = [];
        foreach ($turnos as $turno) {
            $fecha = $turno['prog_fecha'];
            if (substr($fecha, 0, 7) !== $mes) continue;
            $tipo = $turno['th_tipo_jornada'] ?? '';
            if ($tipo === '' || $tipo === 'ORDINARIA') {
                $tipo = match (strtoupper(trim($turno['tur_codigo'] ?? ''))) {
                    'GD', 'GDF' => 'GUARDIA_DIURNA',
                    'GN', 'GNF' => 'GUARDIA_NOCTURNA',
                    default => $tipo,
                };
                if (($tipo === '' || $tipo === 'ORDINARIA')
                    && preg_match('/^GUARDIA\s+(DIURNA|NOCTURNA)\b/i', trim($turno['tur_nombre'] ?? ''), $partes)) {
                    $tipo = 'GUARDIA_' . strtoupper($partes[1]);
                }
                if ($tipo === '') $tipo = $horarios[$turno['prog_th_ide'] ?? 0] ?? '';
            }
            $clave = match ($tipo) {
                'GUARDIA_DIURNA' => 'diurna',
                'GUARDIA_NOCTURNA' => 'nocturna',
                default => null,
            };
            if ($clave === null) continue;
            if (isset($especiales[$fecha]) || isset($anuales[substr($fecha, 5)])) $clave .= '_feriado';
            elseif ((new DateTimeImmutable($fecha))->format('N') === '7') $clave .= '_domingo';
            $conteo[$clave] = ($conteo[$clave] ?? 0) + 1;
        }
        return $conteo;
    }

    public static function valorizar(array $conteo, array $guardias): ?int
    {
        $centimos = 0;
        foreach ($conteo as $tipo => $cantidad) {
            $tarifa = (string) ($guardias[$tipo] ?? '');
            if (!preg_match('/^\d{1,10}(?:\.\d{1,2})?$/D', $tarifa)) return null;
            [$soles, $decimal] = array_pad(explode('.', $tarifa), 2, '');
            $centimos += $cantidad * ((int) $soles * 100 + (int) str_pad($decimal, 2, '0'));
        }
        return $centimos;
    }
}

<?php

namespace Modules\Asistencia\Services;

use CodeIgniter\Database\BaseConnection;

class AmpliacionRolService
{
    public const MAX_TURNOS = 124;

    private BaseConnection $db;
    private GestionRolService $gestion;

    public function __construct(?BaseConnection $db = null, ?GestionRolService $gestion = null)
    {
        $this->db = $db ?? \Config\Database::connect();
        $this->gestion = $gestion ?? new GestionRolService($this->db);
    }

    public function paraEnvio(int $envioId): array
    {
        $envio = $this->envio($envioId);
        $puedeSolicitar = $this->tieneOficina((int) $envio['id_oficina']);
        $ampliaciones = $this->db->table('casis_rol_ampliacion')->where('envio_id', $envioId)
            ->orderBy('id', 'DESC')->get()->getResultArray();
        $personal = [];
        $horarios = [];
        if ($puedeSolicitar) {
            $mes = $this->mes($envio);
            $ocupados = array_column(array_filter($ampliaciones, static fn(array $a): bool => $a['estado'] !== 'RECHAZADA'), 'personal_id');
            $candidatos = $this->personal($envio)->where('p.perl_fecha_inicio >=', $mes . '-01')
                ->where('p.perl_fecha_inicio <=', $this->finMes($mes))->orderBy('pe.per_paterno')
                ->orderBy('pe.per_materno')->orderBy('pe.per_nombre');
            $excluidos = array_merge($this->personalConTurnosAprobados($envio), $ocupados);
            if ($excluidos) {
                $candidatos->whereNotIn('p.perl_ide', array_unique($excluidos));
            }
            foreach ($candidatos->get()->getResultArray() as $p) {
                $hasta = $this->finVinculo($p, $mes);
                if ($hasta < $p['perl_fecha_inicio']) {
                    continue;
                }
                $personal[(int) $p['perl_ide']] = [
                    'nombre' => $this->nombre($p),
                    'codigo' => $p['perl_codigo'],
                    'desde' => $p['perl_fecha_inicio'],
                    'hasta' => $hasta,
                ];
            }
            foreach ($this->horarios()->orderBy('t.tur_codigo')->orderBy('h.th_codigo')->get()->getResultArray() as $h) {
                $horarios[$h['th_ide']] = sprintf('%s · %s (%s–%s)', $h['tur_codigo'], $h['th_nombre'],
                    substr($h['th_hora_ingreso'], 0, 5), substr($h['th_hora_salida'], 0, 5));
            }
        }
        return [
            'envio' => $envio,
            'puedeSolicitar' => $puedeSolicitar,
            'ampliaciones' => array_map([$this, 'conDatos'], $ampliaciones),
            'personal' => $personal,
            'horarios' => $horarios,
            'desde' => $this->mes($envio) . '-01',
            'hasta' => $this->finMes($this->mes($envio)),
            'maxTurnos' => self::MAX_TURNOS,
        ];
    }

    public function pendientesDireccion(): array
    {
        $oficinas = $this->gestion->oficinas(true, 'DIRECCION');
        if ($oficinas === []) {
            return [];
        }
        $consulta = $this->db->table('casis_rol_ampliacion a')
            ->select('a.id, a.datos_json, a.solicitado_at, e.id_establecimiento, e.anio, e.mes, o.ofi_nombre, es.est_nombre')
            ->join('casis_rol_envio e', 'e.id = a.envio_id')
            ->join('casis_oficina o', 'o.ofi_ide = e.id_oficina')
            ->join('casis_establecimiento es', 'es.est_ide = e.id_establecimiento')
            ->where('a.estado', 'PENDIENTE_DIRECCION')->orderBy('a.id', 'DESC');
        if ($oficinas !== null) {
            $consulta->whereIn('e.id_oficina', $oficinas);
        }
        return array_map([$this, 'conDatos'], $consulta->get()->getResultArray());
    }

    public function detalle(int $id): array
    {
        $ampliacion = $this->obtener($id);
        $envio = $this->envio((int) $ampliacion['envio_id']);
        $esDireccion = $this->tieneOficina((int) $envio['id_oficina'], 'DIRECCION');
        return [
            'ampliacion' => $this->conDatos($ampliacion),
            'envio' => $envio,
            'esDireccion' => $esDireccion,
            'puedeResolver' => $ampliacion['estado'] === 'PENDIENTE_DIRECCION' && $esDireccion
                && (int) $ampliacion['solicitado_por'] !== $this->gestion->usuario(),
        ];
    }

    public function solicitar(int $envioId, int $personalId, string $motivo, array $turnos): int
    {
        $motivo = $this->texto($motivo, true);
        $envio = $this->envio($envioId);
        $this->gestion->exigirOficina((int) $envio['id_oficina']);
        return $this->gestion->transaccion(function () use ($envio, $envioId, $personalId, $motivo, $turnos): int {
            $periodo = $this->periodo($envio);
            $this->bloquearPersonal($personalId);
            $this->gestion->exigirOficina((int) $envio['id_oficina']);
            $duplicada = $this->db->table('casis_rol_ampliacion')->where('envio_id', $envioId)
                ->where('personal_id', $personalId)->whereIn('estado', ['PENDIENTE_DIRECCION', 'APROBADA'])->countAllResults();
            if ($duplicada) {
                throw new \DomainException('El trabajador ya tiene una ampliación pendiente o aprobada para este rol.');
            }
            $datos = $this->validar($envio, $periodo, $personalId, $turnos);
            $this->db->table('casis_rol_ampliacion')->insert([
                'envio_id' => $envioId,
                'personal_id' => $personalId,
                'estado' => 'PENDIENTE_DIRECCION',
                'motivo' => $motivo,
                'datos_json' => json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
                'solicitado_por' => $this->gestion->usuario(),
                'solicitado_at' => $this->gestion->ahora(),
            ]);
            $id = (int) $this->db->insertID();
            $this->gestion->evento('SOLICITAR_AMPLIACION_ROL', ['ampliacion_id' => $id], (int) $envio['rol_id']);
            return $id;
        });
    }

    public function resolver(int $id, string $accion, string $comentario): void
    {
        if (!in_array($accion, ['APROBAR', 'RECHAZAR'], true)) {
            throw new \InvalidArgumentException('Seleccione una decisión válida.');
        }
        $comentario = $this->texto($comentario, $accion === 'RECHAZAR');
        $ampliacion = $this->obtener($id);
        $envio = $this->envio((int) $ampliacion['envio_id']);
        $this->gestion->exigirOficina((int) $envio['id_oficina'], true, 'DIRECCION');
        $this->gestion->transaccion(function () use ($id, $envio, $accion, $comentario): void {
            $periodo = $this->periodo($envio);
            $ampliacion = $this->obtener($id, true);
            $personalId = (int) $ampliacion['personal_id'];
            if ($accion === 'APROBAR') {
                $this->bloquearPersonal($personalId);
            }
            $this->gestion->exigirOficina((int) $envio['id_oficina'], true, 'DIRECCION');
            $actor = $this->gestion->usuario();
            if ((int) $ampliacion['solicitado_por'] === $actor) {
                throw new \DomainException('La decisión de Dirección requiere un usuario distinto de quien solicitó la ampliación.');
            }
            if ($ampliacion['estado'] !== 'PENDIENTE_DIRECCION') {
                throw new \DomainException('Esta ampliación ya fue resuelta.');
            }
            $ids = [];
            $ahora = $this->gestion->ahora();
            if ($accion === 'APROBAR') {
                $datos = $this->conDatos($ampliacion)['datos'];
                $turnos = array_map(static fn(array $t): array => ['fecha' => $t['prog_fecha'], 'horario' => $t['prog_th_ide']], $datos['turnos']);
                $vigente = $this->validar($envio, $periodo, $personalId, $turnos, true);
                if ($vigente !== $datos) {
                    throw new \DomainException('Los datos del trabajador o de sus horarios cambiaron. Rechace la solicitud y solicite una nueva ampliación.');
                }
                foreach ($vigente['turnos'] as $t) {
                    $this->db->table('casis_programacion')->insert([
                        'prog_perl_ide' => $personalId,
                        'prog_fecha' => $t['prog_fecha'],
                        'prog_th_ide' => $t['prog_th_ide'],
                        'prog_estado' => 'PROGRAMADO',
                        'created_by' => $actor,
                        'created_at' => $ahora,
                    ]);
                    $ids[] = (int) $this->db->insertID();
                }
            }
            $this->db->table('casis_rol_ampliacion')->where('id', $id)->update([
                'estado' => $accion === 'APROBAR' ? 'APROBADA' : 'RECHAZADA',
                'resuelto_por' => $actor,
                'resuelto_at' => $ahora,
                'comentario' => $comentario ?: null,
                'programaciones_json' => $ids ? json_encode($ids, JSON_THROW_ON_ERROR) : null,
            ]);
            $this->gestion->evento($accion . '_AMPLIACION_ROL', ['ampliacion_id' => $id, 'programaciones' => $ids], (int) $envio['rol_id']);
        });
    }

    private function validar(array $envio, array $periodo, int $personalId, array $turnos, bool $bloquear = false): array
    {
        $mes = $periodo['mes'];
        $p = $this->personal($envio)->where('p.perl_ide', $personalId)->get()->getRowArray();
        if (!$p || !str_starts_with($p['perl_fecha_inicio'], $mes . '-')
            || in_array($personalId, $this->personalConTurnosAprobados($envio), true)) {
            throw new \DomainException('Seleccione personal ingresado en el mes, de la oficina del rol y sin turnos en el rol aprobado.');
        }
        if (!$turnos || count($turnos) > self::MAX_TURNOS) {
            throw new \InvalidArgumentException('Registre entre 1 y ' . self::MAX_TURNOS . ' turnos.');
        }
        $fin = $this->finVinculo($p, $mes);
        $propuesta = [];
        foreach ($turnos as $t) {
            $fecha = $t['fecha'] ?? '';
            $horario = filter_var($t['horario'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            $dia = is_string($fecha) && preg_match('/^\d{4}-\d{2}-\d{2}$/D', $fecha)
                ? \DateTimeImmutable::createFromFormat('!Y-m-d', $fecha) : false;
            if (!$dia || $dia->format('Y-m-d') !== $fecha || $fecha < $p['perl_fecha_inicio'] || $fecha > $fin || !$horario) {
                throw new \InvalidArgumentException('Cada turno requiere un horario válido y una fecha entre el inicio del vínculo y su último día en el mes.');
            }
            $propuesta[] = ['prog_fecha' => $fecha, 'prog_th_ide' => $horario];
        }
        usort($propuesta, static fn(array $a, array $b): int => [$a['prog_fecha'], $a['prog_th_ide']] <=> [$b['prog_fecha'], $b['prog_th_ide']]);
        $consulta = $this->horarios()->whereIn('h.th_ide', array_unique(array_column($propuesta, 'prog_th_ide')));
        $filas = $bloquear ? $this->db->query($consulta->getCompiledSelect() . ' FOR UPDATE')->getResultArray() : $consulta->get()->getResultArray();
        $horarios = array_column($filas, null, 'th_ide');
        foreach ($propuesta as &$t) {
            $horario = $horarios[$t['prog_th_ide']] ?? null;
            if (!$horario) {
                throw new \DomainException('Uno de los horarios seleccionados ya no está disponible.');
            }
            unset($horario['th_ide']);
            $t += $horario;
        }
        unset($t);

        $consulta = $this->db->table('casis_programacion pr')
            ->select('pr.prog_fecha, h.th_hora_ingreso, h.th_hora_salida, t.tur_codigo')
            ->join('casis_turno_horario h', 'h.th_ide = pr.prog_th_ide')->join('casis_turno t', 't.tur_ide = h.th_tur_ide')
            ->where('pr.prog_perl_ide', $personalId)->where('pr.deleted_at', null)
            ->whereNotIn('pr.prog_estado', ['ANULADO', 'CANCELADO']);
        $existentes = $bloquear ? $this->db->query($consulta->getCompiledSelect() . ' FOR UPDATE')->getResultArray() : $consulta->get()->getResultArray();
        $errores = GestionRolService::evaluar($p, array_merge($existentes, $propuesta),
            json_decode($periodo['reglas'], true, 512, JSON_THROW_ON_ERROR), $mes);
        if ($errores) {
            throw new \DomainException(implode(' ', array_unique($errores)));
        }
        return ['nombre' => $this->nombre($p), 'codigo' => $p['perl_codigo'], 'ingreso' => $p['perl_fecha_inicio'], 'turnos' => $propuesta];
    }

    private function envio(int $id): array
    {
        return (new RolEnvioService($this->db, $this->gestion))->oficial($id);
    }

    private function periodo(array $envio): array
    {
        $periodo = $this->db->query(
            'SELECT p.* FROM casis_plan_periodo p JOIN casis_plan_rol r ON r.periodo_id = p.id
             WHERE r.id = ? AND r.estado = ? FOR UPDATE',
            [$envio['rol_id'], 'APROBADO_DIRECCION']
        )->getRowArray();
        if (!$periodo || $periodo['mes'] !== $this->mes($envio)) {
            throw new \DomainException('El rol original ya no está aprobado para este período.');
        }
        return $periodo;
    }

    private function obtener(int $id, bool $bloquear = false): array
    {
        $sql = 'SELECT * FROM casis_rol_ampliacion WHERE id = ?';
        $fila = $this->db->query($sql . ($bloquear ? ' FOR UPDATE' : ''), [$id])->getRowArray();
        if (!$fila) {
            throw new \OutOfBoundsException('No se encontró la ampliación.');
        }
        return $fila;
    }

    private function bloquearPersonal(int $id): void
    {
        $this->db->query('SELECT perl_ide FROM casis_personal WHERE perl_ide = ? FOR UPDATE', [$id]);
    }

    private function personal(array $envio): \CodeIgniter\Database\BaseBuilder
    {
        return $this->db->table('casis_personal p')
            ->select('p.perl_ide, p.perl_ofi_ide, p.perl_mco_ide, p.perl_codigo, p.perl_fecha_inicio, p.perl_fecha_termino, p.perl_fecha_cese,
                pe.per_paterno, pe.per_materno, pe.per_nombre, c.car_modalidad')
            ->join('casis_persona pe', 'pe.per_ide = p.perl_per_ide')
            ->join('casis_cargo c', 'c.car_ide = p.perl_car_ide AND c.deleted_at IS NULL', 'left')
            ->where('p.perl_est_ide', (int) $envio['id_establecimiento'])->where('p.perl_ofi_ide', (int) $envio['id_oficina'])
            ->where('p.deleted_at', null);
    }

    private function horarios(): \CodeIgniter\Database\BaseBuilder
    {
        return $this->db->table('casis_turno_horario h')
            ->select('h.th_ide, h.th_nombre, h.th_hora_ingreso, h.th_hora_salida, t.tur_codigo')
            ->join('casis_turno t', 't.tur_ide = h.th_tur_ide')
            ->where('h.th_estado', 1)->where('t.tur_estado', 1)->where('h.deleted_at', null)->where('t.deleted_at', null);
    }

    private function personalConTurnosAprobados(array $envio): array
    {
        $programados = array_filter($envio['snapshot'], static fn(array $miembro): bool => !empty($miembro['turnos']));
        return array_map(static fn(array $miembro): int => (int) ($miembro['personal']['perl_ide'] ?? 0), $programados);
    }

    private function nombre(array $p): string
    {
        return trim(implode(' ', array_filter([$p['per_paterno'], $p['per_materno'], $p['per_nombre']])));
    }

    private function mes(array $envio): string
    {
        return sprintf('%04d-%02d', $envio['anio'], $envio['mes']);
    }

    private function finMes(string $mes): string
    {
        return (new \DateTimeImmutable($mes . '-01'))->format('Y-m-t');
    }

    private function finVinculo(array $p, string $mes): string
    {
        return min(array_filter([$this->finMes($mes), $p['perl_fecha_termino'], $p['perl_fecha_cese']]));
    }

    private function tieneOficina(int $oficina, ?string $etapa = null): bool
    {
        $ids = $this->gestion->oficinas($etapa !== null, $etapa);
        return $ids === null || in_array($oficina, $ids, true);
    }

    private function conDatos(array $fila): array
    {
        $fila['datos'] = json_decode($fila['datos_json'], true, 512, JSON_THROW_ON_ERROR);
        unset($fila['datos_json']);
        return $fila;
    }

    private function texto(string $valor, bool $obligatorio): string
    {
        $valor = trim($valor);
        if (($obligatorio && $valor === '') || mb_strlen($valor) > 1000) {
            throw new \InvalidArgumentException('Ingrese un motivo o comentario de hasta 1000 caracteres.');
        }
        return $valor;
    }
}

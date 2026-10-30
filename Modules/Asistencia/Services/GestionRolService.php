<?php

namespace Modules\Asistencia\Services;

/** Gobierno de programación. Los plazos se interpretan en America/Lima. */
class GestionRolService
{
    protected $db;
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }
    public function usuario(): int
    {
        if (!function_exists('auth') || !auth()->loggedIn()) throw new \DomainException('Debe iniciar sesión.');
        return (int) auth()->id();
    }
    public function administrador(): bool
    {
        $this->usuario();
        return auth()->user()->inGroup('superadmin', 'asi_sua');
    }
    public function exigirAdministrador(): void
    {
        if (!$this->administrador()) throw new \DomainException('Solo el administrador de Asistencia puede configurar los periodos y responsables.');
    }
    public function ahora(): string
    {
        return (new \DateTimeImmutable('now', new \DateTimeZone('America/Lima')))->format('Y-m-d H:i:s');
    }
    public static function etapa(array $p, string $ahora): string
    {
        if ($ahora < $p['apertura']) return 'PROGRAMADO';
        if ($ahora < $p['aprobacion']) return 'PROGRAMACION';
        if ($ahora < $p['cierre']) return 'APROBACION';
        return 'CERRADO';
    }
    /** null representa alcance global; [] representa ningún acceso. */
    public function oficinas(bool $aprobar = false, ?string $etapa = null): ?array
    {
        if ($this->administrador()) return null;
        $hoy = substr($this->ahora(), 0, 10);
        $q = $this->db->table('casis_plan_responsable')->where('usuario_id', $this->usuario())->where('desde <=', $hoy)->where('hasta >=', $hoy);
        if ($aprobar) {
            $q->where('aprobar', 1);
            if ($etapa !== null) $q->where('etapa', $etapa);
        } else $q->where('programar', 1);
        $ids = array_map('intval', array_column($q->get()->getResultArray(), 'oficina_id'));
        $oficinas = $this->db->table('casis_oficina')->where('deleted_at', null)->where('ofi_estado', 1)->get()->getResultArray();
        // El jefe asignado en oficinas programa su oficina; aprobar requiere delegación expresa.
        if (!$aprobar) {
            foreach ($oficinas as $o) {
                if ((int)($o['ofi_responsable_id'] ?? 0) === $this->usuario()) $ids[] = (int)$o['ofi_ide'];
            }
            $ids = array_values(array_unique($ids));
        }
        do {
            $antes = count($ids);
            foreach ($oficinas as $o) if (in_array((int)$o['ofi_padre_ide'], $ids, true) && !in_array((int)$o['ofi_ide'], $ids, true)) $ids[] = (int)$o['ofi_ide'];
        } while (count($ids) !== $antes);
        return $ids;
    }
    public function exigirOficina(int $id, bool $aprobar = false, ?string $etapa = null): void
    {
        $ids = $this->oficinas($aprobar, $etapa);
        if (!$id || ($ids !== null && !in_array($id, $ids, true))) throw new \DomainException('No tiene permiso sobre esta oficina o sus trabajadores.');
    }
    public function filtrarPersonal($builder, string $alias = 'p'): void
    {
        $ids = $this->oficinas();
        if ($ids !== null) $builder->whereIn($alias . '.perl_ofi_ide', $ids ?: [0]);
    }
    public function personal(int $id): array
    {
        $p = $this->db->table('casis_personal p')->select('p.*, c.car_modalidad, c.car_nombre')->join('casis_cargo c', 'c.car_ide = p.perl_car_ide AND c.deleted_at IS NULL', 'left')->where('p.perl_ide', $id)->where('p.deleted_at', null)->get()->getRowArray();
        if (!$p) throw new \DomainException('Trabajador no encontrado.');
        $this->exigirOficina((int)$p['perl_ofi_ide']);
        return $p;
    }
    /** Debe ejecutarse dentro de la misma transacción que la escritura. */
    public function editable(int $id, string $fecha): array
    {
        $p = $this->personal($id);
        $periodo = $this->db->query('SELECT * FROM casis_plan_periodo WHERE est_id = ? AND mes = ? FOR UPDATE', [$p['perl_est_ide'], substr($fecha, 0, 7)])->getRowArray();
        if (!$periodo) throw new \DomainException('No existe un periodo de programación para este establecimiento y mes.');
        $etapaPeriodo = self::etapa($periodo, $this->ahora());
        $rol = $this->db->table('casis_plan_rol')->where('periodo_id', $periodo['id'])->where('oficina_id', $p['perl_ofi_ide'])->get()->getRowArray();
        $correccionObservada = $rol && $rol['estado'] === 'OBSERVADO' && $etapaPeriodo !== 'CERRADO';
        if ($etapaPeriodo !== 'PROGRAMACION' && ! $correccionObservada) throw new \DomainException('El periodo no está abierto para modificaciones.');
        // Una transferencia de oficina no debe desbloquear a quien ya integra un rol presentado/aprobado.
        $congelados = $this->db->table('casis_plan_rol')->where('periodo_id', $periodo['id'])->whereIn('estado', ['PRESENTADO', 'APROBADO', 'PENDIENTE_RRHH', 'VISTO_BUENO_RRHH', 'APROBADO_DIRECCION', 'RECHAZADO'])->get()->getResultArray();
        foreach ($congelados as $congelado) {
            foreach (json_decode($congelado['snapshot'] ?? '[]', true) ?: [] as $miembro) {
                if ((int)($miembro['personal']['perl_ide'] ?? 0) === $id) throw new \DomainException('El trabajador pertenece a un rol presentado o aprobado.');
            }
        }
        if ($rol && !in_array($rol['estado'], ['BORRADOR', 'OBSERVADO'], true)) throw new \DomainException('El rol está presentado o aprobado y no admite modificaciones.');
        if (!$rol) {
            $this->db->table('casis_plan_rol')->insert(['periodo_id' => $periodo['id'], 'oficina_id' => $p['perl_ofi_ide'], 'estado' => 'BORRADOR', 'created_by' => $this->usuario(), 'created_at' => $this->ahora()]);
        }
        return $periodo;
    }
    public function turnos(int $personal): array
    {
        return $this->db->table('casis_programacion pr')->select('pr.*, h.th_hora_ingreso, h.th_hora_salida, t.tur_codigo, t.tur_modalidad, t.tur_nombre, t.tur_color')
            ->join('casis_turno_horario h', 'h.th_ide = pr.prog_th_ide')->join('casis_turno t', 't.tur_ide = h.th_tur_ide')
            ->where('pr.prog_perl_ide', $personal)->where('pr.deleted_at', null)->orderBy('pr.prog_fecha')->get()->getResultArray();
    }
    public function validar(int $id, array $periodo): void
    {
        $p = $this->personal($id);
        $errores = self::evaluar($p, $this->turnos($id), json_decode($periodo['reglas'], true, 512, JSON_THROW_ON_ERROR), $periodo['mes']);
        if ($errores) throw new \DomainException(implode(' ', array_unique($errores)));
    }
    /** Evaluador puro, compartido por edición, importación y aprobación. */
    public static function evaluar(array $p, array $turnos, array $reglas, string $mes): array
    {
        if (!in_array($p['car_modalidad'] ?? null, ['ASISTENCIAL', 'ADMINISTRATIVO'], true)) return ['El trabajador debe tener un cargo con modalidad asistencial o administrativa.'];
        $errores = [];
        $intervalos = [];
        $mensuales = [];
        $tz = new \DateTimeZone('America/Lima');
        foreach ($turnos as $t) {
            $inicio = new \DateTimeImmutable($t['prog_fecha'] . ' ' . $t['th_hora_ingreso'], $tz);
            $fin = new \DateTimeImmutable($t['prog_fecha'] . ' ' . $t['th_hora_salida'], $tz);
            if ($fin <= $inicio) $fin = $fin->modify('+1 day');
            $t['inicio'] = $inicio->getTimestamp();
            $t['fin'] = $fin->getTimestamp();
            $intervalos[] = $t;
            if (substr($t['prog_fecha'], 0, 7) !== $mes) continue;
            $mensuales[] = $t;
            if (($p['car_modalidad']) !== $t['tur_modalidad']) $errores[] = 'El turno no corresponde a la modalidad del trabajador.';
            if (($p['car_modalidad']) === 'ADMINISTRATIVO' && ((int)$inicio->format('N') > 5 || $t['fin'] - $t['inicio'] !== 28800)) $errores[] = 'La jornada administrativa requiere ocho horas de lunes a viernes.';
        }
        usort($intervalos, fn($a, $b) => $a['inicio'] <=> $b['inicio']);
        $finAnterior = null;
        $anterior = null;
        foreach ($intervalos as $t) {
            if ($finAnterior !== null && $t['inicio'] < $finAnterior && (substr($t['prog_fecha'], 0, 7) === $mes || substr($anterior['prog_fecha'], 0, 7) === $mes)) $errores[] = 'Existen turnos superpuestos.';
            if ($finAnterior === null || $t['fin'] > $finAnterior) {
                $finAnterior = $t['fin'];
                $anterior = $t;
            }
        }
        foreach ($reglas as $r) {
            if (!empty($r['oficina_id']) && (int)$r['oficina_id'] !== (int)$p['perl_ofi_ide']) continue;
            if (!empty($r['regimen_id']) && (int)$r['regimen_id'] !== (int)$p['perl_mco_ide']) continue;
            if (!empty($r['modalidad']) && $r['modalidad'] !== ($p['car_modalidad'])) continue;
            $tipo = $r['tipo'];
            $limite = (float)($r['limite'] ?? 0);
            $fallo = false;
            if ($tipo === 'TURNOS_PERMITIDOS') foreach ($mensuales as $t) if (!in_array($t['tur_codigo'], $r['turnos'], true)) $fallo = true;
            if ($tipo === 'MAX_TURNOS') $fallo = count(array_filter($mensuales, fn($t) => in_array($t['tur_codigo'], $r['turnos'], true))) > $limite;
            if ($tipo === 'MAX_HORAS_MES') $fallo = array_sum(array_map(fn($t) => ($t['fin'] - $t['inicio']) / 3600, $mensuales)) > $limite;
            if ($tipo === 'MAX_CONTINUAS') {
                $inicio = null;
                $fin = null;
                $toca = false;
                foreach ($intervalos as $t) {
                    if ($fin === null || $t['inicio'] > $fin) {
                        $inicio = $t['inicio'];
                        $fin = $t['fin'];
                        $toca = false;
                    }
                    $fin = max($fin, $t['fin']);
                    $toca = $toca || substr($t['prog_fecha'], 0, 7) === $mes;
                    if ($toca && ($fin - $inicio) / 3600 > $limite) $fallo = true;
                }
            }
            if ($fallo) $errores[] = $r['nombre'] . ': regla incumplida.';
        }
        return $errores;
    }
    public function evento(string $accion, array $detalle, ?int $rol = null): void
    {
        if (!$this->db->table('casis_plan_evento')->insert(['rol_id' => $rol, 'accion' => $accion, 'detalle' => json_encode($detalle, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE), 'created_by' => $this->usuario(), 'created_at' => $this->ahora()])) throw new \RuntimeException('No se pudo registrar la auditoría.');
    }
    public function transaccion(callable $fn)
    {
        $this->db->transException(true)->transBegin();
        try {
            $res = $fn();
            if ($this->db->transStatus() === false) throw new \RuntimeException('No se pudo guardar la operación.');
            $this->db->transCommit();
            return $res;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }
    public function crearPeriodo(array $d): void
    {
        (new ConfiguracionRolService())->guardarPeriodo($d);
    }
    public function responsable(array $d): void
    {
        $this->exigirAdministrador();
        $etapa = strtoupper(trim((string)($d['etapa'] ?? 'DIRECCION')));
        if (!in_array($etapa, ['PROGRAMACION', 'RRHH', 'DIRECCION'], true)) throw new \DomainException('Etapa de aprobación inválida.');
        foreach (['desde', 'hasta'] as $k) {
            $f = \DateTimeImmutable::createFromFormat('!Y-m-d', $d[$k] ?? '');
            if (!$f || $f->format('Y-m-d') !== $d[$k]) throw new \DomainException('Vigencia inválida.');
        }
        if ($d['desde'] > $d['hasta']) throw new \DomainException('Vigencia inválida.');
        if (!$this->db->table('users')->where('id', (int)$d['usuario_id'])->where('active', 1)->countAllResults()) throw new \DomainException('Usuario activo no encontrado.');
        if (!$this->db->table('casis_oficina')->where('ofi_ide', (int)$d['oficina_id'])->where('deleted_at', null)->countAllResults()) throw new \DomainException('Oficina no encontrada.');
        $this->transaccion(function () use ($d, $etapa) {
            $data = array_intersect_key($d, array_flip(['usuario_id', 'oficina_id', 'desde', 'hasta']));
            $data['aprobar'] = $etapa === 'PROGRAMACION' ? 0 : 1;
            $data['programar'] = $etapa === 'PROGRAMACION' ? 1 : 0;
            $data['etapa'] = $etapa;
            $data['created_by'] = $this->usuario();
            $data['created_at'] = $this->ahora();
            $this->db->table('casis_plan_responsable')->insert($data);
            $this->evento('ASIGNAR_RESPONSABLE', $data);
        });
    }
    public function transicion(int $rolId, string $accion, string $motivo): void
    {
        $this->transaccion(function () use ($rolId, $accion, $motivo) {
            $rol = $this->db->table('casis_plan_rol')->where('id', $rolId)->get()->getRowArray();
            if (!$rol) throw new \DomainException('Rol no encontrado.');
            $p = $this->db->query('SELECT * FROM casis_plan_periodo WHERE id = ? FOR UPDATE', [$rol['periodo_id']])->getRowArray();
            $rol = $this->db->table('casis_plan_rol')->where('id', $rolId)->get()->getRowArray();
            $this->exigirOficina((int)$rol['oficina_id'], $accion !== 'PRESENTAR');
            $etapa = self::etapa($p, $this->ahora());
            $destino = null;
            if ($accion === 'PRESENTAR' && $etapa === 'PROGRAMACION' && in_array($rol['estado'], ['BORRADOR', 'OBSERVADO'], true)) $destino = 'PRESENTADO';
            if ($accion === 'OBSERVAR' && $etapa === 'PROGRAMACION' && $rol['estado'] === 'PRESENTADO' && trim($motivo) !== '') $destino = 'OBSERVADO';
            if ($accion === 'APROBAR' && $etapa === 'APROBACION' && $rol['estado'] === 'PRESENTADO') $destino = 'APROBADO';
            if (!$destino) throw new \DomainException('Transición no permitida para el estado o plazo actual. Las observaciones requieren un motivo y plazo de programación vigente.');
            $snapshot = [];
            $personal = $this->db->table('casis_personal')->where('perl_ofi_ide', $rol['oficina_id'])->where('perl_est_ide', $p['est_id'])->where('deleted_at', null)->get()->getResultArray();
            foreach ($personal as $trabajador) {
                $this->validar((int)$trabajador['perl_ide'], $p);
                $snapshot[] = ['personal' => $this->personal((int)$trabajador['perl_ide']), 'turnos' => array_values(array_filter($this->turnos((int)$trabajador['perl_ide']), fn($t) => substr($t['prog_fecha'], 0, 7) === $p['mes']))];
            }
            if (!$snapshot) throw new \DomainException('El rol no contiene personal.');
            $this->db->table('casis_plan_rol')->where('id', $rolId)->update(['estado' => $destino, 'snapshot' => json_encode($snapshot, JSON_THROW_ON_ERROR)]);
            $this->evento($accion, ['anterior' => $rol['estado'], 'estado' => $destino, 'motivo' => $motivo], $rolId);
        });
    }
}

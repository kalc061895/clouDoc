<?php

namespace Modules\Asistencia\Services;

/** Flujo de envío y aprobación con snapshots versionados de la programación vigente. */
class RolEnvioService
{
    private $db;
    private GestionRolService $gestion;

    public function __construct($db = null, ?GestionRolService $gestion = null)
    {
        $this->db = $db ?? \Config\Database::connect();
        $this->gestion = $gestion ?? new GestionRolService();
    }

    public function enviar(int $rolId): int
    {
        return $this->gestion->transaccion(function () use ($rolId): int {
            $rol = $this->db->query('SELECT * FROM casis_plan_rol WHERE id = ? FOR UPDATE', [$rolId])->getRowArray();
            if (! $rol) throw new \OutOfBoundsException('No se encontró el rol de la oficina.');
            if (! in_array($rol['estado'], ['BORRADOR', 'OBSERVADO'], true)) throw new \DomainException('El rol no está disponible para envío.');

            $periodo = $this->db->query('SELECT * FROM casis_plan_periodo WHERE id = ? FOR UPDATE', [$rol['periodo_id']])->getRowArray();
            if (! $periodo) throw new \DomainException('No se encontró el período del rol.');
            $this->gestion->exigirOficina((int) $rol['oficina_id']);

            $etapa = GestionRolService::etapa($periodo, $this->gestion->ahora());
            if ($etapa === 'CERRADO' || ($rol['estado'] === 'BORRADOR' && $etapa !== 'PROGRAMACION')) {
                throw new \DomainException('El período no está habilitado para enviar este rol.');
            }

            $personal = $this->db->table('casis_personal')
                ->where('perl_est_ide', (int) $periodo['est_id'])
                ->where('perl_ofi_ide', (int) $rol['oficina_id'])
                ->where('deleted_at', null)->orderBy('perl_ide')->get()->getResultArray();
            $cabecera = $this->cabeceraSnapshot((int) $periodo['est_id'], (int) $rol['oficina_id']);
            $snapshot = [];
            foreach ($personal as $trabajador) {
                $id = (int) $trabajador['perl_ide'];
                $this->gestion->validar($id, $periodo);
                $turnos = array_values(array_filter(
                    $this->gestion->turnos($id),
                    static fn (array $turno): bool => substr($turno['prog_fecha'], 0, 7) === $periodo['mes']
                ));
                $perfilCompleto = $this->gestion->personal($id);
                $persona = $this->db->table('casis_persona')->where('per_ide', (int) $perfilCompleto['perl_per_ide'])->get()->getRowArray();
                $perfil = array_intersect_key($perfilCompleto, array_flip([
                    'perl_ide', 'perl_per_ide', 'perl_est_ide', 'perl_ofi_ide', 'perl_car_ide',
                    'perl_mco_ide', 'perl_codigo', 'car_modalidad', 'car_nombre',
                ]));
                $perfil['nombre_snapshot'] = trim(implode(' ', array_filter([
                    $persona['per_paterno'] ?? '', $persona['per_materno'] ?? '', $persona['per_nombre'] ?? '',
                ])));
                $perfil['documento_snapshot'] = $persona['per_numero_documento'] ?? '';
                $perfil['cargo_snapshot'] = $perfil['car_nombre'] ?? 'Sin Cargo';
                $snapshot[] = ['personal' => $perfil, 'turnos' => $turnos];
            }
            if (! array_filter($snapshot, static fn (array $miembro): bool => count($miembro['turnos']) > 0)) {
                throw new \DomainException('No hay turnos programados en esta oficina para el período.');
            }

            $json = json_encode($snapshot, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
            $cabeceraJson = json_encode($cabecera, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
            $version = (int) $this->db->table('casis_rol_envio')->where('rol_id', $rolId)->countAllResults() + 1;
            $ahora = $this->gestion->ahora();
            $this->db->table('casis_rol_envio')->insert([
                'rol_id' => $rolId,
                'version' => $version,
                'anio' => (int) substr($periodo['mes'], 0, 4),
                'mes' => (int) substr($periodo['mes'], 5, 2),
                'id_oficina' => (int) $rol['oficina_id'],
                'id_establecimiento' => (int) $periodo['est_id'],
                'datos_json' => $json,
                'cabecera_json' => $cabeceraJson,
                'cabecera_sha256' => hash('sha256', $cabeceraJson),
                'hash_sha256' => hash('sha256', $json),
                'estado' => 'PENDIENTE_RRHH',
                'usuario_envio_id' => $this->gestion->usuario(),
                'enviado_at' => $ahora,
                'created_at' => $ahora,
            ]);
            $envioId = (int) $this->db->insertID();
            $this->db->table('casis_plan_rol')->where('id', $rolId)->update(['estado' => 'PENDIENTE_RRHH', 'snapshot' => $json]);
            $this->gestion->evento('ENVIAR_RRHH', ['envio_id' => $envioId, 'version' => $version, 'hash' => hash('sha256', $json)], $rolId);
            return $envioId;
        });
    }

    public function revisarRRHH(int $envioId, string $accion, string $comentario = ''): void
    {
        $accion = strtoupper(trim($accion));
        if (! in_array($accion, ['OBSERVAR', 'VISTO_BUENO'], true)) throw new \InvalidArgumentException('Acción de RR. HH. inválida.');
        $comentario = $this->comentario($comentario, $accion === 'OBSERVAR');
        $this->gestion->transaccion(function () use ($envioId, $accion, $comentario): void {
            $envio = $this->obtenerBloqueado($envioId);
            if ($envio['estado'] !== 'PENDIENTE_RRHH') throw new \DomainException('El envío ya no está pendiente de revisión de RR. HH.');
            if (GestionRolService::etapa($this->periodoEnvio($envio), $this->gestion->ahora()) === 'CERRADO') throw new \DomainException('El período cerró y ya no admite revisiones.');
            $this->gestion->exigirOficina((int) $envio['id_oficina'], true, 'RRHH');
            $uid = $this->gestion->usuario();
            if ((int) $envio['usuario_envio_id'] === $uid) throw new \DomainException('Quien envió el rol no puede revisarlo en RR. HH.');
            $estado = ['OBSERVAR' => 'OBSERVADO', 'VISTO_BUENO' => 'VISTO_BUENO_RRHH'][$accion];
            $this->db->table('casis_rol_envio')->where('id', $envioId)->update([
                'estado' => $estado, 'usuario_rrhh_id' => $uid,
                'rrhh_at' => $this->gestion->ahora(), 'comentario_rrhh' => $comentario ?: null,
            ]);
            $this->db->table('casis_plan_rol')->where('id', (int) $envio['rol_id'])->update(['estado' => $estado]);
            $this->gestion->evento('RRHH_' . $accion, ['envio_id' => $envioId, 'estado' => $estado, 'comentario' => $comentario], (int) $envio['rol_id']);
        });
    }

    public function revisarDireccion(int $envioId, string $accion, string $comentario = ''): void
    {
        $accion = strtoupper(trim($accion));
        if (! in_array($accion, ['APROBAR', 'DEVOLVER', 'RECHAZAR'], true)) throw new \InvalidArgumentException('Acción de Dirección inválida.');
        $comentario = $this->comentario($comentario, $accion !== 'APROBAR');
        $this->gestion->transaccion(function () use ($envioId, $accion, $comentario): void {
            $envio = $this->obtenerBloqueado($envioId);
            if ($envio['estado'] !== 'VISTO_BUENO_RRHH') throw new \DomainException('El envío aún no tiene el visto bueno de RR. HH.');
            if (GestionRolService::etapa($this->periodoEnvio($envio), $this->gestion->ahora()) !== 'APROBACION') throw new \DomainException('Dirección puede resolver el rol durante el plazo de aprobación configurado.');
            $this->gestion->exigirOficina((int) $envio['id_oficina'], true, 'DIRECCION');
            $uid = $this->gestion->usuario();
            if ((int) $envio['usuario_envio_id'] === $uid || (int) $envio['usuario_rrhh_id'] === $uid) {
                throw new \DomainException('El aprobador de Dirección debe ser distinto de quienes enviaron y revisaron este rol.');
            }
            if ($accion === 'APROBAR') $this->verificarSnapshotOperativo($envio);
            $estado = ['APROBAR' => 'APROBADO_DIRECCION', 'DEVOLVER' => 'OBSERVADO', 'RECHAZAR' => 'RECHAZADO'][$accion];
            $this->db->table('casis_rol_envio')->where('id', $envioId)->update([
                'estado' => $estado, 'usuario_direccion_id' => $uid,
                'direccion_at' => $this->gestion->ahora(), 'comentario_direccion' => $comentario ?: null,
            ]);
            $this->db->table('casis_plan_rol')->where('id', (int) $envio['rol_id'])->update(['estado' => $estado]);
            $this->gestion->evento('DIRECCION_' . $accion, ['envio_id' => $envioId, 'estado' => $estado, 'comentario' => $comentario], (int) $envio['rol_id']);
        });
    }

    public function listar(): array
    {
        $oficinas = [];
        $global = false;
        foreach ([null, 'RRHH', 'DIRECCION'] as $etapa) {
            $ids = $etapa === null ? $this->gestion->oficinas() : $this->gestion->oficinas(true, $etapa);
            if ($ids === null) { $global = true; break; }
            $oficinas = array_merge($oficinas, $ids);
        }
        $q = $this->db->table('casis_rol_envio e')
            ->select('e.id, e.rol_id, e.version, e.anio, e.mes, e.id_oficina, e.id_establecimiento, e.estado, e.usuario_envio_id, e.enviado_at, e.usuario_rrhh_id, e.rrhh_at, e.comentario_rrhh, e.usuario_direccion_id, e.direccion_at, e.comentario_direccion, o.ofi_nombre, es.est_nombre')
            ->join('casis_oficina o', 'o.ofi_ide = e.id_oficina')
            ->join('casis_establecimiento es', 'es.est_ide = e.id_establecimiento')
            ->orderBy('e.id', 'DESC');
        if (! $global) {
            $oficinas = array_values(array_unique(array_map('intval', $oficinas)));
            if (! $oficinas) return [];
            $q->whereIn('e.id_oficina', $oficinas);
        }
        return $q->limit(250)->get()->getResultArray();
    }

    public function detalle(int $envioId): array
    {
        $envio = $this->db->table('casis_rol_envio e')->select('e.*, o.ofi_nombre, es.est_nombre')
            ->join('casis_oficina o', 'o.ofi_ide = e.id_oficina')->join('casis_establecimiento es', 'es.est_ide = e.id_establecimiento')
            ->where('e.id', $envioId)->get()->getRowArray();
        if (! $envio) throw new \OutOfBoundsException('No se encontró el envío.');
        $local = $this->gestion->oficinas();
        $rrhh = $this->gestion->oficinas(true, 'RRHH');
        $direccion = $this->gestion->oficinas(true, 'DIRECCION');
        $usuario = $this->gestion->usuario();
        $actorAprobado = $envio['estado'] === 'APROBADO_DIRECCION'
            && in_array($usuario, [(int) $envio['usuario_envio_id'], (int) $envio['usuario_rrhh_id'], (int) $envio['usuario_direccion_id']], true);
        if (! $actorAprobado && ($local !== null && ! in_array((int) $envio['id_oficina'], $local, true))
            && ($rrhh !== null && ! in_array((int) $envio['id_oficina'], $rrhh, true))
            && ($direccion !== null && ! in_array((int) $envio['id_oficina'], $direccion, true))) {
            throw new \DomainException('No tiene acceso a este envío.');
        }
        if (! hash_equals($envio['hash_sha256'], hash('sha256', $envio['datos_json']))) throw new \RuntimeException('La huella del snapshot no coincide; no se mostrará como íntegro.');
        if (! hash_equals($envio['cabecera_sha256'], hash('sha256', $envio['cabecera_json']))) throw new \RuntimeException('La cabecera del snapshot no coincide; no se mostrará como íntegra.');
        $envio['snapshot'] = json_decode($envio['datos_json'], true, 512, JSON_THROW_ON_ERROR);
        return $envio;
    }

    public function oficial(int $envioId): array
    {
        $envio = $this->detalle($envioId);
        if ($envio['estado'] !== 'APROBADO_DIRECCION') throw new \DomainException('El PDF oficial se habilita después de la aprobación de Dirección.');
        return $envio;
    }

    public function validarProgramacionActual(array $envio): void
    {
        $this->verificarSnapshotOperativo($envio);
    }

    public function puedeVerDocumentoFirma(int $documentoId, int $usuarioId): bool
    {
        $envio = $this->envioPorDocumento($documentoId);
        if (! $envio || $envio['estado'] !== 'APROBADO_DIRECCION') return false;
        return in_array($usuarioId, [(int) $envio['usuario_envio_id'], (int) $envio['usuario_rrhh_id'], (int) $envio['usuario_direccion_id']], true);
    }

    public function puedeFirmarDocumento(int $documentoId, int $usuarioId): bool
    {
        $envio = $this->envioPorDocumento($documentoId);
        if (! $envio || $envio['estado'] !== 'APROBADO_DIRECCION') return false;
        if ($usuarioId === (int) $envio['usuario_envio_id']) {
            return empty($envio['firma_elaborador_at']) && empty($envio['firma_rrhh_at']) && empty($envio['firma_direccion_at']);
        }
        if ($usuarioId === (int) $envio['usuario_rrhh_id']) {
            return !empty($envio['firma_elaborador_at']) && empty($envio['firma_rrhh_at']) && empty($envio['firma_direccion_at']);
        }
        if ($usuarioId === (int) $envio['usuario_direccion_id']) {
            return !empty($envio['firma_elaborador_at']) && !empty($envio['firma_rrhh_at']) && empty($envio['firma_direccion_at']);
        }
        return false;
    }

    /** Llamado dentro de la transacción que guarda la versión firmada en FirmaPerú. */
    public function registrarFirmaDocumento(int $documentoId, int $usuarioId): void
    {
        $envio = $this->envioPorDocumento($documentoId, true);
        if (! $envio || ! $this->puedeFirmarDocumento($documentoId, $usuarioId)) throw new \DomainException('La etapa de firma ya cambió o no corresponde a este usuario.');
        $fecha = $this->gestion->ahora();
        if ($usuarioId === (int) $envio['usuario_envio_id']) $campo = 'firma_elaborador_at';
        elseif ($usuarioId === (int) $envio['usuario_rrhh_id']) $campo = 'firma_rrhh_at';
        else $campo = 'firma_direccion_at';

        $update = $this->db->table('casis_rol_envio')->where('id', (int) $envio['id'])->where('estado', 'APROBADO_DIRECCION')->where($campo, null);
        if ($campo === 'firma_rrhh_at') $update->where('firma_elaborador_at !=', null);
        if ($campo === 'firma_direccion_at') $update->where('firma_elaborador_at !=', null)->where('firma_rrhh_at !=', null);
        $data = [$campo => $fecha];
        if ($campo === 'firma_direccion_at') $data['firma_completada_at'] = $fecha;
        $update->update($data);
        if ($this->db->affectedRows() !== 1) throw new \DomainException('No se pudo avanzar la secuencia de firmas.');
    }

    private function cabeceraSnapshot(int $establecimientoId, int $oficinaId): array
    {
        $establecimiento = $this->db->table('casis_establecimiento e')
            ->select('e.est_ide, e.est_nombre, e.est_ipress, e.est_categoria, m.mic_nombre, r.red_nombre, d.dir_nombre')
            ->join('casis_microred m', 'm.mic_ide = e.est_mic_ide', 'left')
            ->join('casis_red r', 'r.red_ide = m.mic_red_ide', 'left')
            ->join('casis_diresa d', 'd.dir_ide = r.red_dir_ide', 'left')
            ->where('e.est_ide', $establecimientoId)->where('e.deleted_at', null)->get()->getRowArray();
        if (! $establecimiento) throw new \DomainException('El establecimiento del período ya no está disponible.');
        $oficinas = $this->db->table('casis_oficina')
            ->where('ofi_est_ide', $establecimientoId)->where('deleted_at', null)->get()->getResultArray();
        $tipos = $this->db->table('casis_tipo_oficina')->where('deleted_at', null)->get()->getResultArray();
        $tipos = array_column($tipos, 'tofi_nombre', 'tofi_ide');
        $index = array_column($oficinas, null, 'ofi_ide');
        $oficina = $index[$oficinaId] ?? null;
        if (! $oficina) throw new \DomainException('La oficina no pertenece al establecimiento del período.');
        $departamento = 'Todos';
        $actual = $oficina;
        $visitados = [];
        while ($actual && ! isset($visitados[$actual['ofi_ide']])) {
            $visitados[$actual['ofi_ide']] = true;
            if (mb_strtoupper($tipos[$actual['ofi_tofi_ide']] ?? '') === 'DEPARTAMENTO') {
                $departamento = $actual['ofi_nombre'];
                break;
            }
            $actual = $index[$actual['ofi_padre_ide']] ?? null;
        }
        $tipo = $tipos[$oficina['ofi_tofi_ide']] ?? 'Oficina';
        return $establecimiento + [
            'departamento' => $departamento,
            'servicio_area' => $oficina['ofi_nombre'],
            'ambito' => $tipo . ': ' . $oficina['ofi_nombre'],
            'tofi_ide' => (int) $oficina['ofi_tofi_ide'],
        ];
    }

    private function obtenerBloqueado(int $id): array
    {
        $row = $this->db->query('SELECT * FROM casis_rol_envio WHERE id = ? FOR UPDATE', [$id])->getRowArray();
        if (! $row) throw new \OutOfBoundsException('No se encontró el envío.');
        if (! hash_equals($row['hash_sha256'], hash('sha256', $row['datos_json']))) throw new \RuntimeException('La huella del snapshot no coincide.');
        return $row;
    }

    private function envioPorDocumento(int $documentoId, bool $bloquear = false): ?array
    {
        if (! $this->db->tableExists('casis_rol_envio') || ! $this->db->fieldExists('envio_id', 'casis_rol_documento')) return null;
        $query = 'SELECT e.* FROM casis_rol_documento d JOIN casis_rol_envio e ON e.id = d.envio_id WHERE d.id = ?';
        if ($bloquear) $query .= ' FOR UPDATE';
        return $this->db->query($query, [$documentoId])->getRowArray() ?: null;
    }

    private function periodoEnvio(array $envio): array
    {
        $periodo = $this->db->query(
            'SELECT p.* FROM casis_plan_rol r JOIN casis_plan_periodo p ON p.id = r.periodo_id WHERE r.id = ? FOR UPDATE',
            [(int) $envio['rol_id']]
        )->getRowArray();
        if (! $periodo) throw new \DomainException('No se encontró el período de este envío.');
        return $periodo;
    }

    private function verificarSnapshotOperativo(array $envio): void
    {
        $snapshot = json_decode($envio['datos_json'], true, 512, JSON_THROW_ON_ERROR);
        $inicio = sprintf('%04d-%02d-01', $envio['anio'], $envio['mes']);
        $fin = date('Y-m-t', strtotime($inicio));
        $idsSnapshot = array_map(static fn (array $miembro): int => (int) ($miembro['personal']['perl_ide'] ?? 0), $snapshot);
        sort($idsSnapshot);
        $idsActuales = array_map('intval', array_column($this->db->table('casis_personal')->select('perl_ide')
            ->where('perl_est_ide', (int) $envio['id_establecimiento'])->where('perl_ofi_ide', (int) $envio['id_oficina'])
            ->where('deleted_at', null)->get()->getResultArray(), 'perl_ide'));
        sort($idsActuales);
        if ($idsSnapshot !== $idsActuales) throw new \DomainException('El personal de la oficina cambió desde el envío. Devuelva el rol a observación y envíe una nueva versión.');
        foreach ($snapshot as $miembro) {
            $personalId = (int) ($miembro['personal']['perl_ide'] ?? 0);
            $actuales = $this->db->table('casis_programacion')->where('prog_perl_ide', $personalId)
                ->where('prog_fecha >=', $inicio)->where('prog_fecha <=', $fin)->where('deleted_at', null)
                ->orderBy('prog_fecha')->orderBy('prog_ide')->get()->getResultArray();
            $campos = ['prog_fecha', 'prog_th_ide', 'prog_eup_ide', 'prog_eus_ide', 'prog_estado', 'prog_observacion', 'prog_es_cambio', 'prog_origen_id'];
            $normalizar = static function (array $filas) use ($campos): array {
                $filas = array_map(static function (array $fila) use ($campos): array {
                    $r = [];
                    foreach ($campos as $campo) {
                        $valor = $fila[$campo] ?? null;
                        $r[$campo] = in_array($campo, ['prog_th_ide', 'prog_eup_ide', 'prog_eus_ide', 'prog_es_cambio', 'prog_origen_id'], true)
                            ? ($valor === null ? null : (int) $valor)
                            : $valor;
                    }
                    return $r;
                }, $filas);
                usort($filas, static fn (array $a, array $b): int => strcmp(json_encode($a), json_encode($b)));
                return $filas;
            };
            $esperados = $normalizar($miembro['turnos'] ?? []);
            $presentes = $normalizar($actuales);
            if ($esperados !== $presentes) throw new \DomainException('La programación operativa cambió desde el envío. Devuelva el rol a observación y revise el snapshot antes de aprobar.');
        }
    }

    private function comentario(string $comentario, bool $obligatorio): string
    {
        $comentario = trim($comentario);
        $largo = mb_strlen($comentario);
        if (($obligatorio && $largo < 5) || $largo > 2000) throw new \InvalidArgumentException('El comentario debe tener entre 5 y 2000 caracteres cuando la acción lo requiere.');
        return $comentario;
    }
}

<?php

namespace Modules\Asistencia\Services;

use CodeIgniter\HTTP\Files\UploadedFile;
use Modules\Asistencia\Models\AdjuntoModel;
use Modules\Asistencia\Models\RegistroCambioTurnoModel;

class CambioTurnoService
{
    private const ESTADOS_INTERCAMBIABLES = ['PROGRAMADO', 'CONFIRMADO', 'REPROGRAMADO', 'CAMBIO TURNO'];

    protected $db;
    protected PeriodoService $periodos;
    protected ProgramacionTurnoService $programacion;
    protected RegistroCambioTurnoModel $registro;
    protected AdjuntoModel $adjuntos;

    public function __construct($db = null, ?PeriodoService $periodos = null, ?ProgramacionTurnoService $programacion = null, ?RegistroCambioTurnoModel $registro = null, ?AdjuntoModel $adjuntos = null)
    {
        $this->db = $db ?? \Config\Database::connect();
        $this->periodos = $periodos ?? new PeriodoService();
        $this->programacion = $programacion ?? new ProgramacionTurnoService();
        $this->registro = $registro ?? new RegistroCambioTurnoModel();
        $this->adjuntos = $adjuntos ?? new AdjuntoModel();
    }

    public function personal(int $id): array
    {
        $personal = $this->db->table('casis_personal p')
            ->select('p.*, pe.per_paterno, pe.per_materno, pe.per_nombre')
            ->join('casis_persona pe', 'pe.per_ide = p.perl_per_ide')
            ->where('p.perl_ide', $id)->where('p.deleted_at', null)->where('p.perl_estado', 1)
            ->get()->getRowArray();
        if (!$personal) {
            throw new \DomainException('El trabajador no existe o no está activo.');
        }
        return $personal;
    }

    public function companeros(int $id): array
    {
        $p = $this->personal($id);
        if (empty($p['perl_est_ide']) || empty($p['perl_car_ide'])) {
            return [];
        }
        return $this->db->table('casis_personal p')
            ->select('p.perl_ide, pe.per_paterno, pe.per_materno, pe.per_nombre, pe.per_numero_documento')
            ->join('casis_persona pe', 'pe.per_ide = p.perl_per_ide')
            ->where('p.perl_est_ide', $p['perl_est_ide'])->where('p.perl_car_ide', $p['perl_car_ide'])
            ->where('p.perl_ide !=', $id)->where('p.perl_estado', 1)->where('p.deleted_at', null)
            ->orderBy('pe.per_paterno')->orderBy('pe.per_materno')->get()->getResultArray();
    }

    public static function validarPersonal(array $a, array $b): void
    {
        if ((int) $a['perl_ide'] === (int) $b['perl_ide']) {
            throw new \DomainException('Seleccione dos trabajadores distintos.');
        }
        foreach (['perl_est_ide', 'perl_car_ide'] as $campo) {
            if (empty($a[$campo]) || empty($b[$campo]) || (int) $a[$campo] !== (int) $b[$campo]) {
                throw new \DomainException('Ambos trabajadores deben pertenecer al mismo establecimiento y cargo.');
            }
        }
    }

    public static function version(array $p): string
    {
        return hash('sha256', json_encode($p));
    }

    public function turnos(int $personalId, string $fecha): array
    {
        $this->personal($personalId);
        (new CalendarioPersonalService())->periodo($fecha);
        $filas = $this->db->table('casis_programacion')->where('prog_perl_ide', $personalId)
            ->where('prog_fecha', $fecha)->where('deleted_at', null)
            ->whereIn('prog_estado', self::ESTADOS_INTERCAMBIABLES)
            ->orderBy('prog_ide')->get()->getResultArray();
        $result = [];
        foreach ($filas as $p) {
            $horario = $this->db->table('casis_turno_horario h')
                ->select('h.th_codigo, h.th_hora_ingreso, h.th_hora_salida, t.tur_codigo')
                ->join('casis_turno t', 't.tur_ide = h.th_tur_ide')
                ->where('h.th_ide', $p['prog_th_ide'])->where('h.deleted_at', null)->get()->getRowArray();
            if ($horario) {
                $result[] = array_merge($horario, ['prog_ide' => $p['prog_ide'], 'version' => self::version($p)]);
            }
        }
        return $result;
    }

    public function historial(int $personalId, string $inicio, string $fin): array
    {
        $filas = $this->db->table('casis_registro_cambio_turno r')
            ->select('r.*, ps.per_paterno AS sol_paterno, ps.per_materno AS sol_materno, ps.per_nombre AS sol_nombre, pa.per_paterno AS ace_paterno, pa.per_materno AS ace_materno, pa.per_nombre AS ace_nombre, hs.th_codigo AS sol_codigo, hs.th_hora_ingreso AS sol_ingreso, hs.th_hora_salida AS sol_salida, ha.th_codigo AS ace_codigo, ha.th_hora_ingreso AS ace_ingreso, ha.th_hora_salida AS ace_salida')
            ->join('casis_personal s', 's.perl_ide = r.rc_sol_perl_ide')
            ->join('casis_persona ps', 'ps.per_ide = s.perl_per_ide')
            ->join('casis_personal a', 'a.perl_ide = r.rc_ace_perl_ide')
            ->join('casis_persona pa', 'pa.per_ide = a.perl_per_ide')
            ->join('casis_turno_horario hs', 'hs.th_ide = r.rc_turno_sol_ide', 'left')
            ->join('casis_turno_horario ha', 'ha.th_ide = r.rc_turno_ace_ide', 'left')
            ->where('r.deleted_at', null)
            ->groupStart()->where('r.rc_sol_perl_ide', $personalId)->orWhere('r.rc_ace_perl_ide', $personalId)->groupEnd()
            ->groupStart()
                ->groupStart()->where('r.rc_fecha_sol >=', $inicio)->where('r.rc_fecha_sol <=', $fin)->groupEnd()
                ->orGroupStart()->where('r.rc_fecha_ace >=', $inicio)->where('r.rc_fecha_ace <=', $fin)->groupEnd()
            ->groupEnd()->orderBy('r.created_at', 'DESC')->orderBy('r.rc_ide', 'DESC')->get()->getResultArray();
        foreach ($filas as &$fila) {
            $fila['adjuntos'] = $this->adjuntos->where('adj_modulo', 'CAMBIO_TURNO')->where('adj_registro_id', $fila['rc_ide'])->findAll();
        }
        return $filas;
    }

    public function eliminar(int $cambioId, int $personalId, string $motivo, int $usuarioId): void
    {
        $motivo = trim($motivo);
        if ($motivo === '' || mb_strlen($motivo) > 2000) {
            throw new \DomainException('Indique el motivo de eliminación (máximo 2000 caracteres).');
        }
        $this->db->transException(true)->transBegin();
        try {
            $cambio = $this->db->query('SELECT * FROM casis_registro_cambio_turno WHERE rc_ide = ? FOR UPDATE', [$cambioId])->getRowArray();
            if (!$cambio || !empty($cambio['deleted_at']) || $cambio['rc_estado'] !== 'APLICADO'
                || !in_array($personalId, [(int) $cambio['rc_sol_perl_ide'], (int) $cambio['rc_ace_perl_ide']], true)) {
                throw new \DomainException('El cambio no existe, ya fue eliminado o no pertenece a este trabajador.');
            }
            $snapshot = json_decode($cambio['rc_observacion'] ?? '', true);
            $sol = $snapshot['programacion_original_sol'] ?? null;
            $ace = $snapshot['programacion_original_ace'] ?? null;
            if (!$sol || !$ace || empty($sol['prog_ide']) || empty($ace['prog_ide'])) {
                throw new \DomainException('Este cambio no conserva la programación original necesaria para restaurarlo.');
            }
            $personas = [(int) $cambio['rc_sol_perl_ide'], (int) $cambio['rc_ace_perl_ide']];
            sort($personas);
            $this->db->query('SELECT perl_ide FROM casis_personal WHERE perl_ide IN (?, ?) ORDER BY perl_ide FOR UPDATE', $personas);
            $ids = [(int) $sol['prog_ide'], (int) $ace['prog_ide']];
            sort($ids);
            $actuales = $this->db->query('SELECT * FROM casis_programacion WHERE prog_ide IN (?, ?) ORDER BY prog_ide FOR UPDATE', $ids)->getResultArray();
            $actuales = array_column($actuales, null, 'prog_ide');
            // Evitar deshacer un intercambio que aún tiene otros cambios aplicados sobre sus turnos.
            $posteriores = $this->db->query("SELECT rc_observacion FROM casis_registro_cambio_turno WHERE rc_ide > ? AND deleted_at IS NULL AND rc_estado = 'APLICADO'", [$cambioId])->getResultArray();
            foreach ($posteriores as $posterior) {
                $datos = json_decode($posterior['rc_observacion'] ?? '', true);
                $usados = [(int) ($datos['programacion_original_sol']['prog_ide'] ?? 0), (int) ($datos['programacion_original_ace']['prog_ide'] ?? 0)];
                if (array_intersect($ids, $usados)) {
                    throw new \DomainException('Estos turnos tienen un cambio posterior. Elimine primero el cambio más reciente.');
                }
            }
            foreach ([[$sol, $cambio['rc_ace_perl_ide']], [$ace, $cambio['rc_sol_perl_ide']]] as [$original, $asignado]) {
                $actual = $actuales[$original['prog_ide']] ?? null;
                if (!$actual || !empty($actual['deleted_at'])) {
                    throw new \DomainException('Uno de los turnos fue eliminado. No se puede restaurar este cambio.');
                }
                $esperado = array_replace($original, ['prog_perl_ide' => $asignado, 'prog_estado' => 'CAMBIO TURNO', 'prog_es_cambio' => 1]);
                foreach (['prog_perl_ide', 'prog_fecha', 'prog_th_ide', 'prog_eup_ide', 'prog_eus_ide', 'prog_estado', 'prog_es_cambio', 'prog_origen_id', 'prog_observacion'] as $campo) {
                    if ((string) ($actual[$campo] ?? '') !== (string) ($esperado[$campo] ?? '')) {
                        throw new \DomainException('La programación fue modificada después del intercambio. No se sobrescribirán esos cambios.');
                    }
                }
            }
            foreach (array_unique([$sol['prog_fecha'], $ace['prog_fecha']]) as $fecha) {
                foreach ($personas as $persona) {
                    try {
                        $this->periodos->validarPermisoPeriodo($persona, $fecha);
                    } catch (\Exception $e) {
                        throw new \DomainException($e->getMessage(), 0, $e);
                    }
                }
            }
            $ahora = date('Y-m-d H:i:s');
            foreach ([$sol, $ace] as $original) {
                if (!$this->db->table('casis_programacion')->where('prog_ide', $original['prog_ide'])->update([
                    'prog_perl_ide' => $original['prog_perl_ide'], 'prog_estado' => $original['prog_estado'],
                    'prog_es_cambio' => $original['prog_es_cambio'] ?? 0,
                    'updated_by' => $usuarioId, 'updated_at' => $ahora,
                ])) {
                    throw new \RuntimeException('No se pudo restaurar la programación.');
                }
            }
            foreach ([$sol, $ace] as $original) {
                $cruce = $this->programacion->validarCruceHorarios((int) $original['prog_perl_ide'], $original['prog_fecha'], (int) $original['prog_th_ide'], (int) $original['prog_ide']);
                if ($cruce['cruce']) {
                    throw new \DomainException('No se puede restaurar: ' . $cruce['mensaje']);
                }
            }
            $snapshot['reversion'] = ['motivo' => $motivo, 'usuario_id' => $usuarioId, 'fecha' => $ahora];
            if (!$this->db->table('casis_registro_cambio_turno')->where('rc_ide', $cambioId)->update([
                'rc_estado' => 'REVERTIDO', 'rc_observacion' => json_encode($snapshot, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
                'deleted_at' => $ahora, 'deleted_by' => $usuarioId, 'updated_at' => $ahora, 'updated_by' => $usuarioId,
            ]) || !$this->db->transStatus() || !$this->db->transCommit()) {
                throw new \RuntimeException('No se pudo eliminar el cambio de turno.');
            }
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    public function registrar(array $datos, ?UploadedFile $archivo, int $usuarioId): int
    {
        $solId = (int) $datos['personal_id'];
        $aceId = (int) $datos['otro_personal_id'];
        $motivo = trim($datos['motivo']);
        if ($motivo === '' || mb_strlen($motivo) > 2000) {
            throw new \DomainException('Ingrese el motivo del cambio (máximo 2000 caracteres).');
        }
        $mime = null;
        if ($archivo && $archivo->getError() !== UPLOAD_ERR_NO_FILE) {
            if (!$archivo->isValid() || $archivo->hasMoved() || $archivo->getSize() > 10 * 1024 * 1024) {
                throw new \DomainException('El sustento debe ser un archivo válido de hasta 10 MB.');
            }
            $mime = $archivo->getMimeType();
            if (!in_array($mime, ['application/pdf', 'image/jpeg', 'image/png'], true)) {
                throw new \DomainException('El sustento debe ser PDF, JPG o PNG.');
            }
        }
        $rutaGuardada = null;
        $this->db->transException(true)->transBegin();
        try {
            // Orden estable de bloqueos para serializar intercambios entre las mismas personas.
            $ids = [$solId, $aceId];
            sort($ids);
            $this->db->query('SELECT perl_ide FROM casis_personal WHERE perl_ide IN (?, ?) ORDER BY perl_ide FOR UPDATE', $ids);
            self::validarPersonal($this->personal($solId), $this->personal($aceId));
            $progIds = [(int) $datos['prog_sol_id'], (int) $datos['prog_ace_id']];
            sort($progIds);
            $filas = $this->db->query('SELECT * FROM casis_programacion WHERE prog_ide IN (?, ?) ORDER BY prog_ide FOR UPDATE', $progIds)->getResultArray();
            $porId = array_column($filas, null, 'prog_ide');
            $sol = $porId[(int) $datos['prog_sol_id']] ?? null;
            $ace = $porId[(int) $datos['prog_ace_id']] ?? null;
            foreach ([[$sol, $solId, 'version_sol'], [$ace, $aceId, 'version_ace']] as [$p, $id, $version]) {
                if (!$p || !empty($p['deleted_at']) || (int) $p['prog_perl_ide'] !== $id
                    || !in_array($p['prog_estado'], self::ESTADOS_INTERCAMBIABLES, true)
                    || !hash_equals(self::version($p), (string) ($datos[$version] ?? ''))) {
                    throw new \DomainException('La programación cambió o ya no está disponible. Vuelva a seleccionar ambos turnos.');
                }
            }
            foreach (array_unique([$sol['prog_fecha'], $ace['prog_fecha']]) as $fecha) {
                foreach ([$solId, $aceId] as $id) {
                    try {
                        $this->periodos->validarPermisoPeriodo($id, $fecha);
                    } catch (\Exception $e) {
                        throw new \DomainException($e->getMessage(), 0, $e);
                    }
                }
            }
            $ahora = date('Y-m-d H:i:s');
            // Se intercambian los responsables: cada puesto conserva su fecha, horario y servicio.
            foreach ([[$sol, $aceId], [$ace, $solId]] as [$p, $nuevoPersonal]) {
                if (!$this->db->table('casis_programacion')->where('prog_ide', $p['prog_ide'])->update([
                    'prog_perl_ide' => $nuevoPersonal, 'prog_estado' => 'CAMBIO TURNO', 'prog_es_cambio' => 1,
                    'updated_by' => $usuarioId, 'updated_at' => $ahora,
                ])) {
                    throw new \RuntimeException('No se pudo actualizar la programación.');
                }
            }
            foreach ([[$sol, $aceId], [$ace, $solId]] as [$p, $nuevoPersonal]) {
                $cruce = $this->programacion->validarCruceHorarios($nuevoPersonal, $p['prog_fecha'], (int) $p['prog_th_ide'], (int) $p['prog_ide']);
                if ($cruce['cruce']) {
                    throw new \DomainException($cruce['mensaje']);
                }
            }
            $id = $this->registro->insert([
                'rc_sol_perl_ide' => $solId, 'rc_ace_perl_ide' => $aceId,
                'rc_fecha_sol' => $sol['prog_fecha'], 'rc_fecha_ace' => $ace['prog_fecha'],
                'rc_turno_sol_ide' => $sol['prog_th_ide'], 'rc_turno_ace_ide' => $ace['prog_th_ide'],
                'rc_justificacion' => $motivo,
                'rc_observacion' => json_encode(['programacion_original_sol' => $sol, 'programacion_original_ace' => $ace], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
                'rc_estado' => 'APLICADO', 'created_by' => $usuarioId,
            ]);
            if (!$id) {
                throw new \RuntimeException('No se pudo registrar el historial del cambio.');
            }
            if ($mime !== null) {
                $nombre = bin2hex(random_bytes(16)) . '.' . ['application/pdf' => 'pdf', 'image/jpeg' => 'jpg', 'image/png' => 'png'][$mime];
                $ruta = 'uploads/cambios_turno/' . $nombre;
                $tamano = $archivo->getSize();
                $original = $archivo->getClientName();
                $archivo->move(WRITEPATH . 'uploads/cambios_turno', $nombre);
                $rutaGuardada = WRITEPATH . $ruta;
                if (!$this->adjuntos->insert([
                    'adj_modulo' => 'CAMBIO_TURNO', 'adj_registro_id' => $id, 'adj_local_path' => $ruta,
                    'adj_nombre_original' => $original, 'adj_mime_type' => $mime, 'adj_tamano' => $tamano,
                    'adj_orden' => 1, 'created_by' => $usuarioId,
                ])) {
                    throw new \RuntimeException('No se pudo guardar el sustento.');
                }
            }
            if (!$this->db->transStatus() || !$this->db->transCommit()) {
                throw new \RuntimeException('No se pudo confirmar el intercambio.');
            }
            return (int) $id;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            if ($rutaGuardada !== null && is_file($rutaGuardada)) {
                unlink($rutaGuardada);
            }
            throw $e;
        }
    }
}

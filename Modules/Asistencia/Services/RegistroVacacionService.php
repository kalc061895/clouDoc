<?php

namespace Modules\Asistencia\Services;

use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\Database\BaseConnection;
use Modules\Asistencia\Models\AdjuntoModel;

class RegistroVacacionService
{
    protected $db;
    private PeriodoService $cortes;

    public function __construct(?BaseConnection $db = null, ?PeriodoService $cortes = null)
    {
        $this->db = $db ?? \Config\Database::connect();
        $this->cortes = $cortes ?? new PeriodoService($this->db);
    }

    public static function fecha(string $valor): \DateTimeImmutable
    {
        $fecha = \DateTimeImmutable::createFromFormat('!Y-m-d', $valor);
        if (!$fecha || $fecha->format('Y-m-d') !== $valor) {
            throw new \DomainException('Fecha no valida.');
        }
        return $fecha;
    }

    public static function periodosAnuales(string $ingreso, string $hoy, ?string $cese = null): array
    {
        $base = self::fecha($ingreso);
        $limite = self::fecha($hoy);
        if ($cese && self::fecha($cese) < $limite) $limite = self::fecha($cese);
        $periodos = [];
        // Cada aniversario se calcula desde la fecha original, sin desplazar los siguientes.
        for ($n = 0; ; $n++) {
            $inicio = $base->modify('+' . $n . ' years');
            if ($inicio > $limite) break;
            $aniversario = $base->modify('+' . ($n + 1) . ' years');
            $periodos[] = [
                'inicio' => $inicio->format('Y-m-d'),
                'fin' => $aniversario->modify('-1 day')->format('Y-m-d'),
                'disponible_desde' => $aniversario->format('Y-m-d'),
                'cumplido' => $aniversario <= $limite,
            ];
        }
        return $periodos;
    }

    protected function personal(int $id): array
    {
        $p = $this->db->table('casis_personal')->where('perl_ide', $id)->where('deleted_at', null)->get()->getRowArray();
        if (!$p) throw new \DomainException('Trabajador no encontrado.');
        return $p;
    }

    public function obtenerVacacionesPorPersonal(int $id): array
    {
        $p = $this->personal($id);
        $guardados = $this->db->table('casis_vacacion')->where('vac_perl_ide', $id)->where('deleted_at', null)->get()->getResultArray();
        $usos = $this->db->table('casis_registro_vacacion')->where('rv_perl_ide', $id)->where('deleted_at', null)->orderBy('rv_fecha_inicio', 'DESC')->get()->getResultArray();
        $periodos = [];
        $aviso = null;
        try {
            $periodos = self::periodosAnuales((string) $p['perl_fecha_inicio'], date('Y-m-d'), $p['perl_fecha_cese'] ?: null);
        } catch (\DomainException $e) {
            $aviso = 'Registre una fecha de ingreso valida para calcular los periodos anuales.';
        }
        foreach ($periodos as &$periodo) {
            $periodo['vac_ide'] = null;
            $periodo['ganados'] = $periodo['cumplido'] ? 30 : 0;
            $periodo['habilitado'] = $periodo['cumplido'];
            foreach ($guardados as $k => $g) {
                if ($g['vac_periodo_inicio'] === $periodo['inicio'] && $g['vac_periodo_fin'] === $periodo['fin']) {
                    $periodo['vac_ide'] = (int) $g['vac_ide'];
                    $periodo['ganados'] = $periodo['cumplido'] ? (int) $g['vac_dias_ganados'] : 0;
                    $periodo['habilitado'] = $periodo['cumplido'] && (int) $g['vac_estado'] === 1;
                    unset($guardados[$k]);
                    break;
                }
            }
        }
        unset($periodo);
        // Conservar visibles los registros antiguos que no coinciden con el aniversario.
        foreach ($guardados as $g) {
            $periodos[] = ['inicio' => $g['vac_periodo_inicio'], 'fin' => $g['vac_periodo_fin'],
                'disponible_desde' => null, 'cumplido' => false, 'habilitado' => false,
                'vac_ide' => (int) $g['vac_ide'], 'ganados' => (int) $g['vac_dias_ganados'], 'revisar' => true];
        }
        foreach ($periodos as &$periodo) {
            $periodo['asignados'] = 0;
            foreach ($usos as $uso) {
                if ($periodo['vac_ide'] === (int) $uso['rv_vac_ide'] && (int) $uso['rv_estado'] === 1) {
                    $periodo['asignados'] += (int) $uso['rv_dias'];
                }
            }
            $periodo['saldo'] = $periodo['ganados'] - $periodo['asignados'];
        }
        unset($periodo);
        $adjuntos = new AdjuntoModel($this->db);
        foreach ($usos as &$uso) {
            $uso['adjuntos'] = $adjuntos->where('adj_modulo', 'VACACION')->where('adj_registro_id', $uso['rv_ide'])->findAll();
        }
        unset($uso);
        return ['personal' => $p, 'periodos' => $periodos, 'usos' => $usos, 'aviso' => $aviso];
    }

    private function exigirCorteEditable(int $id, string $fecha): void
    {
        self::fecha($fecha);
        $corte = $this->cortes->validarPeriodo($id, $fecha);
        if (!$corte['permitido']) throw new \DomainException($corte['mensaje']);
        $fila = $this->db->query('SELECT per_estado, deleted_at FROM casis_periodos WHERE per_ide = ? FOR UPDATE', [$corte['periodo_id']])->getRowArray();
        if (!$fila || $fila['deleted_at'] || !in_array($fila['per_estado'], ['ABIERTO', 'REABIERTO'], true)) {
            throw new \DomainException('El periodo de corte de la fecha ' . $fecha . ' cambió y ya no permite modificaciones.');
        }
    }

    public static function validarUso(array $periodo, string $inicio, string $fin): int
    {
        $desde = self::fecha($inicio);
        $hasta = self::fecha($fin);
        if (empty($periodo['habilitado']) || !$periodo['disponible_desde'] || $inicio < $periodo['disponible_desde']) {
            throw new \DomainException('El periodo debe haber cumplido los 12 meses antes del uso.');
        }
        if ($hasta < $desde) throw new \DomainException('La fecha final no puede ser anterior a la inicial.');
        $dias = (int) $desde->diff($hasta)->days + 1;
        if ($dias > $periodo['saldo']) throw new \DomainException('El uso excede el saldo disponible del periodo.');
        return $dias;
    }

    public function registrarUso(int $id, array $datos, int $usuario, ?UploadedFile $archivo = null): void
    {
        $this->guardarUso($id, null, $datos, $usuario, $archivo);
    }

    public function editarUso(int $id, int $usoId, array $datos, int $usuario, ?UploadedFile $archivo = null): void
    {
        $this->guardarUso($id, $usoId, $datos, $usuario, $archivo);
    }

    private function guardarUso(int $id, ?int $usoId, array $datos, int $usuario, ?UploadedFile $archivo): void
    {
        $mime = null;
        $rutaGuardada = null;
        if ($archivo && $archivo->getError() !== UPLOAD_ERR_NO_FILE) {
            if (!$archivo->isValid() || $archivo->hasMoved() || $archivo->getSize() > 10 * 1024 * 1024) {
                throw new \DomainException('Seleccione un archivo válido de hasta 10 MB.');
            }
            $mime = $archivo->getMimeType();
            if (!in_array($mime, ['application/pdf', 'image/jpeg', 'image/png'], true)) {
                throw new \DomainException('El sustento debe ser PDF, JPG o PNG.');
            }
        }
        $this->db->transException(true)->transBegin();
        try {
            $this->db->query('SELECT perl_ide FROM casis_personal WHERE perl_ide = ? FOR UPDATE', [$id]);
            $detalle = $this->obtenerVacacionesPorPersonal($id);
            $uso = $usoId === null ? null : $this->usoActivo($id, $usoId);
            $periodo = null;
            foreach ($detalle['periodos'] as $p) {
                if ($p['inicio'] === ($datos['periodo'] ?? '') && empty($p['revisar'])) $periodo = $p;
            }
            if (!$periodo) throw new \DomainException('Seleccione un periodo anual valido.');
            if ($uso && $periodo['vac_ide'] === (int) $uso['rv_vac_ide']) {
                $periodo['saldo'] += (int) $uso['rv_dias'];
            }
            $inicio = (string) ($datos['fecha_inicio'] ?? '');
            $fin = (string) ($datos['fecha_fin'] ?? '');
            $dias = self::validarUso($periodo, $inicio, $fin);
            if ($uso && $uso['rv_fecha_inicio'] !== $inicio) $this->exigirCorteEditable($id, $uso['rv_fecha_inicio']);
            $this->exigirCorteEditable($id, $inicio);
            $cese = $detalle['personal']['perl_fecha_cese'] ?? null;
            if ($cese && $fin > $cese) throw new \DomainException('El uso no puede superar la fecha de cese.');
            $cruce = $this->db->table('casis_registro_vacacion')->where('rv_perl_ide', $id)
                ->where('rv_estado', 1)->where('deleted_at', null)->where('rv_fecha_inicio <=', $fin)
                ->where('rv_fecha_fin >=', $inicio);
            if ($uso) $cruce->where('rv_ide !=', $usoId);
            if ($cruce->countAllResults() > 0) {
                throw new \DomainException('Las fechas se cruzan con otro uso de vacaciones.');
            }
            $ahora = date('Y-m-d H:i:s');
            $vacId = $periodo['vac_ide'];
            if (!$vacId) {
                if (!$this->db->table('casis_vacacion')->insert([
                    'vac_perl_ide' => $id, 'vac_periodo_inicio' => $periodo['inicio'], 'vac_periodo_fin' => $periodo['fin'],
                    'vac_dias_ganados' => $periodo['ganados'], 'vac_dias_gozados' => 0, 'vac_dias_pendientes' => $periodo['ganados'],
                    'vac_estado' => 1, 'created_at' => $ahora, 'created_by' => $usuario,
                ])) throw new \RuntimeException('No se pudo crear el periodo.');
                $vacId = (int) $this->db->insertID();
            }
            $registro = [
                'rv_vac_ide' => $vacId, 'rv_perl_ide' => $id, 'rv_fecha_inicio' => $inicio, 'rv_fecha_fin' => $fin,
                'rv_dias' => $dias, 'rv_numero_documento' => trim((string) ($datos['documento'] ?? '')),
                'rv_observacion' => trim((string) ($datos['observacion'] ?? '')), 'rv_estado' => 1,
            ];
            $tabla = $this->db->table('casis_registro_vacacion');
            if ($uso) {
                $ok = $tabla->where('rv_ide', $usoId)->update($registro + ['updated_at' => $ahora, 'updated_by' => $usuario]);
            } else {
                $ok = $tabla->insert($registro + ['created_at' => $ahora, 'created_by' => $usuario]);
                $usoId = (int) $this->db->insertID();
            }
            if (!$ok) throw new \RuntimeException('No se pudo guardar el uso.');
            if ($mime !== null) {
                $nombre = bin2hex(random_bytes(16)) . '.' . ['application/pdf' => 'pdf', 'image/jpeg' => 'jpg', 'image/png' => 'png'][$mime];
                $ruta = 'uploads/vacaciones/' . $nombre;
                $tamano = $archivo->getSize();
                $original = $archivo->getClientName();
                $archivo->move(WRITEPATH . 'uploads/vacaciones', $nombre);
                $rutaGuardada = WRITEPATH . $ruta;
                $adjuntos = new AdjuntoModel($this->db);
                $orden = $adjuntos->selectMax('adj_orden')->where('adj_modulo', 'VACACION')->where('adj_registro_id', $usoId)->first();
                if (!$adjuntos->insert([
                    'adj_modulo' => 'VACACION', 'adj_registro_id' => $usoId, 'adj_local_path' => $ruta,
                    'adj_nombre_original' => $original, 'adj_mime_type' => $mime, 'adj_tamano' => $tamano,
                    'adj_orden' => (int) ($orden['adj_orden'] ?? 0) + 1, 'created_by' => $usuario,
                ])) throw new \RuntimeException('No se pudo guardar el adjunto.');
            }
            $this->recalcularSaldo($id, $vacId, $usuario, $ahora);
            if ($uso && (int) $uso['rv_vac_ide'] !== $vacId) {
                $this->recalcularSaldo($id, (int) $uso['rv_vac_ide'], $usuario, $ahora);
            }
            if (!$this->db->transStatus() || !$this->db->transCommit()) throw new \RuntimeException('No se pudo confirmar el uso.');
        } catch (\Throwable $e) {
            $this->db->transRollback();
            if ($rutaGuardada !== null && is_file($rutaGuardada)) unlink($rutaGuardada);
            throw $e;
        }
    }

    public function eliminarUso(int $personalId, int $usoId, string $motivo, int $usuario): void
    {
        $motivo = trim($motivo);
        if ($motivo === '' || mb_strlen($motivo) > 2000) throw new \DomainException('Indique el motivo de eliminación (máximo 2000 caracteres).');
        $this->db->transException(true)->transBegin();
        try {
            $this->db->query('SELECT perl_ide FROM casis_personal WHERE perl_ide = ? FOR UPDATE', [$personalId]);
            $uso = $this->usoActivo($personalId, $usoId);
            $this->exigirCorteEditable($personalId, $uso['rv_fecha_inicio']);
            $ahora = date('Y-m-d H:i:s');
            if (!$this->db->table('casis_registro_vacacion')->where('rv_ide', $usoId)->update([
                'rv_estado' => 0, 'deleted_at' => $ahora, 'deleted_by' => $usuario,
                'updated_at' => $ahora, 'updated_by' => $usuario,
                'rv_observacion' => ($uso['rv_observacion'] ?? '') . "\n[Eliminación {$ahora}] " . $motivo,
            ])) throw new \RuntimeException('No se pudo eliminar el uso.');
            $this->recalcularSaldo($personalId, (int) $uso['rv_vac_ide'], $usuario, $ahora);
            if (!$this->db->transStatus() || !$this->db->transCommit()) throw new \RuntimeException('No se pudo restablecer el saldo.');
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    private function usoActivo(int $personalId, int $usoId): array
    {
        $uso = $this->db->query('SELECT * FROM casis_registro_vacacion WHERE rv_ide = ? AND rv_perl_ide = ? FOR UPDATE', [$usoId, $personalId])->getRowArray();
        if (!$uso || !empty($uso['deleted_at']) || (int) $uso['rv_estado'] !== 1) throw new \DomainException('El uso no existe o ya fue eliminado.');
        return $uso;
    }

    private function recalcularSaldo(int $personalId, int $vacId, int $usuario, string $ahora): void
    {
        $periodo = $this->db->query('SELECT * FROM casis_vacacion WHERE vac_ide = ? AND vac_perl_ide = ? FOR UPDATE', [$vacId, $personalId])->getRowArray();
        if (!$periodo || !empty($periodo['deleted_at'])) throw new \DomainException('No se encuentra el periodo de vacaciones.');
        $sum = $this->db->table('casis_registro_vacacion')->selectSum('rv_dias', 'dias')
            ->where('rv_vac_ide', $vacId)->where('rv_estado', 1)->where('deleted_at', null)->get()->getRowArray();
        $asignados = (int) ($sum['dias'] ?? 0);
        if (!$this->db->table('casis_vacacion')->where('vac_ide', $vacId)->update([
            'vac_dias_gozados' => $asignados, 'vac_dias_pendientes' => (int) $periodo['vac_dias_ganados'] - $asignados,
            'updated_at' => $ahora, 'updated_by' => $usuario,
        ])) throw new \RuntimeException('No se pudo actualizar el saldo.');
    }
}

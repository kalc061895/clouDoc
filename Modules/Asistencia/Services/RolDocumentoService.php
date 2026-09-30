<?php

namespace Modules\Asistencia\Services;

class RolDocumentoService
{
    protected $db;
    protected ProgramacionTurnoService $programacion;
    protected RolPdfRenderer $renderer;
    protected string $directorio;

    public function __construct($db = null, ?ProgramacionTurnoService $programacion = null, ?RolPdfRenderer $renderer = null, ?string $directorio = null)
    {
        $this->db = $db ?? \Config\Database::connect();
        $this->programacion = $programacion ?? new ProgramacionTurnoService();
        $this->renderer = $renderer ?? new RolPdfRenderer();
        $this->directorio = $directorio ?? WRITEPATH . 'asistencia/roles/';
    }

    public function catalogos(): array
    {
        return [
            'establecimientos' => $this->db->table('casis_establecimiento')->select('est_ide, est_nombre')->where('deleted_at', null)->orderBy('est_nombre')->get()->getResultArray(),
            'tipos' => $this->db->table('casis_tipo_oficina')->select('tofi_ide, tofi_nombre')->where('deleted_at', null)->where('tofi_estado', 1)->orderBy('tofi_nombre')->get()->getResultArray(),
            'oficinas' => $this->db->table('casis_oficina')->select('ofi_ide, ofi_nombre, ofi_est_ide, ofi_tofi_ide, ofi_padre_ide')->where('deleted_at', null)->where('ofi_estado', 1)->orderBy('ofi_nombre')->get()->getResultArray(),
            'upss' => $this->db->table('casis_establecimiento_upss e')->select('e.eup_est_ide AS est_ide, u.ups_ide, u.ups_nombre')->join('casis_upss u', 'u.ups_ide = e.eup_ups_ide')->where('e.deleted_at', null)->where('u.deleted_at', null)->where('e.eup_estado', 1)->where('u.ups_estado', 1)->orderBy('u.ups_nombre')->get()->getResultArray(),
            'servicios' => $this->db->table('casis_establecimiento_upss_servicio s')->select('e.eup_est_ide AS est_ide, e.eup_ups_ide AS ups_ide, u.uss_ide, u.uss_nombre')->join('casis_establecimiento_upss e', 'e.eup_ide = s.eus_eup_ide')->join('casis_upss_servicio u', 'u.uss_ide = s.eus_uss_ide')->where('s.deleted_at', null)->where('e.deleted_at', null)->where('u.deleted_at', null)->where('s.eus_estado', 1)->where('e.eup_estado', 1)->where('u.uss_estado', 1)->orderBy('u.uss_nombre')->get()->getResultArray(),
        ];
    }

    public function consultar(array $input): array
    {
        $f = RolDocumentoData::filtros($input);
        $est = $this->db->table('casis_establecimiento e')
            ->select('e.est_ide, e.est_nombre, e.est_ipress, e.est_categoria, m.mic_nombre, r.red_nombre, d.dir_nombre')
            ->join('casis_microred m', 'm.mic_ide = e.est_mic_ide', 'left')
            ->join('casis_red r', 'r.red_ide = m.mic_red_ide', 'left')
            ->join('casis_diresa d', 'd.dir_ide = r.red_dir_ide', 'left')
            ->where('e.est_ide', $f['est_ide'])->where('e.deleted_at', null)->get()->getRowArray();
        if (! $est) {
            throw new \InvalidArgumentException('El establecimiento no existe.');
        }
        $catalogos = $this->catalogos();
        $oficinas = array_values(array_filter($catalogos['oficinas'], static fn($o) => (int) $o['ofi_est_ide'] === $f['est_ide']));
        $tipos = array_column($catalogos['tipos'], 'tofi_nombre', 'tofi_ide');
        if ($f['tofi_ide'] && ! isset($tipos[$f['tofi_ide']])) {
            throw new \InvalidArgumentException('El tipo de oficina no existe o está inactivo.');
        }
        $oficinaIds = RolDocumentoData::oficinasIncluidas($oficinas, $f['ofi_ide'], $f['tofi_ide'], $f['incluir_hijos']);
        $upss = $this->seleccionRelacionada($catalogos['upss'], $f, 'ups_ide', 'ups_nombre');
        $servicio = $this->seleccionRelacionada($catalogos['servicios'], $f, 'uss_ide', 'uss_nombre');
        $index = array_column($oficinas, null, 'ofi_ide');
        $oficina = $index[$f['ofi_ide']] ?? null;
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
        $ambito = $oficina ? ($tipos[$oficina['ofi_tofi_ide']] ?? 'Oficina') . ': ' . $oficina['ofi_nombre'] : ($f['tofi_ide'] ? 'Tipo: ' . $tipos[$f['tofi_ide']] : 'Todo el establecimiento');
        if ($f['incluir_hijos'] && ($f['ofi_ide'] || $f['tofi_ide'])) {
            $ambito .= ' (incluye dependencias)';
        }
        if ($upss) $ambito .= ' / UPSS: ' . $upss;
        if ($servicio) $ambito .= ' / Servicio: ' . $servicio;

        $matriz = $this->programacion->obtenerMatrizMensual($f['anio'], $f['mes'], $f['est_ide'], $f['ups_ide'], $f['uss_ide']);
        if ($oficinaIds !== null) {
            $personalIds = $oficinaIds ? array_column($this->db->table('casis_personal')->select('perl_ide')->where('perl_est_ide', $f['est_ide'])->whereIn('perl_ofi_ide', $oficinaIds)->where('deleted_at', null)->get()->getResultArray(), 'perl_ide') : [];
            $permitidos = array_fill_keys($personalIds, true);
            $matriz['matriz'] = array_values(array_filter($matriz['matriz'], static fn($p) => isset($permitidos[$p['perl_ide']])));
        }
        // Un rol documenta turnos asignados; no imprime personal sin programación en ese ámbito.
        $matriz['matriz'] = array_values(array_filter($matriz['matriz'], static fn($p) => ! empty($p['total_turnos'])));
        $cabecera = $est + [
            'departamento' => $departamento,
            'servicio_area' => $servicio ?: ($upss ? $upss . ' (UPSS)' : ($oficina['ofi_nombre'] ?? 'Todos')),
            'ambito' => $ambito,
        ];
        return RolDocumentoData::preparar($matriz, $cabecera, $f);
    }

    private function seleccionRelacionada(array $rows, array $f, string $key, string $label): ?string
    {
        if (! $f[$key]) return null;
        foreach ($rows as $row) {
            if (
                (int) $row[$key] === $f[$key] && (int) $row['est_ide'] === $f['est_ide']
                && ($key !== 'uss_ide' || ! $f['ups_ide'] || (int) $row['ups_ide'] === $f['ups_ide'])
            ) {
                return $row[$label];
            }
        }
        throw new \InvalidArgumentException('La UPSS o el servicio no corresponde al establecimiento y filtros elegidos.');
    }

    public function generar(array $input, int $usuario): array
    {
        $documento = $this->consultar($input);
        if (! $documento['personal']) {
            throw new \InvalidArgumentException('No hay turnos programados para los filtros seleccionados.');
        }
        $huella = $input['huella'] ?? '';
        if (! is_string($huella) || ! hash_equals(RolDocumentoData::huella($documento), $huella)) {
            throw new \DomainException('La programación o los filtros cambiaron. Consulte nuevamente antes de generar.');
        }
        $f = $documento['filtros'];
        $codigo = sprintf('ROL-%04d%02d-%s', $f['anio'], $f['mes'], strtoupper(bin2hex(random_bytes(8))));
        $fecha = date('Y-m-d H:i:s');
        $documento['codigo'] = $codigo;
        $documento['generado_at'] = $fecha;
        $bytes = $this->renderer->generar($documento);
        if (! str_starts_with($bytes, '%PDF-')) {
            throw new \RuntimeException('No se pudo construir el archivo PDF.');
        }
        if (! is_dir($this->directorio) && ! mkdir($this->directorio, 0770, true) && ! is_dir($this->directorio)) {
            throw new \RuntimeException('No se pudo crear el directorio de roles.');
        }
        $archivo = $codigo . '.pdf';
        $ruta = $this->directorio . $archivo;
        try {
            if (file_put_contents($ruta, $bytes, LOCK_EX) !== strlen($bytes)) {
                throw new \RuntimeException('No se pudo guardar el PDF completo.');
            }
            $row = [
                'codigo' => $codigo,
                'anio' => $f['anio'],
                'mes' => $f['mes'],
                'est_ide' => $f['est_ide'],
                'establecimiento' => $documento['cabecera']['est_nombre'],
                'ambito' => $documento['cabecera']['ambito'],
                'estado' => 'GENERADO',
                'filtros_json' => json_encode($f, JSON_THROW_ON_ERROR),
                'snapshot_json' => json_encode($documento, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
                'archivo' => $archivo,
                'sha256' => hash('sha256', $bytes),
                'total_personal' => count($documento['personal']),
                'total_horas' => $documento['total_horas'],
                'created_by' => $usuario,
                'created_at' => $fecha,
            ];
            if (! $this->db->table('casis_rol_documento')->insert($row)) {
                throw new \RuntimeException('No se pudo registrar el documento.');
            }
            return ['id' => (int) $this->db->insertID(), 'codigo' => $codigo, 'estado' => 'GENERADO'];
        } catch (\Throwable $e) {
            if (is_file($ruta)) unlink($ruta);
            throw $e;
        }
    }

    public function historial(array $input): array
    {
        $builder = $this->db->table('casis_rol_documento')->select('id, codigo, anio, mes, establecimiento, ambito, estado, total_personal, total_horas, created_at, created_by, anulado_at, motivo_anulacion');
        foreach (['anio' => [2000, 2100], 'mes' => [1, 12], 'est_ide' => [1, 2147483647]] as $key => [$min, $max]) {
            $value = $input[$key] ?? '';
            if ($value !== '') {
                if (! is_scalar($value) || ! ctype_digit((string) $value) || (int) $value < $min || (int) $value > $max) {
                    throw new \InvalidArgumentException('Filtro inválido: ' . $key);
                }
                $builder->where($key, (int) $value);
            }
        }
        $estado = $input['estado'] ?? '';
        if ($estado !== '') {
            if (! in_array($estado, ['GENERADO', 'ANULADO'], true)) throw new \InvalidArgumentException('Estado inválido.');
            $builder->where('estado', $estado);
        }
        $page = filter_var($input['pagina'] ?? 1, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 100000]]) ?: 1;
        $total = $builder->countAllResults(false);
        return ['filas' => $builder->orderBy('id', 'DESC')->limit(25, ($page - 1) * 25)->get()->getResultArray(), 'total' => $total, 'pagina' => $page, 'paginas' => max(1, (int) ceil($total / 25))];
    }

    public function archivo(int $id): array
    {
        $row = $this->db->table('casis_rol_documento')->where('id', $id)->get()->getRowArray();
        if (! $row) throw new \OutOfBoundsException('El rol no existe.');
        if (! preg_match('/^ROL-\d{6}-[A-F0-9]{16}\.pdf$/D', $row['archivo'])) {
            throw new \RuntimeException('La ruta del documento no es válida.');
        }
        $ruta = $this->directorio . $row['archivo'];
        if (! is_file($ruta) || ! hash_equals($row['sha256'], hash_file('sha256', $ruta))) {
            throw new \RuntimeException('El PDF no está disponible o su integridad no coincide con el registro.');
        }
        return ['ruta' => $ruta, 'nombre' => $row['archivo'], 'estado' => $row['estado']];
    }

    public function anular(int $id, string $motivo, int $usuario): void
    {
        $motivo = trim($motivo);
        if (mb_strlen($motivo) < 5 || mb_strlen($motivo) > 500) {
            throw new \InvalidArgumentException('El motivo debe tener entre 5 y 500 caracteres.');
        }
        $ok = $this->db->table('casis_rol_documento')->where('id', $id)->where('estado', 'GENERADO')->update([
            'estado' => 'ANULADO',
            'motivo_anulacion' => $motivo,
            'anulado_por' => $usuario,
            'anulado_at' => date('Y-m-d H:i:s'),
        ]);
        if (! $ok || $this->db->affectedRows() !== 1) throw new \DomainException('El rol no existe o ya fue anulado.');
    }
}

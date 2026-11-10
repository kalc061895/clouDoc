<?php

namespace Modules\Asistencia\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class TuaSusaludService
{
    private $db;
    private GrupoProgramacionRolService $grupos;
    private ?array $ambitoCache = null;

    public function __construct($db = null)
    {
        $this->db = $db ?? \Config\Database::connect();
        $this->grupos = new GrupoProgramacionRolService($this->db);
    }

    public function establecimientos(): array
    {
        return $this->db->table('casis_establecimiento e')->select('e.est_ide, e.est_nombre, e.est_ipress, m.mic_nombre, r.red_nombre')
            ->join('casis_microred m', 'm.mic_ide=e.est_mic_ide AND m.deleted_at IS NULL', 'left')
            ->join('casis_red r', 'r.red_ide=m.mic_red_ide AND r.deleted_at IS NULL', 'left')
            ->where('e.deleted_at', null)->orderBy('e.est_nombre')->get()->getResultArray();
    }

    public function establecimientosAccesibles(): array
    {
        $rows=$this->establecimientos();$ids=$this->ambitoEstablecimientos();
        return $ids===null ? $rows : array_values(array_filter($rows,static fn($e)=>in_array((int)$e['est_ide'],$ids,true)));
    }

    public function puedeAcceder(): bool
    {
        return $this->ambitoEstablecimientos() !== [];
    }

    public function puedeConfigurar(): bool
    {
        $user = function_exists('auth') ? auth()->user() : null;
        if (!$user) return false;
        if ($user->inGroup('superadmin', 'asistencia', 'asi_sua', 'asi_adm', 'asi_dm')) return true;
        $perfil=(new AccesoAsistenciaService())->perfilTerritorial();
        return $perfil && !($perfil['global']??false) && ($perfil['modo']??'')==='completo' && !empty($this->ambitoEstablecimientos());
    }

    private function ambitoEstablecimientos(): ?array
    {
        if ($this->ambitoCache !== null) return $this->ambitoCache;
        $user = function_exists('auth') ? auth()->user() : null;
        if (!$user) return $this->ambitoCache = [];
        if ($user->inGroup('superadmin','asistencia','asi_sua','asi_adm','asi_dm')) return $this->ambitoCache = null;
        $acceso = new AccesoAsistenciaService();
        $perfil = $acceso->perfilTerritorial();
        if ($perfil) {
            if ($perfil['global'] ?? false) return $this->ambitoCache = null;
            return $this->ambitoCache = array_values(array_unique(array_map('intval', $acceso->establecimientosDelAmbito())));
        }
        $gestion = new GestionRolService();
        $oficinas = [];
        if ($user->inGroup('asi_rol')) $oficinas = $acceso->oficinaActualDeUsuario() ? [$acceso->oficinaActualDeUsuario()] : [];
        elseif ($user->inGroup('asi_dire')) $oficinas = $gestion->oficinas(true, 'DIRECCION') ?? [];
        elseif ($user->inGroup('asi_rrhh')) $oficinas = $gestion->oficinas(true, 'RRHH') ?? [];
        if (!$oficinas) return $this->ambitoCache = [];
        return $this->ambitoCache = array_values(array_unique(array_map('intval', array_column(
            $this->db->table('casis_oficina')->select('ofi_est_ide')->whereIn('ofi_ide', $oficinas)->where('deleted_at', null)->get()->getResultArray(), 'ofi_est_ide'
        ))));
    }

    public function validarEstablecimiento(int $establecimientoId): void
    {
        $ids=$this->ambitoEstablecimientos();
        if ($establecimientoId<1 || ($ids!==null && !in_array($establecimientoId,$ids,true))) throw new \DomainException('El establecimiento está fuera del ámbito de acceso.');
    }

    public function periodos(int $establecimientoId): array
    {
        if ($establecimientoId < 1) return [];
        $this->validarEstablecimiento($establecimientoId);
        $rows = $this->db->table('casis_plan_periodo')->select('mes')->distinct()->orderBy('mes', 'DESC')->get()->getResultArray();
        $result = [];
        foreach ($rows as $row) {
            $mes = (string) $row['mes'];
            $periodo = $this->grupos->resolverPeriodo($establecimientoId, $mes);
            if (!$periodo || !in_array($establecimientoId, $this->grupos->establecimientosDePeriodo($periodo), true)) continue;
            $desde = $mes . '-01';
            $hasta = (new \DateTimeImmutable($desde))->modify('+1 month')->format('Y-m-d');
            $hayProgramacion = $this->db->table('casis_programacion pr')->join('casis_personal p', 'p.perl_ide=pr.prog_perl_ide')
                ->join('casis_cargo c', "c.car_ide=p.perl_car_ide AND c.deleted_at IS NULL AND c.car_modalidad='ASISTENCIAL'")
                ->where('p.perl_est_ide', $establecimientoId)->where('p.deleted_at', null)->where('pr.deleted_at', null)
                ->where('pr.prog_fecha >=', $desde)->where('pr.prog_fecha <', $hasta)->whereNotIn('pr.prog_estado',['ANULADO','CANCELADO'])->countAllResults() > 0;
            if ($hayProgramacion) $result[] = ['mes' => $mes, 'id' => (int) $periodo['id'], 'nivel' => $periodo['nivel'], 'grupo' => $periodo['grupo_nombre'] ?? ''];
        }
        return $result;
    }

    public function datos(int $establecimientoId, string $mes): array
    {
        $this->validarEstablecimiento($establecimientoId);
        $periodo = $this->grupos->resolverPeriodo($establecimientoId, $mes);
        $mesValido = array_column($this->periodos($establecimientoId), 'mes');
        if (!$periodo || !in_array($mes, $mesValido, true)) throw new \DomainException('Seleccione un período con programación registrada para el establecimiento.');
        $inicio = $mes . '-01';
        $fin = (new \DateTimeImmutable($inicio))->modify('+1 month')->format('Y-m-d');
        $personal = $this->db->table('casis_personal p')->select("p.perl_ide, e.est_ipress, e.est_nombre, r.red_nombre, td.tdi_abreviatura, pe.per_numero_documento, col.col_abreviatura, pp.pp_numero_colegiatura, pe.per_paterno, pe.per_materno, pe.per_nombre, se.se_nombre, s.uss_nombre")
            ->join('casis_persona pe', 'pe.per_ide=p.perl_per_ide AND pe.deleted_at IS NULL', 'inner')
            ->join('casis_establecimiento e', 'e.est_ide=p.perl_est_ide AND e.deleted_at IS NULL', 'inner')
            ->join('casis_microred m', 'm.mic_ide=e.est_mic_ide AND m.deleted_at IS NULL', 'left')
            ->join('casis_red r', 'r.red_ide=m.mic_red_ide AND r.deleted_at IS NULL', 'left')
            ->join('casis_tipo_documento_identidad td', 'td.tdi_ide=pe.per_tdi_ide', 'left')
            ->join('casis_personal_profesion pp', 'pp.pp_perl_ide=p.perl_ide AND pp.pp_principal=1 AND pp.deleted_at IS NULL', 'inner')
            ->join('casis_cargo c', "c.car_ide=p.perl_car_ide AND c.deleted_at IS NULL AND c.car_modalidad='ASISTENCIAL'", 'inner')
            ->join('casis_colegiatura col', 'col.col_ide=pp.pp_col_ide AND col.deleted_at IS NULL', 'left')
            ->join('casis_segunda_especialidad se', 'se.se_ide=pp.pp_se_ide AND se.deleted_at IS NULL', 'left')
            ->join('casis_tua_servicio_oficina map', 'map.oficina_id=p.perl_ofi_ide', 'left')
            ->join('casis_upss_servicio s', 's.uss_ide=map.servicio_id AND s.deleted_at IS NULL AND s.uss_estado=1', 'left')
            ->where('p.perl_est_ide', $establecimientoId)->where('p.perl_estado', 1)->where('p.deleted_at', null)
            ->orderBy('pe.per_paterno')->orderBy('pe.per_materno')->orderBy('pe.per_nombre')->get()->getResultArray();
        $ids = array_map('intval', array_column($personal, 'perl_ide'));
        $turnos = [];
        if ($ids) {
            $resultados = $this->db->table('casis_programacion p')->select('p.prog_perl_ide, DAY(p.prog_fecha) AS dia, h.th_hora_ingreso, h.th_hora_salida')
                ->join('casis_turno_horario h', 'h.th_ide=p.prog_th_ide')->whereIn('p.prog_perl_ide', $ids)
                ->where('p.prog_fecha >=', $inicio)->where('p.prog_fecha <', $fin)->where('p.deleted_at', null)->whereNotIn('p.prog_estado',['ANULADO','CANCELADO'])
                ->orderBy('p.prog_fecha')->orderBy('h.th_hora_ingreso')->get()->getResultArray();
            foreach ($resultados as $t) {
                $key = (int) $t['prog_perl_ide'];
                $dia = (int) $t['dia'];
                $turnos[$key][$dia]['in'][] = substr((string) $t['th_hora_ingreso'], 0, 5);
                $turnos[$key][$dia]['out'][] = substr((string) $t['th_hora_salida'], 0, 5);
            }
        }
        foreach ($personal as &$fila) {
            $fila['especialidad'] = $fila['se_nombre'] ?: 'SIN ESPECIALIDAD';
            $fila['profesion'] = $fila['col_abreviatura'] ?: '';
            $fila['colegiatura'] = trim((string) $fila['pp_numero_colegiatura']) !== '' ? $fila['pp_numero_colegiatura'] : '000000';
            $fila['apellidos'] = trim(($fila['per_paterno'] ?? '') . ' ' . ($fila['per_materno'] ?? ''));
            $fila['servicio'] = $fila['uss_nombre'] ?: 'SIN ASIGNACION';
            $fila['dias'] = $turnos[(int) $fila['perl_ide']] ?? [];
        }
        unset($fila);
        return ['personal' => $personal, 'mes' => $mes, 'diasMes' => (int) (new \DateTimeImmutable($inicio))->format('t'), 'periodo' => $periodo];
    }

    public function excel(int $establecimientoId, string $mes): Spreadsheet
    {
        $data = $this->datos($establecimientoId, $mes);
        $book = new Spreadsheet();
        $sheet = $book->getActiveSheet();
        $sheet->setTitle('TUA SUSALUD');
        $fijos = ['Código Único', 'Nombre', 'RED', 'Tipo Documento', 'Número Documento', 'Profesión', 'Número de Colegiatura', 'Apellidos', 'Nombres', 'Especialidad', 'Servicio'];
        foreach ($fijos as $i => $label) $sheet->setCellValue(Coordinate::stringFromColumnIndex($i + 1) . '2', $label);
        foreach (range(1, 31) as $dia) {
            $col = 12 + (($dia - 1) * 2);
            $letra = Coordinate::stringFromColumnIndex($col);
            $siguiente = Coordinate::stringFromColumnIndex($col + 1);
            $sheet->mergeCells($letra . '1:' . $siguiente . '1');
            $sheet->setCellValue($letra . '1', 'Día ' . $dia);
            $sheet->setCellValue($letra . '2', 'Ingreso');
            $sheet->setCellValue($siguiente . '2', 'Salida');
        }
        $sheet->mergeCells('A1:B1');
        $sheet->setCellValue('A1', 'IPRESS');
        $sheet->mergeCells('C1:K1');
        $sheet->setCellValue('C1', 'PROFESIONAL EN SALUD');
        $row = 3;
        foreach ($data['personal'] as $p) {
            $vals = [$p['est_ipress'] ?? '', $p['est_nombre'] ?? '', $p['red_nombre'] ?? '', $p['tdi_abreviatura'] ?? '', $p['per_numero_documento'] ?? '', $p['profesion'], $p['colegiatura'], $p['apellidos'], $p['per_nombre'] ?? '', $p['especialidad'], $p['servicio']];
            foreach ($vals as $i => $value) $sheet->setCellValue(Coordinate::stringFromColumnIndex($i + 1) . $row, $value);
            for ($d = 1; $d <= 31; $d++) {
                $colIn = Coordinate::stringFromColumnIndex(12 + (($d - 1) * 2));
                $colOut = Coordinate::stringFromColumnIndex(13 + (($d - 1) * 2));
                $sheet->setCellValue($colIn . $row, implode("\n", $p['dias'][$d]['in'] ?? []));
                $sheet->setCellValue($colOut . $row, implode("\n", $p['dias'][$d]['out'] ?? []));
            }
            $row++;
        }
        $last = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(73);
        $sheet->getStyle("A1:{$last}" . max(2, $row - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FFD1D5DB');
        $sheet->getStyle("A1:{$last}2")->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle("A1:{$last}2")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF174A7E');
        $sheet->getStyle("A1:{$last}" . max(2, $row - 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
        $sheet->getStyle("L1:{$last}" . max(2, $row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->setAutoFilter("A2:{$last}" . max(2, $row - 1));
        foreach (range(1, 11) as $c) $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($c))->setWidth($c === 10 || $c === 11 ? 26 : 18);
        for ($c = 12; $c <= 73; $c++) $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($c))->setWidth(11);
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A3)->setFitToWidth(1)->setFitToHeight(0);
        $sheet->getPageSetup()->setFitToPage(true);
        $book->getProperties()->setTitle('TUA SUSALUD ' . $mes);
        return $book;
    }

    public function catalogoAsignacion(int $establecimientoId = 0): array
    {
        $establecimientos = $this->establecimientosAccesibles();
        $oficinas = [];
        $servicios = [];
        $asignados = [];
        if ($establecimientoId) {
            $this->validarEstablecimiento($establecimientoId);
            $oficinas = $this->db->table('casis_oficina')->where('ofi_est_ide', $establecimientoId)->where('ofi_estado',1)->where('deleted_at', null)->orderBy('ofi_nombre')->get()->getResultArray();
            $servicios = $this->db->table('casis_establecimiento_upss e')->select('DISTINCT s.uss_ide, s.uss_nombre', false)->join('casis_establecimiento_upss_servicio es', 'es.eus_eup_ide=e.eup_ide AND es.deleted_at IS NULL AND es.eus_estado=1')->join('casis_upss_servicio s', 's.uss_ide=es.eus_uss_ide AND s.deleted_at IS NULL AND s.uss_estado=1')->where('e.eup_est_ide', $establecimientoId)->where('e.deleted_at', null)->where('e.eup_estado', 1)->orderBy('s.uss_nombre')->get()->getResultArray();
            $asignados = array_column($this->db->table('casis_tua_servicio_oficina')->whereIn('oficina_id', array_map('intval', array_column($oficinas, 'ofi_ide')) ?: [0])->get()->getResultArray(), 'servicio_id', 'oficina_id');
        }
        return compact('establecimientos', 'oficinas', 'servicios', 'asignados', 'establecimientoId');
    }

    public function listarAsignaciones(int $establecimientoId = 0): array
    {
        if ($establecimientoId) $this->validarEstablecimiento($establecimientoId);
        $q=$this->db->table('casis_tua_servicio_oficina x')->select('x.id, x.oficina_id, x.servicio_id, x.created_at, x.updated_at, o.ofi_nombre, e.est_ide, e.est_nombre, e.est_ipress, r.red_nombre, s.uss_nombre')
            ->join('casis_oficina o','o.ofi_ide=x.oficina_id AND o.deleted_at IS NULL','inner')
            ->join('casis_establecimiento e','e.est_ide=o.ofi_est_ide AND e.deleted_at IS NULL','inner')
            ->join('casis_microred m','m.mic_ide=e.est_mic_ide AND m.deleted_at IS NULL','left')
            ->join('casis_red r','r.red_ide=m.mic_red_ide AND r.deleted_at IS NULL','left')
            ->join('casis_upss_servicio s','s.uss_ide=x.servicio_id AND s.deleted_at IS NULL','inner');
        $ids=$this->ambitoEstablecimientos();if($ids!==null)$q->whereIn('e.est_ide',$ids?:[0]);
        if($establecimientoId)$q->where('e.est_ide',$establecimientoId);
        return $q->orderBy('e.est_nombre')->orderBy('o.ofi_nombre')->get()->getResultArray();
    }

    public function guardarRelacionServicioOficina(array $datos): int
    {
        $id=(int)($datos['id']??0);$establecimiento=(int)($datos['establecimiento_id']??0);$oficina=(int)($datos['oficina_id']??0);$servicio=(int)($datos['servicio_id']??0);
        $this->validarEstablecimiento($establecimiento);
        if(!$this->db->table('casis_oficina')->where('ofi_ide',$oficina)->where('ofi_est_ide',$establecimiento)->where('ofi_estado',1)->where('deleted_at',null)->countAllResults()) throw new \DomainException('Seleccione una oficina activa del establecimiento.');
        $valido=$this->db->table('casis_establecimiento_upss e')->join('casis_establecimiento_upss_servicio es','es.eus_eup_ide=e.eup_ide')->join('casis_upss_servicio s','s.uss_ide=es.eus_uss_ide AND s.deleted_at IS NULL AND s.uss_estado=1')
            ->where('e.eup_est_ide',$establecimiento)->where('es.eus_uss_ide',$servicio)->where('e.deleted_at',null)->where('e.eup_estado',1)
            ->where('es.deleted_at',null)->where('es.eus_estado',1)->countAllResults()>0;
        if(!$valido) throw new \DomainException('Seleccione un servicio UPSS activo del establecimiento.');
        $duplicado=$this->db->table('casis_tua_servicio_oficina')->where('oficina_id',$oficina);
        if($id)$duplicado->where('id !=',$id);
        if($duplicado->countAllResults())throw new \DomainException('La oficina ya tiene un servicio asignado. Edite la relación existente.');
        $now=date('Y-m-d H:i:s');$uid=function_exists('auth')&&auth()->loggedIn()?(int)auth()->id():null;
        if($id){$actual=$this->db->table('casis_tua_servicio_oficina')->where('id',$id)->get()->getRowArray();if(!$actual)throw new \DomainException('La relación no existe.');$this->validarOficinaAsignada((int)$actual['oficina_id']);$this->db->table('casis_tua_servicio_oficina')->where('id',$id)->update(['oficina_id'=>$oficina,'servicio_id'=>$servicio,'updated_at'=>$now,'updated_by'=>$uid]);return $id;}
        $this->db->table('casis_tua_servicio_oficina')->insert(['oficina_id'=>$oficina,'servicio_id'=>$servicio,'created_at'=>$now,'updated_at'=>$now,'created_by'=>$uid,'updated_by'=>$uid]);
        return (int)$this->db->insertID();
    }

    public function eliminarRelacionServicioOficina(int $id): void
    {
        $row=$this->db->table('casis_tua_servicio_oficina')->where('id',$id)->get()->getRowArray();
        if(!$row)throw new \DomainException('La relación ya no existe.');
        $this->validarOficinaAsignada((int)$row['oficina_id']);
        $this->db->table('casis_tua_servicio_oficina')->where('id',$id)->delete();
    }

    private function validarOficinaAsignada(int $oficinaId): void
    {
        $row=$this->db->table('casis_oficina')->select('ofi_est_ide')->where('ofi_ide',$oficinaId)->where('deleted_at',null)->get()->getRowArray();
        if(!$row)throw new \DomainException('La relación está fuera del ámbito disponible.');
        $this->validarEstablecimiento((int)$row['ofi_est_ide']);
    }

    public function guardarAsignacion(int $establecimientoId, array $asignaciones): void
    {
        $this->validarEstablecimiento($establecimientoId);
        $permitidas = array_map('intval', array_column($this->db->table('casis_oficina')->select('ofi_ide')->where('ofi_est_ide', $establecimientoId)->where('deleted_at', null)->get()->getResultArray(), 'ofi_ide'));
        $this->db->transBegin();
        try {
            foreach ($asignaciones as $oficina => $servicio) {
                $oficina = (int) $oficina;
                $servicio = (int) $servicio;
                if (!in_array($oficina, $permitidas, true)) throw new \DomainException('Una oficina no pertenece al establecimiento seleccionado.');
                if (!$servicio) {
                    $this->db->table('casis_tua_servicio_oficina')->where('oficina_id', $oficina)->delete();
                    continue;
                }
                $valido = $this->db->table('casis_establecimiento_upss e')->join('casis_establecimiento_upss_servicio es', 'es.eus_eup_ide=e.eup_ide')->where('e.eup_est_ide', $establecimientoId)->where('es.eus_uss_ide', $servicio)->where('e.deleted_at', null)->where('e.eup_estado', 1)->where('es.deleted_at', null)->where('es.eus_estado', 1)->countAllResults() > 0;
                if (!$valido) throw new \DomainException('El servicio no está habilitado para el establecimiento.');
                $actual = $this->db->table('casis_tua_servicio_oficina')->where('oficina_id', $oficina)->get()->getRowArray();
                $values = ['oficina_id' => $oficina, 'servicio_id' => $servicio, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => function_exists('auth') && auth()->loggedIn() ? auth()->id() : null];
                if ($actual) $this->db->table('casis_tua_servicio_oficina')->where('oficina_id', $oficina)->update($values);
                else $this->db->table('casis_tua_servicio_oficina')->insert($values + ['created_at' => date('Y-m-d H:i:s'), 'created_by' => function_exists('auth') && auth()->loggedIn() ? auth()->id() : null]);
            }
            if (!$this->db->transStatus()) throw new \RuntimeException('No se pudo guardar las asignaciones.');
            $this->db->transCommit();
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }
}

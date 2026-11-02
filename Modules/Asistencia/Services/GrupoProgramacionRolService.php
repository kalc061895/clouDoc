<?php

namespace Modules\Asistencia\Services;

/** Scope resolver for role-programming periods, from the closest unit upward. */
class GrupoProgramacionRolService
{
    private $db;
    private const NIVELES = ['ESTABLECIMIENTO','MICRORED','RED','DIRESA'];

    public function __construct($db = null) { $this->db = $db ?? \Config\Database::connect(); }

    public function listar(): array
    {
        return $this->db->table('casis_plan_grupo g')
            ->select('g.*, e.est_nombre, m.mic_nombre, r.red_nombre, d.dir_nombre')
            ->join('casis_establecimiento e', "g.nivel='ESTABLECIMIENTO' AND e.est_ide=g.entidad_id", 'left')
            ->join('casis_microred m', "g.nivel='MICRORED' AND m.mic_ide=g.entidad_id", 'left')
            ->join('casis_red r', "g.nivel='RED' AND r.red_ide=g.entidad_id", 'left')
            ->join('casis_diresa d', "g.nivel='DIRESA' AND d.dir_ide=g.entidad_id", 'left')
            ->orderBy('g.nivel')->orderBy('g.nombre')->get()->getResultArray();
    }

    public function guardar(array $datos, int $id = 0): int
    {
        $this->exigirAdmin();
        $nivel = strtoupper(trim((string)($datos['nivel'] ?? '')));
        $entidadId = (int)($datos['entidad_id'] ?? 0);
        $nombre = trim((string)($datos['nombre'] ?? ''));
        if (!in_array($nivel, self::NIVELES, true) || $entidadId < 1) throw new \DomainException('Seleccione un nivel y una entidad válidos.');
        if ($nombre === '' || mb_strlen($nombre) > 150) throw new \DomainException('Indique un nombre de hasta 150 caracteres.');
        $table = match ($nivel) {
            'ESTABLECIMIENTO' => ['casis_establecimiento','est_ide'],
            'MICRORED' => ['casis_microred','mic_ide'],
            'RED' => ['casis_red','red_ide'],
            'DIRESA' => ['casis_diresa','dir_ide'],
        };
        if (!$this->db->table($table[0])->where($table[1],$entidadId)->where('deleted_at',null)->countAllResults()) throw new \DomainException('La entidad seleccionada no existe o está inactiva.');
        $duplicado = $this->db->table('casis_plan_grupo')->where('nivel',$nivel)->where('entidad_id',$entidadId);
        if ($id) $duplicado->where('id !=',$id);
        if ($duplicado->countAllResults()) throw new \DomainException('Ya existe un grupo de programación para esa entidad y nivel.');
        if ($id) {
            $grupo = $this->db->table('casis_plan_grupo')->where('id',$id)->get()->getRowArray();
            if (!$grupo) throw new \DomainException('Grupo no encontrado.');
            if ($this->db->table('casis_plan_periodo')->where('grupo_id',$id)->countAllResults() && ($grupo['nivel'] !== $nivel || (int)$grupo['entidad_id'] !== $entidadId)) throw new \DomainException('No puede cambiar el alcance de un grupo con períodos registrados.');
            $this->db->table('casis_plan_grupo')->where('id',$id)->update(['nivel'=>$nivel,'entidad_id'=>$entidadId,'nombre'=>$nombre,'updated_at'=>date('Y-m-d H:i:s')]);
            return $id;
        }
        $this->db->table('casis_plan_grupo')->insert(['nivel'=>$nivel,'entidad_id'=>$entidadId,'nombre'=>$nombre,'estado'=>'ACTIVO','created_by'=>$this->usuario(),'created_at'=>date('Y-m-d H:i:s')]);
        return (int)$this->db->insertID();
    }

    public function eliminar(int $id): void
    {
        $this->exigirAdmin();
        if ($this->db->table('casis_plan_periodo')->where('grupo_id',$id)->countAllResults()) throw new \DomainException('El grupo tiene períodos asociados y no se puede eliminar.');
        if (!$this->db->table('casis_plan_grupo')->where('id',$id)->delete()) throw new \DomainException('No se pudo eliminar el grupo.');
    }

    public function resolverPeriodo(int $establecimientoId, string $mes, bool $bloquear = false): ?array
    {
        $ubicacion = $this->ubicacion($establecimientoId);
        if (!$ubicacion) return null;
        foreach ([['ESTABLECIMIENTO','est_ide'],['MICRORED','microred_id'],['RED','red_id'],['DIRESA','diresa_id']] as [$nivel,$campo]) {
            $id = (int)($ubicacion[$campo] ?? 0);
            if (!$id) continue;
            $builder = $this->db->table('casis_plan_periodo p')->select('p.*, g.nivel, g.entidad_id, g.nombre AS grupo_nombre')
                ->join('casis_plan_grupo g','g.id=p.grupo_id')->where('g.nivel',$nivel)->where('g.entidad_id',$id)->where('g.estado','ACTIVO')->where('p.mes',$mes);
            $periodo = $builder->get()->getRowArray();
            if ($periodo && $bloquear && $this->db->DBDriver !== 'SQLite3') $periodo = $this->db->query('SELECT p.*, g.nivel, g.entidad_id, g.nombre AS grupo_nombre FROM casis_plan_periodo p JOIN casis_plan_grupo g ON g.id=p.grupo_id WHERE p.id=? FOR UPDATE',[(int)$periodo['id']])->getRowArray();
            if ($periodo) return $periodo;
        }
        // Compatibilidad defensiva mientras se completa la migración de históricos.
        $legacy = $this->db->table('casis_plan_periodo')->where('grupo_id',null)->where('est_id',$establecimientoId)->where('mes',$mes)->get()->getRowArray();
        return $legacy ?: null;
    }

    public function establecimientosDePeriodo(array $periodo): array
    {
        if (!empty($periodo['grupo_id'])) {
            $grupo = $this->db->table('casis_plan_grupo')->where('id',(int)$periodo['grupo_id'])->get()->getRowArray();
            return $grupo ? $this->establecimientosDeGrupo($grupo) : [];
        }
        return empty($periodo['est_id']) ? [] : [(int)$periodo['est_id']];
    }

    public function establecimientosDeGrupo(array $grupo): array
    {
        $columna = match ($grupo['nivel']) {'ESTABLECIMIENTO'=>'e.est_ide','MICRORED'=>'e.est_mic_ide','RED'=>'m.mic_red_ide','DIRESA'=>'r.red_dir_ide',default=>null};
        if (!$columna) return [];
        return array_map('intval', array_column($this->db->table('casis_establecimiento e')->select('e.est_ide')->join('casis_microred m','m.mic_ide=e.est_mic_ide AND m.deleted_at IS NULL','left')->join('casis_red r','r.red_ide=m.mic_red_ide AND r.deleted_at IS NULL','left')->where($columna,(int)$grupo['entidad_id'])->where('e.deleted_at',null)->get()->getResultArray(),'est_ide'));
    }

    private function ubicacion(int $establecimientoId): ?array
    {
        return $this->db->table('casis_establecimiento e')->select('e.est_ide, e.est_mic_ide AS microred_id, m.mic_red_ide AS red_id, r.red_dir_ide AS diresa_id')
            ->join('casis_microred m','m.mic_ide=e.est_mic_ide AND m.deleted_at IS NULL','left')->join('casis_red r','r.red_ide=m.mic_red_ide AND r.deleted_at IS NULL','left')
            ->where('e.est_ide',$establecimientoId)->where('e.deleted_at',null)->get()->getRowArray();
    }
    private function exigirAdmin(): void { if (!function_exists('auth') || !auth()->loggedIn() || !auth()->user()->inGroup('superadmin','asi_sua')) throw new \DomainException('Solo el administrador de Asistencia puede configurar grupos de programación.'); }
    private function usuario(): ?int { return function_exists('auth') && auth()->loggedIn() ? (int)auth()->id() : null; }
}

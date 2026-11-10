<?php

namespace Modules\Asistencia\Services;

class EstablecimientoUpssService
{
    private $db;
    private AccesoAsistenciaService $acceso;

    public function __construct($db = null)
    {
        $this->db = $db ?? \Config\Database::connect();
        $this->acceso = new AccesoAsistenciaService();
    }

    public function catalogos(): array
    {
        $this->acceso->exigir('lectura_catalogos');
        $establecimientos = $this->db->table('casis_establecimiento e')->select('e.est_ide, e.est_ipress, e.est_nombre, e.est_mic_ide, m.mic_nombre, r.red_nombre')
            ->join('casis_microred m','m.mic_ide=e.est_mic_ide AND m.deleted_at IS NULL','left')
            ->join('casis_red r','r.red_ide=m.mic_red_ide AND r.deleted_at IS NULL','left')->where('e.deleted_at',null);
        $perfil=$this->acceso->perfilTerritorial();
        if($perfil && !($perfil['global']??false)){
            $ids=$this->acceso->establecimientosDelAmbito();
            $establecimientos->whereIn('e.est_ide',$ids?:[0]);
        }
        return [
            'establecimientos'=>$establecimientos->orderBy('e.est_nombre')->get()->getResultArray(),
            'upss'=>$this->db->table('casis_upss')->select('ups_ide, ups_codigo, ups_nombre, ups_agrupacion, ups_estado')->where('deleted_at',null)->orderBy('ups_nombre')->get()->getResultArray(),
        ];
    }

    public function listar(array $filtros = []): array
    {
        $this->acceso->exigir('lectura_catalogos');
        $q=$this->db->table('casis_establecimiento_upss x')->select('x.eup_ide, x.eup_est_ide, x.eup_ups_ide, x.eup_estado, x.eup_observacion, x.created_at, x.updated_at, e.est_ipress, e.est_nombre, m.mic_nombre, r.red_nombre, u.ups_codigo, u.ups_nombre, u.ups_agrupacion')
            ->join('casis_establecimiento e','e.est_ide=x.eup_est_ide AND e.deleted_at IS NULL','inner')
            ->join('casis_microred m','m.mic_ide=e.est_mic_ide AND m.deleted_at IS NULL','left')
            ->join('casis_red r','r.red_ide=m.mic_red_ide AND r.deleted_at IS NULL','left')
            ->join('casis_upss u','u.ups_ide=x.eup_ups_ide','inner')->where('x.deleted_at',null);
        $perfil=$this->acceso->perfilTerritorial();
        if($perfil && !($perfil['global']??false)){
            $ids=$this->acceso->establecimientosDelAmbito();
            $q->whereIn('x.eup_est_ide',$ids?:[0]);
        }
        $establecimiento=(int)($filtros['establecimiento']??0);
        if($establecimiento){
            if(!$this->acceso->establecimientoDentroAmbito($establecimiento))throw new \DomainException('El establecimiento está fuera de su ámbito de acceso.');
            $q->where('x.eup_est_ide',$establecimiento);
        }
        if(isset($filtros['estado'])&&$filtros['estado']!=='')$q->where('x.eup_estado',(int)$filtros['estado']);
        if(!empty($filtros['search']))$q->groupStart()->like('e.est_nombre',$filtros['search'])->orLike('e.est_ipress',$filtros['search'])->orLike('u.ups_nombre',$filtros['search'])->orLike('u.ups_codigo',$filtros['search'])->orLike('x.eup_observacion',$filtros['search'])->groupEnd();
        return $q->orderBy('e.est_nombre')->orderBy('u.ups_nombre')->get()->getResultArray();
    }

    public function crear(array $datos): int
    {
        $this->acceso->exigir('gestionar_catalogos');
        $normal=$this->normalizar($datos);
        $this->validarRelacion($normal['eup_est_ide'],$normal['eup_ups_ide']);
        $existente=$this->db->table('casis_establecimiento_upss')->where('eup_est_ide',$normal['eup_est_ide'])->where('eup_ups_ide',$normal['eup_ups_ide'])->get()->getRowArray();
        $ahora=date('Y-m-d H:i:s');$usuario=$this->usuario();
        if($existente){
            if(empty($existente['deleted_at']))throw new \DomainException('La UPSS ya está vinculada a este establecimiento.');
            $this->db->table('casis_establecimiento_upss')->where('eup_ide',(int)$existente['eup_ide'])->update($normal+['deleted_at'=>null,'deleted_by'=>null,'updated_at'=>$ahora,'updated_by'=>$usuario]);
            return (int)$existente['eup_ide'];
        }
        $this->db->table('casis_establecimiento_upss')->insert($normal+['created_at'=>$ahora,'updated_at'=>$ahora,'created_by'=>$usuario,'updated_by'=>$usuario]);
        return (int)$this->db->insertID();
    }

    public function actualizar(int $id,array $datos): void
    {
        $this->acceso->exigir('gestionar_catalogos');
        $actual=$this->obtenerActivo($id);
        $normal=$this->normalizar($datos);
        $cambio=(int)$actual['eup_est_ide']!==$normal['eup_est_ide']||(int)$actual['eup_ups_ide']!==$normal['eup_ups_ide'];
        if($cambio)$this->validarRelacion($normal['eup_est_ide'],$normal['eup_ups_ide']);
        elseif(!$this->acceso->establecimientoDentroAmbito($normal['eup_est_ide']))throw new \DomainException('Seleccione un establecimiento disponible para su perfil.');
        if($cambio){
            $enUso=$this->db->table('casis_programacion')->where('prog_eup_ide',$id)->where('deleted_at',null)->countAllResults()
                || $this->db->table('casis_establecimiento_upss_servicio')->where('eus_eup_ide',$id)->where('deleted_at',null)->countAllResults();
            if($enUso)throw new \DomainException('No se puede cambiar el establecimiento o la UPSS porque esta relación ya tiene servicios o programaciones. Edite solo su estado u observación.');
        }
        $duplicado=$this->db->table('casis_establecimiento_upss')->where('eup_est_ide',$normal['eup_est_ide'])->where('eup_ups_ide',$normal['eup_ups_ide'])->where('eup_ide !=',$id)->where('deleted_at',null)->countAllResults();
        if($duplicado)throw new \DomainException('La UPSS ya está vinculada a este establecimiento.');
        $this->db->table('casis_establecimiento_upss')->where('eup_ide',$id)->update($normal+['updated_at'=>date('Y-m-d H:i:s'),'updated_by'=>$this->usuario()]);
    }

    public function eliminar(int $id): void
    {
        $this->acceso->exigir('gestionar_catalogos');
        $this->obtenerActivo($id);
        $this->db->table('casis_establecimiento_upss')->where('eup_ide',$id)->update(['eup_estado'=>0,'deleted_at'=>date('Y-m-d H:i:s'),'deleted_by'=>$this->usuario(),'updated_at'=>date('Y-m-d H:i:s'),'updated_by'=>$this->usuario()]);
    }

    private function normalizar(array $datos): array
    {
        $est=(int)($datos['eup_est_ide']??0);$ups=(int)($datos['eup_ups_ide']??0);$estado=(int)($datos['eup_estado']??1);$observacion=trim((string)($datos['eup_observacion']??''));
        if($estado!==0&&$estado!==1)throw new \DomainException('El estado debe ser activo o inactivo.');
        if(mb_strlen($observacion)>60000)throw new \DomainException('La observación excede el tamaño permitido.');
        return ['eup_est_ide'=>$est,'eup_ups_ide'=>$ups,'eup_estado'=>$estado,'eup_observacion'=>$observacion!==''?$observacion:null];
    }

    private function validarRelacion(int $establecimiento,int $upss): void
    {
        if(!$this->acceso->establecimientoDentroAmbito($establecimiento))throw new \DomainException('Seleccione un establecimiento disponible para su perfil.');
        if(!$this->db->table('casis_establecimiento')->where('est_ide',$establecimiento)->where('deleted_at',null)->countAllResults())throw new \DomainException('El establecimiento no existe o está archivado.');
        if(!$this->db->table('casis_upss')->where('ups_ide',$upss)->where('ups_estado',1)->where('deleted_at',null)->countAllResults())throw new \DomainException('Seleccione una UPSS activa.');
    }

    private function obtenerActivo(int $id): array
    {
        $row=$this->db->table('casis_establecimiento_upss')->where('eup_ide',$id)->where('deleted_at',null)->get()->getRowArray();
        if(!$row)throw new \DomainException('La relación no existe o ya fue archivada.');
        if(!$this->acceso->establecimientoDentroAmbito((int)$row['eup_est_ide']))throw new \DomainException('La relación está fuera de su ámbito de acceso.');
        return $row;
    }

    private function usuario(): ?int { return function_exists('auth')&&auth()->loggedIn()?(int)auth()->id():null; }
}

<?php

namespace Modules\Asistencia\Services;

class EstablecimientoUpssServicioService
{
    private $db;
    private AccesoAsistenciaService $acceso;

    public function __construct($db = null)
    {
        $this->db=$db??\Config\Database::connect();
        $this->acceso=new AccesoAsistenciaService();
    }

    public function catalogos(): array
    {
        $this->acceso->exigir('lectura_catalogos');
        $relaciones=$this->db->table('casis_establecimiento_upss eup')->select('eup.eup_ide,eup.eup_est_ide,eup.eup_ups_ide,e.est_ipress,e.est_nombre,m.mic_nombre,r.red_nombre,u.ups_codigo,u.ups_nombre')
            ->join('casis_establecimiento e','e.est_ide=eup.eup_est_ide AND e.deleted_at IS NULL','inner')
            ->join('casis_microred m','m.mic_ide=e.est_mic_ide AND m.deleted_at IS NULL','left')
            ->join('casis_red r','r.red_ide=m.mic_red_ide AND r.deleted_at IS NULL','left')
            ->join('casis_upss u','u.ups_ide=eup.eup_ups_ide AND u.deleted_at IS NULL','inner')
            ->where('eup.deleted_at',null)->where('eup.eup_estado',1);
        $perfil=$this->acceso->perfilTerritorial();
        if($perfil&&!($perfil['global']??false))$relaciones->whereIn('eup.eup_est_ide',$this->acceso->establecimientosDelAmbito()?:[0]);
        $rows=$relaciones->orderBy('e.est_nombre')->orderBy('u.ups_nombre')->get()->getResultArray();
        $est=[];
        foreach($rows as $row)$est[(int)$row['eup_est_ide']]=['est_ide'=>(int)$row['eup_est_ide'],'est_ipress'=>$row['est_ipress'],'est_nombre'=>$row['est_nombre']];
        return ['establecimientos'=>array_values($est),'relaciones'=>$rows,'servicios'=>$this->db->table('casis_upss_servicio s')->select('s.uss_ide,s.uss_ups_ide,s.uss_codigo,s.uss_nombre,s.uss_abreviatura,s.uss_estado,u.ups_estado')->join('casis_upss u','u.ups_ide=s.uss_ups_ide','left')->where('s.deleted_at',null)->orderBy('s.uss_nombre')->get()->getResultArray()];
    }

    public function listar(array $filtros=[]): array
    {
        $this->acceso->exigir('lectura_catalogos');
        $q=$this->queryListado();
        $perfil=$this->acceso->perfilTerritorial();
        if($perfil&&!($perfil['global']??false))$q->whereIn('e.est_ide',$this->acceso->establecimientosDelAmbito()?:[0]);
        if(($est=(int)($filtros['establecimiento']??0))>0){if(!$this->acceso->establecimientoDentroAmbito($est))throw new \DomainException('El establecimiento está fuera de su ámbito de acceso.');$q->where('e.est_ide',$est);}
        if(isset($filtros['estado'])&&$filtros['estado']!=='')$q->where('x.eus_estado',(int)$filtros['estado']);
        if(!empty($filtros['search']))$q->groupStart()->like('e.est_nombre',$filtros['search'])->orLike('e.est_ipress',$filtros['search'])->orLike('u.ups_nombre',$filtros['search'])->orLike('s.uss_nombre',$filtros['search'])->orLike('s.uss_codigo',$filtros['search'])->orLike('x.eus_observacion',$filtros['search'])->groupEnd();
        return $q->orderBy('e.est_nombre')->orderBy('u.ups_nombre')->orderBy('s.uss_nombre')->get()->getResultArray();
    }

    public function crear(array $datos): int
    {
        $this->acceso->exigir('gestionar_catalogos');$d=$this->normalizar($datos);$this->validar($d['eus_eup_ide'],$d['eus_uss_ide']);
        $ex=$this->db->table('casis_establecimiento_upss_servicio')->where('eus_eup_ide',$d['eus_eup_ide'])->where('eus_uss_ide',$d['eus_uss_ide'])->get()->getRowArray();
        $now=date('Y-m-d H:i:s');$uid=$this->usuario();
        if($ex){if(empty($ex['deleted_at']))throw new \DomainException('El servicio ya está asociado a esta UPSS del establecimiento.');$this->db->table('casis_establecimiento_upss_servicio')->where('eus_ide',(int)$ex['eus_ide'])->update($d+['deleted_at'=>null,'deleted_by'=>null,'updated_at'=>$now,'updated_by'=>$uid]);return (int)$ex['eus_ide'];}
        $this->db->table('casis_establecimiento_upss_servicio')->insert($d+['created_at'=>$now,'updated_at'=>$now,'created_by'=>$uid,'updated_by'=>$uid]);return (int)$this->db->insertID();
    }

    public function actualizar(int $id,array $datos): void
    {
        $this->acceso->exigir('gestionar_catalogos');$actual=$this->activo($id);$d=$this->normalizar($datos);
        $cambio=(int)$actual['eus_eup_ide']!==$d['eus_eup_ide']||(int)$actual['eus_uss_ide']!==$d['eus_uss_ide'];
        if($cambio)$this->validar($d['eus_eup_ide'],$d['eus_uss_ide']);
        elseif(!$this->acceso->establecimientoDentroAmbito((int)$actual['eup_est_ide']))throw new \DomainException('La relación está fuera de su ámbito de acceso.');
        if($cambio&&$this->db->table('casis_programacion')->where('prog_eus_ide',$id)->where('deleted_at',null)->countAllResults())throw new \DomainException('No se puede cambiar la relación porque ya está usada en programaciones.');
        $dup=$this->db->table('casis_establecimiento_upss_servicio')->where('eus_eup_ide',$d['eus_eup_ide'])->where('eus_uss_ide',$d['eus_uss_ide'])->where('eus_ide !=',$id)->where('deleted_at',null)->countAllResults();if($dup)throw new \DomainException('El servicio ya está asignado a esta UPSS.');
        $this->db->table('casis_establecimiento_upss_servicio')->where('eus_ide',$id)->update($d+['updated_at'=>date('Y-m-d H:i:s'),'updated_by'=>$this->usuario()]);
    }

    public function eliminar(int $id): void
    {
        $this->acceso->exigir('gestionar_catalogos');$row=$this->activo($id);
        if(!$this->acceso->establecimientoDentroAmbito((int)$row['eup_est_ide']))throw new \DomainException('La relación está fuera de su ámbito de acceso.');
        $now=date('Y-m-d H:i:s');$this->db->table('casis_establecimiento_upss_servicio')->where('eus_ide',$id)->update(['eus_estado'=>0,'deleted_at'=>$now,'deleted_by'=>$this->usuario(),'updated_at'=>$now,'updated_by'=>$this->usuario()]);
    }

    private function queryListado()
    {
        return $this->db->table('casis_establecimiento_upss_servicio x')->select('x.eus_ide,x.eus_eup_ide,x.eus_uss_ide,x.eus_estado,x.eus_observacion,x.updated_at,e.est_ide,e.est_ipress,e.est_nombre,m.mic_nombre,r.red_nombre,eup.eup_ups_ide,u.ups_codigo,u.ups_nombre,s.uss_codigo,s.uss_nombre,s.uss_abreviatura')
            ->join('casis_establecimiento_upss eup','eup.eup_ide=x.eus_eup_ide','inner')->join('casis_establecimiento e','e.est_ide=eup.eup_est_ide','inner')
            ->join('casis_microred m','m.mic_ide=e.est_mic_ide','left')->join('casis_red r','r.red_ide=m.mic_red_ide','left')
            ->join('casis_upss u','u.ups_ide=eup.eup_ups_ide','inner')->join('casis_upss_servicio s','s.uss_ide=x.eus_uss_ide','inner')->where('x.deleted_at',null);
    }

    private function normalizar(array $d): array
    {
        $estado=(int)($d['eus_estado']??1);if(!in_array($estado,[0,1],true))throw new \DomainException('Seleccione un estado válido.');
        $obs=trim((string)($d['eus_observacion']??''));if(mb_strlen($obs)>60000)throw new \DomainException('La observación excede el límite permitido.');
        return ['eus_eup_ide'=>(int)($d['eus_eup_ide']??0),'eus_uss_ide'=>(int)($d['eus_uss_ide']??0),'eus_estado'=>$estado,'eus_observacion'=>$obs!==''?$obs:null];
    }

    private function validar(int $eup,int $servicio): void
    {
        $parent=$this->db->table('casis_establecimiento_upss eup')->select('eup.eup_est_ide,eup.eup_ups_ide')->where('eup.eup_ide',$eup)->where('eup.eup_estado',1)->where('eup.deleted_at',null)->get()->getRowArray();
        if(!$parent)throw new \DomainException('Seleccione una relación UPSS-establecimiento activa.');
        if(!$this->acceso->establecimientoDentroAmbito((int)$parent['eup_est_ide']))throw new \DomainException('El establecimiento está fuera de su ámbito de acceso.');
        if(!$this->db->table('casis_upss')->where('ups_ide',(int)$parent['eup_ups_ide'])->where('ups_estado',1)->where('deleted_at',null)->countAllResults())throw new \DomainException('La UPSS de la relación está inactiva o archivada. Active la UPSS antes de asignarle servicios.');
        if(!$this->db->table('casis_upss_servicio')->where('uss_ide',$servicio)->where('uss_ups_ide',(int)$parent['eup_ups_ide'])->where('uss_estado',1)->where('deleted_at',null)->countAllResults())throw new \DomainException('El servicio seleccionado no pertenece a la UPSS activa.');
    }

    private function activo(int $id): array
    {
        $row=$this->queryListado()->where('x.eus_ide',$id)->get()->getRowArray();if(!$row)throw new \DomainException('La relación no existe o ya fue archivada.');return $row;
    }
    private function usuario(): ?int{return function_exists('auth')&&auth()->loggedIn()?(int)auth()->id():null;}
}

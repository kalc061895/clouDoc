<?php
namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\PeriodoModel;

class CalificacionMensualService extends GestionRolService
{
    private function tabla(string $s) { return $this->db->table('casis_cal_'.$s); }
    public function criterios(): array { return array_column($this->tabla('criterio')->orderBy('id')->get()->getResultArray(),'valor','codigo'); }
    public function guardarCriterios(array $input): void
    {
        $this->exigirAdministrador(); $datos=[];
        foreach (CalificacionMotor::DEFAULTS as $k=>$default) {
            $v=$input[$k]??null;
            if (!is_string($v)) throw new \InvalidArgumentException('Criterio inválido: '.$k);
            if ($k==='codigos_guardia') { if (!preg_match('/^[A-Za-z0-9_, -]{1,100}$/D',$v)) throw new \InvalidArgumentException('Use códigos de guardia separados por comas.'); }
            elseif (!ctype_digit($v) || (int)$v>($k==='ventana_minutos'?360:1440) || ($k==='tardanzas_excesivas' && (int)$v<1)) throw new \InvalidArgumentException('Valor fuera de rango: '.$k);
            $datos[$k]=$v;
        }
        $this->db->transStart();
        foreach ($datos as $k=>$v) $this->tabla('criterio')->where('codigo',$k)->update(['valor'=>$v,'updated_by'=>$this->usuario(),'updated_at'=>$this->ahora()]);
        $this->db->transComplete();
        if (!$this->db->transStatus()) throw new \RuntimeException('No se guardaron los criterios.');
    }
    public function catalogos(): array
    {
        $this->usuario();
        return ['periodos'=>$this->db->table('casis_periodos')->where('deleted_at',null)->where('per_estado','CERRADO')->orderBy('per_fecha_inicio','DESC')->get()->getResultArray(), 'ambitos'=>(new ReporteMensualService())->catalogos()];
    }
    protected function periodo(int $id, bool $lock=false): array
    {
        $p=$this->db->query('SELECT * FROM casis_periodos WHERE per_ide = ? AND deleted_at IS NULL'.($lock?' FOR UPDATE':''),[$id])->getRowArray();
        if (!$p || $p['per_estado']!=='CERRADO') throw new \DomainException('Primero debe cerrar el periodo de registro de papeletas.');
        return $p;
    }
    protected function fuentes(array $f, array $periodo): array
    {
        $columnas=['establecimiento'=>'e.est_ide','microred'=>'m.mic_ide','red'=>'m.mic_red_ide'];
        if (!isset($columnas[$f['ambito_tipo']]) || $f['ambito_id']<1) throw new \InvalidArgumentException('Seleccione un ámbito válido.');
        $inicio=$periodo['per_fecha_inicio']; $fin=$periodo['per_fecha_fin'];
        $q=$this->db->table('casis_personal p')->select('p.*, pe.per_numero_documento, pe.per_paterno, pe.per_materno, pe.per_nombre, e.est_nombre')
            ->join('casis_persona pe','pe.per_ide=p.perl_per_ide')->join('casis_establecimiento e','e.est_ide=p.perl_est_ide')->join('casis_microred m','m.mic_ide=e.est_mic_ide')
            ->where('p.deleted_at',null)->where('pe.deleted_at',null)->where('e.deleted_at',null)->where($columnas[$f['ambito_tipo']],$f['ambito_id'])
            ->groupStart()->where('p.perl_fecha_inicio <=',$fin)->orWhere('p.perl_fecha_inicio',null)->groupEnd()
            ->groupStart()->where('p.perl_fecha_cese >=',$inicio)->orWhere('p.perl_fecha_cese',null)->groupEnd();
        if ($f['trabajador_id']) $q->where('p.perl_ide',$f['trabajador_id']);
        $personal=$q->orderBy('p.perl_ide')->get()->getResultArray();
        if (!$personal) throw new \DomainException('No hay trabajadores en el ámbito y periodo seleccionados.');
        $modelo=new PeriodoModel();
        foreach ($personal as $p) {
            $this->exigirOficina((int)$p['perl_ofi_ide'],true);
            $aplicable=$modelo->resolverPeriodoPorPersonalYFecha((int)$p['perl_ide'],$inicio);
            if ((int)($aplicable['per_ide']??0)!==(int)$periodo['per_ide']) throw new \DomainException('El trabajador '.$p['per_numero_documento'].' tiene otro grupo de corte. Seleccione su periodo o califíquelo individualmente.');
        }
        $ids=array_column($personal,'perl_ide');
        $turnos=$this->db->table('casis_programacion pr')->select('pr.*, th.th_hora_ingreso, th.th_hora_salida, t.tur_codigo')->join('casis_turno_horario th','th.th_ide=pr.prog_th_ide','left')->join('casis_turno t','t.tur_ide=th.th_tur_ide','left')->where('pr.deleted_at',null)->whereIn('pr.prog_perl_ide',$ids)->where('pr.prog_fecha >=',$inicio)->where('pr.prog_fecha <=',$fin)->whereNotIn('pr.prog_estado',['ANULADO','CANCELADO'])->orderBy('pr.prog_ide')->get()->getResultArray();
        $marcas=$this->db->table('casis_asistencia')->where('deleted_at',null)->whereIn('asi_perl_ide',$ids)->where('asi_fecha_hora >=',date('Y-m-d H:i:s',strtotime($inicio.' -6 hours')))->where('asi_fecha_hora <=',date('Y-m-d H:i:s',strtotime($fin.' +2 days +6 hours')))->orderBy('asi_ide')->get()->getResultArray();
        $fuentes=compact('personal','turnos','marcas','inicio','fin');
        foreach (['licencias'=>['licencia','rl'],'vacaciones'=>['vacacion','rv']] as $key=>[$table,$pre]) $fuentes[$key]=$this->db->table('casis_registro_'.$table)->where('deleted_at',null)->whereIn($pre.'_perl_ide',$ids)->where($pre.'_estado',1)->where($pre.'_fecha_inicio <=',$fin)->where($pre.'_fecha_fin >=',$inicio)->orderBy($pre.'_ide')->get()->getResultArray();
        $fuentes['permisos']=$this->db->table('casis_registro_permiso')->where('deleted_at',null)->whereIn('rp_perl_ide',$ids)->whereNotIn('rp_estado',['ANULADO','RECHAZADO','CANCELADO'])->where('rp_fecha >=',$inicio)->where('rp_fecha <=',$fin)->orderBy('rp_ide')->get()->getResultArray();
        $fuentes['feriados']=$this->db->table('casis_diaferiado')->where('deleted_at',null)->orderBy('df_ide')->get()->getResultArray();
        return $fuentes;
    }
    public function generar(array $input): int
    {
        $f=['ambito_tipo'=>$input['ambito_tipo']??'', 'ambito_id'=>(int)($input['ambito_id']??0), 'trabajador_id'=>(int)($input['trabajador_id']??0),'periodo_id'=>(int)($input['periodo_id']??0)];
        $p=$this->periodo($f['periodo_id']); $fuentes=$this->fuentes($f,$p); $reglas=$this->criterios();
        $resultado=CalificacionMotor::evaluar($fuentes,$reglas);
        $observada=(bool)array_filter($resultado,static fn($r)=>!empty($r['observaciones']));
        $this->db->transStart();
        $this->tabla('mensual')->insert($f+['estado'=>$observada?'OBSERVADA':'LISTA','fuentes'=>json_encode($fuentes,JSON_THROW_ON_ERROR),'reglas'=>json_encode($reglas,JSON_THROW_ON_ERROR),'resultado'=>json_encode($resultado,JSON_THROW_ON_ERROR),'created_by'=>$this->usuario(),'created_at'=>$this->ahora()]);
        $id=(int)$this->db->insertID(); $this->registrarEvento($id,'GENERAR','Cálculo automático conservado como versión independiente.');
        $this->db->transComplete();
        if (!$this->db->transStatus()) throw new \RuntimeException('No se pudo guardar la calificación.');
        return $id;
    }
    public function obtener(int $id): array
    {
        $this->usuario(); $c=$this->tabla('mensual')->where('id',$id)->get()->getRowArray();
        if (!$c) throw new \OutOfBoundsException('Calificación no encontrada.');
        foreach (json_decode($c['fuentes'],true)['personal'] as $p) $this->exigirOficina((int)$p['perl_ofi_ide'],true);
        return $c;
    }
    public function historial(): array
    {
        $this->usuario(); $items=$this->tabla('mensual')->select('id')->orderBy('id','DESC')->get()->getResultArray(); $out=[];
        foreach ($items as $item) { try { $out[]=$this->obtener((int)$item['id']); } catch (\DomainException $e) { continue; } }
        return $out;
    }
    public function eventos(int $id): array { $this->obtener($id); return $this->tabla('evento')->where('calificacion_id',$id)->orderBy('id')->get()->getResultArray(); }
    public function pdfs(int $id): array { $this->obtener($id); return $this->tabla('pdf')->select('id,sha256,created_at,created_by')->where('calificacion_id',$id)->orderBy('id','DESC')->get()->getResultArray(); }
    private function registrarEvento(int $id,string $accion,string $detalle): void { $this->tabla('evento')->insert(['calificacion_id'=>$id,'accion'=>$accion,'detalle'=>$detalle,'created_by'=>$this->usuario(),'created_at'=>$this->ahora()]); }
    public function accion(int $id,string $accion,string $motivo): void
    {
        $c=$this->obtener($id);
        $this->db->transBegin();
        try {
            $p=$this->periodo((int)$c['periodo_id'],true);
            $c=$this->db->query('SELECT * FROM casis_cal_mensual WHERE id=?'.($this->db->DBDriver==='MySQLi'?' FOR UPDATE':''),[$id])->getRowArray();
            if (in_array($c['estado'],['CERRADA','ANULADA'],true)) throw new \DomainException('La calificación es definitiva.');
            if (!in_array($accion,['REVISAR','CERRAR','ANULAR'],true)) throw new \InvalidArgumentException('Acción inválida.');
            if (mb_strlen(trim($motivo))<10 || mb_strlen($motivo)>2000) throw new \InvalidArgumentException('Registre un sustento de 10 a 2000 caracteres.');
            if ($accion==='CERRAR') {
                if (!in_array($c['estado'],['LISTA','REVISADA'],true)) throw new \DomainException('Revise y sustente las observaciones antes de cerrar.');
                if (json_encode($this->fuentes($c,$p),JSON_THROW_ON_ERROR)!==$c['fuentes']) throw new \DomainException('Los registros cambiaron. Genere una nueva calificación y revise sus observaciones.');
                $ids=array_column(json_decode($c['fuentes'],true)['personal'],'perl_ide');
                foreach ($this->tabla('mensual')->where('periodo_id',$c['periodo_id'])->where('estado','CERRADA')->get()->getResultArray() as $otra) if (array_intersect($ids,array_column(json_decode($otra['fuentes'],true)['personal'],'perl_ide'))) throw new \DomainException('Hay trabajadores ya cerrados en otra calificación de este periodo.');
            }
            $estado=['REVISAR'=>'REVISADA','CERRAR'=>'CERRADA','ANULAR'=>'ANULADA'][$accion];
            $this->tabla('mensual')->where('id',$id)->update(['estado'=>$estado]); $this->registrarEvento($id,$accion,$motivo);
            if (!$this->db->transStatus()) throw new \RuntimeException('No se guardó el estado.');
            $this->db->transCommit();
        } catch (\Throwable $e) { $this->db->transRollback(); throw $e; }
    }
    public function generarPdf(int $id): void
    {
        $c=$this->obtener($id);
        if ($c['estado']!=='CERRADA') throw new \DomainException('Cierre la calificación antes de generar el PDF definitivo.');
        $pdf=new \Dompdf\Dompdf(['isRemoteEnabled'=>false,'isPhpEnabled'=>false,'defaultFont'=>'DejaVu Sans']);
        $pdf->setPaper('A4','landscape');
        $pdf->loadHtml(view('Modules\Asistencia\Views\calificacion\pdf',['c'=>$c]),'UTF-8'); $pdf->render(); $bytes=$pdf->output();
        $this->db->transStart();
        $this->tabla('pdf')->insert(['calificacion_id'=>$id,'contenido'=>base64_encode($bytes),'sha256'=>hash('sha256',$bytes),'created_by'=>$this->usuario(),'created_at'=>$this->ahora()]);
        $this->registrarEvento($id,'PDF','Documento conservado con huella SHA-256.'); $this->db->transComplete();
        if (!$this->db->transStatus()) throw new \RuntimeException('No se guardó el PDF.');
    }
    public function archivo(int $id): array
    {
        $p=$this->tabla('pdf')->where('id',$id)->get()->getRowArray();
        if (!$p) throw new \OutOfBoundsException('PDF no encontrado.');
        $this->obtener((int)$p['calificacion_id']);
        $p['contenido']=base64_decode($p['contenido'],true);
        if ($p['contenido']===false) throw new \RuntimeException('Contenido PDF inválido.');
        if (!hash_equals($p['sha256'],hash('sha256',$p['contenido']))) throw new \RuntimeException('El PDF no supera la verificación de integridad.');
        return $p;
    }
}

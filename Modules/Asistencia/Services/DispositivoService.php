<?php
namespace Modules\Asistencia\Services;

use CodeIgniter\HTTP\Files\UploadedFile;

class DispositivoService
{
    private $db;
    private PeriodoService $periodos;
    public function __construct($db=null, ?PeriodoService $periodos=null)
    {
        $this->db=$db??\Config\Database::connect();
        $this->periodos=$periodos??new PeriodoService();
    }
    public function usuario(bool $configurar=false): int
    {
        if (!auth()->loggedIn()) throw new \DomainException('Debe iniciar sesión.');
        $grupos=$configurar ? ['superadmin','asi_sua','asistencia'] : ['superadmin','asi_sua','asi_adm','asistencia'];
        if (!auth()->user()->inGroup(...$grupos)) throw new \DomainException('No tiene permiso para esta operación de dispositivos.');
        return (int)auth()->id();
    }
    private function ahora(): string { return (new \DateTimeImmutable('now',new \DateTimeZone('America/Lima')))->format('Y-m-d H:i:s'); }
    public function listar(): array
    {
        $this->usuario();
        return $this->db->table('casis_dispositivo d')->select('d.*, e.est_nombre')->join('casis_establecimiento e','e.est_ide=d.est_id')->orderBy('d.nombre')->get()->getResultArray();
    }
    public function establecimientos(): array
    {
        $this->usuario();
        return $this->db->table('casis_establecimiento')->where('deleted_at',null)->orderBy('est_nombre')->get()->getResultArray();
    }
    public function dispositivo(int $id): array
    {
        $this->usuario();
        $row=$this->db->table('casis_dispositivo')->where('id',$id)->get()->getRowArray();
        if (!$row) throw new \DomainException('Dispositivo no encontrado.');
        return $row;
    }
    public function guardar(array $d): void
    {
        $uid=$this->usuario(true);
        $v=\Config\Services::validation();
        $rules=[
            'codigo'=>'required|regex_match[/^[A-Za-z0-9_-]{1,40}$/]',
            'nombre'=>'required|max_length[150]', 'marca'=>'required|in_list[ZKTECO,HIKVISION]',
            'est_id'=>'required|is_natural_no_zero', 'modelo'=>'permit_empty|max_length[100]',
            'serie'=>'permit_empty|max_length[100]', 'ip'=>'permit_empty|valid_ip',
            'puerto'=>'permit_empty|integer|greater_than[0]|less_than[65536]', 'activo'=>'required|in_list[0,1]',
        ];
        $d=array_intersect_key($d,$rules+['id'=>true]);
        foreach ($d as &$value) if (is_string($value)) $value=trim($value);
        unset($value);
        if (!$v->reset()->setRules($rules)->run($d)) throw new \DomainException(implode(' ',$v->getErrors()));
        if (!$this->db->table('casis_establecimiento')->where('est_ide',$d['est_id'])->where('deleted_at',null)->countAllResults()) throw new \DomainException('Establecimiento inválido.');
        $id=(int)($d['id']??0); unset($d['id']);
        if ($id) $this->dispositivo($id);
        if ($this->db->table('casis_dispositivo')->where('codigo',$d['codigo'])->where('id !=',$id)->countAllResults()) throw new \DomainException('El código del equipo ya existe.');
        $d['ip']=($d['ip']??'')?:null; $d['puerto']=empty($d['puerto'])?null:(int)$d['puerto'];
        $d['updated_by']=$uid; $d['updated_at']=$this->ahora();
        if ($id) $ok=$this->db->table('casis_dispositivo')->where('id',$id)->update($d);
        else { $d['created_by']=$uid; $d['created_at']=$d['updated_at']; $ok=$this->db->table('casis_dispositivo')->insert($d); }
        if (!$ok) throw new \RuntimeException('No se pudo guardar el equipo.');
    }
    public function historial(int $pagina=1): array
    {
        $this->usuario();
        return $this->db->table('casis_asistencia_importacion i')->select('i.*, u.username, e.est_nombre')
            ->join('users u','u.id=i.imp_user_ide','left')->join('casis_establecimiento e','e.est_ide=i.imp_est_ide','left')
            ->where('i.imp_codigo !=',null)->orderBy('i.imp_ide','DESC')->limit(25,(max(1,$pagina)-1)*25)->get()->getResultArray();
    }
    public function carga(int $id): array
    {
        $this->usuario();
        $row=$this->db->table('casis_asistencia_importacion i')->select('i.*, u.username')->join('users u','u.id=i.imp_user_ide','left')->where('i.imp_ide',$id)->where('i.imp_codigo !=',null)->get()->getRowArray();
        if (!$row) throw new \DomainException('Carga no encontrada.');
        return $row;
    }
    public function ruta(array $carga): string
    {
        $raiz=realpath(WRITEPATH.'asistencia/dispositivos');
        $path=realpath(WRITEPATH.$carga['imp_ruta_archivo']);
        if (!$raiz || !$path || !str_starts_with($path,$raiz.DIRECTORY_SEPARATOR) || !is_file($path)) throw new \DomainException('El archivo no está disponible.');
        return $path;
    }
    public function recibir(UploadedFile $file, int $dispositivoId): int
    {
        $uid=$this->usuario(); $equipo=$this->dispositivo($dispositivoId);
        if (!$equipo['activo']) throw new \DomainException('El equipo está inactivo.');
        if (!$file->isValid() || $file->getSize()>10*1024*1024) throw new \DomainException('Seleccione un archivo válido de hasta 10 MB.');
        $ext=strtolower($file->getClientExtension());
        if (!in_array($ext,['txt','dat','csv','log','xls','xlsx'],true)) throw new \DomainException('Extensión no admitida.');
        $hash=hash_file('sha256',$file->getTempName());
        // Mismo contenido y establecimiento: no vuelve a cargarse aunque cambie el nombre o equipo.
        $clave=hash('sha256',$equipo['est_id'].'|'.$hash);
        $existente=$this->db->table('casis_asistencia_importacion')->where('imp_hash_archivo',$hash)->where('imp_est_ide',$equipo['est_id'])->where('imp_codigo !=',null)->get()->getRowArray();
        if ($existente) return (int)$existente['imp_ide'];
        $ahora=$this->ahora();
        $codigo='IMP-'.str_replace(['-',':',' '],['','','-'],$ahora).'-'.strtoupper(bin2hex(random_bytes(6)));
        $relative='asistencia/dispositivos/'.$codigo.'.'.$ext;
        $dir=WRITEPATH.'asistencia/dispositivos';
        if (!is_dir($dir) && !mkdir($dir,0750,true) && !is_dir($dir)) throw new \RuntimeException('No se pudo preparar el almacén de archivos.');
        $nombre=mb_substr(basename(str_replace('\\','/',$file->getClientName())),0,255);
        $file->move($dir,$codigo.'.'.$ext);
        try {
            $ok=$this->db->table('casis_asistencia_importacion')->insert([
                'imp_codigo'=>$codigo,'imp_nombre_archivo'=>$nombre,'imp_ruta_archivo'=>$relative,'imp_tipo_archivo'=>$ext,
                'imp_hash_archivo'=>$hash,'imp_clave'=>$clave,'imp_dispositivo_id'=>$dispositivoId,'imp_est_ide'=>$equipo['est_id'],
                'imp_dispositivo_snapshot'=>json_encode($equipo,JSON_THROW_ON_ERROR),'imp_user_ide'=>$uid,
                'imp_estado'=>'RECIBIDO','created_at'=>$ahora,'updated_at'=>$ahora,
            ]);
            if (!$ok) throw new \RuntimeException('No se pudo registrar el archivo.');
            $id=(int)$this->db->insertID();
        } catch (\Throwable $e) {
            unlink(WRITEPATH.$relative);
            $prev=$this->db->table('casis_asistencia_importacion')->where('imp_clave',$clave)->get()->getRowArray();
            if ($prev) return (int)$prev['imp_ide'];
            throw $e;
        }
        $this->revisar($id);
        return $id;
    }
    private function analizar(array $carga, bool $bloquearPeriodos=false): array
    {
        $ruta=$this->ruta($carga);
        if (!hash_equals($carga['imp_hash_archivo'],hash_file('sha256',$ruta))) throw new \DomainException('El archivo almacenado cambió; no se importará.');
        $r=(new MarcacionArchivoParser())->leer($ruta,$carga['imp_tipo_archivo']);
        $equipo=json_decode($carga['imp_dispositivo_snapshot'],true,512,JSON_THROW_ON_ERROR);
        if ($r['tipo']!==$equipo['marca']) { $r['errores'][]='El formato detectado no coincide con la marca del dispositivo seleccionado.'; $r['total_errores']++; }
        $personas=$this->db->table('casis_personal p')->select('p.perl_ide,p.perl_fecha_inicio,p.perl_fecha_termino,p.perl_fecha_cese,pe.per_numero_documento')
            ->join('casis_persona pe','pe.per_ide=p.perl_per_ide')->where('p.perl_est_ide',$carga['imp_est_ide'])->where('p.deleted_at',null)->get()->getResultArray();
        $map=[];
        foreach ($personas as $p) {
            try { $doc=MarcacionArchivoParser::documento((string)$p['per_numero_documento']); }
            catch (\DomainException $e) { continue; }
            $map[$doc][]=$p;
        }
        $cache=[]; $validos=[]; $duplicados=$r['duplicados'];
        foreach ($r['registros'] as $marca) {
            $fecha=substr($marca['fecha'],0,10);
            $candidatos=array_values(array_filter($map[$marca['documento']]??[],static function($p)use($fecha){
                return (empty($p['perl_fecha_inicio']) || $p['perl_fecha_inicio']<=$fecha)
                    && (empty($p['perl_fecha_termino']) || $p['perl_fecha_termino']>=$fecha)
                    && (empty($p['perl_fecha_cese']) || $p['perl_fecha_cese']>=$fecha);
            }));
            try {
                if (count($candidatos)!==1) throw new \DomainException(count($candidatos)?'Documento con varias asignaciones laborales vigentes.':'Documento sin personal asignado al establecimiento en esa fecha.');
                $id=(int)$candidatos[0]['perl_ide'];
                $existe=$this->db->table('casis_asistencia')->where('asi_perl_ide',$id)->where('asi_fecha_hora',$marca['fecha'])->countAllResults();
                if ($existe) { $duplicados++; continue; }
                $key=$id.'|'.$fecha;
                if (!isset($cache[$key])) {
                    $cache[$key]=$this->periodos->validarPeriodo($id,$fecha);
                    if ($bloquearPeriodos && !empty($cache[$key]['periodo_id']) && $this->db->DBDriver!=='SQLite3') {
                        $this->db->query('SELECT per_ide FROM casis_periodos WHERE per_ide = ? FOR UPDATE',[$cache[$key]['periodo_id']]);
                        $cache[$key]=$this->periodos->validarPeriodo($id,$fecha);
                    }
                }
                if (!$cache[$key]['permitido']) throw new \DomainException($cache[$key]['mensaje']);
                $marca['personal_id']=$id; $validos[]=$marca;
            } catch (\DomainException $e) {
                $r['total_errores']++; if (count($r['errores'])<100) $r['errores'][]=$marca['ubicacion'].' / '.$marca['documento'].': '.$e->getMessage();
            }
        }
        $r['validos']=$validos; $r['duplicados']=$duplicados;
        return $r;
    }
    public function revisar(int $id): array
    {
        $carga=$this->carga($id);
        if ($carga['imp_estado']==='PROCESADO') return ['procesado'=>true];
        try { $r=$this->analizar($carga); }
        catch (\DomainException $e) { $r=['tipo'=>null,'total'=>0,'total_errores'=>1,'errores'=>[$e->getMessage()],'duplicados'=>0,'validos'=>[]]; }
        catch (\PhpOffice\PhpSpreadsheet\Exception $e) { $r=['tipo'=>null,'total'=>0,'total_errores'=>1,'errores'=>['No se pudo leer el libro Excel. Verifique su formato.'],'duplicados'=>0,'validos'=>[]]; }
        $this->db->table('casis_asistencia_importacion')->where('imp_ide',$id)->where('imp_estado !=','PROCESADO')->update([
            'imp_estado'=>$r['total_errores']?'RECHAZADO':'VALIDADO','imp_origen'=>$r['tipo'],
            'imp_total_registros'=>$r['total'],'imp_total_duplicados'=>$r['duplicados'],'imp_total_error'=>$r['total_errores'],
            'imp_errores'=>json_encode($r['errores'],JSON_THROW_ON_ERROR|JSON_UNESCAPED_UNICODE),'updated_at'=>$this->ahora(),
            'imp_observacion'=>json_encode(['nuevos'=>count($r['validos']),'muestra'=>array_slice($r['validos'],0,30)],JSON_THROW_ON_ERROR),
        ]);
        return $r;
    }
    public function confirmar(int $id, string $ip): void
    {
        $uid=$this->usuario();
        $this->db->transException(true)->transBegin();
        try {
            $bloqueo=$this->db->DBDriver==='SQLite3'?'':' FOR UPDATE';
            $carga=$this->db->query('SELECT * FROM casis_asistencia_importacion WHERE imp_ide = ?'.$bloqueo,[$id])->getRowArray();
            if (!$carga || !$carga['imp_codigo']) throw new \DomainException('Carga no encontrada.');
            if ($carga['imp_estado']==='PROCESADO') throw new \DomainException('Esta carga ya fue procesada.');
            if (!$this->dispositivo((int)$carga['imp_dispositivo_id'])['activo']) throw new \DomainException('El dispositivo está inactivo.');
            $r=$this->analizar($carga,true);
            if ($r['total_errores']) throw new \DomainException('La carga contiene errores. Revise el detalle antes de confirmar.');
            $insertados=0; $ahora=$this->ahora(); $equipo=json_decode($carga['imp_dispositivo_snapshot'],true,512,JSON_THROW_ON_ERROR);
            foreach ($r['validos'] as $marca) {
                // La clave única existente protege también frente a otras cargas simultáneas.
                $conflicto=$this->db->DBDriver==='SQLite3'?' ON CONFLICT(asi_perl_ide,asi_fecha_hora) DO NOTHING':' ON DUPLICATE KEY UPDATE asi_ide=asi_ide';
                $this->db->query('INSERT INTO casis_asistencia (asi_imp_ide,asi_perl_ide,asi_numero_documento,asi_fecha_hora,asi_dispositivo,asi_origen,asi_ip_log,asi_user_ide,created_by,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?)'.$conflicto,[
                    $id,$marca['personal_id'],$marca['documento'],$marca['fecha'],$equipo['codigo'],'IMPORTACION',substr($ip,0,50),$uid,$uid,$ahora,$ahora,
                ]);
                if ($this->db->affectedRows()===1) $insertados++; else $r['duplicados']++;
            }
            $this->db->table('casis_asistencia_importacion')->where('imp_ide',$id)->update([
                'imp_estado'=>'PROCESADO','imp_confirmado_por'=>$uid,'imp_fecha_proceso'=>$ahora,'updated_at'=>$ahora,
                'imp_origen'=>$r['tipo'],'imp_total_registros'=>$r['total'],'imp_total_importados'=>$insertados,
                'imp_total_duplicados'=>$r['duplicados'],'imp_total_error'=>0,'imp_errores'=>'[]',
            ]);
            if (!$this->db->transStatus()) throw new \RuntimeException('No se pudo completar la importación.');
            $this->db->transCommit();
        } catch (\Throwable $e) { $this->db->transRollback(); throw $e; }
    }
}

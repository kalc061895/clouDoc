<?php
use CodeIgniter\Test\CIUnitTestCase;
use Modules\Asistencia\Services\CalificacionMensualService as S;
use Modules\Asistencia\Services\CalificacionMotor as M;

final class CalificacionMensualTest extends CIUnitTestCase
{
    protected $db;
    private S $service;
    private array $source;
    protected function setUp(): void
    {
        parent::setUp();
        $this->db=db_connect(['DBDriver'=>'SQLite3','database'=>':memory:','DBPrefix'=>'','DBDebug'=>true],false);
        foreach ([
            'mensual'=>'periodo_id INTEGER, ambito_tipo TEXT, ambito_id INTEGER, trabajador_id INTEGER, estado TEXT, fuentes TEXT, reglas TEXT, resultado TEXT, created_by INTEGER, created_at TEXT',
            'evento'=>'calificacion_id INTEGER, accion TEXT, detalle TEXT, created_by INTEGER, created_at TEXT',
            'criterio'=>'codigo TEXT, valor TEXT, updated_by INTEGER, updated_at TEXT',
            'pdf'=>'calificacion_id INTEGER, contenido BLOB, sha256 TEXT, created_by INTEGER, created_at TEXT',
        ] as $t=>$fields) $this->db->query('CREATE TABLE casis_cal_'.$t.' (id INTEGER PRIMARY KEY AUTOINCREMENT, '.$fields.')');
        foreach (M::DEFAULTS as $k=>$v) $this->db->table('casis_cal_criterio')->insert(['codigo'=>$k,'valor'=>$v]);
        $this->source=['personal'=>[['perl_ide'=>1,'perl_ofi_ide'=>7]],'inicio'=>'2026-10-01','fin'=>'2026-10-31','turnos'=>[],'marcas'=>[],'licencias'=>[],'vacaciones'=>[],'permisos'=>[],'feriados'=>[]];
        $this->service=$this->getMockBuilder(S::class)->disableOriginalConstructor()->onlyMethods(['usuario','ahora','exigirOficina','periodo','fuentes'])->getMock();
        $property=new ReflectionProperty(S::class,'db'); $property->setValue($this->service,$this->db);
        $this->service->method('usuario')->willReturn(9); $this->service->method('ahora')->willReturn('2026-11-01 10:00:00');
        $this->service->method('periodo')->willReturn(['per_ide'=>1]);
        $this->service->method('fuentes')->willReturnCallback(fn()=> $this->source);
    }
    private function generar(): int { return $this->service->generar(['periodo_id'=>1,'ambito_tipo'=>'establecimiento','ambito_id'=>1]); }
    public function testNoPermiteCerrarObservadaSinRevision(): void
    {
        $id=$this->generar(); self::assertSame('OBSERVADA',$this->service->obtener($id)['estado']);
        try { $this->service->accion($id,'CERRAR','Revisión de cierre'); self::fail(); } catch (DomainException $e) { self::assertStringContainsString('Revise',$e->getMessage()); }
        self::assertSame('OBSERVADA',$this->service->obtener($id)['estado']);
        $this->service->accion($id,'REVISAR','Ausencias confirmadas en los registros de origen.');
        $this->service->accion($id,'CERRAR','Resultados revisados y confirmados.');
        self::assertSame('CERRADA',$this->service->obtener($id)['estado']); self::assertCount(3,$this->service->eventos($id));
        $this->expectException(DomainException::class); $this->service->accion($id,'ANULAR','Intento posterior al cierre.');
    }
    public function testCambioDeFuentesExigeOtraVersion(): void
    {
        $id=$this->generar(); $this->service->accion($id,'REVISAR','Revisión documentada del periodo.');
        $this->source['personal'][0]['perl_ofi_ide']=8;
        $this->expectException(DomainException::class); $this->expectExceptionMessage('registros cambiaron');
        $this->service->accion($id,'CERRAR','Resultados revisados y confirmados.');
    }
    public function testNoDuplicaTrabajadorCerradoEnOtroLote(): void
    {
        $id=$this->generar(); $this->service->accion($id,'REVISAR','Revisión documentada del periodo.'); $this->service->accion($id,'CERRAR','Cierre documentado del periodo.');
        $otro=$this->generar(); $this->service->accion($otro,'REVISAR','Revisión documentada del periodo.');
        $this->expectException(DomainException::class); $this->expectExceptionMessage('ya cerrados'); $this->service->accion($otro,'CERRAR','Cierre documentado del periodo.');
    }
    public function testAccesoADetalleYDocumentosExigeOficina(): void
    {
        $id=$this->generar(); $this->service->method('exigirOficina')->willThrowException(new DomainException('Sin autorización.'));
        $this->expectException(DomainException::class); $this->service->pdfs($id);
    }
    public function testPdfSeConservaYRenderiza(): void
    {
        $this->source['personal'][0]+= ['per_numero_documento'=>'12345678','per_paterno'=>'Pérez','per_materno'=>'García','per_nombre'=>'Ana María','est_nombre'=>'Hospital de prueba'];
        $id=$this->generar(); $this->service->accion($id,'REVISAR','Ausencias confirmadas durante revisión.'); $this->service->accion($id,'CERRAR','Resultados revisados y confirmados.');
        $this->service->generarPdf($id); $this->service->generarPdf($id); $docs=$this->service->pdfs($id);
        self::assertCount(2,$docs); $pdf=$this->service->archivo((int)$docs[0]['id']); self::assertStringStartsWith('%PDF-',$pdf['contenido']);
        if (!is_dir(WRITEPATH.'calificacion-qa')) mkdir(WRITEPATH.'calificacion-qa',0777,true);
        file_put_contents(WRITEPATH.'calificacion-qa/muestra.pdf',$pdf['contenido']);
    }
}

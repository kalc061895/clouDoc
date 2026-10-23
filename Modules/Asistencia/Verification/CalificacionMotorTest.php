<?php
use PHPUnit\Framework\TestCase;
use Modules\Asistencia\Services\CalificacionMotor as M;

final class CalificacionMotorTest extends TestCase
{
    private function fuentes(string $fecha='2026-10-04',string $a='08:00:00',string $b='14:00:00'): array
    {
        return ['inicio'=>'2026-10-01','fin'=>'2026-10-31','personal'=>[['perl_ide'=>1]],'turnos'=>[['prog_perl_ide'=>1,'prog_fecha'=>$fecha,'th_hora_ingreso'=>$a,'th_hora_salida'=>$b,'tur_codigo'=>'GD']], 'marcas'=>[], 'licencias'=>[], 'vacaciones'=>[], 'permisos'=>[], 'feriados'=>[]];
    }
    private function marcas(array &$f,array $horas): void
    {
        foreach ($horas as [$fecha,$tipo]) $f['marcas'][]=['asi_perl_ide'=>1,'asi_fecha_hora'=>$fecha,'asi_tipo'=>$tipo];
    }
    public function testAsistenciaTardanzaYDomingo(): void
    {
        $f=$this->fuentes(); $this->marcas($f,[['2026-10-04 08:10:00','ENTRADA'],['2026-10-04 14:05:00','SALIDA']]);
        $r=M::evaluar($f,M::DEFAULTS)[0];
        self::assertSame(350,$r['minutos_asistidos']); self::assertSame(10,$r['minutos_tardanza']); self::assertSame(1,$r['guardias_domingo']);
    }
    public function testNocturnaCruzaFinDeMes(): void
    {
        $f=$this->fuentes('2026-10-31','20:00:00','08:00:00'); $this->marcas($f,[['2026-10-31 20:00:00','ENTRADA'],['2026-11-01 08:00:00','SALIDA']]);
        self::assertSame(720,M::evaluar($f,M::DEFAULTS)[0]['minutos_asistidos']);
    }
    public function testLicenciaSuperpuestaNoDuplicaNiGeneraFalta(): void
    {
        $f=$this->fuentes(); $l=['rl_perl_ide'=>1,'rl_fecha_inicio'=>'2026-09-30','rl_fecha_fin'=>'2026-10-04']; $f['licencias']=[$l,$l];
        $r=M::evaluar($f,M::DEFAULTS)[0]; self::assertSame(4,$r['dias_licencia']); self::assertSame(0,$r['faltas']); self::assertSame(0,$r['minutos_asistidos']);
    }
    public function testFaltaEIngresoSinSalidaSonDistintos(): void
    {
        $f=$this->fuentes(); self::assertSame(1,M::evaluar($f,M::DEFAULTS)[0]['faltas']);
        $this->marcas($f,[['2026-10-04 08:00:00','ENTRADA']]); $r=M::evaluar($f,M::DEFAULTS)[0];
        self::assertSame(0,$r['faltas']); self::assertContains('MARCACIONES_INCOMPLETAS_O_MULTIPLES',array_column($r['observaciones'],'codigo'));
    }
    public function testPermisoAprobadoNoDuplicaTiempo(): void
    {
        $f=$this->fuentes(); $this->marcas($f,[['2026-10-04 08:00:00','ENTRADA'],['2026-10-04 14:00:00','SALIDA']]);
        $p=['rp_perl_ide'=>1,'rp_estado'=>'APROBADO','rp_fecha'=>'2026-10-04','rp_hora_salida'=>'10:00:00','rp_hora_retorno'=>'11:00:00']; $f['permisos']=[$p,$p];
        $r=M::evaluar($f,M::DEFAULTS)[0]; self::assertSame(60,$r['minutos_permiso']); self::assertSame(300,$r['minutos_asistidos']);
        $f['permisos'][0]['rp_estado']='PENDIENTE'; $f['permisos']=[$f['permisos'][0]];
        $r=M::evaluar($f,M::DEFAULTS)[0]; self::assertSame(360,$r['minutos_asistidos']); self::assertContains('REVISAR_PAPELETA_PENDIENTE',array_column($r['observaciones'],'codigo'));
    }
    public function testMultiplesParesYFeriadoRecurrente(): void
    {
        $f=$this->fuentes(); $f['feriados']=[['df_fecha'=>'2020-10-04','df_repetitivo'=>1]];
        $this->marcas($f,[['2026-10-04 08:00:00','ENTRADA'],['2026-10-04 10:00:00','SALIDA'],['2026-10-04 11:00:00','ENTRADA'],['2026-10-04 14:00:00','SALIDA']]);
        $r=M::evaluar($f,M::DEFAULTS)[0]; self::assertSame(300,$r['minutos_asistidos']); self::assertSame(1,$r['guardias_feriado']);
    }
    public function testNoInfiereHorasDeMarcasSinTipo(): void
    {
        $f=$this->fuentes(); $this->marcas($f,[['2026-10-04 08:00:00',''],['2026-10-04 14:00:00','']]);
        self::assertSame(0,M::evaluar($f,M::DEFAULTS)[0]['minutos_asistidos']);
    }
}

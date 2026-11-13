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
    public function testAplicaIdentificacionInicialSinConfirmacion(): void
    {
        $f=$this->fuentes(); $this->marcas($f,[['2026-10-04 08:00:00',''],['2026-10-04 14:00:00','']]);
        $r = M::evaluar($f,M::DEFAULTS)[0];
        self::assertSame(360,$r['minutos_asistidos']);
        self::assertTrue($r['detalle'][0]['clasificacion']['automatica']);
        self::assertFalse($r['detalle'][0]['clasificacion']['proponible']);
        self::assertSame([], $r['observaciones']);
    }
    public function testAplicaSecuenciaInicialYPermiteRevisionPosterior(): void
    {
        $f = $this->fuentes();
        $this->marcas($f, [['2026-10-04 08:00:00',''], ['2026-10-04 10:00:00',''], ['2026-10-04 11:00:00',''], ['2026-10-04 14:00:00','']]);
        foreach ($f['marcas'] as $i => &$marca) $marca['asi_ide'] = $i + 1;
        unset($marca);
        $r = M::evaluar($f, M::DEFAULTS)[0];
        $propuesta = $r['detalle'][0]['clasificacion'];
        self::assertFalse($propuesta['proponible']);
        self::assertTrue($propuesta['automatica']);
        self::assertSame(['ENTRADA','SALIDA','ENTRADA','SALIDA'], array_column($propuesta['marcaciones'], 'tipo_propuesto'));
        self::assertStringContainsString('pausa', $propuesta['marcaciones'][1]['evento']);
        self::assertSame(300, $r['minutos_asistidos']);
        self::assertSame([], $r['observaciones']);
        $confirmadas = [];
        foreach ($propuesta['marcaciones'] as $marca) $confirmadas[$marca['asi_ide']] = ['tipo'=>$marca['tipo_propuesto']];
        $r = M::evaluar($f, M::DEFAULTS, $confirmadas)[0];
        self::assertSame(300, $r['minutos_asistidos']);
        self::assertCount(4, $r['clasificaciones_confirmadas']);
        self::assertSame('', $r['detalle'][0]['clasificacion']['marcaciones'][0]['tipo_original']);
        self::assertSame('', $f['marcas'][0]['asi_tipo']);
    }
    public function testNoProponeSecuenciasIncompletasContradictoriasOCompartidas(): void
    {
        $f = $this->fuentes();
        $this->marcas($f, [['2026-10-04 08:00:00','']]);
        self::assertFalse(M::evaluar($f, M::DEFAULTS)[0]['detalle'][0]['clasificacion']['proponible']);
        $this->marcas($f, [['2026-10-04 14:00:00','ENTRADA']]);
        self::assertFalse(M::evaluar($f, M::DEFAULTS)[0]['detalle'][0]['clasificacion']['proponible']);
        $f['marcas'][1]['asi_tipo'] = null;
        $f['turnos'][] = $f['turnos'][0];
        self::assertFalse(M::evaluar($f, M::DEFAULTS)[0]['detalle'][0]['clasificacion']['proponible']);
    }
    public function testProponeGuardiaNocturnaConTipoAusente(): void
    {
        $f = $this->fuentes('2026-10-31', '20:00:00', '08:00:00');
        $f['marcas'] = [['asi_perl_ide'=>1, 'asi_fecha_hora'=>'2026-10-31 20:00:00'], ['asi_perl_ide'=>1, 'asi_fecha_hora'=>'2026-11-01 08:00:00']];
        $propuesta = M::evaluar($f, M::DEFAULTS)[0]['detalle'][0]['clasificacion'];
        self::assertFalse($propuesta['proponible']);
        self::assertTrue($propuesta['automatica']);
        self::assertSame(['ENTRADA','SALIDA'], array_column($propuesta['marcaciones'], 'tipo_propuesto'));
    }
    public function testRevisionManualExcluyeMarcaExtraYCorrigeTipoRegistrado(): void
    {
        $f = $this->fuentes();
        $f['marcas'] = [
            ['asi_ide'=>1, 'asi_perl_ide'=>1, 'asi_fecha_hora'=>'2026-10-04 08:00:00', 'asi_tipo'=>'SALIDA'],
            ['asi_ide'=>2, 'asi_perl_ide'=>1, 'asi_fecha_hora'=>'2026-10-04 08:01:00', 'asi_tipo'=>null],
            ['asi_ide'=>3, 'asi_perl_ide'=>1, 'asi_fecha_hora'=>'2026-10-04 14:00:00', 'asi_tipo'=>null],
        ];
        $r = M::evaluar($f, M::DEFAULTS, [1=>['tipo'=>'ENTRADA'], 2=>['tipo'=>'IGNORAR'], 3=>['tipo'=>'SALIDA']])[0];
        self::assertSame(360, $r['minutos_asistidos']);
        self::assertSame([], $r['observaciones']);
        self::assertSame('IGNORAR', $r['clasificaciones_confirmadas'][2]['tipo']);
        self::assertSame('SALIDA', $r['detalle'][0]['clasificacion']['marcaciones'][0]['tipo_original']);
        self::assertCount(2, $r['detalle'][0]['clasificacion']['marcaciones']);
    }
    public function testTurnoElegidoResuelveVentanasCompartidas(): void
    {
        $f = $this->fuentes();
        $f['turnos'][0]['prog_ide'] = 10;
        $f['turnos'][] = ['prog_ide'=>11, 'prog_perl_ide'=>1, 'prog_fecha'=>'2026-10-04', 'th_hora_ingreso'=>'15:00:00', 'th_hora_salida'=>'18:00:00', 'tur_codigo'=>'T'];
        $f['marcas'] = [];
        foreach (['08:00:00', '14:00:00', '15:00:00', '18:00:00'] as $i=>$hora) {
            $f['marcas'][] = ['asi_ide'=>$i+1, 'asi_perl_ide'=>1, 'asi_fecha_hora'=>'2026-10-04 '.$hora, 'asi_tipo'=>null];
        }
        $confirmadas = [1=>['tipo'=>'ENTRADA','turno_id'=>10], 2=>['tipo'=>'SALIDA','turno_id'=>10], 3=>['tipo'=>'ENTRADA','turno_id'=>11], 4=>['tipo'=>'SALIDA','turno_id'=>11]];
        $r = M::evaluar($f, M::DEFAULTS, $confirmadas)[0];
        self::assertSame(540, $r['minutos_asistidos']);
        self::assertSame([], $r['observaciones']);
    }

    public function testIdentificacionInicialRespetaRefrigerioYCorrecciones(): void
    {
        $f = $this->fuentes();
        $f['turnos'][0] += ['th_interpretacion'=>'MANUAL', 'th_refrigerio_marcacion'=>'CON_MARCAS',
            'th_refrigerio_salida'=>'11:00', 'th_refrigerio_retorno'=>'12:00'];
        $f['marcas'] = [
            ['asi_ide'=>1,'asi_perl_ide'=>1,'asi_fecha_hora'=>'2026-10-04 08:00:00','asi_tipo'=>''],
            ['asi_ide'=>2,'asi_perl_ide'=>1,'asi_fecha_hora'=>'2026-10-04 14:00:00','asi_tipo'=>''],
        ];
        $r = M::evaluar($f, M::DEFAULTS)[0];
        self::assertSame(360, $r['minutos_asistidos']);
        self::assertTrue($r['detalle'][0]['clasificacion']['automatica']);
        self::assertFalse($r['detalle'][0]['clasificacion']['proponible']);
        self::assertContains('INTERPRETACION_HORARIO_PENDIENTE', array_column($r['observaciones'], 'codigo'));

        unset($f['turnos'][0]['th_refrigerio_marcacion'], $f['turnos'][0]['th_refrigerio_salida'], $f['turnos'][0]['th_refrigerio_retorno']);
        self::assertSame(360, M::evaluar($f, M::DEFAULTS)[0]['minutos_asistidos']);
        $r = M::evaluar($f, M::DEFAULTS, [2=>['tipo'=>'IGNORAR']])[0];
        self::assertSame(0, $r['minutos_asistidos']);
        self::assertSame('IGNORAR', $r['clasificaciones_confirmadas'][2]['tipo']);
        self::assertContains('MARCACIONES_INCOMPLETAS_O_MULTIPLES', array_column($r['observaciones'], 'codigo'));
    }
}

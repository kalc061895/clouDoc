<?php

use PHPUnit\Framework\TestCase;
use Modules\Asistencia\Services\InterpretacionHorario as I;
use Modules\Asistencia\Services\CalificacionMotor as M;

final class InterpretacionHorarioTest extends TestCase
{
    private function horario(array $cambios = []): array
    {
        return array_replace(I::DEFAULTS, ['th_interpretacion'=>'AUTO', 'th_refrigerio_marcacion'=>'SIN_REFRIGERIO',
            'th_hora_ingreso'=>'08:00', 'th_hora_salida'=>'16:00'], $cambios);
    }

    private function interpretar(array $h, array $horas, bool $ambigua = false): array
    {
        $a = strtotime('2026-10-31 '.$h['th_hora_ingreso']);
        $b = strtotime('2026-10-31 '.$h['th_hora_salida']);
        if ($b < $a) $b += 86400;
        $marcas = array_map(static fn($hora) => ['asi_fecha_hora'=>$hora, 'asi_tipo'=>''], $horas);
        return I::interpretar($marcas, $h, $a, $b, $ambigua);
    }

    public function testDosMarcasCalculanSinConfirmacionYConservanOrigen(): void
    {
        $h = $this->horario() + ['prog_perl_ide'=>1, 'prog_fecha'=>'2026-10-31', 'tur_codigo'=>'M'];
        $f = ['personal'=>[['perl_ide'=>1]], 'turnos'=>[$h], 'marcas'=>[
            ['asi_ide'=>1,'asi_perl_ide'=>1,'asi_fecha_hora'=>'2026-10-31 08:10:00','asi_tipo'=>''],
            ['asi_ide'=>2,'asi_perl_ide'=>1,'asi_fecha_hora'=>'2026-10-31 16:15:00','asi_tipo'=>''],
        ], 'inicio'=>'2026-10-01','fin'=>'2026-10-31','licencias'=>[],'vacaciones'=>[],'permisos'=>[],'feriados'=>[]];
        $r = M::evaluar($f, M::DEFAULTS)[0];
        self::assertSame(470, $r['minutos_asistidos']);
        self::assertSame(485, $r['detalle'][0]['minutos_presencia']);
        self::assertSame(10, $r['minutos_tardanza']);
        self::assertTrue($r['detalle'][0]['clasificacion']['automatica']);
        self::assertSame('', $r['detalle'][0]['clasificacion']['marcaciones'][0]['tipo_original']);
        self::assertSame('', $f['marcas'][0]['asi_tipo']);
        self::assertSame([], $r['observaciones']);
    }

    public function testRefrigerioNocturnoCruzaMes(): void
    {
        $h = $this->horario(['th_hora_ingreso'=>'20:00','th_hora_salida'=>'08:00',
            'th_refrigerio_marcacion'=>'CON_MARCAS','th_refrigerio_salida'=>'01:00','th_refrigerio_retorno'=>'02:00','th_margen_refrigerio'=>15]);
        $r = $this->interpretar($h, ['2026-10-31 19:55','2026-11-01 01:03','2026-11-01 02:02','2026-11-01 08:05']);
        self::assertTrue($r['aplicada']);
        self::assertSame(['ENTRADA','SALIDA','ENTRADA','SALIDA'], array_column($r['marcas'], 'asi_tipo'));
        self::assertSame('Salida a refrigerio', $r['marcas'][1]['interpretacion_automatica']['evento']);
    }

    public function testNoInventaMarcasNiResuelveAmbiguedades(): void
    {
        $h = $this->horario();
        foreach ([['2026-10-31 08:00'], ['2026-10-31 08:00','2026-10-31 12:01','2026-10-31 16:00'],
            ['2026-10-31 11:00','2026-10-31 16:00']] as $horas) {
            self::assertFalse($this->interpretar($h, $horas)['aplicada']);
        }
        self::assertFalse($this->interpretar($h, ['2026-10-31 08:00','2026-10-31 16:00'], true)['aplicada']);
        $h['th_hora_salida'] = '10:00';
        self::assertFalse($this->interpretar($h, ['2026-10-31 08:00','2026-10-31 10:00'])['aplicada']);
    }

    public function testRefrigerioExigidoNoSeOmite(): void
    {
        $h = $this->horario(['th_refrigerio_marcacion'=>'CON_MARCAS', 'th_refrigerio_salida'=>'12:00','th_refrigerio_retorno'=>'13:00']);
        self::assertFalse($this->interpretar($h, ['2026-10-31 08:00','2026-10-31 16:00'])['aplicada']);
        $h['th_refrigerio_marcacion'] = 'SIN_MARCAS';
        self::assertTrue($this->interpretar($h, ['2026-10-31 08:00','2026-10-31 16:00'])['aplicada']);
    }

    public function testValidaConfiguracionCompleta(): void
    {
        self::assertSame([], I::validar($this->horario()));
        foreach ([['th_refrigerio_marcacion'=>'NO_DEFINIDO'], ['th_hora_salida'=>'08:00'], ['th_hora_ingreso'=>'25:00'],
            ['th_margen_marcacion'=>-1], ['th_refrigerio_marcacion'=>'CON_MARCAS'],
            ['th_refrigerio_marcacion'=>'CON_MARCAS','th_refrigerio_salida'=>'13:00','th_refrigerio_retorno'=>'12:00']] as $cambio) {
            self::assertNotEmpty(I::validar($this->horario($cambio)));
        }
    }

    public function testAgrupaPrimerIngresoYUltimaSalidaConTrazabilidad(): void
    {
        $h = $this->horario() + ['prog_perl_ide'=>1, 'prog_fecha'=>'2026-10-31', 'tur_codigo'=>'M'];
        $marcas = [];
        foreach (['08:10','08:11','08:11','16:00','16:04','16:15'] as $i => $hora) {
            $marcas[] = ['asi_ide'=>$i+1, 'asi_perl_ide'=>1, 'asi_fecha_hora'=>'2026-10-31 '.$hora, 'asi_tipo'=>''];
        }
        $f = ['personal'=>[['perl_ide'=>1]], 'turnos'=>[$h], 'marcas'=>$marcas,
            'inicio'=>'2026-10-01','fin'=>'2026-10-31','licencias'=>[],'vacaciones'=>[],'permisos'=>[],'feriados'=>[]];
        $r = M::evaluar($f, M::DEFAULTS)[0];
        self::assertSame(470, $r['minutos_asistidos']);
        self::assertSame(10, $r['minutos_tardanza']);
        self::assertSame(485, $r['detalle'][0]['minutos_presencia']);
        $c = $r['detalle'][0]['clasificacion'];
        self::assertSame([1,6], array_column($c['marcaciones'], 'asi_ide'));
        self::assertSame([2,3,4,5], array_column($c['marcaciones_agrupadas'], 'asi_ide'));
        self::assertSame([1,1,6,6], array_column($c['marcaciones_agrupadas'], 'marca_elegida_id'));
        self::assertSame([], $r['observaciones']);
        self::assertSame($marcas, $f['marcas']);
    }

    public function testAgrupacionSoloSinRefrigerioYConAmbosGrupos(): void
    {
        $horas = ['2026-10-31 08:00','2026-10-31 08:01','2026-10-31 16:00'];
        self::assertTrue($this->interpretar($this->horario(), $horas)['aplicada']);
        foreach ([['th_tipo_jornada'=>'GUARDIA_DIURNA'], ['th_interpretacion'=>'MANUAL'],
            ['th_refrigerio_marcacion'=>'SIN_MARCAS','th_refrigerio_salida'=>'12:00','th_refrigerio_retorno'=>'13:00'],
            ['th_refrigerio_marcacion'=>'CON_MARCAS','th_refrigerio_salida'=>'12:00','th_refrigerio_retorno'=>'13:00']] as $cambio) {
            self::assertFalse($this->interpretar($this->horario($cambio), $horas)['aplicada']);
        }
        self::assertFalse($this->interpretar($this->horario(), $horas, true)['aplicada']);
        self::assertFalse($this->interpretar($this->horario(), ['2026-10-31 08:00','2026-10-31 08:01','2026-10-31 08:02'])['aplicada']);
        self::assertFalse($this->interpretar($this->horario(['th_hora_salida'=>'10:00']), ['2026-10-31 08:00','2026-10-31 08:01','2026-10-31 10:00'])['aplicada']);
        $r = I::interpretar([
            ['asi_fecha_hora'=>'2026-10-31 08:00','asi_tipo'=>'ENTRADA'],
            ['asi_fecha_hora'=>'2026-10-31 08:01','asi_tipo'=>'SALIDA'],
            ['asi_fecha_hora'=>'2026-10-31 16:00','asi_tipo'=>'SALIDA'],
        ], $this->horario(), strtotime('2026-10-31 08:00'), strtotime('2026-10-31 16:00'), false);
        self::assertFalse($r['aplicada']);
    }
}

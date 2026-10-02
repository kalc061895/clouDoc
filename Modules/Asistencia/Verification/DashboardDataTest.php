<?php

use CodeIgniter\Test\CIUnitTestCase;
use Modules\Asistencia\Services\DashboardData;

final class DashboardDataTest extends CIUnitTestCase
{
    public function testCoberturaPersonasUnicasYAlertas(): void
    {
        $p = [];
        for ($id = 1; $id <= 5; $id++) $p[] = ['perl_ide' => $id, 'per_nombre' => 'Persona ' . $id, 'per_sexo' => 'F', 'per_fecha_nacimiento' => '1990-10-02', 'est_nombre' => 'Hospital', 'ofi_nombre' => 'Unidad', 'mco_nombre' => 'CAS'];
        $t = ['prog_perl_ide' => 1, 'th_hora_ingreso' => '08:00:00', 'th_hora_salida' => '14:00:00', 'tur_codigo' => 'M', 'ups_nombre' => 'UPSS', 'uss_nombre' => 'Servicio'];
        $turnos = [$t, $t, array_replace($t, ['prog_perl_ide' => 2]), array_replace($t, ['prog_perl_ide' => 3]), array_replace($t, ['prog_perl_ide' => 4, 'th_hora_ingreso' => '19:00:00', 'th_hora_salida' => '07:00:00'])];
        $marcas = [['asi_perl_ide' => 1, 'asi_fecha_hora' => '2026-10-02 07:50:00', 'asi_tipo' => 'ENTRADA'], ['asi_perl_ide' => 2, 'asi_fecha_hora' => '2026-10-02 14:00:00', 'asi_tipo' => 'SALIDA'], ['asi_perl_ide' => 5, 'asi_fecha_hora' => '2026-10-02 08:00:00', 'asi_tipo' => 'ENTRADA']];
        $lic = [['rl_perl_ide' => 3, 'rl_fecha_fin' => '2026-10-03', 'lic_nombre' => 'Licencia']];
        $r = DashboardData::resumir($p, $turnos, $marcas, $lic, [], [], [], '2026-10-02', new DateTimeImmutable('2026-10-02 16:00:00', new DateTimeZone('America/Lima')));
        self::assertSame(4, $r['stats']['programados']);
        self::assertSame(1, $r['stats']['ingresaron']);
        self::assertSame(1, $r['stats']['posibles_faltas']);
        self::assertSame(1, $r['stats']['justificados']);
        self::assertSame(1, $r['stats']['futuro']);
        self::assertSame(1, $r['stats']['sin_programacion']);
        self::assertSame(25.0, $r['stats']['cobertura']);
        self::assertSame(4, array_values($r['grupos']['servicio'])[0]['total']);
        self::assertCount(5, $r['listas']['cumpleanos']);
    }

    public function testEntradaNocturnaPosteriorAMedianoche(): void
    {
        $p = [['perl_ide' => 1, 'est_nombre' => 'Hospital', 'ofi_nombre' => '', 'mco_nombre' => '']];
        $t = [['prog_perl_ide' => 1, 'th_hora_ingreso' => '23:00:00', 'th_hora_salida' => '07:00:00', 'ups_nombre' => '', 'uss_nombre' => '']];
        $m = [['asi_perl_ide' => 1, 'asi_fecha_hora' => '2026-10-02 00:05:00', 'asi_tipo' => 'ENTRADA']];
        $r = DashboardData::resumir($p, $t, $m, [], [], [], [], '2026-10-01', new DateTimeImmutable('2026-10-02 08:00:00', new DateTimeZone('America/Lima')));
        self::assertSame(1, $r['stats']['ingresaron']);
        self::assertSame(0, $r['stats']['pendientes']);
    }
}

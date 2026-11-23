<?php

use CodeIgniter\Test\CIUnitTestCase;
use Modules\Asistencia\Database\Migrations\AddAdvertenciasRevisionRol;
use Modules\Asistencia\Services\AdvertenciasRol;
use Modules\Asistencia\Services\ConfiguracionRolService;

final class AdvertenciasRolTest extends CIUnitTestCase
{
    public function testMigracionConservaEnviosYConfiguraPeriodosExistentes(): void
    {
        $db = db_connect(['DBDriver' => 'SQLite3', 'database' => ':memory:', 'DBPrefix' => '', 'DBDebug' => true], false);
        try {
            $db->query('CREATE TABLE casis_plan_periodo (id INTEGER PRIMARY KEY)');
            $db->query('CREATE TABLE casis_rol_envio (id INTEGER PRIMARY KEY, datos_json TEXT)');
            $db->table('casis_plan_periodo')->insert(['id' => 1]);
            $db->table('casis_rol_envio')->insert(['id' => 1, 'datos_json' => '[]']);
            require_once dirname(__DIR__) . '/Database/Migrations/2026-11-23-120000_AddAdvertenciasRevisionRol.php';
            $migracion = new AddAdvertenciasRevisionRol(\Config\Database::forge($db));
            $migracion->up();

            $periodo = $db->table('casis_plan_periodo')->get()->getRowArray();
            self::assertSame(2, (int) $periodo['max_dias_sin_turno']);
            self::assertNull($periodo['max_horas_semanales']);
            $envio = $db->table('casis_rol_envio')->get()->getRowArray();
            self::assertSame('[]', $envio['datos_json']);
            self::assertNull($envio['observaciones_rrhh']);
            self::assertNull($envio['observaciones_direccion']);
        } finally {
            $db->close();
        }
    }

    public function testNocturnoDistribuyeLasHorasAlCambiarDeSemana(): void
    {
        $inicio = new DateTimeImmutable('2026-11-01', new DateTimeZone('America/Lima'));
        $turnos = [
            ['prog_fecha' => '2026-11-01', 'th_hora_ingreso' => '21:00:00', 'th_hora_salida' => '03:00:00'],
            ['prog_fecha' => '2026-11-02', 'th_hora_ingreso' => '08:00:00', 'th_hora_salida' => '12:00:00'],
            ['prog_fecha' => '2026-11-30', 'th_hora_ingreso' => '22:00:00', 'th_hora_salida' => '08:00:00'],
        ];

        self::assertSame(
            ['02/11 al 08/11: 7.00 h programadas (máx. 3.00 h).'],
            AdvertenciasRol::evaluar($turnos, $inicio, null, 3)
        );
    }

    public function testNocturnoCuentaComoTurnoEnAmbosDiasYDetectaRachas(): void
    {
        $inicio = new DateTimeImmutable('2028-02-01');
        $turnos = [
            ['prog_fecha' => '2028-02-14', 'th_hora_ingreso' => '20:00:00', 'th_hora_salida' => '04:00:00'],
        ];

        self::assertSame([], AdvertenciasRol::evaluar($turnos, $inicio, 14, null));
        self::assertSame(
            ['Hasta 14 días consecutivos sin turno (máx. 13).'],
            AdvertenciasRol::evaluar($turnos, $inicio, 13, null)
        );
        self::assertSame(
            ['Hasta 29 días consecutivos sin turno (máx. 2).'],
            AdvertenciasRol::evaluar([], $inicio, 2, null)
        );
    }

    public function testParametrosDistinguenValorVacioDeCero(): void
    {
        $periodo = [
            'grupo_id' => 1, 'mes' => '2026-11',
            'apertura' => '2026-10-24T08:00', 'aprobacion' => '2026-10-28T08:00', 'cierre' => '2026-10-31T18:00',
        ];
        $datos = ConfiguracionRolService::datosPeriodo($periodo);
        self::assertSame(2, $datos['max_dias_sin_turno']);
        self::assertNull($datos['max_horas_semanales']);

        $datos = ConfiguracionRolService::datosPeriodo($periodo + ['max_dias_sin_turno' => '', 'max_horas_semanales' => '0']);
        self::assertNull($datos['max_dias_sin_turno']);
        self::assertSame(0.0, $datos['max_horas_semanales']);

        $this->expectException(DomainException::class);
        ConfiguracionRolService::datosPeriodo($periodo + ['max_horas_semanales' => '168.01']);
    }
}

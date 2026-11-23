<?php

use CodeIgniter\Test\CIUnitTestCase;
use Modules\Asistencia\Services\AdvertenciasRol;
use Modules\Asistencia\Services\AusenciasRolService;
use Modules\Asistencia\Services\RolDocumentoData;
use Modules\Asistencia\Services\RolPdfRenderer;

final class AusenciasRolTest extends CIUnitTestCase
{
    public function testAusenciasVigentesSeRecortanAlMesYLasHistoricasSeConservan(): void
    {
        $db = db_connect(['DBDriver' => 'SQLite3', 'database' => ':memory:', 'DBPrefix' => '', 'DBDebug' => true], false);
        try {
            $db->query('CREATE TABLE casis_registro_licencia (rl_ide INT, rl_perl_ide INT, rl_lic_ide INT, rl_fecha_inicio TEXT, rl_fecha_fin TEXT, rl_numero_documento TEXT, rl_estado INT, deleted_at TEXT)');
            $db->query('CREATE TABLE casis_licencia (lic_ide INT, lic_nombre TEXT)');
            $db->query('CREATE TABLE casis_registro_vacacion (rv_ide INT, rv_perl_ide INT, rv_fecha_inicio TEXT, rv_fecha_fin TEXT, rv_numero_documento TEXT, rv_estado INT, deleted_at TEXT)');
            $db->query('CREATE TABLE casis_registro_permiso (rp_ide INT, rp_perl_ide INT, rp_pero_ide INT, rp_fecha TEXT, rp_hora_salida TEXT, rp_hora_retorno TEXT, rp_numero_documento TEXT, rp_estado TEXT, deleted_at TEXT)');
            $db->query('CREATE TABLE casis_permiso (pero_ide INT, pero_nombre TEXT)');
            $db->query("INSERT INTO casis_licencia VALUES (1, 'Destaque')");
            $db->query("INSERT INTO casis_registro_licencia VALUES (1, 5, 1, '2026-11-20', '2026-12-15', 'R-43', 1, NULL)");
            $db->query("INSERT INTO casis_registro_vacacion VALUES
                (1, 5, '2026-10-20', '2026-11-18', 'R-42', 1, NULL),
                (2, 5, '2026-11-19', '2026-11-24', NULL, 0, NULL),
                (3, 5, '2026-11-19', '2026-11-24', NULL, 1, '2026-10-21'),
                (4, 7, '2026-11-01', '2026-11-30', NULL, 1, NULL)");
            $db->query("INSERT INTO casis_permiso VALUES (1, 'Lactancia')");
            $db->query("INSERT INTO casis_registro_permiso VALUES
                (1, 5, 1, '2026-11-19', '08:00:00', '09:00:00', NULL, '1', NULL),
                (2, 5, 1, '2026-11-20', '08:00:00', '09:00:00', NULL, 'RECHAZADO', NULL)");

            $service = new AusenciasRolService($db);
            $miembros = $service->completarSnapshot([
                ['personal' => ['perl_ide' => 5]],
                ['personal' => ['perl_ide' => 7], 'ausencias' => []],
            ], '2026-11');
            self::assertSame([], $miembros[1]['ausencias']);
            self::assertSame(['Destaque', 'Vacaciones', 'Lactancia'], array_column($miembros[0]['ausencias'], 'nombre'));
            self::assertSame('2026-11-30', $miembros[0]['ausencias'][0]['fin']);
            self::assertSame('2026-11-01', $miembros[0]['ausencias'][1]['inicio']);
            self::assertSame('08:00–09:00', $miembros[0]['ausencias'][2]['horario']);
        } finally {
            $db->close();
        }
    }

    public function testCoincidenciaPreservaTurnoYCortaElBloque(): void
    {
        $vacaciones = ['nombre' => 'Vacaciones', 'inicio' => '2026-10-01', 'fin' => '2026-10-30',
            'documento' => 'R-42', 'por_horas' => false, 'horario' => '', 'estado' => ''];
        $lactancia = ['nombre' => 'Lactancia', 'inicio' => '2026-10-31', 'fin' => '2026-10-31',
            'documento' => '', 'por_horas' => true, 'horario' => '08:00–09:00', 'estado' => ''];
        $calendario = AusenciasRolService::calendario([$vacaciones], [], 31);
        self::assertSame([1 => ['dias' => 30, 'detalle' => 'Vacaciones · R-42']], $calendario['tramos']);
        self::assertArrayNotHasKey(31, $calendario['dias']);
        self::assertSame([], AdvertenciasRol::evaluar([], new DateTimeImmutable('2026-10-01'), 2, null, $calendario['cubiertos']));

        $calendario = AusenciasRolService::calendario([$vacaciones, $lactancia], [16 => true, 31 => true], 31);
        self::assertSame([1, 17], array_keys($calendario['tramos']));
        self::assertSame([15, 14], array_column($calendario['tramos'], 'dias'));
        self::assertArrayNotHasKey(31, $calendario['cubiertos']);
        self::assertStringContainsString('Lactancia · 08:00–09:00', $calendario['alertas'][1]);
    }

    public function testPdfAdmiteUnRolSinCodigosDeTurnoConVacaciones(): void
    {
        $dias = array_map(static fn(int $dia): array => ['dia' => $dia, 'dia_semana' => ($dia - 1) % 7 + 1, 'es_fin_de_semana' => false], range(1, 31));
        $persona = [
            'trabajador' => 'Personal de prueba', 'dni' => '', 'cargo' => 'Cargo de prueba',
            'dias' => array_fill(1, 31, []), 'ausencias' => [1 => ['dias' => 30, 'detalle' => 'Vacaciones · R-42']],
        ];
        $cabecera = [
            'est_nombre' => 'Establecimiento de prueba', 'dir_nombre' => '', 'red_nombre' => '', 'mic_nombre' => '',
            'est_ipress' => '', 'est_categoria' => '', 'servicio_area' => 'Oficina de prueba', 'ambito' => 'Oficina',
        ];
        $documento = RolDocumentoData::preparar(
            ['dias' => $dias, 'dias_mes' => 31, 'matriz' => [$persona]], $cabecera, ['anio' => 2026, 'mes' => 10]
        ) + ['codigo' => 'ROL-PRUEBA', 'generado_at' => '2026-10-01'];
        $html = view('Modules\Asistencia\Views\roles\tabla', ['documento' => $documento]);
        self::assertStringContainsString('colspan="30">Vacaciones · R-42</td>', $html);
        self::assertStringNotContainsString('colspan="0"', $html);
        self::assertStringStartsWith('%PDF-', (new RolPdfRenderer())->generar($documento));
    }
}

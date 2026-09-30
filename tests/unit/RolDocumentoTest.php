<?php

use CodeIgniter\Test\CIUnitTestCase;
use Modules\Asistencia\Services\RolDocumentoData;
use Modules\Asistencia\Services\RolDocumentoService;
use Modules\Asistencia\Services\ProgramacionTurnoService;
use Modules\Asistencia\Services\RolPdfRenderer;
use Tests\Support\Fixtures\RolDocumentoFixture;

final class RolDocumentoTest extends CIUnitTestCase
{
    public function testCalendarioTieneDiasRealesYConservaDocumentoConCeros(): void
    {
        foreach ([[2026, 2, 28], [2024, 2, 29], [2026, 4, 30], [2026, 5, 31]] as [$anio, $mes, $esperados]) {
            $f = RolDocumentoData::filtros(['anio' => $anio, 'mes' => $mes, 'est_ide' => 1]);
            $doc = RolDocumentoData::preparar(RolDocumentoFixture::matriz($anio, $mes, 1), [], $f);
            self::assertCount($esperados, $doc['dias']);
            self::assertSame('00000001', $doc['personal'][0]['dni']);
        }
        $doc = RolDocumentoFixture::documento(1);
        self::assertSame(5, $doc['dias'][0]['dia_semana']); // 1 de mayo de 2026: viernes.
    }

    public function testVariosTurnosPorDiaNoDivideCodigosCompuestosNiConfundeConteosConHoras(): void
    {
        $matriz = RolDocumentoFixture::matriz(2026, 5, 1);
        $turno = ['tur_codigo' => 'MT', 'tur_nombre' => 'Doble', 'tur_color' => '#abc', 'th_hora_ingreso' => '07:00', 'th_hora_salida' => '19:00', 'duracion_horas' => 12];
        $matriz['matriz'][0]['dias'] = [1 => [$turno, array_replace($turno, ['tur_codigo' => 'GN', 'duracion_horas' => 12])], 2 => [array_replace($turno, ['tur_codigo' => 'M', 'duracion_horas' => 6.5])]];
        $doc = RolDocumentoData::preparar($matriz, [], ['anio' => 2026, 'mes' => 5]);
        self::assertSame(['MT' => 1, 'GN' => 1, 'M' => 1], $doc['personal'][0]['conteos']);
        self::assertSame(30.5, $doc['total_horas']);
        self::assertContains('MT', $doc['codigos']);
        self::assertSame('#aabbcc', $doc['personal'][0]['dias'][1][0]['tur_color']);
    }

    public function testFiltroJerarquicoNoPierdeSubareasNiEntraEnCiclos(): void
    {
        $oficinas = [
            ['ofi_ide' => 10, 'ofi_padre_ide' => null, 'ofi_tofi_ide' => 3],
            ['ofi_ide' => 11, 'ofi_padre_ide' => 10, 'ofi_tofi_ide' => 6],
            ['ofi_ide' => 12, 'ofi_padre_ide' => 11, 'ofi_tofi_ide' => 5],
            ['ofi_ide' => 13, 'ofi_padre_ide' => null, 'ofi_tofi_ide' => 2],
        ];
        self::assertSame([10, 11, 12], RolDocumentoData::oficinasIncluidas($oficinas, 10, 3, true));
        self::assertSame([10], RolDocumentoData::oficinasIncluidas($oficinas, 10, 3, false));
        self::assertSame([10, 11, 12], RolDocumentoData::oficinasIncluidas($oficinas, null, 3, true));
        self::assertSame([], RolDocumentoData::oficinasIncluidas($oficinas, null, 99, true));
        $oficinas[0]['ofi_padre_ide'] = 12;
        self::assertSame([10, 11, 12], RolDocumentoData::oficinasIncluidas($oficinas, 10, null, true));
        $this->expectException(InvalidArgumentException::class);
        RolDocumentoData::oficinasIncluidas($oficinas, 13, 3, true);
    }

    public function testRechazaFiltrosInvalidosYColoresNoSeguros(): void
    {
        self::assertSame('#FFFFFF', RolDocumentoData::color('red; background:url(http://example.com)'));
        self::assertSame('#FFFFFF', RolDocumentoData::tinta('#000000'));
        self::assertSame('#111827', RolDocumentoData::tinta('#FFFFFF'));
        foreach ([['mes' => 13], ['anio' => 1999], ['est_ide' => -1], ['ofi_ide' => '1abc'], ['ups_ide' => ['1']]] as $cambio) {
            try {
                RolDocumentoData::filtros(array_replace(['anio' => 2026, 'mes' => 5, 'est_ide' => 1], $cambio));
                self::fail('Aceptó filtros inválidos.');
            } catch (InvalidArgumentException $e) {
                self::assertNotEmpty($e->getMessage());
            }
        }
    }

    public function testPdfRealYVistaEscapanContenidoYConservanColores(): void
    {
        $doc = RolDocumentoFixture::documento();
        $doc['personal'][0]['trabajador'] = '<script>alert(1)</script>';
        $html = view('Modules\Asistencia\Views\roles\pdf', ['documento' => $doc]);
        self::assertStringNotContainsString('<script>', $html);
        self::assertStringContainsString('background-color:#24466B', $html);
        self::assertStringContainsString('00000001', $html);
        $pdf = (new RolPdfRenderer())->generar($doc);
        self::assertStringStartsWith('%PDF-', $pdf);
        self::assertGreaterThan(10000, strlen($pdf));
    }

    public function testPersistenciaHistorialAnulacionEIntegridad(): void
    {
        $db = \Config\Database::connect(['DBDriver' => 'SQLite3', 'database' => ':memory:', 'DBPrefix' => '', 'DBDebug' => true], false);
        require_once ROOTPATH . 'Modules/Asistencia/Database/Migrations/2026-09-30-120000_CreateRolDocumentos.php';
        $migration = new \Modules\Asistencia\Database\Migrations\CreateRolDocumentos(\Config\Database::forge($db));
        $migration->up();
        $dir = WRITEPATH . 'testing/roles-' . bin2hex(random_bytes(4)) . '/';
        $renderer = $this->createMock(RolPdfRenderer::class);
        $renderer->method('generar')->willReturn('%PDF-1.7 test');
        $service = $this->getMockBuilder(RolDocumentoService::class)->setConstructorArgs([$db, $this->createMock(ProgramacionTurnoService::class), $renderer, $dir])->onlyMethods(['consultar'])->getMock();
        $doc = RolDocumentoFixture::documento(1);
        $service->method('consultar')->willReturn($doc);
        $input = $doc['filtros'] + ['huella' => RolDocumentoData::huella($doc)];
        try {
            $result = $service->generar($input, 7);
            $original = $service->archivo($result['id']);
            self::assertFileExists($original['ruta']);
            $row = $db->table('casis_rol_documento')->get()->getRowArray();
            self::assertSame('00000001', json_decode($row['snapshot_json'], true)['personal'][0]['dni']);
            self::assertSame(1, $service->historial(['anio' => '2026', 'estado' => 'GENERADO'])['total']);
            self::assertSame(0, $service->historial(['est_ide' => '2'])['total']);
            $service->anular($result['id'], 'Corrección de programación', 7);
            self::assertSame('ANULADO', $service->archivo($result['id'])['estado']);
            self::assertSame('%PDF-1.7 test', file_get_contents($original['ruta']));
            try { $service->anular($result['id'], 'Otra anulación', 7); self::fail('Permitió anular dos veces.'); } catch (DomainException $e) { self::assertNotEmpty($e->getMessage()); }
            file_put_contents($original['ruta'], 'archivo alterado');
            try { $service->archivo($result['id']); self::fail('No verificó integridad.'); } catch (RuntimeException $e) { self::assertStringContainsString('integridad', $e->getMessage()); }
            // Un fallo de inserción no debe dejar un archivo huérfano.
            $antes = glob($dir . '*.pdf');
            $db->query('DROP TABLE casis_rol_documento');
            try { $service->generar($input, 7); self::fail('Aceptó inserción fallida.'); } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) { self::assertSame($antes, glob($dir . '*.pdf')); }
        } finally {
            foreach (glob($dir . '*.pdf') ?: [] as $path) unlink($path);
            if (is_dir($dir)) rmdir($dir);
            $db->close();
        }
    }

    public function testImpideGuardarSiCambioLaProgramacionDesdeLaConsulta(): void
    {
        $doc = RolDocumentoFixture::documento(1);
        $renderer = $this->createMock(RolPdfRenderer::class);
        $renderer->expects(self::never())->method('generar');
        $service = $this->getMockBuilder(RolDocumentoService::class)->disableOriginalConstructor()->onlyMethods(['consultar'])->getMock();
        $service->method('consultar')->willReturn($doc);
        $this->expectException(DomainException::class);
        $service->generar(['huella' => str_repeat('0', 64)], 7);
    }

    public function testConsultaFiltraDepartamentoConDependenciasYValidaUpSs(): void
    {
        $db = \Config\Database::connect(['DBDriver' => 'SQLite3', 'database' => ':memory:', 'DBPrefix' => '', 'DBDebug' => true], false);
        $db->query('CREATE TABLE casis_establecimiento (est_ide INTEGER, est_nombre TEXT, est_ipress TEXT, est_categoria TEXT, est_mic_ide INTEGER, deleted_at TEXT)');
        $db->query('CREATE TABLE casis_microred (mic_ide INTEGER, mic_nombre TEXT, mic_red_ide INTEGER)');
        $db->query('CREATE TABLE casis_red (red_ide INTEGER, red_nombre TEXT, red_dir_ide INTEGER)');
        $db->query('CREATE TABLE casis_diresa (dir_ide INTEGER, dir_nombre TEXT)');
        $db->query('CREATE TABLE casis_personal (perl_ide INTEGER, perl_est_ide INTEGER, perl_ofi_ide INTEGER, deleted_at TEXT)');
        $db->table('casis_establecimiento')->insert(['est_ide' => 1, 'est_nombre' => 'Hospital', 'est_ipress' => '00003299', 'est_categoria' => 'II-2', 'est_mic_ide' => 1]);
        $db->table('casis_microred')->insert(['mic_ide' => 1, 'mic_nombre' => 'Microred', 'mic_red_ide' => 1]);
        $db->table('casis_red')->insert(['red_ide' => 1, 'red_nombre' => 'Red', 'red_dir_ide' => 1]);
        $db->table('casis_diresa')->insert(['dir_ide' => 1, 'dir_nombre' => 'DIRESA']);
        $db->table('casis_personal')->insertBatch([
            ['perl_ide' => 1, 'perl_est_ide' => 1, 'perl_ofi_ide' => 10],
            ['perl_ide' => 2, 'perl_est_ide' => 1, 'perl_ofi_ide' => 11],
            ['perl_ide' => 3, 'perl_est_ide' => 1, 'perl_ofi_ide' => 12],
            ['perl_ide' => 4, 'perl_est_ide' => 2, 'perl_ofi_ide' => 20],
        ]);
        $programacion = $this->createMock(ProgramacionTurnoService::class);
        $programacion->expects(self::exactly(2))->method('obtenerMatrizMensual')->with(2026, 5, 1, 5, 6)->willReturn(RolDocumentoFixture::matriz(2026, 5, 4));
        $service = $this->getMockBuilder(RolDocumentoService::class)->setConstructorArgs([$db, $programacion])->onlyMethods(['catalogos'])->getMock();
        $service->method('catalogos')->willReturn([
            'oficinas' => [
                ['ofi_ide' => 10, 'ofi_est_ide' => 1, 'ofi_nombre' => 'Enfermería', 'ofi_tofi_ide' => 3, 'ofi_padre_ide' => null],
                ['ofi_ide' => 11, 'ofi_est_ide' => 1, 'ofi_nombre' => 'Área', 'ofi_tofi_ide' => 5, 'ofi_padre_ide' => 10],
                ['ofi_ide' => 12, 'ofi_est_ide' => 1, 'ofi_nombre' => 'Otra área', 'ofi_tofi_ide' => 5, 'ofi_padre_ide' => null],
                ['ofi_ide' => 20, 'ofi_est_ide' => 2, 'ofi_nombre' => 'Otro establecimiento', 'ofi_tofi_ide' => 3, 'ofi_padre_ide' => null],
            ],
            'tipos' => [['tofi_ide' => 3, 'tofi_nombre' => 'DEPARTAMENTO'], ['tofi_ide' => 5, 'tofi_nombre' => 'AREA']],
            'upss' => [['est_ide' => 1, 'ups_ide' => 5, 'ups_nombre' => 'EMERGENCIA']],
            'servicios' => [['est_ide' => 1, 'ups_ide' => 5, 'uss_ide' => 6, 'uss_nombre' => 'Tópico']],
        ]);
        $f = ['anio' => 2026, 'mes' => 5, 'est_ide' => 1, 'ofi_ide' => 10, 'tofi_ide' => 3, 'ups_ide' => 5, 'uss_ide' => 6, 'incluir_hijos' => '1'];
        try {
            $doc = $service->consultar($f);
            self::assertSame([1, 2], array_column($doc['personal'], 'perl_ide'));
            self::assertSame('Enfermería', $doc['cabecera']['departamento']);
            self::assertSame('Microred', $doc['cabecera']['mic_nombre']);
            self::assertSame('Tópico', $doc['cabecera']['servicio_area']);
            self::assertSame([1], array_column($service->consultar(array_replace($f, ['incluir_hijos' => '0']))['personal'], 'perl_ide'));
            foreach ([['ofi_ide' => 20], ['ups_ide' => 99], ['uss_ide' => 99], ['tofi_ide' => 99]] as $cambio) {
                try { $service->consultar(array_replace($f, $cambio)); self::fail('Aceptó filtro fuera del ámbito.'); } catch (InvalidArgumentException $e) { self::assertNotEmpty($e->getMessage()); }
            }
        } finally { $db->close(); }
    }

    public function testPaginacionMantieneTodasLasFilasYNumeracionConTurnosApilados(): void
    {
        $doc = RolDocumentoFixture::documento(29);
        $turno = $doc['personal'][0]['dias'][1][0];
        $doc['personal'][0]['dias'][1] = [$turno, $turno, $turno];
        $paginas = RolDocumentoData::paginas($doc);
        $offset = 0;
        foreach ($paginas as $pagina) {
            self::assertSame($offset, $pagina['offset']);
            $offset += count($pagina['filas']);
        }
        self::assertSame(29, $offset);
        self::assertSame(range(1, 29), array_column(array_merge(...array_column($paginas, 'filas')), 'perl_ide'));
    }
}

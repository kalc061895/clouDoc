<?php

use CodeIgniter\Test\CIUnitTestCase;
use Modules\Asistencia\Services\ConfiguracionRolService as C;

final class ConfiguracionRolTest extends CIUnitTestCase
{
    protected $db;
    private C $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->db = db_connect(['DBDriver' => 'SQLite3', 'database' => ':memory:', 'DBPrefix' => '', 'DBDebug' => true], false);
        foreach ([
            'casis_plan_plantilla' => 'id INTEGER PRIMARY KEY AUTOINCREMENT, nombre TEXT, descripcion TEXT, reglas TEXT, created_at TEXT, created_by INTEGER, updated_at TEXT, deleted_at TEXT',
            'casis_plan_periodo' => 'id INTEGER PRIMARY KEY AUTOINCREMENT, est_id INTEGER, mes TEXT, apertura TEXT, aprobacion TEXT, cierre TEXT, reglas TEXT, created_at TEXT, created_by INTEGER, UNIQUE(est_id, mes)',
            'casis_plan_rol' => 'id INTEGER PRIMARY KEY, periodo_id INTEGER',
            'casis_plan_evento' => 'id INTEGER PRIMARY KEY AUTOINCREMENT, rol_id INTEGER, accion TEXT, detalle TEXT, created_by INTEGER, created_at TEXT',
            'casis_establecimiento' => 'est_ide INTEGER PRIMARY KEY, deleted_at TEXT',
            'casis_personal' => 'perl_ide INTEGER PRIMARY KEY, perl_est_ide INTEGER',
            'casis_programacion' => 'prog_perl_ide INTEGER, prog_fecha TEXT, deleted_at TEXT',
            'casis_oficina' => 'ofi_ide INTEGER PRIMARY KEY, ofi_est_ide INTEGER, ofi_padre_ide INTEGER, ofi_estado INTEGER, ofi_responsable_id INTEGER, deleted_at TEXT',
            'casis_plan_responsable' => 'usuario_id INTEGER, oficina_id INTEGER, desde TEXT, hasta TEXT, aprobar INTEGER',
        ] as $table => $fields) $this->db->query("CREATE TABLE {$table} ({$fields})");
        $this->db->table('casis_establecimiento')->insert(['est_ide' => 1]);
        $this->service = $this->getMockBuilder(C::class)->disableOriginalConstructor()->onlyMethods(['usuario', 'administrador', 'ahora'])->getMock();
        $this->service->method('usuario')->willReturn(7);
        $this->service->method('administrador')->willReturn(true);
        $this->service->method('ahora')->willReturn('2026-10-21 12:00:00');
        (new ReflectionProperty($this->service, 'db'))->setValue($this->service, $this->db);
    }

    protected function tearDown(): void
    {
        $this->db->close();
        parent::tearDown();
    }

    private function periodo(): array
    {
        return ['est_id' => 1, 'mes' => '2026-11', 'apertura' => '2026-10-25T08:00', 'aprobacion' => '2026-10-28T08:00', 'cierre' => '2026-10-31T18:00', 'reglas' => '[{"nombre":"Horas","tipo":"MAX_HORAS_MES","limite":150}]'];
    }

    public function testPlantillaSeEditaYEliminaSinAlterarPeriodo(): void
    {
        $original = $this->periodo();
        $this->service->guardarPlantilla(['nombre' => 'General', 'reglas' => $original['reglas']]);
        $plantilla = $this->db->table('casis_plan_plantilla')->get()->getRowArray();
        $original['reglas'] = $plantilla['reglas'];
        $this->service->guardarPeriodo($original);
        $this->service->guardarPlantilla(['nombre' => 'Nueva versión', 'reglas' => '[]'], 1);
        $this->service->eliminarPlantilla(1);
        self::assertSame($plantilla['reglas'], $this->db->table('casis_plan_periodo')->get()->getRowArray()['reglas']);
        self::assertNotNull($this->db->table('casis_plan_plantilla')->get()->getRowArray()['deleted_at']);
        self::assertSame(4, $this->db->table('casis_plan_evento')->countAllResults());
    }

    public function testPeriodoFuturoSePuedeEditarYEliminar(): void
    {
        $datos = $this->periodo();
        $this->service->guardarPeriodo($datos);
        $datos['cierre'] = '2026-11-01T18:00';
        $this->service->guardarPeriodo($datos, 1);
        self::assertSame('2026-11-01 18:00:00', $this->db->table('casis_plan_periodo')->get()->getRowArray()['cierre']);
        $this->service->eliminarPeriodo(1);
        self::assertSame(0, $this->db->table('casis_plan_periodo')->countAllResults());
    }

    public function testDuplicadoSeRechazaSinInsertarOtroPeriodo(): void
    {
        $this->service->guardarPeriodo($this->periodo());
        try { $this->service->guardarPeriodo($this->periodo()); self::fail('Debe rechazar duplicado.'); }
        catch (DomainException $e) { self::assertStringContainsString('Ya existe', $e->getMessage()); }
        self::assertSame(1, $this->db->table('casis_plan_periodo')->countAllResults());
    }

    public function testNoModificaNiEliminaPeriodoAbiertoOConRolesOTurnos(): void
    {
        $this->service->guardarPeriodo($this->periodo());
        $this->db->table('casis_plan_periodo')->where('id', 1)->update(['apertura' => '2026-10-20 08:00:00']);
        foreach (['guardar', 'eliminar'] as $accion) {
            try {
                if ($accion === 'guardar') $this->service->guardarPeriodo($this->periodo(), 1);
                else $this->service->eliminarPeriodo(1);
                self::fail('Debe bloquear el período abierto.');
            } catch (DomainException $e) { self::assertStringContainsString('antes de su apertura', $e->getMessage()); }
        }
        $this->db->table('casis_plan_periodo')->where('id', 1)->update(['apertura' => '2026-10-25 08:00:00']);
        $this->db->table('casis_plan_rol')->insert(['id' => 1, 'periodo_id' => 1]);
        try { $this->service->eliminarPeriodo(1); self::fail('Debe proteger roles.'); }
        catch (DomainException $e) { self::assertStringContainsString('roles asociados', $e->getMessage()); }
        $this->db->table('casis_plan_rol')->where('id', 1)->delete();
        $this->db->table('casis_personal')->insert(['perl_ide' => 1, 'perl_est_ide' => 1]);
        $this->db->table('casis_programacion')->insert(['prog_perl_ide' => 1, 'prog_fecha' => '2026-11-01']);
        try { $this->service->eliminarPeriodo(1); self::fail('Debe proteger turnos.'); }
        catch (DomainException $e) { self::assertStringContainsString('turnos programados', $e->getMessage()); }
        self::assertSame(1, $this->db->table('casis_plan_periodo')->countAllResults());
    }

    public function testJefeProgramaSuOficinaYDescendientesSinAprobar(): void
    {
        $g = $this->getMockBuilder(C::class)->disableOriginalConstructor()->onlyMethods(['usuario', 'administrador', 'ahora'])->getMock();
        $g->method('usuario')->willReturn(7);
        $g->method('administrador')->willReturn(false);
        $g->method('ahora')->willReturn('2026-10-21 12:00:00');
        (new ReflectionProperty($g, 'db'))->setValue($g, $this->db);
        foreach ([[1, null, 7, 1], [2, 1, null, 1], [3, null, 8, 1], [4, null, 7, 0]] as [$id, $padre, $jefe, $estado]) {
            $this->db->table('casis_oficina')->insert(['ofi_ide' => $id, 'ofi_padre_ide' => $padre, 'ofi_responsable_id' => $jefe, 'ofi_estado' => $estado]);
        }
        self::assertSame([1, 2], $g->oficinas());
        self::assertSame([], $g->oficinas(true));
        try { $g->guardarPlantilla(['nombre' => 'No autorizado', 'reglas' => '[]']); self::fail('Debe exigir administrador.'); }
        catch (DomainException $e) { self::assertStringContainsString('administrador', $e->getMessage()); }
    }

    public function testValidacionRechazaReglasYFechasInvalidas(): void
    {
        foreach (['{}', 'null', 'invalid', '[{"nombre":"X","tipo":"OTRO"}]', '[{"nombre":"X","tipo":"MAX_TURNOS","limite":1.5,"turnos":["M"]}]', '[{"nombre":"X","tipo":"TURNOS_PERMITIDOS","turnos":[]}]', '[{"nombre":"X","tipo":"MAX_CONTINUAS","limite":-1}]'] as $json) {
            try { C::normalizarReglas($json); self::fail('Debe rechazar regla.'); }
            catch (DomainException $e) { self::assertNotEmpty($e->getMessage()); }
        }
        foreach (['2026-02-30T08:00', '2026-11-01T08:00'] as $fecha) {
            $datos = $this->periodo(); $datos['apertura'] = $fecha;
            try { C::datosPeriodo($datos); self::fail('Debe rechazar fecha u orden.'); }
            catch (DomainException $e) { self::assertNotEmpty($e->getMessage()); }
        }
        self::assertSame([], C::normalizarReglas('[]'));
    }

    public function testFormulariosRenderizanCatalogosYEscapanNombres(): void
    {
        helper(['form', 'url']);
        foreach (['periodos', 'plantillas'] as $tipo) {
            $renderer = new class(config('View'), APPPATH . 'Views', null, false) extends \CodeIgniter\View\View {
                public function extend(string $layout) { return ''; }
            };
            $datos = [
                'tipo' => $tipo, 'editar' => null, 'admin' => true,
                'periodos' => [], 'plantillas' => [],
                'establecimientos' => [['est_ide' => 1, 'est_nombre' => 'Hospital']],
                'oficinas' => [['ofi_ide' => 1, 'ofi_nombre' => '<script>alert(1)</script>']],
                'regimenes' => [['mco_ide' => 1, 'mco_nombre' => 'Régimen']],
                'turnos' => [['tur_codigo' => 'M', 'tur_nombre' => 'Mañana']],
            ];
            $renderer->setData($datos)->render('Modules\Asistencia\Views\gestion_roles\configuracion');
            ob_start();
            $renderer->renderSection('content');
            $html = ob_get_clean();
            self::assertStringContainsString('Guardar', $html);
            self::assertStringContainsString('Mañana', $html);
            self::assertStringContainsString('&lt;script&gt;', $html);
            self::assertStringNotContainsString('<script>alert(1)</script>', $html);
        }
    }
}

<?php

use CodeIgniter\Test\CIUnitTestCase;
use Modules\Asistencia\Services\RemuneracionService;

final class RemuneracionTest extends CIUnitTestCase
{
    protected $db;
    private RemuneracionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->db = db_connect(['DBDriver' => 'SQLite3', 'database' => ':memory:', 'DBPrefix' => '', 'DBDebug' => true], false);
        $this->db->query('CREATE TABLE casis_cargo (car_ide INTEGER PRIMARY KEY, car_nombre TEXT, deleted_at TEXT)');
        $this->db->query('CREATE TABLE casis_remuneraciones (rem_ide INTEGER PRIMARY KEY AUTOINCREMENT, rem_car_ide INTEGER, rem_nivel TEXT, rem_configuracion TEXT, rem_estado INTEGER, created_by INTEGER, updated_by INTEGER, created_at TEXT, updated_at TEXT, UNIQUE(rem_car_ide, rem_nivel))');
        $this->db->table('casis_cargo')->insert(['car_ide' => 1, 'car_nombre' => 'Médico']);
        $this->service = new RemuneracionService($this->db);
    }

    protected function tearDown(): void
    {
        $this->db->close();
        parent::tearDown();
    }

    private function datos(): array
    {
        $datos = ['rem_car_ide' => '1', 'rem_nivel' => ' II ', 'rem_estado' => 1];
        foreach (RemuneracionService::CAMPOS as $ruta => $label) $datos[str_replace('.', '_', $ruta)] = '';
        $datos['remuneracion'] = '3500.50';
        $datos['guardias_diurna'] = '0';
        return $datos;
    }

    public function testCrearEditarListarEliminarConJsonYAuditoria(): void
    {
        $datos = $this->datos();
        $this->service->guardar($datos, 7);
        $row = $this->service->listar()[0];
        self::assertSame('II', $row['rem_nivel']);
        self::assertSame('3500.50', $row['rem_configuracion']['remuneracion']);
        self::assertSame('0.00', $row['rem_configuracion']['guardias']['diurna']);
        self::assertNull($row['rem_configuracion']['guardias']['nocturna']);
        self::assertEquals(7, $row['created_by']);
        $config = $row['rem_configuracion'];
        $config['adicional'] = ['concepto' => '123.45'];
        $this->db->table('casis_remuneraciones')->where('rem_ide', $row['rem_ide'])->update(['rem_configuracion' => json_encode($config)]);
        $datos['remuneracion'] = '4000';
        $this->service->guardar($datos, 8, (int)$row['rem_ide']);
        $editado = $this->service->obtener((int)$row['rem_ide']);
        self::assertSame('4000.00', $editado['rem_configuracion']['remuneracion']);
        self::assertSame('123.45', $editado['rem_configuracion']['adicional']['concepto']);
        self::assertEquals(8, $editado['updated_by']);
        $this->service->eliminar((int)$row['rem_ide']);
        self::assertSame([], $this->service->listar());
    }

    public function testDuplicadoInactivoSeRechaza(): void
    {
        $datos = $this->datos();
        $datos['rem_estado'] = 0;
        $this->service->guardar($datos, 7);
        $this->expectException(\DomainException::class);
        $this->expectExceptionCode(409);
        $this->service->guardar($this->datos(), 7);
    }

    public function testValoresInvalidosNoSePersisten(): void
    {
        foreach ([['remuneracion', ''], ['guardias_diurna', '-1'], ['guardias_diurna', '1.001'], ['descuentos_dias_divisor', '0'], ['descuentos_horas_jornada', '0.00'], ['rem_nivel', '  '], ['rem_car_ide', '999'], ['rem_estado', 2]] as [$key, $value]) {
            try {
                $this->service->guardar(array_replace($this->datos(), [$key => $value]), 7);
                self::fail('Se aceptó un valor inválido para ' . $key);
            } catch (\DomainException $e) {
                self::assertNotEmpty($e->getMessage());
            }
        }
        self::assertSame([], $this->service->listar());
    }

    public function testActualizarInexistenteDevuelve404(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionCode(404);
        $this->service->guardar($this->datos(), 7, 999);
    }
}

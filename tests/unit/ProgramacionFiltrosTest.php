<?php

use CodeIgniter\Test\CIUnitTestCase;
use Modules\Asistencia\Services\ProgramacionTurnoService;

final class ProgramacionFiltrosTest extends CIUnitTestCase
{
    public function testReporteConservaFiltrosCabecerasDiasYDocumento(): void
    {
        $service = $this->getMockBuilder(ProgramacionTurnoService::class)->disableOriginalConstructor()
            ->onlyMethods(['obtenerMatrizMensual', 'nombresFiltrosReporte'])->getMock();
        $service->expects(self::once())->method('obtenerMatrizMensual')->with(2024, 2, 1, 2, 3, null, 4, '00123456')->willReturn([
            'dias_mes' => 29, 'dias' => array_map(static fn($dia) => ['dia' => $dia], range(1, 29)),
            'matriz' => [['dni' => '00123456', 'trabajador' => 'Trabajador de prueba', 'cargo' => 'Enfermería', 'dias' => [], 'total_horas' => 0]],
        ]);
        $service->method('nombresFiltrosReporte')->with(1, 2, 3, 4, '00123456')->willReturn([
            'Establecimiento' => 'Hospital', 'Oficina' => 'Oficina A', 'UPSS' => 'UPSS B', 'Servicio UPSS' => 'Servicio C', 'DNI / documento' => '00123456',
        ]);
        $sheet = $service->exportarExcelReporteLegible(2024, 2, 1, 2, 3, 4, '00123456')->getActiveSheet();
        self::assertSame('Establecimiento: Hospital', $sheet->getCell('A3')->getValue());
        self::assertSame('Oficina: Oficina A', $sheet->getCell('A4')->getValue());
        self::assertSame('UPSS: UPSS B', $sheet->getCell('A5')->getValue());
        self::assertSame('Servicio UPSS: Servicio C', $sheet->getCell('A6')->getValue());
        self::assertSame('DNI / documento: 00123456', $sheet->getCell('A7')->getValue());
        self::assertSame('DNI', $sheet->getCell('B9')->getValue());
        self::assertSame('00123456', $sheet->getCell('B10')->getValue());
        self::assertSame(29, $sheet->getCell('AG9')->getValue());
        self::assertSame('TOTAL HORAS', $sheet->getCell('AH9')->getValue());
        self::assertSame('A1:AH10', $sheet->getPageSetup()->getPrintArea());
        self::assertSame('E10', $sheet->getFreezePane());
    }

    public function testUpSsServicioOficinaYDniSeAplicanSinCrearRelaciones(): void
    {
        $db = new class {
            public array $consultas = [];
            public function table($tabla) {
                $builder = new class($tabla) {
                    public array $condiciones = [];
                    public function __construct(private string $tabla) {}
                    public function select($select) { return $this; }
                    public function join(...$args) { return $this; }
                    public function where(...$args) { $this->condiciones[] = $args; return $this; }
                    public function whereIn(...$args) { $this->condiciones[] = $args; return $this; }
                    public function orderBy(...$args) { return $this; }
                    public function get() { return $this; }
                    public function getResultArray() {
                        return $this->tabla === 'casis_personal p' ? [['perl_ide' => 1]] : [];
                    }
                };
                $this->consultas[$tabla] = $builder;
                return $builder;
            }
        };
        $service = $this->getMockBuilder(ProgramacionTurnoService::class)->disableOriginalConstructor()->onlyMethods(['obtenerCatalogoTurnos'])->getMock();
        $service->method('obtenerCatalogoTurnos')->willReturn([]);
        (new ReflectionProperty($service, 'db'))->setValue($service, $db);
        $resultado = $service->obtenerMatrizMensual(2026, 9, null, 2, 3, null, 4, '00123456');
        self::assertContains(['p.perl_ofi_ide', 4], $db->consultas['casis_personal p']->condiciones);
        self::assertContains(['pe.per_numero_documento', '00123456'], $db->consultas['casis_personal p']->condiciones);
        self::assertContains(['eup.eup_ups_ide', 2], $db->consultas['casis_programacion pr']->condiciones);
        self::assertContains(['eus.eus_uss_ide', 3], $db->consultas['casis_programacion pr']->condiciones);
        self::assertSame([], $resultado['matriz']);
    }
}

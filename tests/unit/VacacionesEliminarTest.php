<?php

use CodeIgniter\Test\CIUnitTestCase;
use Modules\Asistencia\Services\RegistroVacacionService;

final class VacacionesEliminarTest extends CIUnitTestCase
{
    public function testEliminaUnaSolaVezYRecalculaSaldo(): void
    {
        $db = new VacacionesEliminarDb();
        $service = (new ReflectionClass(RegistroVacacionService::class))->newInstanceWithoutConstructor();
        (new ReflectionProperty($service, 'db'))->setValue($service, $db);
        $service->eliminarUso(1, 10, 'Solicitud cancelada', 5);
        self::assertSame(0, $db->uso['rv_estado']);
        self::assertSame(5, $db->uso['deleted_by']);
        self::assertStringContainsString('Solicitud cancelada', $db->uso['rv_observacion']);
        self::assertSame(7, $db->periodo['vac_dias_gozados']);
        self::assertSame(23, $db->periodo['vac_dias_pendientes']);
        $this->expectException(DomainException::class);
        $service->eliminarUso(1, 10, 'Duplicado', 5);
    }

    public function testFalloDeSaldoRevierteLaEliminacion(): void
    {
        $db = new VacacionesEliminarDb();
        $db->fallarSaldo = true;
        $service = (new ReflectionClass(RegistroVacacionService::class))->newInstanceWithoutConstructor();
        (new ReflectionProperty($service, 'db'))->setValue($service, $db);
        try {
            $service->eliminarUso(1, 10, 'Solicitud cancelada', 5);
            self::fail('Aceptó un error al actualizar el saldo');
        } catch (RuntimeException $e) {
            self::assertSame(1, $db->uso['rv_estado']);
            self::assertArrayNotHasKey('deleted_at', $db->uso);
            self::assertSame(18, $db->periodo['vac_dias_pendientes']);
        }
    }
}

final class VacacionesEliminarDb
{
    public array $uso = ['rv_ide' => 10, 'rv_vac_ide' => 2, 'rv_estado' => 1, 'rv_observacion' => 'Original'];
    public array $periodo = ['vac_dias_ganados' => 30, 'vac_dias_gozados' => 12, 'vac_dias_pendientes' => 18];
    public bool $fallarSaldo = false;
    private array $snapshot;
    private string $consulta = '';
    private string $tabla = '';
    public function transException($v) { return $this; }
    public function transBegin() { $this->snapshot = [$this->uso, $this->periodo]; }
    public function transRollback() { [$this->uso, $this->periodo] = $this->snapshot; }
    public function transStatus() { return true; }
    public function transCommit() { return true; }
    public function query($sql, $params) { $this->consulta = $sql; return $this; }
    public function getRowArray() {
        if ($this->consulta === 'suma') return ['dias' => 7];
        return str_contains($this->consulta, 'casis_registro_vacacion') ? $this->uso : $this->periodo;
    }
    public function table($tabla) { $this->tabla = $tabla; return $this; }
    public function where(...$args) { return $this; }
    public function selectSum(...$args) { $this->consulta = 'suma'; return $this; }
    public function get() { return $this; }
    public function update($datos) {
        if ($this->tabla === 'casis_vacacion') {
            if ($this->fallarSaldo) return false;
            $this->periodo = array_replace($this->periodo, $datos);
        } else $this->uso = array_replace($this->uso, $datos);
        return true;
    }
}

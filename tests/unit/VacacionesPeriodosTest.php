<?php

use CodeIgniter\Test\CIUnitTestCase;
use Modules\Asistencia\Services\RegistroVacacionService;

final class VacacionesPeriodosTest extends CIUnitTestCase
{
    public function testNoGanaDiasAntesDeDoceMeses(): void
    {
        $p = RegistroVacacionService::periodosAnuales('2025-10-15', '2026-10-14');
        self::assertCount(1, $p);
        self::assertFalse($p[0]['cumplido']);
        self::assertSame('2026-10-15', $p[0]['disponible_desde']);
        self::assertSame('2026-10-14', $p[0]['fin']);
    }

    public function testAniversarioHabilitaPeriodoAnteriorYComienzaElSiguiente(): void
    {
        $p = RegistroVacacionService::periodosAnuales('2025-10-15', '2026-10-15');
        self::assertCount(2, $p);
        self::assertTrue($p[0]['cumplido']);
        self::assertFalse($p[1]['cumplido']);
        self::assertSame('2026-10-15', $p[1]['inicio']);
    }

    public function testNoSeReiniciaEnEneroNiSigueAcumulandoTrasCese(): void
    {
        $p = RegistroVacacionService::periodosAnuales('2023-08-20', '2026-09-29', '2025-08-19');
        self::assertCount(2, $p);
        self::assertTrue($p[0]['cumplido']);
        self::assertFalse($p[1]['cumplido']);
        self::assertSame('2024-08-20', $p[1]['inicio']);
    }

    public function testIngresoBisiestoNoDesplazaAniversarioDelSiguienteBisiesto(): void
    {
        $p = RegistroVacacionService::periodosAnuales('2024-02-29', '2028-02-29');
        self::assertSame('2025-03-01', $p[0]['disponible_desde']);
        self::assertSame('2028-02-29', $p[3]['disponible_desde']);
        self::assertTrue($p[3]['cumplido']);
    }

    public function testIngresoFuturoNoGeneraSaldo(): void
    {
        self::assertSame([], RegistroVacacionService::periodosAnuales('2027-01-01', '2026-09-29'));
    }

    public function testRechazaFechaDeIngresoInvalida(): void
    {
        $this->expectException(DomainException::class);
        RegistroVacacionService::periodosAnuales('2025-02-30', '2026-09-29');
    }

    public function testUsoCuentaDiasCalendarioInclusivos(): void
    {
        $p = ['habilitado' => true, 'disponible_desde' => '2026-01-15', 'saldo' => 30];
        self::assertSame(1, RegistroVacacionService::validarUso($p, '2026-02-01', '2026-02-01'));
        self::assertSame(5, RegistroVacacionService::validarUso($p, '2026-01-30', '2026-02-03'));
    }

    public function testRechazaUsoSinSaldoAntesDelAniversarioOConFechasInvertidas(): void
    {
        foreach ([['2026-01-01', '2026-01-03'], ['2026-02-01', '2026-02-10'], ['2026-02-02', '2026-02-01']] as [$inicio, $fin]) {
            try {
                RegistroVacacionService::validarUso(['habilitado' => true, 'disponible_desde' => '2026-01-15', 'saldo' => 5], $inicio, $fin);
                self::fail('Aceptó un uso inválido');
            } catch (DomainException $e) {
                self::assertNotEmpty($e->getMessage());
            }
        }
    }
}

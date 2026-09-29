<?php

use CodeIgniter\Test\CIUnitTestCase;
use Modules\Asistencia\Models\AdjuntoModel;
use Modules\Asistencia\Models\RegistroCambioTurnoModel;
use Modules\Asistencia\Services\CambioTurnoService;
use Modules\Asistencia\Services\PeriodoService;
use Modules\Asistencia\Services\ProgramacionTurnoService;

final class CambioTurnoServiceTest extends CIUnitTestCase
{
    private function escenario(bool $cruce = false, bool $fallarHistorial = false, bool $cerrado = false): array
    {
        $db = new CambioTurnoMemoryDb();
        $periodos = $this->getMockBuilder(PeriodoService::class)->disableOriginalConstructor()->onlyMethods(['validarPermisoPeriodo'])->getMock();
        if ($cerrado) {
            $periodos->method('validarPermisoPeriodo')->willThrowException(new Exception('Periodo cerrado'));
        }
        $programacion = $this->getMockBuilder(ProgramacionTurnoService::class)->disableOriginalConstructor()->onlyMethods(['validarCruceHorarios'])->getMock();
        $programacion->method('validarCruceHorarios')->willReturnCallback(function ($personal, $fecha, $horario, $excluir) use ($db, $cruce) {
            // La validación debe observar el intercambio completo y excluir el turno entrante.
            self::assertSame($personal, $db->filas[$excluir]['prog_perl_ide']);
            self::assertSame($fecha, $db->filas[$excluir]['prog_fecha']);
            return ['cruce' => $cruce || $db->cruce, 'mensaje' => 'Cruce nocturno'];
        });
        $registro = $this->getMockBuilder(RegistroCambioTurnoModel::class)->disableOriginalConstructor()->onlyMethods(['insert'])->getMock();
        $registro->method('insert')->willReturnCallback(function ($datos) use ($db, $fallarHistorial) {
            $db->historial = $datos;
            return $fallarHistorial ? false : 99;
        });
        $adjuntos = $this->getMockBuilder(AdjuntoModel::class)->disableOriginalConstructor()->getMock();
        $service = new class($db, $periodos, $programacion, $registro, $adjuntos) extends CambioTurnoService {
            public array $personas = [
                1 => ['perl_ide' => 1, 'perl_est_ide' => 7, 'perl_car_ide' => 3],
                2 => ['perl_ide' => 2, 'perl_est_ide' => 7, 'perl_car_ide' => 3],
            ];
            public function personal(int $id): array { return $this->personas[$id]; }
        };
        $datos = ['personal_id' => 1, 'otro_personal_id' => 2, 'prog_sol_id' => 10, 'prog_ace_id' => 20,
            'version_sol' => CambioTurnoService::version($db->filas[10]),
            'version_ace' => CambioTurnoService::version($db->filas[20]), 'motivo' => 'Intercambio solicitado'];
        return [$service, $db, $datos];
    }

    public function testIntercambiaFechasDistintasYConservaHorarioServicioYAuditoria(): void
    {
        [$service, $db, $datos] = $this->escenario();
        $antes = $db->filas;
        self::assertSame(99, $service->registrar($datos, null, 5));
        self::assertTrue($db->committed);
        self::assertSame(2, $db->filas[10]['prog_perl_ide']);
        self::assertSame(1, $db->filas[20]['prog_perl_ide']);
        foreach ([10, 20] as $id) {
            foreach (['prog_fecha', 'prog_th_ide', 'prog_eup_ide', 'prog_observacion'] as $campo) {
                self::assertSame($antes[$id][$campo], $db->filas[$id][$campo]);
            }
            self::assertSame('CAMBIO TURNO', $db->filas[$id]['prog_estado']);
            self::assertSame(1, $db->filas[$id]['prog_es_cambio']);
        }
        $snapshot = json_decode($db->historial['rc_observacion'], true);
        self::assertSame($antes[10], $snapshot['programacion_original_sol']);
        self::assertSame($antes[20], $snapshot['programacion_original_ace']);
    }

    public function testAceptaIntercambioEnLaMismaFecha(): void
    {
        [$service, $db, $datos] = $this->escenario();
        $db->filas[20]['prog_fecha'] = $db->filas[10]['prog_fecha'];
        $datos['version_ace'] = CambioTurnoService::version($db->filas[20]);
        self::assertSame(99, $service->registrar($datos, null, 5));
    }

    public function testRechazaSolicitudDuplicada(): void
    {
        [$service, $db, $datos] = $this->escenario();
        $service->registrar($datos, null, 5);
        $despues = $db->filas;
        try {
            $service->registrar($datos, null, 5);
            self::fail('Aceptó una solicitud duplicada');
        } catch (DomainException $e) {
            self::assertSame($despues, $db->filas);
        }
    }

    public function testRechazaProgramacionModificadaMientrasElFormularioEstabaAbierto(): void
    {
        [$service, $db, $datos] = $this->escenario();
        $db->filas[20]['prog_th_ide'] = 99;
        $this->expectException(DomainException::class);
        $service->registrar($datos, null, 5);
    }

    public function testRevierteAmbosTurnosAnteCruce(): void
    {
        [$service, $db, $datos] = $this->escenario(true);
        $antes = $db->filas;
        try {
            $service->registrar($datos, null, 5);
            self::fail('Aceptó un cruce');
        } catch (DomainException $e) {
            self::assertSame($antes, $db->filas);
            self::assertFalse($db->committed);
            self::assertNull($db->historial);
        }
    }

    public function testRevierteProgramacionSiFallaHistorial(): void
    {
        [$service, $db, $datos] = $this->escenario(false, true);
        $antes = $db->filas;
        try {
            $service->registrar($datos, null, 5);
            self::fail('Aceptó un historial fallido');
        } catch (RuntimeException $e) {
            self::assertSame($antes, $db->filas);
            self::assertFalse($db->committed);
        }
    }

    public function testNoModificaPeriodosCerrados(): void
    {
        [$service, $db, $datos] = $this->escenario(false, false, true);
        $antes = $db->filas;
        try {
            $service->registrar($datos, null, 5);
            self::fail('Aceptó un periodo cerrado');
        } catch (DomainException $e) {
            self::assertSame($antes, $db->filas);
        }
    }

    public function testRechazaMismaPersonaInstitucionOCargoDistintos(): void
    {
        $a = ['perl_ide' => 1, 'perl_est_ide' => 7, 'perl_car_ide' => 3];
        foreach ([$a, ['perl_ide' => 2, 'perl_est_ide' => 8, 'perl_car_ide' => 3], ['perl_ide' => 2, 'perl_est_ide' => 7, 'perl_car_ide' => 4]] as $b) {
            try {
                CambioTurnoService::validarPersonal($a, $b);
                self::fail('Aceptó personas incompatibles');
            } catch (DomainException $e) {
                self::assertNotEmpty($e->getMessage());
            }
        }
    }

    public function testRechazaTurnoAnuladoOEliminado(): void
    {
        foreach (['prog_estado' => 'ANULADO', 'deleted_at' => '2026-09-29 10:00:00'] as $campo => $valor) {
            [$service, $db, $datos] = $this->escenario();
            $db->filas[20][$campo] = $valor;
            $datos['version_ace'] = CambioTurnoService::version($db->filas[20]);
            try {
                $service->registrar($datos, null, 5);
                self::fail('Aceptó un turno no disponible');
            } catch (DomainException $e) {
                self::assertSame(1, $db->filas[10]['prog_perl_ide']);
            }
        }
    }

    public function testRechazaSustentoNoPermitidoAntesDeModificarTurnos(): void
    {
        [$service, $db, $datos] = $this->escenario();
        $archivo = $this->getMockBuilder(\CodeIgniter\HTTP\Files\UploadedFile::class)->disableOriginalConstructor()->getMock();
        $archivo->method('getError')->willReturn(UPLOAD_ERR_OK);
        $archivo->method('isValid')->willReturn(true);
        $archivo->method('hasMoved')->willReturn(false);
        $archivo->method('getSize')->willReturn(100);
        $archivo->method('getMimeType')->willReturn('text/html');
        $archivo->expects(self::never())->method('move');
        $antes = $db->filas;
        try {
            $service->registrar($datos, $archivo, 5);
            self::fail('Aceptó HTML como sustento');
        } catch (DomainException $e) {
            self::assertSame($antes, $db->filas);
        }
    }

    public function testEliminarRestableceAmbosTurnosYConservaAuditoria(): void
    {
        [$service, $db, $datos] = $this->escenario();
        $originales = $db->filas;
        $service->registrar($datos, null, 5);
        $service->eliminar(99, 2, 'Se cancela la solicitud', 8);
        foreach ([10, 20] as $id) {
            foreach (['prog_perl_ide', 'prog_fecha', 'prog_th_ide', 'prog_estado', 'prog_observacion'] as $campo) {
                self::assertSame($originales[$id][$campo], $db->filas[$id][$campo]);
            }
            self::assertSame(0, $db->filas[$id]['prog_es_cambio']);
        }
        self::assertSame('REVERTIDO', $db->historial['rc_estado']);
        self::assertSame(8, $db->historial['deleted_by']);
        self::assertNotEmpty($db->historial['deleted_at']);
        self::assertSame('Se cancela la solicitud', json_decode($db->historial['rc_observacion'], true)['reversion']['motivo']);
    }

    public function testNoEliminaDosVecesNiDesdeOtroTrabajador(): void
    {
        [$service, $db, $datos] = $this->escenario();
        $service->registrar($datos, null, 5);
        try {
            $service->eliminar(99, 3, 'No corresponde', 8);
            self::fail('Aceptó a un trabajador ajeno');
        } catch (DomainException $e) {
            self::assertSame('APLICADO', $db->historial['rc_estado']);
        }
        $service->eliminar(99, 1, 'Anulación', 8);
        $this->expectException(DomainException::class);
        $service->eliminar(99, 1, 'Anulación duplicada', 8);
    }

    public function testNoSobrescribeModificacionesPosteriores(): void
    {
        [$service, $db, $datos] = $this->escenario();
        $service->registrar($datos, null, 5);
        $db->filas[10]['prog_th_ide'] = 77;
        $antes = $db->filas;
        try {
            $service->eliminar(99, 1, 'Anulación', 8);
            self::fail('Sobrescribió una modificación');
        } catch (DomainException $e) {
            self::assertSame($antes, $db->filas);
            self::assertSame('APLICADO', $db->historial['rc_estado']);
        }
    }

    public function testExigeDeshacerAntesElIntercambioPosterior(): void
    {
        [$service, $db, $datos] = $this->escenario();
        $service->registrar($datos, null, 5);
        $db->posteriores = [['rc_observacion' => json_encode(['programacion_original_sol' => ['prog_ide' => 10]])]];
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('cambio posterior');
        $service->eliminar(99, 1, 'Anulación', 8);
    }

    public function testRestauraEstadoDeCambioPrevioEnVezDeForzarProgramado(): void
    {
        [$service, $db, $datos] = $this->escenario();
        $db->filas[10]['prog_estado'] = 'CAMBIO TURNO';
        $db->filas[10]['prog_es_cambio'] = 1;
        $datos['version_sol'] = CambioTurnoService::version($db->filas[10]);
        $service->registrar($datos, null, 5);
        $service->eliminar(99, 1, 'Anulación del último cambio', 8);
        self::assertSame('CAMBIO TURNO', $db->filas[10]['prog_estado']);
        self::assertSame(1, $db->filas[10]['prog_es_cambio']);
    }

    public function testReversionEsAtomicaSiHayCruceOErrorAlEliminar(): void
    {
        foreach (['cruce', 'fallarEliminacion'] as $fallo) {
            [$service, $db, $datos] = $this->escenario();
            $service->registrar($datos, null, 5);
            $antes = $db->filas;
            $db->$fallo = true;
            try {
                $service->eliminar(99, 1, 'Anulación', 8);
                self::fail('Aceptó una restauración fallida');
            } catch (DomainException | RuntimeException $e) {
                self::assertSame($antes, $db->filas);
                self::assertSame('APLICADO', $db->historial['rc_estado']);
                self::assertFalse($db->committed);
            }
        }
    }

    public function testValidadorRealDetectaCruceNocturnoYPermiteTurnosContiguos(): void
    {
        $horarios = $this->getMockBuilder(\Modules\Asistencia\Models\TurnoHorarioModel::class)->disableOriginalConstructor()->onlyMethods(['find'])->getMock();
        $horarios->method('find')->willReturn(['th_hora_ingreso' => '07:00:00', 'th_hora_salida' => '13:00:00']);
        $programacion = (new ReflectionClass(ProgramacionTurnoService::class))->newInstanceWithoutConstructor();
        (new ReflectionProperty($programacion, 'turnoHorarioModel'))->setValue($programacion, $horarios);
        $db = new class {
            public string $salida = '08:00:00';
            public function __call($name, $args) { return $this; }
            public function getResultArray() {
                return [['prog_fecha' => '2026-10-09', 'th_hora_ingreso' => '19:00:00', 'th_hora_salida' => $this->salida, 'tur_codigo' => 'N']];
            }
        };
        (new ReflectionProperty($programacion, 'db'))->setValue($programacion, $db);
        self::assertTrue($programacion->validarCruceHorarios(1, '2026-10-10', 11, 10)['cruce']);
        $db->salida = '07:00:00';
        self::assertFalse($programacion->validarCruceHorarios(1, '2026-10-10', 11, 10)['cruce']);
    }
}

/** Conexión en memoria: prueba la atomicidad del servicio sin tocar datos reales. */
final class CambioTurnoMemoryDb
{
    public array $filas;
    public ?array $historial = null;
    public bool $committed = false;
    public bool $cruce = false;
    public bool $fallarEliminacion = false;
    public array $posteriores = [];
    private string $consulta = '';
    private string $tabla = '';
    private array $snapshot;
    private ?array $historialAnterior;
    private int $id;
    public function __construct()
    {
        foreach ([10 => 1, 20 => 2] as $id => $persona) {
            $this->filas[$id] = ['prog_ide' => $id, 'prog_perl_ide' => $persona, 'prog_th_ide' => $id + 1,
                'prog_fecha' => '2026-10-' . $id, 'prog_estado' => 'PROGRAMADO', 'deleted_at' => null,
                'prog_eup_ide' => 7, 'prog_observacion' => 'IMPORTADO_EXCEL'];
        }
    }
    public function transException($value) { return $this; }
    public function transBegin() { $this->snapshot = $this->filas; $this->historialAnterior = $this->historial; $this->committed = false; return true; }
    public function transRollback() { $this->filas = $this->snapshot; $this->historial = $this->historialAnterior; return true; }
    public function transStatus() { return true; }
    public function transCommit() { $this->committed = true; return true; }
    public function query($sql, $params) { $this->consulta = $sql; return $this; }
    public function getRowArray() { return $this->historial; }
    public function getResultArray() { return str_contains($this->consulta, 'SELECT rc_observacion') ? $this->posteriores : array_values($this->filas); }
    public function table($name) { $this->tabla = $name; return $this; }
    public function where($key, $id) { $this->id = $id; return $this; }
    public function update($data) {
        if ($this->tabla === 'casis_registro_cambio_turno') {
            if ($this->fallarEliminacion) return false;
            $this->historial = array_replace($this->historial, $data);
        } else {
            $this->filas[$this->id] = array_replace($this->filas[$this->id], $data);
        }
        return true;
    }
}

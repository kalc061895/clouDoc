<?php

use CodeIgniter\Test\CIUnitTestCase;
use Modules\Asistencia\Services\GestionRolService;

final class DesignacionRolTest extends CIUnitTestCase
{
    public function testJerarquiaNoIncluyePadresHermanosNiOtroEstablecimiento(): void
    {
        $oficinas = [
            ['ofi_ide'=>1, 'ofi_padre_ide'=>null, 'ofi_est_ide'=>10],
            ['ofi_ide'=>2, 'ofi_padre_ide'=>1, 'ofi_est_ide'=>10],
            ['ofi_ide'=>3, 'ofi_padre_ide'=>2, 'ofi_est_ide'=>10],
            ['ofi_ide'=>4, 'ofi_padre_ide'=>1, 'ofi_est_ide'=>10],
            ['ofi_ide'=>5, 'ofi_padre_ide'=>2, 'ofi_est_ide'=>20],
        ];
        self::assertSame([2, 3], GestionRolService::resolverOficinas([['oficina_id'=>2]], $oficinas));
        self::assertSame([1, 2, 3, 4], GestionRolService::resolverOficinas([['establecimiento_id'=>10]], $oficinas));
        self::assertSame([], GestionRolService::resolverOficinas([['oficina_id'=>99]], $oficinas));
        self::assertSame([], GestionRolService::resolverOficinas([], $oficinas));
    }

    public function testPermisosSeparadosPorEtapaUsuarioYVigencia(): void
    {
        $db = db_connect(['DBDriver'=>'SQLite3', 'database'=>':memory:', 'DBPrefix'=>''], false);
        $db->query('CREATE TABLE casis_oficina (ofi_ide INTEGER, ofi_padre_ide INTEGER, ofi_est_ide INTEGER, ofi_estado INTEGER, deleted_at TEXT)');
        $db->query('CREATE TABLE casis_plan_responsable (usuario_id INTEGER, oficina_id INTEGER, establecimiento_id INTEGER, etapa TEXT, desde TEXT, hasta TEXT, aprobar INTEGER, programar INTEGER)');
        foreach ([1, 2, 3, 4] as $id) $db->table('casis_oficina')->insert(['ofi_ide'=>$id, 'ofi_est_ide'=>10, 'ofi_estado'=>1]);
        foreach ([['RRHH',1,7,'2026-12-31'], ['DIRECCION',2,7,'2026-12-31'], ['PROGRAMACION',3,8,'2026-12-31'], ['PROGRAMACION',4,7,'2026-01-01']] as [$etapa,$oficina,$usuario,$hasta]) {
            $db->table('casis_plan_responsable')->insert(['usuario_id'=>$usuario,'oficina_id'=>$oficina,'etapa'=>$etapa,'desde'=>'2025-01-01','hasta'=>$hasta,'aprobar'=>$etapa==='PROGRAMACION'?0:1,'programar'=>$etapa==='PROGRAMACION'?1:0]);
        }
        $service = new class($db) extends GestionRolService {
            public function __construct($db) { $this->db = $db; }
            public function administrador(): bool { return false; }
            public function usuario(): int { return 7; }
            public function ahora(): string { return '2026-11-16 12:00:00'; }
        };
        self::assertSame([], $service->oficinas());
        self::assertSame([1], $service->oficinas(true, 'RRHH'));
        self::assertSame([2], $service->oficinas(true, 'DIRECCION'));
        $this->expectException(\DomainException::class);
        $service->exigirOficina(2, true, 'RRHH');
    }
}

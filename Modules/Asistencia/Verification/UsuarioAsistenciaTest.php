<?php

use CodeIgniter\Test\CIUnitTestCase;
use Modules\Asistencia\Services\UsuarioAsistenciaService;

final class UsuarioAsistenciaTest extends CIUnitTestCase
{
    public function testSoloAdmitePerfilesDeAsistencia(): void
    {
        self::assertTrue(UsuarioAsistenciaService::gruposPermitidos(['asi_rol','asi_rrhh']));
        self::assertFalse(UsuarioAsistenciaService::gruposPermitidos([]));
        self::assertFalse(UsuarioAsistenciaService::gruposPermitidos(['superadmin']));
        self::assertFalse(UsuarioAsistenciaService::gruposPermitidos(['asi_rol','user']));
    }

    private function database()
    {
        $db = db_connect(['DBDriver'=>'SQLite3','database'=>':memory:','DBPrefix'=>''],false);
        $db->query('CREATE TABLE users (id INTEGER, username TEXT, dni TEXT, nombres TEXT, paterno TEXT, materno TEXT, active INTEGER, deleted_at TEXT)');
        $db->query('CREATE TABLE auth_groups_users (user_id INTEGER, "group" TEXT)');
        $db->query('CREATE TABLE casis_persona (per_numero_documento TEXT, per_nombre TEXT, per_paterno TEXT, per_materno TEXT, per_email TEXT, per_telefono TEXT, deleted_at TEXT)');
        return $db;
    }

    public function testListadoExcluyeCuentasExternasMixtasYEliminadas(): void
    {
        $db = $this->database();
        foreach ([1,2,3,4] as $id) $db->table('users')->insert(['id'=>$id,'username'=>'u'.$id,'deleted_at'=>$id===4?'2026-01-01':null]);
        foreach ([[1,'asi_rol'],[2,'user'],[3,'asi_rrhh'],[3,'superadmin'],[4,'asi_rol']] as [$id,$grupo]) $db->table('auth_groups_users')->insert(['user_id'=>$id,'group'=>$grupo]);
        $s = new UsuarioAsistenciaService($db);
        self::assertSame([1], array_map('intval',array_column($s->listar(),'id')));
        $this->expectException(\DomainException::class);
        $s->exigirUsuario(3);
    }

    public function testDniDevuelveSoloDatosDelFormularioYExcluyeEliminados(): void
    {
        $db = $this->database();
        $db->table('casis_persona')->insert(['per_numero_documento'=>'01234567','per_nombre'=>'Ana','per_paterno'=>'Prueba','per_email'=>'ana@example.test']);
        $db->table('casis_persona')->insert(['per_numero_documento'=>'12345678','deleted_at'=>'2026-01-01']);
        $s = new UsuarioAsistenciaService($db);
        self::assertSame('Ana',$s->buscarDni('01234567')['nombres']);
        self::assertSame('01234567',$s->buscarDni('01234567')['dni']);
        self::assertNull($s->buscarDni('12345678'));
        self::assertNull($s->buscarDni('99999999'));
        $this->expectException(\InvalidArgumentException::class);
        $s->buscarDni("' OR 1=1");
    }
}

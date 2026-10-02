<?php

use App\Libraries\AsistenciaLayoutData;
use CodeIgniter\Test\CIUnitTestCase;

final class AsistenciaLayoutTest extends CIUnitTestCase
{
    public function testBusquedaExcluyeOpcionesNoAsignadasInactivasYDestinosInvalidos(): void
    {
        $item = static fn($id, $url, $status = 'active') => ['id' => $id, 'name' => 'Opción ' . $id, 'status' => $status, 'url' => $url, 'children' => []];
        $assigned = [$item(2, 'asistencia/personal'), $item(3, 'asistencia/licencia', 'inactive'), $item(5, 'javascript:alert(1)'), $item(6, '//example.com'), $item(7, 'asistencia/personal'), $item(9, 'asistencia/oculto')];
        $tree = [array_replace($item(1, '#'), ['name' => 'Administración', 'children' => [$assigned[0], $assigned[1], $item(4, 'asistencia/no-asignado'), $assigned[2], $assigned[3], $assigned[4]]]), array_replace($item(8, '#', 'inactive'), ['children' => [$assigned[5]]])];
        $options = AsistenciaLayoutData::opciones($tree, $assigned);
        self::assertCount(1, $options);
        self::assertSame('Administración', $options[0]['categoria']);
        self::assertSame(base_url('asistencia/personal'), $options[0]['url']);
        self::assertSame([], AsistenciaLayoutData::opciones($tree, []));
    }

    public function testPerfilUtilizaDatosRealesYFallbackSeguro(): void
    {
        $user = (object) ['nombres' => 'Ana María', 'paterno' => 'Pérez', 'materno' => 'Ruiz', 'cargo' => 'Enfermera', 'username' => 'ana', 'email' => 'ana@example.test', 'photo' => '../privado.jpg'];
        $profile = AsistenciaLayoutData::perfil($user);
        self::assertSame('Ana María Pérez Ruiz', $profile['nombre']);
        self::assertSame('AR', $profile['iniciales']);
        self::assertSame('Enfermera', $profile['cargo']);
        self::assertNull($profile['foto']);
        self::assertSame('usuario', AsistenciaLayoutData::perfil((object) ['username' => 'usuario'])['nombre']);
        self::assertSame('Cargo no registrado', AsistenciaLayoutData::perfil(null)['cargo']);
    }

    public function testComponentesEscapanDatosYGeneranEnlacesDeSesion(): void
    {
        $profile = AsistenciaLayoutData::perfil((object) ['nombres' => '<script>prueba</script>', 'cargo' => 'Cargo & área', 'email' => 'correo@example.test']);
        $html = view('partials/asistencia/userDropdown', ['perfilAsistencia' => $profile, 'orientacion' => 'vertical']);
        self::assertStringNotContainsString('<script>prueba</script>', $html);
        self::assertStringContainsString('Cargo &amp; área', $html);
        self::assertStringContainsString(base_url('logout'), $html);
        self::assertStringNotContainsString('page-user-profile.html', $html);
        $modal = view('partials/asistencia/sessionModals', ['perfilAsistencia' => $profile, 'opcionesAsistencia' => [['nombre' => '</script><script>alert(1)</script>', 'categoria' => '', 'url' => base_url('asistencia')]]]);
        self::assertStringNotContainsString('</script><script>alert(1)</script>', $modal);
        self::assertStringContainsString('asis-menu-options', $modal);
    }
}

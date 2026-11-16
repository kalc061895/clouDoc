<?php

use CodeIgniter\Test\CIUnitTestCase;
use Modules\Asistencia\Controllers\RolDocumentoController;
use Modules\Asistencia\Database\Seeds\MenuSeeder;
use Modules\Asistencia\Database\Seeds\MenuGroupUserSeeder;

final class RetiroRolesLegacyTest extends CIUnitTestCase
{
    public function testNavegacionNoReactivaPantallasRetiradas(): void
    {
        $menus = array_column(MenuSeeder::menus(), null, 'id');
        foreach ([1041,1042] as $id) {
            self::assertSame('inactive', $menus[$id]['status']);
            foreach (MenuGroupUserSeeder::asignaciones() as $ids) self::assertNotContains($id, $ids);
        }
        self::assertSame('active', $menus[1083]['status']);
        self::assertSame('active', $menus[1043]['status']);
    }

    public function testEnlacesAntiguosRedirigenAlFlujoActual(): void
    {
        $controller = new RolDocumentoController();
        foreach (['index','historial'] as $method) {
            $response = $controller->$method();
            self::assertSame(302, $response->getStatusCode());
            self::assertSame(site_url('asistencia/gestion-roles/envios'), $response->getHeaderLine('Location'));
        }
    }

    public function testOperacionesAntiguasNoGeneranNiAnulanDocumentos(): void
    {
        $controller = new class extends RolDocumentoController {
            public function __construct() { $this->response = service('response'); }
        };
        foreach (['consultar','generar','listar','anular'] as $method) {
            $response = $method === 'anular' ? $controller->$method(1) : $controller->$method();
            self::assertSame(410, $response->getStatusCode());
            self::assertSame('error', json_decode($response->getBody(), true)['status']);
        }
    }
}

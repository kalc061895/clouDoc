<?php

declare(strict_types=1);

use CodeIgniter\Test\CIUnitTestCase;
use Modules\Asistencia\Controllers\PersonalController;
use Modules\Asistencia\Services\AccesoAsistenciaService;
use Modules\Asistencia\Services\PersonalService;

final class PersonalWizardHttpTest extends CIUnitTestCase
{
    private function controller(bool $allowed, array $result = []): PersonalController
    {
        $access = $this->getMockBuilder(AccesoAsistenciaService::class)->disableOriginalConstructor()
            ->onlyMethods(['permitir', 'establecimientoDentroAmbito'])->getMock();
        $access->method('permitir')->with('crear_personal')->willReturn($allowed);
        $access->method('establecimientoDentroAmbito')->with(3)->willReturn(true);
        $personal = $this->getMockBuilder(PersonalService::class)->disableOriginalConstructor()
            ->onlyMethods(['guardarPersonalWizard'])->getMock();
        if ($result) {
            $personal->expects(self::once())->method('guardarPersonalWizard')->willReturn($result);
        } else {
            $personal->expects(self::never())->method('guardarPersonalWizard');
        }
        $controller = new class($personal, $access) extends PersonalController {
            public function __construct($personal, $access) { $this->personalService = $personal; $this->acceso = $access; }
        };
        $request = service('request');
        $request->setBody(json_encode(['perl_est_ide' => 3]));
        $controller->initController($request, service('response'), service('logger'));
        return $controller;
    }

    public function testTokenEndpointRequiresCreatePermissionAndDoesNotSave(): void
    {
        self::assertSame(403, $this->controller(false)->apiTokenWizard()->getStatusCode());
        $response = $this->controller(true)->apiTokenWizard();
        self::assertSame(200, $response->getStatusCode());
        self::assertStringContainsString('no-store', $response->getHeaderLine('Cache-Control'));
        $json = json_decode($response->getBody(), true);
        self::assertSame(csrf_token(), $json['csrf']['name']);
        self::assertNotEmpty($json['csrf']['hash']);
    }

    public function testSavePreservesExistingSuccessContractAndReturnsCurrentToken(): void
    {
        $response = $this->controller(true, ['status' => 'success', 'message' => 'Registrado'])->apiGuardarWizard();
        self::assertSame(200, $response->getStatusCode());
        $json = json_decode($response->getBody(), true);
        self::assertSame('success', $json['status']);
        self::assertSame('Registrado', $json['message']);
        self::assertArrayHasKey('csrf', $json);
    }

    public function testFailedSavePreservesErrorsAndReturnsCurrentToken(): void
    {
        $response = $this->controller(true, ['status' => 'error', 'message' => 'Datos inválidos'])->apiGuardarWizard();
        self::assertSame(400, $response->getStatusCode());
        $json = json_decode($response->getBody(), true);
        self::assertSame('Datos inválidos', $json['messages']['database']);
        self::assertArrayHasKey('csrf', $json);
    }
}

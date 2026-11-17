<?php

declare(strict_types=1);

use App\Exceptions\PasswordChangeException;
use App\Services\PasswordChangeService;
use CodeIgniter\Shield\Authentication\Passwords;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Result;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Services;
use Modules\Asistencia\Controllers\PasswordController;

final class PasswordChangeTest extends CIUnitTestCase
{
    private function context(): array
    {
        $user = $this->getMockBuilder(User::class)->onlyMethods(['getPasswordHash', 'saveEmailIdentity'])->getMock();
        $hash = password_hash('AnteriorSegura!29', PASSWORD_DEFAULT);
        $user->method('getPasswordHash')->willReturn($hash);
        $users = $this->getMockBuilder(UserModel::class)->disableOriginalConstructor()->onlyMethods(['findById'])->getMock();
        $users->expects(self::once())->method('findById')->with(7)->willReturn($user);
        $passwords = $this->getMockBuilder(Passwords::class)->disableOriginalConstructor()->onlyMethods(['verify', 'check'])->getMock();
        $passwords->method('verify')->willReturnCallback(static fn ($password, $hash) => password_verify($password, $hash));
        return [$user, $passwords, new PasswordChangeService($users, $passwords)];
    }

    public function testValidChangeUsesShieldIdentityAndClearsTransientPassword(): void
    {
        [$user, $passwords, $service] = $this->context();
        $passwords->expects(self::once())->method('check')->with('NuevaSegura!81', $user)->willReturn(new Result(['success' => true]));
        $user->expects(self::once())->method('saveEmailIdentity')->willReturnCallback(static function () use ($user) {
            self::assertSame('NuevaSegura!81', $user->password);
            return true;
        });
        $service->cambiar(7, 'AnteriorSegura!29', 'NuevaSegura!81', 'NuevaSegura!81');
        self::assertSame('', $user->password);
    }

    public function testWrongCurrentPasswordCannotWriteIdentity(): void
    {
        [$user, $passwords, $service] = $this->context();
        $user->expects(self::never())->method('saveEmailIdentity');
        $passwords->expects(self::never())->method('check');
        try {
            $service->cambiar(7, 'Incorrecta', 'NuevaSegura!81', 'NuevaSegura!81');
            self::fail('Expected validation failure.');
        } catch (PasswordChangeException $e) {
            self::assertArrayHasKey('current_password', $e->errors);
        }
    }

    public function testCannotReuseCurrentPassword(): void
    {
        [$user, , $service] = $this->context();
        $user->expects(self::never())->method('saveEmailIdentity');
        $this->expectException(PasswordChangeException::class);
        $service->cambiar(7, 'AnteriorSegura!29', 'AnteriorSegura!29', 'AnteriorSegura!29');
    }

    public function testConfirmationMustMatchBeforeAccessingPersistence(): void
    {
        $users = $this->getMockBuilder(UserModel::class)->disableOriginalConstructor()->onlyMethods(['findById'])->getMock();
        $users->expects(self::never())->method('findById');
        $this->expectException(PasswordChangeException::class);
        (new PasswordChangeService($users))->cambiar(7, 'Anterior', 'NuevaSegura!81', 'Otra');
    }

    public function testShieldPolicyFailureDoesNotWrite(): void
    {
        [$user, $passwords, $service] = $this->context();
        $passwords->method('check')->willReturn(new Result(['success' => false, 'reason' => 'Contraseña débil.']));
        $user->expects(self::never())->method('saveEmailIdentity');
        $this->expectException(PasswordChangeException::class);
        $service->cambiar(7, 'AnteriorSegura!29', 'Debil', 'Debil');
    }

    public function testFailedPersistenceIsNotReportedAsSuccess(): void
    {
        [$user, $passwords, $service] = $this->context();
        $passwords->method('check')->willReturn(new Result(['success' => true]));
        $user->method('saveEmailIdentity')->willReturn(false);
        try {
            $service->cambiar(7, 'AnteriorSegura!29', 'NuevaSegura!81', 'NuevaSegura!81');
            self::fail('Expected persistence failure.');
        } catch (RuntimeException) {
            self::assertSame('', $user->password);
        }
    }

    private function controller(bool $loggedIn, array $post, PasswordChangeService $service, bool $allow = true): PasswordController
    {
        helper('auth');
        $auth = $this->getMockBuilder(\CodeIgniter\Shield\Auth::class)->disableOriginalConstructor()->onlyMethods(['id', '__call'])->getMock();
        $auth->method('id')->willReturn(7);
        $auth->method('__call')->willReturn($loggedIn);
        Services::injectMock('auth', $auth);
        $throttler = $this->getMockBuilder(\CodeIgniter\Throttle\Throttler::class)->disableOriginalConstructor()->onlyMethods(['check'])->getMock();
        $throttler->method('check')->willReturn($allow);
        Services::injectMock('throttler', $throttler);
        $controller = new class($service) extends PasswordController {
            public function __construct(private PasswordChangeService $service) {}
            protected function passwordService(): PasswordChangeService { return $this->service; }
        };
        $request = service('request');
        $request->setGlobal('post', $post);
        $controller->initController($request, service('response'), service('logger'));
        return $controller;
    }

    public function testEndpointUsesSessionActorInsteadOfPayloadUserId(): void
    {
        $service = $this->createMock(PasswordChangeService::class);
        $service->expects(self::once())->method('cambiar')->with(7, 'Anterior', 'Nueva', 'Nueva');
        $response = $this->controller(true, ['user_id' => 99, 'current_password' => 'Anterior', 'password' => 'Nueva', 'password_confirm' => 'Nueva'], $service)->guardar();
        self::assertSame(200, $response->getStatusCode());
        $json = json_decode($response->getBody(), true);
        self::assertTrue($json['success']);
        self::assertArrayHasKey('csrf', $json['meta']);
        self::assertStringNotContainsString('Nueva', $response->getBody());
    }

    public function testEndpointRejectsUnauthenticatedCall(): void
    {
        $service = $this->createMock(PasswordChangeService::class);
        $service->expects(self::never())->method('cambiar');
        self::assertSame(401, $this->controller(false, [], $service)->guardar()->getStatusCode());
    }

    public function testEndpointRejectsNonStringPasswords(): void
    {
        $service = $this->createMock(PasswordChangeService::class);
        $service->expects(self::never())->method('cambiar');
        $response = $this->controller(true, ['current_password' => ['array'], 'password' => 'Nueva', 'password_confirm' => 'Nueva'], $service)->guardar();
        self::assertSame(422, $response->getStatusCode());
    }

    public function testShieldNativeIdentityStoresAHashThatAcceptsOnlyNewPassword(): void
    {
        $passwords = new Passwords(config('Auth'));
        Services::injectMock('passwords', $passwords);
        $identity = new \CodeIgniter\Shield\Entities\UserIdentity(['id' => 2, 'user_id' => 7,
            'type' => 'email_password', 'secret' => 'someone@example.test', 'secret2' => $passwords->hash('AnteriorSegura!29')]);
        $user = $this->getMockBuilder(User::class)->onlyMethods(['getEmailIdentity'])->getMock();
        $user->fill(['id' => 7, 'username' => 'someone', 'email' => 'someone@example.test']);
        $user->method('getEmailIdentity')->willReturn($identity);
        $users = $this->getMockBuilder(UserModel::class)->disableOriginalConstructor()->onlyMethods(['findById'])->getMock();
        $users->method('findById')->willReturn($user);
        $identities = $this->getMockBuilder(\CodeIgniter\Shield\Models\UserIdentityModel::class)->disableOriginalConstructor()->onlyMethods(['save'])->getMock();
        $identities->expects(self::once())->method('save')->willReturnCallback(static function ($saved) use ($passwords) {
            self::assertNotSame('Nebulosa!Roca72Azul', $saved->secret2);
            self::assertTrue($passwords->verify('Nebulosa!Roca72Azul', $saved->secret2));
            self::assertFalse($passwords->verify('AnteriorSegura!29', $saved->secret2));
            return true;
        });
        \CodeIgniter\Config\Factories::injectMock('models', \CodeIgniter\Shield\Models\UserIdentityModel::class, $identities);
        (new PasswordChangeService($users, $passwords))->cambiar(7, 'AnteriorSegura!29', 'Nebulosa!Roca72Azul', 'Nebulosa!Roca72Azul');
    }

    public function testEndpointLimitsAttemptsBeforeCallingService(): void
    {
        $service = $this->createMock(PasswordChangeService::class);
        $service->expects(self::never())->method('cambiar');
        self::assertSame(429, $this->controller(true, [], $service, false)->guardar()->getStatusCode());
    }

    public function testPasswordRouteRequiresCsrfAndFilterRejectsMissingToken(): void
    {
        service('routes')->getRoutes(); // Trigger module route discovery.
        self::assertContains('csrf', service('routes')->getFiltersForRoute('asistencia/mi-cuenta/password', 'POST'));
        $request = service('request');
        $request->setMethod('POST');
        $request->setGlobal('post', []);
        $request->setHeader('X-Requested-With', 'XMLHttpRequest');
        $this->expectException(\CodeIgniter\Security\Exceptions\SecurityException::class);
        (new \CodeIgniter\Filters\CSRF())->before($request);
    }
}

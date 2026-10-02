<?php

use App\Services\UserPreferenceService;
use App\Database\Migrations\CreateUserPreferences;
use CodeIgniter\Test\CIUnitTestCase;

require_once ROOTPATH . 'app/Database/Migrations/2026-10-02-120000_CreateUserPreferences.php';

final class UserPreferenceTest extends CIUnitTestCase
{
    public function testValidaValoresSinAceptarOtroUsuario(): void
    {
        self::assertSame(UserPreferenceService::DEFAULTS, UserPreferenceService::validar(UserPreferenceService::DEFAULTS));
        foreach ([['Theme' => 'system'], ['BoxedLayout' => 'false'], ['Layout' => []], ['ColorTheme' => '<script>'], ['user_id' => 2]] as $change) {
            try {
                UserPreferenceService::validar(array_replace(UserPreferenceService::DEFAULTS, $change));
                self::fail('Se aceptó una preferencia inválida.');
            } catch (InvalidArgumentException $e) { self::assertNotEmpty($e->getMessage()); }
        }
    }

    public function testMigracionYPersistenciaAisladaPorUsuario(): void
    {
        $db = db_connect(['DBDriver' => 'SQLite3', 'database' => ':memory:', 'DBPrefix' => '', 'DBDebug' => true, 'foreignKeys' => true], false);
        $forge = \Config\Database::forge($db);
        $users = config('Auth')->tables['users'];
        $forge->addField(['id' => ['type' => 'INTEGER']]);
        $forge->addKey('id', true); $forge->createTable($users);
        $db->table($users)->insertBatch([['id' => 1], ['id' => 2]]);
        $migration = new CreateUserPreferences($forge); $migration->up();
        $service = new UserPreferenceService($db);
        self::assertSame(UserPreferenceService::DEFAULTS, $service->obtener(1));
        $dark = array_replace(UserPreferenceService::DEFAULTS, ['Theme' => 'dark', 'ColorTheme' => 'Green_Theme', 'BoxedLayout' => false]);
        $service->guardar(1, $dark);
        self::assertSame($dark, (new UserPreferenceService($db))->obtener(1));
        self::assertSame(UserPreferenceService::DEFAULTS, $service->obtener(2));
        $service->guardar(2, array_replace(UserPreferenceService::DEFAULTS, ['Layout' => 'horizontal']));
        $service->guardar(1, UserPreferenceService::DEFAULTS);
        self::assertSame(2, $db->table('user_preferences')->countAllResults());
        self::assertSame('horizontal', $service->obtener(2)['Layout']);
        self::assertSame(UserPreferenceService::DEFAULTS, $service->obtener(1));
        $db->table($users)->where('id', 1)->delete();
        self::assertSame(1, $db->table('user_preferences')->countAllResults());
        $migration->down(); self::assertFalse($db->tableExists('user_preferences'));
        $db->close();
    }
}

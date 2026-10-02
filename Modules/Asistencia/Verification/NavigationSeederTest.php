<?php

use CodeIgniter\Test\CIUnitTestCase;
use Modules\Asistencia\Database\Seeds\NavigationSeeder;
use Modules\Asistencia\Database\Seeds\MenuSeeder;
use Modules\Asistencia\Database\Seeds\MenuGroupUserSeeder;
use Modules\Asistencia\Database\Seeds\GroupUserSeeder;
use App\Models\MenuModel;

final class NavigationSeederTest extends CIUnitTestCase
{
    private function database()
    {
        $db = db_connect(['DBDriver' => 'SQLite3', 'database' => ':memory:', 'DBPrefix' => '', 'DBDebug' => true], false);
        $db->query('CREATE TABLE group_user (id INTEGER PRIMARY KEY, name TEXT NOT NULL)');
        $db->query('CREATE TABLE menus (id INTEGER PRIMARY KEY, parent_id INTEGER, type TEXT, name TEXT, url TEXT, icon TEXT, status TEXT, "order" INTEGER, separator TEXT)');
        $db->query('CREATE TABLE menu_group_user (id INTEGER PRIMARY KEY AUTOINCREMENT, group_user_id INTEGER, menu_id INTEGER)');
        $db->query('CREATE TABLE auth_groups_users (user_id INTEGER, "group" TEXT)');
        $db->table('auth_groups_users')->insert(['user_id' => 8, 'group' => 'asistencia']);
        return $db;
    }

    public function testRepetirSeederNoDuplicaYMantieneMembresias(): void
    {
        $db = $this->database();
        $seeder = new NavigationSeeder(config('Database'), $db);
        $seeder->run(); $seeder->run();
        self::assertSame(4, $db->table('group_user')->countAllResults());
        self::assertSame(count(MenuSeeder::menus()), $db->table('menus')->countAllResults());
        foreach (GroupUserSeeder::GROUPS as $id => $name) {
            $actual = array_map('intval', array_column($db->table('menu_group_user')->where('group_user_id', $id)->orderBy('menu_id')->get()->getResultArray(), 'menu_id'));
            self::assertSame(MenuGroupUserSeeder::asignaciones()[$name], $actual);
            self::assertArrayHasKey($name, config('AuthGroups')->groups);
        }
        self::assertSame('asistencia', $db->table('auth_groups_users')->get()->getRowArray()['group']);
        self::assertSame(1, $db->table('auth_groups_users')->countAllResults());
        $db->close();
    }

    public function testColisionRevierteGruposYCargaParcial(): void
    {
        $db = $this->database();
        $db->table('menus')->insert(['id' => 1006, 'name' => 'Ajeno', 'url' => 'otro/modulo']);
        try { (new NavigationSeeder(config('Database'), $db))->run(); self::fail('Debió detectar la colisión.'); }
        catch (RuntimeException $e) { self::assertStringContainsString('1006', $e->getMessage()); }
        self::assertSame(0, $db->table('group_user')->countAllResults());
        self::assertSame(1, $db->table('menus')->countAllResults());
        self::assertSame('otro/modulo', $db->table('menus')->get()->getRowArray()['url']);
        $db->close();
    }

    public function testMenuPorGrupoYUnionSinDuplicados(): void
    {
        $db = $this->database();
        (new NavigationSeeder(config('Database'), $db))->run();
        $model = new MenuModel($db);
        $support = array_map('intval', array_column($model->getMenusByRole(['asi_apo']), 'id'));
        self::assertContains(1006, $support); self::assertContains(1061, $support);
        self::assertNotContains(1002, $support); self::assertNotContains(1043, $support); self::assertNotContains(1013, $support);
        $all = $model->getMenusByRole(['asi_apo', 'asi_sua']);
        self::assertCount(count(MenuSeeder::menus()), $all);
        $db->table('menus')->where('id', 1006)->update(['status' => 'inactive']);
        self::assertNotContains(1006, array_map('intval', array_column($model->getMenusByRole(['asi_apo']), 'id')));
        self::assertSame([], $model->getMenusByRole([]));
        self::assertSame([], $model->getMenusByRole(["' OR 1=1 --"]));
        $db->close();
    }
}

<?php

use CodeIgniter\Test\CIUnitTestCase;
use Modules\Asistencia\Database\Migrations\CreatePresupuestoGuardias;
use Modules\Asistencia\Services\PresupuestoGuardiasExportacion;
use Modules\Asistencia\Services\PresupuestoGuardiasService as Presupuesto;

final class PresupuestoGuardiasTest extends CIUnitTestCase
{
    public function testMigracionRegistraMenuSoloEnPerfilesDePresupuesto(): void
    {
        $db = db_connect(['DBDriver' => 'SQLite3', 'database' => ':memory:', 'DBPrefix' => '', 'DBDebug' => true], false);
        try {
            $db->query('CREATE TABLE menus (id INT PRIMARY KEY, parent_id INT, type TEXT, name TEXT, url TEXT, icon TEXT, status TEXT, "order" INT, separator TEXT)');
            $db->query('CREATE TABLE group_user (id INT, name TEXT)');
            $db->query('CREATE TABLE menu_group_user (group_user_id INT, menu_id INT)');
            $db->query("INSERT INTO group_user VALUES (1, 'asi_rrhh'), (2, 'asi_dire'), (3, 'asi_rol')");
            require_once dirname(__DIR__) . '/Database/Migrations/2026-11-23-130000_CreatePresupuestoGuardias.php';
            $migracion = new CreatePresupuestoGuardias(\Config\Database::forge($db));
            $migracion->up();

            self::assertTrue($db->tableExists('casis_presupuesto_guardia_exportacion'));
            self::assertSame([1, 2], array_map('intval', array_column(
                $db->table('menu_group_user')->where('menu_id', 1104)->orderBy('group_user_id')->get()->getResultArray(), 'group_user_id'
            )));
        } finally {
            $db->close();
        }
    }

    public function testFeriadosDomingosYNocturnasSeValorizanPorElDiaDeInicio(): void
    {
        $turnos = [
            ['prog_fecha' => '2026-11-23', 'tur_codigo' => 'GN', 'th_hora_ingreso' => '19:00', 'th_hora_salida' => '07:00'],
            ['prog_fecha' => '2026-11-24', 'th_tipo_jornada' => 'ORDINARIA', 'tur_codigo' => 'GDE', 'tur_nombre' => 'Guardia diurna emergencia'],
            ['prog_fecha' => '2026-11-29', 'prog_th_ide' => 4],
            ['prog_fecha' => '2026-11-22', 'th_tipo_jornada' => 'GUARDIA_DIURNA'],
            ['prog_fecha' => '2026-11-29', 'th_tipo_jornada' => 'ORDINARIA', 'tur_codigo' => 'M'],
        ];
        $feriados = [
            ['df_fecha' => '2026-11-24', 'df_repetitivo' => 0],
            ['df_fecha' => '2024-11-22', 'df_repetitivo' => 1],
        ];
        $conteo = Presupuesto::contar($turnos, $feriados, [4 => 'GUARDIA_NOCTURNA'], '2026-11');
        self::assertSame(['nocturna' => 1, 'diurna_feriado' => 2, 'nocturna_domingo' => 1], $conteo);
        self::assertSame(56126, Presupuesto::valorizar($conteo, ['nocturna' => '100.10', 'diurna_feriado' => '150.05', 'nocturna_domingo' => '161.06']));
        self::assertNull(Presupuesto::valorizar($conteo, ['nocturna' => '0']));
        self::assertSame(0, Presupuesto::valorizar([], []));
    }

    public function testAlcanceVersionAprobadaYExportacionesArchivadas(): void
    {
        $db = db_connect(['DBDriver' => 'SQLite3', 'database' => ':memory:', 'DBPrefix' => '', 'DBDebug' => true], false);
        try {
            foreach ([
                'casis_plan_grupo' => 'id INT, nombre TEXT',
                'casis_plan_periodo' => 'id INT, mes TEXT, grupo_id INT',
                'casis_plan_rol' => 'id INT, periodo_id INT',
                'casis_rol_envio' => 'id INT, rol_id INT, estado TEXT, id_oficina INT, id_establecimiento INT, version INT, hash_sha256 TEXT, datos_json TEXT',
                'casis_oficina' => 'ofi_ide INT, ofi_nombre TEXT',
                'casis_establecimiento' => 'est_ide INT, est_nombre TEXT, est_mic_ide INT',
                'casis_microred' => 'mic_ide INT, mic_nombre TEXT, mic_red_ide INT',
                'casis_red' => 'red_ide INT, red_nombre TEXT',
                'casis_modalidad_contrato' => 'mco_ide INT, mco_nombre TEXT',
                'casis_diaferiado' => 'df_fecha TEXT, df_repetitivo INT, deleted_at TEXT',
                'casis_remuneraciones' => 'rem_ide INT, rem_car_ide INT, rem_nivel TEXT, rem_estado INT, rem_configuracion TEXT',
                'casis_presupuesto_guardia_exportacion' => 'id INTEGER PRIMARY KEY, codigo TEXT, formato TEXT, periodo_id INT, usuario_id INT, generado_at TEXT, sha256 TEXT, snapshot TEXT, contenido BLOB',
            ] as $tabla => $campos) {
                $db->query("CREATE TABLE $tabla ($campos)");
            }
            $db->query("INSERT INTO casis_plan_periodo VALUES (1, '2026-11', NULL), (2, '2026-12', NULL)");
            $db->query('INSERT INTO casis_plan_rol VALUES (1, 1), (2, 2)');
            $db->query("INSERT INTO casis_oficina VALUES (1, 'Emergencia'), (2, 'Farmacia')");
            $db->query("INSERT INTO casis_modalidad_contrato VALUES (7, 'CAS')");
            $db->table('casis_remuneraciones')->insert(['rem_ide' => 4, 'rem_car_ide' => 5, 'rem_nivel' => 'II', 'rem_estado' => 1,
                'rem_configuracion' => '{"guardias":{"nocturna":"123.45"}}']);
            $perfil = ['perl_car_ide' => 5, 'perl_nivel' => 'II', 'perl_mco_ide' => 7, 'cargo_snapshot' => 'Enfermería'];
            $json = json_encode([
                ['personal' => $perfil + ['perl_ide' => 1, 'documento_snapshot' => '00000001', 'nombre_snapshot' => 'Zapata Uno'], 'turnos' =>
                    [['prog_fecha' => '2026-11-23', 'th_tipo_jornada' => 'GUARDIA_NOCTURNA']]],
                ['personal' => $perfil + ['perl_ide' => 2, 'documento_snapshot' => '00000002', 'nombre_snapshot' => 'Águila Dos'], 'turnos' => []],
            ], JSON_THROW_ON_ERROR);
            foreach ([[1, 1, 1, 1, 'APROBADO_DIRECCION'], [2, 1, 2, 1, 'APROBADO_DIRECCION'],
                [3, 1, 3, 1, 'PENDIENTE_RRHH'], [4, 2, 1, 2, 'APROBADO_DIRECCION']] as [$id, $rol, $version, $oficina, $estado]) {
                $db->table('casis_rol_envio')->insert(['id' => $id, 'rol_id' => $rol, 'version' => $version, 'estado' => $estado,
                    'id_oficina' => $oficina, 'id_establecimiento' => 1, 'hash_sha256' => hash('sha256', $json), 'datos_json' => $json]);
            }
            $reporte = (new Presupuesto($db))->consultar(['periodo' => 1, 'regimen' => 7], [1]);
            self::assertSame([1], array_map('intval', array_column($reporte['periodos'], 'id')));
            self::assertSame(2, (int) $reporte['fuentes'][0]['id']);
            self::assertSame(['Águila Dos', 'Zapata Uno'], array_column(array_column($reporte['filas'], 'valores'), 1));
            self::assertSame([0, 12345], array_column(array_column($reporte['filas'], 'valores'), 9));

            $exportador = new PresupuestoGuardiasExportacion($db);
            foreach (['xlsx' => 'PK', 'pdf' => '%PDF-'] as $formato => $cabecera) {
                $archivo = $exportador->generar($reporte, $formato, 9);
                self::assertStringStartsWith($cabecera, $archivo['contenido']);
                $archivado = $exportador->archivo($archivo['codigo'], 9, [1]);
                self::assertSame($archivo['contenido'], $archivado['contenido']);
                self::assertStringContainsString('"nocturna":"123.45"', $archivado['snapshot']);
            }
            $this->expectExceptionCode(403);
            $exportador->archivo($archivo['codigo'], 9, [2]);
        } finally {
            $db->close();
        }
    }
}

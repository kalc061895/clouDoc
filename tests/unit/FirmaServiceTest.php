<?php

use CodeIgniter\Test\CIUnitTestCase;
use Modules\Firma\Config\Firma;
use Modules\Firma\Services\FirmaService;

final class FirmaServiceTest extends CIUnitTestCase
{
    protected $db;
    private FirmaService $service;
    private string $dir;
    private string $pdf;

    protected function setUp(): void
    {
        parent::setUp();
        $this->db = \Config\Database::connect(['DBDriver' => 'SQLite3', 'database' => ':memory:', 'DBPrefix' => '', 'DBDebug' => true], false);
        require_once ROOTPATH . 'Modules/Firma/Database/Migrations/2026-10-01-100000_CreateFirma.php';
        (new \Modules\Firma\Database\Migrations\CreateFirma(\Config\Database::forge($this->db)))->up();
        require_once ROOTPATH . 'Modules/Firma/Database/Migrations/2026-10-01-160000_AddAsuntoFirma.php';
        (new \Modules\Firma\Database\Migrations\AddAsuntoFirma(\Config\Database::forge($this->db)))->up();
        $this->dir = WRITEPATH . 'testing/firma-' . bin2hex(random_bytes(5)) . '/';
        mkdir($this->dir, 0770, true);
        $this->pdf = $this->dir . 'original.pdf';
        $pdf = new \Dompdf\Dompdf();
        $pdf->loadHtml('<h1>Documento de prueba</h1>');
        $pdf->render();
        file_put_contents($this->pdf, $pdf->output());
        $this->service = new FirmaService($this->db, new Firma(), $this->dir);
    }

    protected function tearDown(): void
    {
        foreach (glob($this->dir . '*') as $file) unlink($file);
        rmdir($this->dir);
        $this->db->close();
        parent::tearDown();
    }

    private function token(array $operation): string
    {
        return json_decode(base64_decode($operation['param_b64']), true)['param_token'];
    }

    public function testPreservaOriginalYAislaUsuarios(): void
    {
        $id = $this->service->registrar($this->pdf, 'ejemplo.pdf', 7);
        self::assertSame(hash_file('sha256', $this->pdf), $this->service->archivo($id, 1)['sha256']);
        self::assertSame(0, $this->service->listar(8)['total']);
        $this->expectException(OutOfBoundsException::class);
        $this->service->versiones($id, 8);
    }

    public function testTokensVencidosCanceladosEInvalidosNoDanAcceso(): void
    {
        $id = $this->service->registrar($this->pdf, 'ejemplo.pdf', 7);
        $op = $this->service->iniciar($id, 7, ['asunto' => 'Prueba', 'motivo' => 'Autor', 'cargo' => 'Responsable', 'estilo' => '1']);
        $token = $this->token($op);
        self::assertSame($id, (int) $this->service->porToken($token)['documento_id']);
        self::assertNotSame($token, $this->db->table('firma_operaciones')->get()->getRowArray()['token_hash']);
        $this->service->cancelar($op['id'], 7);
        foreach ([$token, 'invalido', str_repeat('0', 64)] as $invalid) {
            try { $this->service->porToken($invalid); self::fail('Aceptó un token inválido.'); }
            catch (OutOfBoundsException $e) { self::assertNotEmpty($e->getMessage()); }
        }
        $op = $this->service->iniciar($id, 7, ['asunto' => 'Prueba', 'motivo' => 'Autor', 'cargo' => 'Responsable', 'estilo' => '1']);
        $this->db->table('firma_operaciones')->where('id', $op['id'])->update(['expires_at' => '2000-01-01 00:00:00']);
        self::assertSame('VENCIDA', $this->service->estado($op['id'], 7)['estado']);
        $this->expectException(OutOfBoundsException::class);
        $this->service->porToken($this->token($op));
    }

    public function testRechazaRetornoSinFirmaYArchivoQueNoEsPdf(): void
    {
        $id = $this->service->registrar($this->pdf, 'ejemplo.pdf', 7);
        $op = $this->service->iniciar($id, 7, ['asunto' => 'Prueba', 'motivo' => 'Autor', 'cargo' => 'Responsable', 'estilo' => '1']);
        try { $this->service->recibir($this->token($op), $this->pdf); self::fail('Aceptó el original sin firma.'); }
        catch (InvalidArgumentException $e) { self::assertCount(1, $this->service->versiones($id, 7)); }
        file_put_contents($this->dir . 'falso.pdf', '<html>No es PDF</html>');
        $this->expectException(InvalidArgumentException::class);
        $this->service->registrar($this->dir . 'falso.pdf', 'falso.pdf', 7);
    }

    public function testRecepcionConservaVersionYBloqueaReplayYVersionObsoleta(): void
    {
        $id = $this->service->registrar($this->pdf, 'ejemplo.pdf', 7);
        $a = $this->service->iniciar($id, 7, ['asunto' => 'Prueba', 'motivo' => 'Autor', 'cargo' => 'Responsable', 'estilo' => '1']);
        $b = $this->service->iniciar($id, 7, ['asunto' => 'Prueba', 'motivo' => 'Autor', 'cargo' => 'Responsable', 'estilo' => '1']);
        // Fixture estructural deliberadamente no criptográfica; no usa un certificado real.
        $signed = $this->dir . 'retorno.pdf';
        file_put_contents($signed, file_get_contents($this->pdf) . "\n/ByteRange [0 100 200 300] /Contents <ABCD>\n%%EOF");
        $this->service->recibir($this->token($a), $signed);
        self::assertCount(2, $this->service->versiones($id, 7));
        self::assertSame('Prueba', $this->service->versiones($id, 7)[0]['asunto']);
        self::assertSame('RECIBIDA', $this->service->estado($a['id'], 7)['estado']);
        self::assertSame(hash_file('sha256', $this->pdf), $this->service->archivo($id, 1)['sha256']);
        try { $this->service->recibir($this->token($a), $signed); self::fail('Aceptó repetición.'); }
        catch (OutOfBoundsException $e) { self::assertCount(2, $this->service->versiones($id, 7)); }
        $this->expectException(DomainException::class);
        $this->service->recibir($this->token($b), $signed);
    }

    public function testDetectaAlteracionDelArchivoGuardado(): void
    {
        $id = $this->service->registrar($this->pdf, 'ejemplo.pdf', 7);
        $file = $this->service->archivo($id, 1);
        file_put_contents($file['ruta'], 'modificado');
        $this->expectException(RuntimeException::class);
        $this->service->archivo($id, 1);
    }

    public function testDatosDeFirmaObligatoriosAntesDeCrearOperacion(): void
    {
        $id = $this->service->registrar($this->pdf, 'prueba.pdf', 7);
        $valid = ['asunto' => 'Documento', 'motivo' => 'Autor', 'cargo' => 'Responsable', 'estilo' => '1'];
        foreach (array_keys($valid) as $field) {
            foreach (['', '   ', [], null] as $value) {
                try {
                    $this->service->iniciar($id, 7, array_replace($valid, [$field => $value]));
                    self::fail('Aceptó campo inválido: ' . $field);
                } catch (InvalidArgumentException $e) {
                    self::assertSame(0, $this->db->table('firma_operaciones')->countAllResults());
                }
            }
        }
    }

    public function testRolAnuladoBloqueaFirmaYRetorno(): void
    {
        $this->db->query('CREATE TABLE casis_rol_documento (id INTEGER PRIMARY KEY, estado VARCHAR(20))');
        $this->db->table('casis_rol_documento')->insert(['id' => 12, 'estado' => 'GENERADO']);
        $id = $this->service->registrar($this->pdf, 'rol.pdf', 7, 'asistencia.roles', '12');
        $op = $this->service->iniciar($id, 7, ['asunto' => 'Prueba', 'motivo' => 'Autor', 'cargo' => 'Responsable', 'estilo' => '1']);
        $this->db->table('casis_rol_documento')->where('id', 12)->update(['estado' => 'ANULADO']);
        try { $this->service->iniciar($id, 7, ['asunto' => 'Prueba', 'motivo' => 'Autor', 'cargo' => 'Responsable', 'estilo' => '1']); self::fail('Aceptó rol anulado.'); }
        catch (DomainException $e) { self::assertNotEmpty($e->getMessage()); }
        $this->expectException(DomainException::class);
        $this->service->porToken($this->token($op));
    }

    public function testLimiteDeTamanoYVista(): void
    {
        $html = view('Modules\Firma\Views\index', ['config' => new Firma()]);
        self::assertStringContainsString('firma-opciones', $html);
        self::assertStringContainsString('assets/js/firma/documentos.js', $html);
        self::assertStringContainsString('firmaperu.min.js', $html);
        $config = new Firma();
        $config->maxBytes = 10;
        $service = new FirmaService($this->db, $config, $this->dir);
        $this->expectException(InvalidArgumentException::class);
        $service->registrar($this->pdf, 'grande.pdf', 7);
    }
}

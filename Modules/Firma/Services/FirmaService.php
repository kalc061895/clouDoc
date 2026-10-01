<?php

namespace Modules\Firma\Services;

use Modules\Firma\Config\Firma;

class FirmaService
{
    private $db;
    private Firma $config;
    private string $directory;

    public function __construct($db = null, ?Firma $config = null, ?string $directory = null)
    {
        $this->db = $db ?? db_connect();
        $this->config = $config ?? config(Firma::class);
        $this->directory = rtrim($directory ?? WRITEPATH . 'firma', '/\\') . DIRECTORY_SEPARATOR;
    }

    public function validarPdf(string $path): void
    {
        if (
            ! is_file($path) || filesize($path) < 8 || filesize($path) > $this->config->maxBytes
            || file_get_contents($path, false, null, 0, 5) !== '%PDF-'
            || (new \finfo(FILEINFO_MIME_TYPE))->file($path) !== 'application/pdf'
        ) {
            throw new \InvalidArgumentException('Seleccione un PDF válido de hasta ' . round($this->config->maxBytes / 1048576) . ' MB.');
        }
    }

    private function guardar(string $path): array
    {
        $this->validarPdf($path);
        if (! is_dir($this->directory) && ! mkdir($this->directory, 0770, true) && ! is_dir($this->directory)) {
            throw new \RuntimeException('No se pudo crear el almacén de documentos.');
        }
        $name = bin2hex(random_bytes(24)) . '.pdf';
        if (! copy($path, $this->directory . $name)) throw new \RuntimeException('No se pudo guardar el PDF.');
        return ['archivo' => $name, 'sha256' => hash_file('sha256', $this->directory . $name)];
    }

    /** API de integración interna. El módulo origen debe autorizar al usuario antes de llamarla. */
    public function registrar(string $path, string $nombre, int $usuario, string $origen = 'externo', string $referencia = ''): int
    {
        if ($usuario < 1 || mb_strlen($origen) > 80 || mb_strlen($referencia) > 100) throw new \InvalidArgumentException('Origen o usuario inválido.');
        $nombre = mb_substr(preg_replace('/[\x00-\x1F\x7F]/u', '', basename(str_replace('\\', '/', $nombre))), 0, 200);
        if ($nombre === '') throw new \InvalidArgumentException('Indique el nombre del documento.');
        $file = $this->guardar($path);
        $this->db->transBegin();
        try {
            $this->insertar('firma_documentos', [
                'nombre' => $nombre,
                'origen' => $origen,
                'referencia' => $referencia,
                'created_by' => $usuario,
                'version_actual' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $id = (int) $this->db->insertID();
            $this->insertar('firma_versiones', $file + [
                'documento_id' => $id,
                'numero' => 1,
                'estado' => 'ORIGINAL',
                'motivo' => '',
                'cargo' => '',
                'created_by' => $usuario,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $this->commit();
            return $id;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            unlink($this->directory . $file['archivo']);
            throw $e;
        }
    }

    public function documento(int $id, int $usuario): array
    {
        $doc = $this->db->table('firma_documentos')->where('id', $id)->where('created_by', $usuario)->get()->getRowArray();
        if (! $doc) throw new \OutOfBoundsException('Documento no disponible.');
        return $doc;
    }

    public function listar(int $usuario, int $pagina = 1): array
    {
        $q = $this->db->table('firma_documentos')->where('created_by', $usuario);
        $total = $q->countAllResults(false);
        return [
            'filas' => $q->orderBy('id', 'DESC')->limit(20, (max(1, $pagina) - 1) * 20)->get()->getResultArray(),
            'total' => $total,
            'paginas' => max(1, (int) ceil($total / 20))
        ];
    }

    public function versiones(int $id, int $usuario): array
    {
        $this->documento($id, $usuario);
        return $this->db->table('firma_versiones')->select('numero, estado, asunto, motivo, cargo, created_at, sha256')
            ->where('documento_id', $id)->orderBy('numero', 'DESC')->get()->getResultArray();
    }

    public function archivo(int $id, int $numero): array
    {
        $v = $this->db->table('firma_versiones')->where('documento_id', $id)->where('numero', $numero)->get()->getRowArray();
        if (! $v) throw new \OutOfBoundsException('Versión no encontrada.');
        if (! preg_match('/^[a-f0-9]{48}\.pdf$/D', $v['archivo'])) throw new \RuntimeException('Archivo inválido.');
        $path = $this->directory . $v['archivo'];
        if (! is_file($path) || ! hash_equals($v['sha256'], hash_file('sha256', $path))) throw new \RuntimeException('La integridad del archivo no coincide.');
        return $v + ['ruta' => $path];
    }

    private function validarOrigen(array $doc): void
    {
        if ($doc['origen'] === 'asistencia.roles') {
            $rol = $this->db->table('casis_rol_documento')->where('id', $doc['referencia'])->get()->getRowArray();
            if (! $rol || $rol['estado'] !== 'GENERADO') throw new \DomainException('El rol de origen fue anulado o no está disponible.');
        }
    }

    public function iniciar(int $id, int $usuario, array $input): array
    {
        $doc = $this->documento($id, $usuario);
        $this->validarOrigen($doc);
        $this->archivo($id, (int) $doc['version_actual']);
        $asunto = $input['asunto'] ?? '';
        $motivo = $input['motivo'] ?? '';
        $cargo = $input['cargo'] ?? '';
        $estilo = $input['estilo'] ?? '';
        if (
            ! is_string($asunto) || trim($asunto) === '' || mb_strlen($asunto) > 200
            || ! is_string($motivo) || trim($motivo) === '' || mb_strlen($motivo) > 200
            || ! is_string($cargo) || trim($cargo) === '' || mb_strlen($cargo) > 150 || ! in_array($estilo, [1, 2, '1', '2'], true)
        ) {
            throw new \InvalidArgumentException('Complete asunto, motivo, cargo y estilo de firma. Asunto y motivo admiten hasta 200 caracteres; cargo, hasta 150.');
        }
        $token = bin2hex(random_bytes(32));
        $this->insertar('firma_operaciones', [
            'documento_id' => $id,
            'version_base' => $doc['version_actual'],
            'token_hash' => hash('sha256', $token),
            'estado' => 'PENDIENTE',
            'asunto' => trim($asunto),
            'motivo' => trim($motivo),
            'cargo' => trim($cargo),
            'estilo' => (int) $estilo,
            'created_by' => $usuario,
            'created_at' => date('Y-m-d H:i:s'),
            'expires_at' => date('Y-m-d H:i:s', time() + $this->config->ttl)
        ]);
        return [
            'id' => (int) $this->db->insertID(),
            'port' => (string) $this->config->port,
            'param_b64' => base64_encode(json_encode([
                'param_url' => $this->config->url('firma/cliente/parametros'),
                'param_token' => $token,
                'document_extension' => 'pdf'
            ], JSON_THROW_ON_ERROR))
        ];
    }

    public function porToken(string $token): array
    {
        if (! preg_match('/^[a-f0-9]{64}$/D', $token)) throw new \OutOfBoundsException('Operación no disponible.');
        $op = $this->db->table('firma_operaciones')->where('token_hash', hash('sha256', $token))->get()->getRowArray();
        if (! $op || $op['estado'] !== 'PENDIENTE' || strtotime($op['expires_at']) <= time()) {
            throw new \OutOfBoundsException('Operación vencida, cancelada o completada.');
        }
        $doc = $this->documento((int) $op['documento_id'], (int) $op['created_by']);
        if ((int) $doc['version_actual'] !== (int) $op['version_base']) throw new \DomainException('El documento tiene una versión más reciente.');
        $this->validarOrigen($doc);
        return $op;
    }

    public function estado(int $id, int $usuario): array
    {
        $op = $this->db->table('firma_operaciones')->select('id, documento_id, estado, expires_at, completed_at')->where('id', $id)->where('created_by', $usuario)->get()->getRowArray();
        if (! $op) throw new \OutOfBoundsException('Operación no disponible.');
        if ($op['estado'] === 'PENDIENTE' && strtotime($op['expires_at']) <= time()) $op['estado'] = 'VENCIDA';
        return $op;
    }

    public function cancelar(int $id, int $usuario): void
    {
        $this->estado($id, $usuario);
        $this->db->table('firma_operaciones')->where('id', $id)->where('created_by', $usuario)->where('estado', 'PENDIENTE')->update(['estado' => 'CANCELADA']);
    }

    public function recibir(string $token, string $path): void
    {
        $op = $this->porToken($token);
        $this->validarPdf($path);
        $base = $this->archivo((int) $op['documento_id'], (int) $op['version_base']);
        $bytes = file_get_contents($path);
        // Comprobación estructural, no validación criptográfica del certificado.
        $signaturePattern = '/\/ByteRange\s*\[\s*0\s+\d+\s+\d+\s+\d+\s*\]/';
        $previousSignatures = preg_match_all($signaturePattern, file_get_contents($base['ruta']));
        if (
            hash_equals($base['sha256'], hash('sha256', $bytes)) || preg_match_all($signaturePattern, $bytes) <= $previousSignatures
            || ! preg_match('/\/Contents\s*</', $bytes)
        ) {
            throw new \InvalidArgumentException('El archivo recibido no contiene una nueva firma PDF reconocible.');
        }
        $file = $this->guardar($path);
        $this->db->transBegin();
        try {
            $this->db->table('firma_operaciones')->where('id', $op['id'])->where('estado', 'PENDIENTE')
                ->where('expires_at >', date('Y-m-d H:i:s'))->update(['estado' => 'RECIBIDA', 'completed_at' => date('Y-m-d H:i:s')]);
            if ($this->db->affectedRows() !== 1) throw new \DomainException('La operación ya no está pendiente.');
            $numero = (int) $op['version_base'] + 1;
            $this->db->table('firma_documentos')->where('id', $op['documento_id'])->where('version_actual', $op['version_base'])->update(['version_actual' => $numero]);
            if ($this->db->affectedRows() !== 1) throw new \DomainException('Otra firma actualizó el documento. Inicie nuevamente.');
            $this->insertar('firma_versiones', $file + [
                'documento_id' => $op['documento_id'],
                'numero' => $numero,
                'estado' => 'FIRMA_RECIBIDA',
                'asunto' => $op['asunto'],
                'motivo' => $op['motivo'],
                'cargo' => $op['cargo'],
                'created_by' => $op['created_by'],
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $this->commit();
        } catch (\Throwable $e) {
            $this->db->transRollback();
            unlink($this->directory . $file['archivo']);
            throw $e;
        }
    }

    private function insertar(string $table, array $data): void
    {
        if (! $this->db->table($table)->insert($data)) throw new \RuntimeException('No se pudo registrar la operación.');
    }

    private function commit(): void
    {
        if (! $this->db->transStatus() || ! $this->db->transCommit()) throw new \RuntimeException('No se pudo completar la transacción.');
    }
}

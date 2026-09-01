<?php

namespace App\Core\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\Files\UploadedFile;

abstract class BaseModuleController extends BaseController
{
    /**
     * Helper para responder en formato JSON estandarizado
     *
     * @param string $status 'success' | 'error' | 'warning' | 'info'
     * @param string $message Mensaje descriptivo para el usuario
     * @param mixed $data Datos adicionales o payload
     * @param int $httpCode Código de estado HTTP
     * @return ResponseInterface
     */
    protected function jsonResponse(string $status, string $message, $data = [], int $httpCode = 200): ResponseInterface
    {
        return $this->response
            ->setStatusCode($httpCode)
            ->setJSON([
                'status'  => $status,
                'message' => $message,
                'data'    => $data,
                'time'    => date('Y-m-d H:i:s'),
            ]);
    }

    /**
     * Valida si la petición es estrictamente AJAX
     *
     * @return bool
     */
    protected function isAjax(): bool
    {
        return $this->request->isAJAX();
    }

    /**
     * Obtiene el ID del usuario autenticado para fines de auditoría
     *
     * @return int|null
     */
    protected function getAuditUserId(): ?int
    {
        if (function_exists('auth') && auth()->loggedIn()) {
            return (int) auth()->id();
        }

        return session()->get('user_id') ?? session()->get('usu_ide') ?? 1;
    }

    /**
     * Procesa y almacena de forma segura un archivo PDF o documento digital
     *
     * @param UploadedFile|null $file Objeto UploadedFile desde $this->request->getFile(...)
     * @param string $subfolder Carpeta de destino relativa a uploads/
     * @param array $allowedMimes Tipos MIME permitidos
     * @param int $maxSizeKb Tamaño máximo en Kilobytes (default 15MB)
     * @return array|null Retorna los metadatos del archivo guardado o null si no se subió archivo
     * @throws \RuntimeException Si la validación falla
     */
    protected function uploadDocument(?UploadedFile $file, string $subfolder = 'legajos/documentos', array $allowedMimes = ['application/pdf', 'image/jpeg', 'image/png', 'image/webp'], int $maxSizeKb = 15360): ?array
    {
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return null;
        }

        // Validar tamaño
        $fileSizeKb = $file->getSizeByUnit('kb');
        if ($fileSizeKb > $maxSizeKb) {
            throw new \RuntimeException("El archivo excede el tamaño máximo permitido de " . round($maxSizeKb / 1024, 1) . " MB.");
        }

        // Validar tipo MIME
        $mimeType = $file->getMimeType();
        if (!empty($allowedMimes) && !in_array($mimeType, $allowedMimes, true)) {
            throw new \RuntimeException("El tipo de archivo ({$mimeType}) no está permitido. Solo se aceptan documentos PDF e imágenes.");
        }

        $targetDir = FCPATH . 'uploads/' . trim($subfolder, '/') . '/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $originalName = $file->getClientName();
        $randomName   = $file->getRandomName();

        $file->move($targetDir, $randomName);

        return [
            'nombre_original' => $originalName,
            'nombre_guardado' => $randomName,
            'ruta_relativa'   => 'uploads/' . trim($subfolder, '/') . '/' . $randomName,
            'ruta_absoluta'   => $targetDir . $randomName,
            'mime_type'       => $mimeType,
            'tamano_kb'       => (int) $fileSizeKb,
            'extension'       => $file->getClientExtension(),
        ];
    }
}


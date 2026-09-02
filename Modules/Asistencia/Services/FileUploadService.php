<?php

namespace Modules\Asistencia\Services;

use CodeIgniter\HTTP\Files\UploadedFile;
use Modules\Asistencia\Models\AdjuntoModel;
use App\Models\EmpresaConfiguracionModel;
use App\Libraries\GoogleDrive;

use CodeIgniter\HTTP\DownloadResponse;
use CodeIgniter\HTTP\ResponseInterface;

class FileUploadService
{
    protected $adjuntoModel;

    public function __construct()
    {
        $this->adjuntoModel = new AdjuntoModel();
    }

    /**
     * Procesa y guarda un arreglo o un solo archivo asociado a cualquier módulo
     * 
     * @param UploadedFile|UploadedFile[]|null $archivos Archivo(s) del $request->getFile() o getFiles()
     * @param string $modulo Identificador (LICENCIA, VACACION, PERMISO, etc.)
     * @param int $registroId ID del registro principal al que pertenece
     * @param string $subfolder Carpeta destino dentro de writable/uploads/
     * @param int|null $usuarioId
     * @return array Array de registros de adjuntos insertados
     */
    public function procesarYGuardar($archivos, string $modulo, int $registroId, string $subfolder = 'asistencia/licencias', ?int $usuarioId = null): array
    {
        if (empty($archivos)) {
            return [];
        }

        // Si es un solo archivo, normalizarlo a un array
        if ($archivos instanceof UploadedFile) {
            $archivos = [$archivos];
        }

        $usuarioId = $usuarioId ?? session()->get('user_id') ?? session()->get('usu_ide') ?? 1;
        $adjuntosGuardados = [];

        // Obtener el orden actual más alto para este registro
        $ordenActual = $this->adjuntoModel->where('adj_modulo', $modulo)
            ->where('adj_registro_id', $registroId)
            ->countAllResults();

        foreach ($archivos as $file) {
            if (!$file->isValid() || $file->hasMoved()) {
                continue; // Omitir archivos inválidos o no subidos
            }

            $newName = $file->getRandomName();
            $driveId = '-';
            /*
            // 1. Integración opcional con Google Drive
            if (class_exists('\App\Models\EmpresaConfiguracionModel')) {
                $configModel = new EmpresaConfiguracionModel();
                if (method_exists($configModel, 'getDriveConfig') && $configModel->getDriveConfig()) {
                    $googleDrive = new GoogleDrive();
                    $folderId = $configModel->getConfig('google_drive_folder');
                    if ($folderId) {
                        $driveId = $googleDrive->uploadFile($file->getTempName(), $newName, $folderId);
                    }
                }
            }
            */

            // 2. Mover a servidor local
            $targetPath = WRITEPATH . 'uploads/' . trim($subfolder, '/');
            $file->move($targetPath, $newName);

            $ordenActual++;

            // 3. Registrar en la tabla casis_adjuntos
            $dataAdjunto = [
                'adj_modulo'          => strtoupper($modulo),
                'adj_registro_id'     => $registroId,
                'adj_local_path'      => 'uploads/' . trim($subfolder, '/') . '/' . $newName,
                'adj_drive_path'      => $driveId,
                'adj_nombre_original' => $file->getClientName(),
                'adj_mime_type'       => $file->getClientMimeType(),
                'adj_tamano'          => $file->getSize(),
                'adj_orden'          => $ordenActual,
                'created_by'          => $usuarioId,
            ];

            $this->adjuntoModel->insert($dataAdjunto);
            $dataAdjunto['adj_ide'] = $this->adjuntoModel->getInsertID();

            $adjuntosGuardados[] = $dataAdjunto;
        }

        return $adjuntosGuardados;
    }

    /**
     * Eliminar físicamente y de BD un adjunto por su ID
     */

    public function eliminarAdjunto(int $adjIde): bool
    {
        $adjunto = $this->adjuntoModel->find($adjIde);
        if (!$adjunto) {
            return false;
        }

        // Borrar archivo físico si existe
        $filePath = WRITEPATH . $adjunto['adj_local_path'];
        if (file_exists($filePath)) {
            @unlink($filePath);
        }

        return $this->adjuntoModel->delete($adjIde);
    }

    public function subirArchivo($file, string $modulo): ?array
    {
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return null;
        }

        $moduloFolder = strtolower($modulo);
        $subfolder    = date('Y/m/');
        $uploadPath   = WRITEPATH . "uploads/{$moduloFolder}/{$subfolder}";

        $newName        = $file->getRandomName();
        $nombreOriginal = $file->getClientName();
        $mimeType       = $file->getClientMimeType();
        $tamano         = $file->getSize();

        // Mover archivo
        $file->move($uploadPath, $newName);

        return [
            'adj_modulo'          => strtoupper($modulo),
            'adj_local_path'      => "uploads/{$moduloFolder}/{$subfolder}{$newName}",
            'adj_drive_path'      => '-',
            'adj_nombre_original' => $nombreOriginal,
            'adj_mime_type'       => $mimeType,
            'adj_tamano'          => $tamano,
        ];
    }
    /**
     * Sirve el archivo desde writable al navegador (Inline o Descarga)
     */
    public function obtenerRespuestaArchivo(string $relativePath, string $nombreOriginal, ?string $mimeType = null): ResponseInterface
    {
        $filePath = WRITEPATH . $relativePath;

        if (!file_exists($filePath)) {
            return response()->setStatusCode(404, 'El archivo físico no fue encontrado.');
        }

        $mime = $mimeType ?? mime_content_type($filePath);

        return response()
            ->setHeader('Content-Type', $mime)
            ->setHeader('Content-Disposition', 'inline; filename="' . $nombreOriginal . '"')
            ->setBody(file_get_contents($filePath));
    }
}

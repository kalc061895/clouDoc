<?php

namespace Modules\Seleccion\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use Modules\Asistencia\Models\AdjuntoModel;
use Modules\Asistencia\Services\FileUploadService;
use CodeIgniter\API\ResponseTrait;


class AdjuntoController extends BaseController
{
    use ResponseTrait;

    protected $adjuntoModel;
    protected $fileUploadService;

    public function __construct()
    {
        $this->adjuntoModel      = new AdjuntoModel();
        $this->fileUploadService = new FileUploadService();
    }

    /**
     * GET: /api/v1/adjuntos/ver/(:num)
     * Ver/Descargar cualquier adjunto del sistema por su ID
     */
    public function ver($adjIde)
    {
        // 1. Validar autenticación
        if (!auth()->loggedIn()) {
            return $this->failUnauthorized('No tiene autorización.');
        }

        // 2. Buscar metadata en la BD
        $adjunto = $this->adjuntoModel->find($adjIde);

        if (!$adjunto) {
            return $this->failNotFound('El registro del archivo no existe.');
        }

        // 3. Delegar la respuesta HTTP al servicio de archivos
        return $this->fileUploadService->obtenerRespuestaArchivo(
            $adjunto['adj_local_path'],
            $adjunto['adj_nombre_original'],
            $adjunto['adj_mime_type']
        );
    }
    public function verDocumento()
    {
        // Obtener desde el parámetro GET
        $pathBase64 = $this->request->getGet('path');

        if (empty($pathBase64)) {
            return $this->response->setStatusCode(400, 'Ruta no proporcionada');
        }

        // Decodificar Base64
        $pathRelativo = base64_decode(rawurldecode($pathBase64));

        // Sanitizar y validar
        $pathRelativo = str_replace(['..', '\\'], '', $pathRelativo);

        if (!str_starts_with($pathRelativo, 'uploads/')) {
            return $this->response->setStatusCode(403, 'Acceso denegado');
        }

        $nombreOriginal = basename($pathRelativo);
        $fileService = new FileUploadService();

        return $fileService->obtenerRespuestaArchivo($pathRelativo, $nombreOriginal);
    }
}

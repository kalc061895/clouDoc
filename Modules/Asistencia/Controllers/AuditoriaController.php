<?php

namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Asistencia\Services\AccesoAsistenciaService;
use Modules\Asistencia\Services\AuditoriaAsistenciaService;

class AuditoriaController extends BaseModuleController
{
    public function index()
    {
        if (!(new AccesoAsistenciaService())->permitir('lectura_auditoria')) return $this->response->setStatusCode(403)->setBody('No tiene acceso a la auditoría de Asistencia.');
        try {
            return view('Modules\\Asistencia\\Views\\auditoria\\index', (new AuditoriaAsistenciaService())->resumen());
        } catch (\Throwable $e) {
            log_message('error', 'Auditoría de Asistencia: {error}', ['error' => $e->getMessage()]);
            return $this->response->setStatusCode(500)->setBody('No se pudieron cargar los registros de auditoría. Verifique que las migraciones estén aplicadas.');
        }
    }
}

<?php

namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Asistencia\Services\PendientesProgramacionRolService;
use Modules\Asistencia\Services\PendientesProgramacionRolExcelService;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PendientesProgramacionRolController extends BaseModuleController
{
    public function index()
    {
        $user = auth()->user();
        if (!$user || !$user->inGroup('superadmin', 'asistencia', 'asi_sua', 'asi_adm', 'asi_dm', 'asi_rol', 'asi_diresa', 'asi_red', 'asi_mred', 'asi_est', 'asi_diresa_apoyo', 'asi_red_apoyo', 'asi_mred_apoyo', 'asi_est_apoyo')) {
            return $this->response->setStatusCode(403)->setBody('No tiene permiso para consultar la programación pendiente.');
        }
        try {
            $periodoId = $this->request->getGet('periodo');
            $data = (new PendientesProgramacionRolService())->reporte($periodoId !== null && ctype_digit((string) $periodoId) ? (int) $periodoId : null);
            return view('Modules\\Asistencia\\Views\\gestion_roles\\pendientes', $data + [
                'admin' => $user->inGroup('superadmin', 'asistencia', 'asi_sua', 'asi_adm', 'asi_dm'),
            ]);
        } catch (\DomainException $e) {
            return $this->response->setStatusCode(403)->setBody(esc($e->getMessage()));
        }
    }

    public function excel()
    {
        $user = auth()->user();
        if (!$user || !$user->inGroup('superadmin', 'asistencia', 'asi_sua', 'asi_adm', 'asi_dm', 'asi_rol', 'asi_diresa', 'asi_red', 'asi_mred', 'asi_est', 'asi_diresa_apoyo', 'asi_red_apoyo', 'asi_mred_apoyo', 'asi_est_apoyo')) {
            return $this->response->setStatusCode(403)->setBody('No tiene permiso para exportar la programacion pendiente.');
        }
        $periodoId = (int) $this->request->getGet('periodo');
        if ($periodoId < 1) return $this->response->setStatusCode(422)->setBody('Seleccione un periodo valido.');
        try {
            $libro = (new PendientesProgramacionRolExcelService())->generar($periodoId, trim((string) $this->request->getGet('busqueda')));
            ob_start();
            (new Xlsx($libro))->save('php://output');
            $contenido = ob_get_clean();
            $nombre = $libro->getProperties()->getTitle() . '.xlsx';
            return $this->response->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $nombre . '"')
                ->setHeader('Cache-Control', 'max-age=0, no-cache, must-revalidate')
                ->setBody($contenido);
        } catch (\DomainException $e) {
            return $this->response->setStatusCode(403)->setBody(esc($e->getMessage()));
        } catch (\Throwable $e) {
            log_message('error', 'Exportacion de programacion pendiente: {message}', ['message' => $e->getMessage()]);
            return $this->response->setStatusCode(500)->setBody('No se pudo generar el archivo Excel.');
        }
    }
}

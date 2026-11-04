<?php

namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Asistencia\Services\RegistroVacacionService;
use Modules\Asistencia\Services\AccesoAsistenciaService;

class VacacionController extends BaseModuleController
{
    public function getPaneVacaciones(int $id)
    {
        $acceso = new AccesoAsistenciaService();
        if (!$acceso->permitir('lectura_personal') || !$acceso->personalDentroAmbito($id)) return $this->response->setStatusCode(404)->setBody('Trabajador no encontrado.');
        try {
            return view('Modules\Asistencia\Views\personal\modals\pane_vacaciones_view', (new RegistroVacacionService())->obtenerVacacionesPorPersonal($id));
        } catch (\DomainException $e) {
            return $this->response->setStatusCode(404)->setBody(esc($e->getMessage()));
        }
    }

    public function getByPersonal(int $id)
    {
        $acceso = new AccesoAsistenciaService();
        if (!$acceso->permitir('lectura_personal') || !$acceso->personalDentroAmbito($id)) return $this->jsonResponse('error', 'Trabajador no encontrado en su ámbito.', [], 404);
        try {
            return $this->jsonResponse('success', 'Vacaciones consultadas.', (new RegistroVacacionService())->obtenerVacacionesPorPersonal($id));
        } catch (\DomainException $e) {
            return $this->jsonResponse('error', $e->getMessage(), [], 404);
        }
    }

    public function registrarUso(int $id)
    {
        $acceso = new AccesoAsistenciaService();
        if (!$acceso->permitir('crear_operacion') || !$acceso->personalDentroAmbito($id)) return $this->jsonResponse('error', 'No tiene permiso sobre este trabajador.', [], 403);
        if (!$this->validate([
            'periodo' => 'required|valid_date[Y-m-d]', 'fecha_inicio' => 'required|valid_date[Y-m-d]',
            'fecha_fin' => 'required|valid_date[Y-m-d]', 'documento' => 'permit_empty|max_length[100]',
            'observacion' => 'permit_empty|max_length[2000]',
        ])) return $this->jsonResponse('error', implode(' ', $this->validator->getErrors()), [], 422);
        try {
            (new RegistroVacacionService())->registrarUso($id, $this->request->getPost(), (int) $this->getAuditUserId(), $this->request->getFile('sustento'));
            return $this->jsonResponse('success', 'Uso registrado y saldo actualizado.');
        } catch (\DomainException $e) {
            return $this->jsonResponse('error', $e->getMessage(), [], 422);
        } catch (\Throwable $e) {
            log_message('error', 'Registro de vacaciones: {error}', ['error' => $e->getMessage()]);
            return $this->jsonResponse('error', 'No se pudo guardar el uso. No se modificaron los saldos.', [], 500);
        }
    }

    public function eliminarUso(int $id, int $usoId)
    {
        $acceso = new AccesoAsistenciaService();
        if (!$acceso->permitir('eliminar_operacion') || !$acceso->personalDentroAmbito($id)) return $this->jsonResponse('error', 'No tiene permiso sobre este trabajador.', [], 403);
        try {
            (new RegistroVacacionService())->eliminarUso($id, $usoId, (string) $this->request->getPost('motivo'), (int) $this->getAuditUserId());
            return $this->jsonResponse('success', 'Uso eliminado y días devueltos al saldo.');
        } catch (\DomainException $e) {
            return $this->jsonResponse('error', $e->getMessage(), [], 422);
        } catch (\Throwable $e) {
            log_message('error', 'Eliminar uso de vacaciones: {error}', ['error' => $e->getMessage()]);
            return $this->jsonResponse('error', 'No se pudo eliminar el uso. No se modificó el saldo.', [], 500);
        }
    }
}

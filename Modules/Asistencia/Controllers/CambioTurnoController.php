<?php

namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Asistencia\Services\CalendarioPersonalService;
use Modules\Asistencia\Services\CambioTurnoService;

class CambioTurnoController extends BaseModuleController
{
    public function pane(int $personalId)
    {
        try {
            [$inicio, $fin] = (new CalendarioPersonalService())->periodo($this->request->getGet('fecha_inicio'));
            $service = new CambioTurnoService();
            return view('Modules\Asistencia\Views\personal\modals\pane_cambio_turno_view', [
                'personal' => $service->personal($personalId),
                'companeros' => $service->companeros($personalId),
                'cambios' => $service->historial($personalId, $inicio, $fin),
                'inicio' => $inicio,
            ]);
        } catch (\InvalidArgumentException | \DomainException $e) {
            return $this->response->setStatusCode(400)->setBody(esc($e->getMessage()));
        }
    }

    public function turnos(int $personalId)
    {
        try {
            $service = new CambioTurnoService();
            $otroId = (int) $this->request->getGet('personal_id');
            if ($otroId !== $personalId) {
                $service::validarPersonal($service->personal($personalId), $service->personal($otroId));
            }
            $turnos = $service->turnos($otroId, (string) $this->request->getGet('fecha'));
            return $this->jsonResponse('success', '', $turnos);
        } catch (\InvalidArgumentException | \DomainException $e) {
            return $this->jsonResponse('error', $e->getMessage(), [], 422);
        }
    }

    public function eliminar(int $personalId, int $cambioId)
    {
        try {
            (new CambioTurnoService())->eliminar($cambioId, $personalId, (string) $this->request->getPost('motivo_cambio'), (int) $this->getAuditUserId());
            return $this->jsonResponse('success', 'Cambio eliminado. Ambos turnos fueron restablecidos.');
        } catch (\DomainException $e) {
            return $this->jsonResponse('error', $e->getMessage(), [], 422);
        } catch (\Throwable $e) {
            log_message('error', 'Error al restablecer cambio de turno: {error}', ['error' => $e->getMessage()]);
            return $this->jsonResponse('error', 'No se pudo eliminar el cambio. Se conservaron ambos turnos.', [], 500);
        }
    }

    public function guardar(int $personalId)
    {
        if (!$this->validate([
            'otro_personal_id' => 'required|is_natural_no_zero',
            'prog_sol_id' => 'required|is_natural_no_zero',
            'prog_ace_id' => 'required|is_natural_no_zero',
            'version_sol' => 'required|exact_length[64]|alpha_numeric',
            'version_ace' => 'required|exact_length[64]|alpha_numeric',
            'motivo' => 'required|max_length[2000]',
        ])) {
            return $this->jsonResponse('error', implode(' ', $this->validator->getErrors()), [], 422);
        }
        try {
            $datos = $this->request->getPost();
            $datos['personal_id'] = $personalId;
            $id = (new CambioTurnoService())->registrar($datos, $this->request->getFile('sustento'), (int) $this->getAuditUserId());
            return $this->jsonResponse('success', 'Cambio registrado. Ambos turnos fueron reprogramados.', ['id' => $id], 201);
        } catch (\DomainException $e) {
            return $this->jsonResponse('error', $e->getMessage(), [], 422);
        } catch (\Throwable $e) {
            log_message('error', 'Error al registrar cambio de turno: {error}', ['error' => $e->getMessage()]);
            return $this->jsonResponse('error', 'No se pudo guardar el cambio. Se revirtieron las modificaciones.', [], 500);
        }
    }
}

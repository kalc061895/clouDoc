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
            $mes = $this->request->getGet('mes');
            return view('Modules\Asistencia\Views\personal\modals\pane_vacaciones_view', (new RegistroVacacionService())->obtenerVacacionesPorPersonal($id) + [
                'mes' => is_string($mes) && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/D', $mes) ? $mes : '',
                'paginaCompleta' => !$this->request->isAJAX(),
                'puedeCrear' => $acceso->permitir('crear_operacion'),
                'puedeEditar' => $acceso->permitir('editar_operacion'),
                'puedeEliminar' => $acceso->permitir('eliminar_operacion'),
            ]);
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
        return $this->guardarUso($id, null);
    }

    public function editarUso(int $id, int $usoId)
    {
        return $this->guardarUso($id, $usoId);
    }

    private function guardarUso(int $id, ?int $usoId)
    {
        $this->response->setHeader('X-Vacacion-CSRF', csrf_hash());
        $acceso = new AccesoAsistenciaService();
        if (!$acceso->permitir($usoId === null ? 'crear_operacion' : 'editar_operacion') || !$acceso->personalDentroAmbito($id)) return $this->jsonResponse('error', 'No tiene permiso sobre este trabajador.', [], 403);
        $datos = $this->request->getPost(['periodo', 'fecha_inicio', 'fecha_fin', 'documento', 'observacion']);
        if (!$this->validateData($datos, [
            'periodo' => 'required|valid_date[Y-m-d]', 'fecha_inicio' => 'required|valid_date[Y-m-d]',
            'fecha_fin' => 'required|valid_date[Y-m-d]', 'documento' => 'permit_empty|max_length[100]',
            'observacion' => 'permit_empty|max_length[2000]',
        ])) return $this->jsonResponse('error', implode(' ', $this->validator->getErrors()), [], 422);
        try {
            $servicio = new RegistroVacacionService();
            $usuario = (int) $this->getAuditUserId();
            $archivo = $this->request->getFile('sustento');
            if ($usoId === null) $servicio->registrarUso($id, $datos, $usuario, $archivo);
            else $servicio->editarUso($id, $usoId, $datos, $usuario, $archivo);
            return $this->jsonResponse('success', $usoId === null ? 'Uso registrado y saldo actualizado.' : 'Uso actualizado y saldo recalculado.');
        } catch (\DomainException $e) {
            return $this->jsonResponse('error', $e->getMessage(), [], 422);
        } catch (\Throwable $e) {
            log_message('error', 'Registro de vacaciones: {error}', ['error' => $e->getMessage()]);
            return $this->jsonResponse('error', 'No se pudo guardar el uso. No se modificaron los saldos.', [], 500);
        }
    }

    public function eliminarUso(int $id, int $usoId)
    {
        $this->response->setHeader('X-Vacacion-CSRF', csrf_hash());
        $acceso = new AccesoAsistenciaService();
        if (!$acceso->permitir('eliminar_operacion') || !$acceso->personalDentroAmbito($id)) return $this->jsonResponse('error', 'No tiene permiso sobre este trabajador.', [], 403);
        try {
            $motivo = $this->request->getPost('motivo');
            (new RegistroVacacionService())->eliminarUso($id, $usoId, is_string($motivo) ? $motivo : '', (int) $this->getAuditUserId());
            return $this->jsonResponse('success', 'Uso eliminado y días devueltos al saldo.');
        } catch (\DomainException $e) {
            return $this->jsonResponse('error', $e->getMessage(), [], 422);
        } catch (\Throwable $e) {
            log_message('error', 'Eliminar uso de vacaciones: {error}', ['error' => $e->getMessage()]);
            return $this->jsonResponse('error', 'No se pudo eliminar el uso. No se modificó el saldo.', [], 500);
        }
    }
}

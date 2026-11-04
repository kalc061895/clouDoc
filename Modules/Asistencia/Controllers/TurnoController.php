<?php

namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Asistencia\Services\TurnoService;
use Modules\Asistencia\Services\TurnoHorarioService;
use Modules\Asistencia\Services\AccesoAsistenciaService;

class TurnoController extends BaseModuleController
{
    protected $turnoService;

    public function __construct()
    {
        $this->turnoService = new TurnoService();
    }

    /**
     * Carga la vista parcial (pane) del tab "Turnos" en el Gestor de Personal.
     * Muestra la programación de turnos del mes actual del trabajador.
     */
    public function getPaneTurnos($personalId)
    {
        $acceso = new AccesoAsistenciaService();
        if (!$acceso->permitir('lectura_personal') || !$acceso->personalDentroAmbito((int) $personalId)) return $this->response->setStatusCode(404)->setBody('Trabajador no encontrado.');
        $calendario = new \Modules\Asistencia\Services\CalendarioPersonalService();
        try {
            [$fechaInicio, $fechaFin] = $calendario->periodo($this->request->getGet('fecha_inicio'));
        } catch (\InvalidArgumentException $e) {
            return $this->response->setStatusCode(400)->setBody($e->getMessage());
        }

        $turnos = $this->turnoService->getTurnosProgramados(
            (int) $personalId,
            $fechaInicio,
            $fechaFin
        );

        return view('Modules\Asistencia\Views\personal\modals\pane_turnos_view', [
            'personal_id' => (int) $personalId,
            'turnos'      => $turnos,
            'dias' => $calendario->dias((int) $personalId, $fechaInicio, $fechaFin, $turnos['dias']),
        ]);
    }

    /**
     * API: Devuelve JSON con los turnos programados de un trabajador.
     * Consumido por los scripts JS internos de los panes.
     */
    public function getByPersonal($personalId)
    {
        $acceso = new AccesoAsistenciaService();
        if (!$acceso->permitir('lectura_personal') || !$acceso->personalDentroAmbito((int) $personalId)) return $this->jsonResponse('error', 'Trabajador no encontrado en su ámbito.', [], 404);
        $fechaInicio = $this->request->getGet('fecha_inicio');
        $fechaFin    = $this->request->getGet('fecha_fin');

        $turnos = $this->turnoService->getTurnosProgramados(
            (int) $personalId,
            $fechaInicio,
            $fechaFin
        );

        return $this->jsonResponse('success', 'Turnos programados cargados.', $turnos);
    }
}

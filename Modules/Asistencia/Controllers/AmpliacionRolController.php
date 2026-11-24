<?php

namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Asistencia\Services\AmpliacionRolService;

class AmpliacionRolController extends BaseModuleController
{
    private const BASE = 'asistencia/gestion-roles/';

    public function index(int $envioId)
    {
        try {
            return view('Modules\Asistencia\Views\gestion_roles\ampliaciones', (new AmpliacionRolService())->paraEnvio($envioId), ['saveData' => false]);
        } catch (\Throwable $e) {
            return redirect()->to(site_url(self::BASE . 'envios'))->with('error', $this->mensaje($e));
        }
    }

    public function detalle(int $id)
    {
        try {
            return view('Modules\Asistencia\Views\gestion_roles\ampliacion_detalle', (new AmpliacionRolService())->detalle($id), ['saveData' => false]);
        } catch (\Throwable $e) {
            return redirect()->to(site_url(self::BASE . 'envios'))->with('error', $this->mensaje($e));
        }
    }

    public function solicitar(int $envioId)
    {
        try {
            $personal = $this->request->getPost('personal');
            $motivo = $this->request->getPost('motivo');
            $fechas = $this->request->getPost('fecha');
            $horarios = $this->request->getPost('horario');
            $personalId = is_string($personal) ? filter_var($personal, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) : false;
            if (!$personalId || !is_string($motivo) || !is_array($fechas) || !is_array($horarios)
                || !array_is_list($fechas) || !array_is_list($horarios) || count($fechas) !== count($horarios)
                || count($fechas) > AmpliacionRolService::MAX_TURNOS) {
                throw new \InvalidArgumentException('Seleccione un trabajador y registre las fechas y horarios de sus turnos.');
            }
            $turnos = array_map(static function ($fecha, $horario): array {
                if (!is_string($fecha) || !is_string($horario)) {
                    throw new \InvalidArgumentException('El formato de los turnos es inválido.');
                }
                return ['fecha' => $fecha, 'horario' => $horario];
            }, $fechas, $horarios);
            $id = (new AmpliacionRolService())->solicitar($envioId, $personalId, $motivo, $turnos);
            return redirect()->to(site_url(self::BASE . 'ampliaciones/' . $id))
                ->with('success', 'La ampliación se envió a Dirección.');
        } catch (\Throwable $e) {
            return redirect()->to(site_url(self::BASE . 'envios/' . $envioId . '/ampliaciones'))
                ->withInput()->with('error', $this->mensaje($e));
        }
    }

    public function resolver(int $id)
    {
        $destino = site_url(self::BASE . 'ampliaciones/' . $id);
        try {
            $accion = $this->request->getPost('accion');
            $comentario = $this->request->getPost('comentario');
            if (!is_string($accion) || !is_string($comentario)) {
                throw new \InvalidArgumentException('La decisión o el comentario tienen un formato inválido.');
            }
            (new AmpliacionRolService())->resolver($id, $accion, $comentario);
            $mensaje = $accion === 'APROBAR' ? 'Ampliación aprobada. Los turnos ya están programados.' : 'Ampliación rechazada.';
            return redirect()->to($destino)->with('success', $mensaje);
        } catch (\Throwable $e) {
            return redirect()->to($destino)->withInput()->with('error', $this->mensaje($e));
        }
    }

    private function mensaje(\Throwable $e): string
    {
        if ($e instanceof \DomainException || $e instanceof \InvalidArgumentException || $e instanceof \OutOfBoundsException) {
            return $e->getMessage();
        }
        log_message('error', 'Ampliación de rol: {message}', ['message' => $e->getMessage()]);
        return 'No se pudo completar la operación de ampliación.';
    }
}

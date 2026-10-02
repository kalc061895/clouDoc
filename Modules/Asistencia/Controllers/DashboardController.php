<?php

namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Asistencia\Services\DashboardService;

class DashboardController extends BaseModuleController
{
    public function index()
    {
        $service = new DashboardService();
        $data = ['dashboard' => null, 'error' => null, 'establecimientos' => [], 'fecha' => '', 'est' => ''];
        try {
            $data['establecimientos'] = $service->establecimientos();
            $data['dashboard'] = $service->consultar($this->request->getGet());
            $data['fecha'] = $data['dashboard']['fecha'];
            $data['est'] = $data['dashboard']['est'];
        } catch (\InvalidArgumentException $e) {
            $data['error'] = $e->getMessage();
            $this->response->setStatusCode(422);
        } catch (\Throwable $e) {
            log_message('error', 'Dashboard asistencia: {message}', ['message' => $e->getMessage()]);
            $data['error'] = 'No se pudo consultar el dashboard. Revise la conexión y las migraciones de Asistencia.';
            $this->response->setStatusCode(500);
        }
        return view('Modules\Asistencia\Views\dashboard\index', $data);
    }
}

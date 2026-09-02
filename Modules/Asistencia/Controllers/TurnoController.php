<?php

namespace Modules\Asistencia\Controllers;

use App\Controllers\BaseController;
use Modules\Asistencia\Services\TurnoService;
use Modules\Asistencia\Services\TurnoHorarioService;

use Codeigniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

class TurnoController extends BaseController
{

    use ResponseTrait;

    protected $turnoService;

    public function __construct()
    {
        $this->turnoService = new TurnoService();
    }

    public function getPaneTurnos($personalId)
    {
        $data['personal_id'] = $personalId;
        return view('Modules\Asistencia\personal\modals\pane_turnos_view', $data);
    }

    public function getByPersonal($personalId)
    {
        $turnos = $this->turnoService->getTurnosProgramados($personalId);
        return $this->respond([
            'status' => 200,
            'data' => $turnos
        ]);
    }

}

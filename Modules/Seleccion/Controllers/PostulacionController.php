<?php

namespace Modules\Seleccion\Controllers;

use App\Controllers\BaseController;
use Modules\Seleccion\Services\PostulacionService;
use Modules\Seleccion\Models\ConvocatoriaCargoModel;
use Modules\Seleccion\Models\ConvocatoriaDocumentoModel;



class PostulacionController extends BaseController
{
    protected PostulacionService $postulacionService;

    public function __construct()
    {
        $this->postulacionService = new PostulacionService();
    }

    public function index()
    {
        // Obtiene el pos_ide desde la sesión del postulante autenticado
        $postulanteId = session()->get('pos_ide') ?? session()->get('user_id');

        $data = [
            'titulo'                 => 'Mis Postulaciones',
            'misPostulaciones'       => $this->postulacionService->obtenerMisPostulaciones((int)$postulanteId),
            'convocatoriasAbiertas' => $this->postulacionService->obtenerConvocatoriasDisponibles((int)$postulanteId),
        ];

        return view('Modules\Seleccion\Views\postulante\index', $data);
    }

    public function iniciar(int $convocatoriaId)
    {
        $data = [
            'titulo'         => 'Registro de Postulación CAS',
            'convocatoriaId' => $convocatoriaId,
        ];

        return view('Modules\Seleccion\Views\postulante\iniciar', $data);
    }

}

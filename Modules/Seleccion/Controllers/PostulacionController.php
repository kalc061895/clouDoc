<?php

namespace Modules\Seleccion\Controllers;

use App\Controllers\BaseController;
use Modules\Seleccion\Models\PostulacionModel;
use Modules\Seleccion\Models\ConvocatoriaModel;
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
        $postulanteId = auth()->id() ?? session()->get('user_id');

        $data = [
            'titulo'                 => 'Mis Postulaciones',
            'misPostulaciones'       => $this->postulacionService->obtenerMisPostulaciones((int)$postulanteId),
            'convocatoriasAbiertas' => $this->postulacionService->obtenerConvocatoriasDisponibles((int)$postulanteId),
        ];

        return view('Modules\Seleccion\Views\postulante\index', $data);
    }

    public function iniciar(int $convocatoriaId)
    {
        $postulacion = new PostulacionModel();
        $convocatoria = new ConvocatoriaModel();

        $postulacionRegistro =   $postulacion
            ->join('selec_postulantes', 'selec_postulantes.pos_ide = selec_postulaciones.pto_pos_ide', 'left')
            ->join('selec_estados_postulacion', 'selec_estados_postulacion.epo_ide = selec_postulaciones.pto_epo_ide', 'left')
            ->join('selec_convocatoria_cargos', 'selec_convocatoria_cargos.cco_ide = selec_postulaciones.pto_cco_ide', 'left')
            ->join('selec_convocatorias', 'selec_convocatorias.con_ide = selec_convocatoria_cargos.cco_con_ide', 'left')
            ->where('pos_user_id', auth()->id() ?? session()->get('id') ?? session()->get('user_id'))
            ->where('con_ide', $convocatoriaId)
            ->first();
        $convocatoriaInfo = $convocatoria->where('con_ide', $convocatoriaId)
            ->first();
        $data = [
            'titulo'         => 'Registro de Postulación CAS',
            'convocatoriaId' => $convocatoriaId,
            'postulacion' => $postulacionRegistro,
            'convocatoria' => $convocatoriaInfo,
        ];

        return view('Modules\Seleccion\Views\postulante\iniciar', $data);
    }

}

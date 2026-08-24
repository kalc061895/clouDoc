<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\PostulanteModel;
use Modules\Seleccion\Models\PostulacionModel;
use Modules\Seleccion\Models\ConvocatoriaModel;
use Modules\Seleccion\Models\PostulanteFormacionModel;
use Modules\Seleccion\Services\PostulanteProfesionService;
use Modules\Seleccion\Models\PostulanteProfesionModel;
use Modules\Seleccion\Models\PostulanteCapacitacionModel;
use Modules\Seleccion\Models\PostulanteExperienciaModel;
use Modules\Seleccion\Models\PostulantesOtroModel;


class GenerarDocumentoService
{
    protected $postulanteModel;

    public function __construct()
    {
        $this->postulanteModel = new PostulanteModel();
    }

    public function obtenerDatosSolicitud($convocatoriaId)
    {
        $convocatoriaModel = new ConvocatoriaModel();

        $postulante = $convocatoriaModel

            ->join('selec_convocatoria_cargos', 'selec_convocatoria_cargos.cco_con_ide = selec_convocatorias.con_ide', 'left')
            ->join('selec_postulaciones', 'selec_postulaciones.pto_cco_ide = selec_convocatoria_cargos.cco_ide', 'left')
            ->join('selec_postulantes', 'selec_postulantes.pos_ide = selec_postulaciones.pto_pos_ide', 'left')
            ->join('selec_cargos', 'selec_cargos.car_ide = selec_convocatoria_cargos.cco_car_ide', 'left')
            ->where('con_ide', $convocatoriaId)
            ->where('pos_user_id', auth()->id())
            ->first();

        if (!$postulante) {
            return [];
        }

        return [
            'codigo_inscripcion'          => $postulante['pto_codigo'],
            'cargo'          => $postulante['car_denominacion'] ?? '____________________',
            'nombres'          => $postulante['pos_nombres'],
            'apellidos'        => trim($postulante['pos_apellido_paterno'] . ' ' . $postulante['pos_apellido_materno']),
            'documento'        => $postulante['pos_documento'],
            'direccion'        => $postulante['pos_direccion'] ?? '____________________',
            'telefono'         => $postulante['pos_telefono'] ?? '',
            'email'            => $postulante['pos_email'] ?? '',
            'fecha_actual'     => date('d/m/Y'),
            'num_convocatoria' => 'N° 002-2026', // Dinámico o pasado por parámetro
        ];
    }

    public function obtenerDatosFichaUnica($convocatoriaId)
    {
        $convocatoriaModel = new ConvocatoriaModel();

        $postulante = $convocatoriaModel

            ->join('selec_convocatoria_cargos', 'selec_convocatoria_cargos.cco_con_ide = selec_convocatorias.con_ide', 'left')
            ->join('selec_postulaciones', 'selec_postulaciones.pto_cco_ide = selec_convocatoria_cargos.cco_ide', 'left')
            ->join('selec_postulantes', 'selec_postulantes.pos_ide = selec_postulaciones.pto_pos_ide', 'left')
            ->join('selec_cargos', 'selec_cargos.car_ide = selec_convocatoria_cargos.cco_car_ide', 'left')
            ->where('con_ide', $convocatoriaId)
            ->where('pos_user_id', auth()->id())
            ->first();

        if (!$postulante) {
            return [];
        }

        // Simulación/Consulta de tablas secundarias relacionándolas con $postulanteId
        // $formacionModel = new FormacionModel();
        $postulanteProfesionService =  new PostulanteProfesionService();
        $profesion = $postulanteProfesionService->getByPostulanteId($postulante['pos_ide']);

        $formacion = (new PostulanteFormacionModel())->where('pfo_pos_ide', $postulante['pos_ide'] ?? 0)
            ->join('selec_niveles_formacion', 'nfo_ide = pfo_nfo_ide', 'left')
            ->join('selec_expediente_documentos', 'exd_ide = pfo_documento_ide', 'left')
            ->findAll();


        $postulanteProfesionService =  new PostulanteProfesionService();
        $profesion = $postulanteProfesionService->getByPostulanteId($postulante['pos_ide']);

        $profesiones = (new PostulanteProfesionModel())->where('ppr_pos_ide', $postulante['pos_ide'] ?? 0)
            ->join('selec_expediente_documentos', 'exd_ide = ppr_documento_ide', 'left')
            ->join('selec_profesiones', 'pro_ide = ppr_pro_ide', 'left')
            ->findAll();


        // $capacitacionModel = new CapacitacionModel();
        $capacitacion = (new PostulanteCapacitacionModel())->where('pca_pos_ide', $postulante['pos_ide'] ?? 0)
            ->join('selec_expediente_documentos', 'exd_ide = pca_documento_ide', 'left')
            ->findAll();

        $experiencia = (new PostulanteExperienciaModel())->where('pex_pos_ide', $postulante['pos_ide'] ?? 0)->join('selec_modalidades_vinculo', 'mvi_ide = pex_mvi_ide', 'left')->join('selec_expediente_documentos', 'exd_ide = pex_documento_ide', 'left')
            ->findAll();

        $identificacion = (new PostulantesOtroModel())->where('otr_pos_ide', $postulante['pos_ide'] ?? 0)
            ->join('selec_expediente_documentos', 'exd_ide = otr_documento_ide', 'left')
            ->findAll();


        return [
            'postulante' => [
                'nombres_completos' => trim($postulante['pos_nombres'] . ' ' . $postulante['pos_apellido_paterno'] . ' ' . $postulante['pos_apellido_materno']),
                'documento'        => $postulante['pos_documento'],
                'fecha_nacimiento' => $postulante['pos_fecha_nacimiento'],
                'sexo'             => ($postulante['pos_sexo'] == 'M') ? 'Masculino' : 'Femenino',
                'direccion'        => $postulante['pos_direccion'],
                'telefono'         => $postulante['pos_telefono'],
                'email'            => $postulante['pos_email'],
            ],
            'colegiatura' =>
            [
                'colegio'         => $profesion['ppr_titulo'] ?? '-',
                'numero'          => $profesion['ppr_colegitura'] ?? '-',
                'estado'          => $profesion['ppr_habilitacion'] ?? '-',
                'fecha_colegiado' => $profesion['ppr_fecha'] ?? '-',
            ],
            'colegiatura2' => $experiencia,
            // formacion profesional

            'profesion' => $profesiones,
            'formacion' => $formacion,
            'capacitaciones' => $capacitacion,
            'experiencia' => $experiencia,
            'identificacion' => $identificacion,
            'convocatoria' => [
                'numero'     => 'CAS N° 001-2026',
                'ejecutora'  => 'RED DE SALUD SAN ROMÁN - UE 403',
                'fecha_imp'  => date('d/m/Y H:i:s')
            ]
        ];
    }

    public function obtenerDatosAutoevaluacion($convocatoriaId)
    {
        $postulante = $this->postulanteModel->find($convocatoriaId);

        // Aquí puedes hacer uniones con tablas de formación académica o experiencia si lo requieres
        return [
            'postulante' => $postulante,
            'items'      => []
        ];
    }
}

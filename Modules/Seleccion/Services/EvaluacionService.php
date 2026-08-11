<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\ConvocatoriaModel;
use Modules\Seleccion\Models\PostulacionModel;
use Modules\Seleccion\Models\PostulanteModel;
use Modules\Seleccion\Models\PlazaModel;
use Modules\Seleccion\Models\FormacionModel;
use Modules\Seleccion\Models\ExperienciaModel;
use Modules\Seleccion\Models\ExtraModel; 
use Modules\Seleccion\Models\CalificacionPreviaModel;
use Modules\Seleccion\Models\CapacitacionModel;


class EvaluacionService
{
    public function listarConvocatorias(): array
    {
        return (new ConvocatoriaModel())
            ->where('estado', 'PUBLICADO')
            ->orderBy('fecha_fin', 'DESC')
            ->findAll();
    }

    public function listarPostulantes(int $idConvocatoria): array
    {
        return (new PostulacionModel())
            ->select("
                postulaciones.id_postulacion,
                postulantes.nombres,
                postulantes.paterno,
                postulantes.materno,
                postulantes.dni,
                plazas.codigo_plaza,
                plazas.cargo,
                postulaciones.estado,
                calificacion_previa.estado_evaluacion
            ")
            ->join('postulantes', 'postulantes.id_postulante = postulaciones.id_postulante')
            ->join('plazas', 'plazas.id_plaza = postulaciones.id_plaza')
            ->join('calificacion_previa', 'postulaciones.id_postulacion = calificacion_previa.id_postulacion', 'left')
            ->where('postulaciones.id_convocatoria', $idConvocatoria)
            ->where('postulantes.dni !=', '')
            ->where('postulantes.dni is not', null)
            ->where('postulaciones.estado ', 'POSTULADO')
            ->orderBy('postulantes.paterno')
            ->findAll();
    }
    public function verPostulacion(int $idPostulacion): array
    {
        $postulacionModel = new PostulacionModel();
        $postulante = new PostulanteModel();
        $formacionModel = new FormacionModel();
        $experienciaModel = new ExperienciaModel();
        $extraModel = new ExtraModel();
        $calificacionPreviaModel = new calificacionPreviaModel();
        $capacitacionModel = new CapacitacionModel();


        $postulacion = $postulacionModel
            ->select("
                postulaciones.*,
                postulantes.nombres,
                postulantes.paterno,
                postulantes.materno,
                postulantes.dni,
                plazas.codigo_plaza,
                plazas.cargo
            ")
            ->join('postulantes', 'postulantes.id_postulante = postulaciones.id_postulante')
            ->join('plazas', 'plazas.id_plaza = postulaciones.id_plaza')
            ->where('postulaciones.id_postulacion', $idPostulacion)
            ->first();
        $postulante = $postulante
            ->where('id_postulante', $postulacion['id_postulante'])
            ->first();
        $formacion = $formacionModel
            ->where('id_postulante', $postulacion['id_postulante'])
            ->join('anexos','anexos.id_anexo = formacion_profesional.id_anexo','join')
            ->orderBy('fecha_inicio', 'DESC')
            ->findAll();
        $experiencia = $experienciaModel
            ->where('id_postulante', $postulacion['id_postulante'])
            ->join('anexos','anexos.id_anexo = experiencia_laboral.id_anexo','join')
            ->orderBy('fecha_inicio', 'DESC')
            ->findAll();
        $capacitacion = $capacitacionModel
            ->where('id_postulante', $postulacion['id_postulante'])
            ->join('anexos','anexos.id_anexo = capacitaciones.id_anexo','join')
            ->findAll();
        $extra = $extraModel
            ->select('informacion_extra.*, anexos.ruta') 
            ->where('id_postulante', $postulacion['id_postulante']) 
            ->join('anexos','anexos.id_anexo = informacion_extra.id_anexo','join')
            ->findAll();
        $calificacionPrevia = $calificacionPreviaModel
            ->where('id_postulacion', $idPostulacion)
            ->where('id_postulante', $postulacion['id_postulante'])
            ->first();
        
        $post = [
            'postulacion' => $postulacion,
            'postulante' => $postulante,
            'formacion' => $formacion,
            'experiencia' => $experiencia,  
            'capacitacion' => $capacitacion,
            'extra' => $extra,
            'calificacionPrevia' => $calificacionPrevia
        ];

        return $post;

    }


}

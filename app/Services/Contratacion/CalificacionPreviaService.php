<?php

namespace App\Services\Contratacion;

use App\Models\Contratacion\CalificacionPreviaModel;
use App\Models\Contratacion\PLazaModel;

class CalificacionPreviaService
{
    protected $model;

    public function __construct()
    {
        $this->model = new CalificacionPreviaModel();
    }

    public function guardar(array $data, int $userId)
    {
        // Evitar doble evaluación
        $existe = $this->model
            ->where('id_postulacion', $data['id_postulacion'])
            ->where('id_postulante', $data['id_postulante'])
            ->first();
        if ($existe != null) {
            $this->model->update(
                $existe['id_calificacion_previa'],
                [
                    'id_postulacion'      => $data['id_postulacion'],
                    'id_postulante'       => $data['id_postulante'],
                    'id_convocatoria'     => $data['id_convocatoria'],
                    'titulo'              => $data['titulo'],
                    'titulo_especialidad' => $data['titulo_especialidad'],
                    'doctorado'           => $data['doctorado'],
                    'maestria'            => $data['maestria'],
                    'experiencia'         => $data['experiencia'],
                    'experiencia_meses'   => $data['experiencia_meses'],
                    'capacitacion'        => $data['capacitacion'],
                    'estado_evaluacion'   => $data['estado_evaluacion'],
                    'observacion'         => $data['observacion'],
                    'evaluado_por'        => $userId,
                    'updated_at'          => date('Y-m-d H:i:s') // Usa updated_at al actualizar
                ]
            );
        } else {


            $this->model->insert([
                'id_postulacion' => $data['id_postulacion'],
                'id_postulante' => $data['id_postulante'],
                'id_convocatoria' => $data['id_convocatoria'],
                'titulo' => $data['titulo'],
                'titulo_especialidad' => $data['titulo_especialidad'],
                'doctorado' => $data['doctorado'],
                'maestria' => $data['maestria'],
                'experiencia' => $data['experiencia'],
                'experiencia_meses'   => $data['experiencia_meses'],
                'capacitacion'        => $data['capacitacion'],
                'estado_evaluacion' => $data['estado_evaluacion'],
                'observacion' => $data['observacion'],
                'evaluado_por' => $userId,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        return [
            'status' => 'success'
        ];
    }

    public function resultadosPorConvocatoria(int $idConvocatoria)
    {
        $model = new CalificacionPreviaModel();
        $rows  = $model->obtenerResultados($idConvocatoria);

        // Agrupar por plaza
        $data = [];
        foreach ($rows as $r) {
            $key = $r['codigo_plaza'].' - '.$r['cargo'];
            $data[$key][] = $r;
        }

        return $data;
    }

    public function resultadosPorPlazaConvocatoria($idPlaza, $idConvocatoria)
    {
        $model = new CalificacionPreviaModel();
        $plazaModel = new PlazaModel();

        
        $plazas = $plazaModel
        ->where('id_convocatoria', $idConvocatoria)
        ->findAll();

        $rows  = $model->obtenerResultados($idConvocatoria);
        
        $data = [
            'plazas'=>$plazas,
            'plaza'=>$rows

        ];
        return $data;
    }
}

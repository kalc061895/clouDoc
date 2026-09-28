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
            ->join('anexos', 'anexos.id_anexo = formacion_profesional.id_anexo', 'join')
            ->orderBy('fecha_inicio', 'DESC')
            ->findAll();
        $experiencia = $experienciaModel
            ->where('id_postulante', $postulacion['id_postulante'])
            ->join('anexos', 'anexos.id_anexo = experiencia_laboral.id_anexo', 'join')
            ->orderBy('fecha_inicio', 'DESC')
            ->findAll();
        $capacitacion = $capacitacionModel
            ->where('id_postulante', $postulacion['id_postulante'])
            ->join('anexos', 'anexos.id_anexo = capacitaciones.id_anexo', 'join')
            ->findAll();
        $extra = $extraModel
            ->select('informacion_extra.*, anexos.ruta')
            ->where('id_postulante', $postulacion['id_postulante'])
            ->join('anexos', 'anexos.id_anexo = informacion_extra.id_anexo', 'join')
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

    /**
     * Registra o actualiza una evaluación curricular completa
     */
    public function guardarEvaluacion(array $datos): bool
    {
        $this->db->transStart();

        $usuarioId = session('usu_ide') ?? 1; // ID de usuario en sesión

        // 1. Preparar Cabecera de Evaluación
        $dataEvaluacion = [
            'eva_pto_ide'       => $datos['pto_ide'],
            'eva_fie_ide'       => $datos['fie_ide'],
            'eva_com_ide'       => $datos['com_ide'] ?? null,
            'eva_usu_ide'       => $usuarioId,
            'eva_tipo'          => $datos['eva_tipo'] ?? 'CURRICULAR',
            'eva_estado'        => $datos['eva_estado'] ?? 'EVALUADO',
            'eva_puntaje_total' => $datos['puntaje_total'] ?? 0.00,
            'eva_fecha_inicio'  => $datos['fecha_inicio'] ?? date('Y-m-d H:i:s'),
            'eva_fecha_fin'     => date('Y-m-d H:i:s'),
            'eva_observacion'   => $datos['observacion'] ?? null,
            'created_by'        => $usuarioId,
            'updated_by'        => $usuarioId,
        ];

        // Guardar o actualizar cabecera
        $evaIde = $this->evaluacionModel->insert($dataEvaluacion, true);

        // 2. Guardar Detalles de Criterios (selec_evaluacion_detalles)
        if (!empty($datos['criterios']) && is_array($datos['criterios'])) {
            foreach ($datos['criterios'] as $criIde => $criterio) {
                $dataDetalle = [
                    'evd_eva_ide'     => $evaIde,
                    'evd_cri_ide'     => $criIde,
                    'evd_cumple'      => isset($criterio['cumple']) ? 1 : 0,
                    'evd_puntaje'     => $criterio['puntaje'] ?? 0.00,
                    'evd_observacion' => $criterio['observacion'] ?? null,
                    'created_by'      => $usuarioId,
                ];
                $this->detalleModel->insert($dataDetalle);
            }
        }

        // 3. Guardar Validación de Experiencias Laborales (selec_evaluacion_experiencia)
        if (!empty($datos['experiencias']) && is_array($datos['experiencias'])) {
            foreach ($datos['experiencias'] as $exp) {
                $diasDeclarados = (int) ($exp['dias_declarados'] ?? 0);
                $diasValidados  = (int) ($exp['dias_validados'] ?? 0);

                $dataExp = [
                    'exe_eva_ide'         => $evaIde,
                    'exe_pex_ide'         => $exp['pex_ide'],
                    'exe_resultado'       => $exp['resultado'] ?? 'VALIDADO',
                    'exe_dias_declarados' => $diasDeclarados,
                    'exe_dias_validados'  => $diasValidados,
                    'exe_puntaje'         => $exp['puntaje'] ?? 0.00,
                    'exe_observacion'     => $exp['observacion'] ?? null,
                    'created_by'          => $usuarioId,
                ];
                $this->experienciaModel->insert($dataExp);
            }
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            throw new DatabaseException('Error al registrar la evaluación en la base de datos.');
        }

        return true;
    }

    /**
     * Helper para calcular y convertir días acumulados a Años, Meses y Días
     */
    public function calcularTiempoFormateado(int $totalDias): array
    {
        $anios = Math.floor($totalDias / 365);
        $meses = Math.floor(($totalDias % 365) / 30);
        $dias  = ($totalDias % 365) % 30;

        return [
            'anios' => (int) $anios,
            'meses' => (int) $meses,
            'dias'  => (int) $dias,
            'total_dias' => $totalDias
        ];
    }
}

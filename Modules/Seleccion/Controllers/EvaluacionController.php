<?php

namespace Modules\Seleccion\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Seleccion\Models\PostulacionModel;
use Modules\Seleccion\Models\ConvocatoriaModel;
use Modules\Seleccion\Models\CriterioEvaluacionModel;
use Modules\Seleccion\Models\FichaEvaluacionModel;
use Modules\Seleccion\Models\PostulanteProfesionModel;
use Modules\Seleccion\Models\PostulanteCapacitacionModel;
use Modules\Seleccion\Models\PostulanteExperienciaModel;
use Modules\Seleccion\Models\PostulantesOtroModel;
use Modules\Seleccion\Models\PostulanteFormacionModel;

class EvaluacionController extends BaseModuleController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Vista principal del módulo de evaluación (Comisión)
     */
    public function index()
    {
        $convocatorias = $this->db->table('selec_convocatorias c')
            ->select('c.con_ide, c.con_nombre, c.con_numero, ec.eco_codigo AS estado_codigo')
            ->join('selec_estados_convocatoria ec', 'ec.eco_ide = c.con_eco_ide', 'left')
            ->where('c.deleted_at', null)
            ->orderBy('c.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return view('Modules\Seleccion\Views\evaluacion\index', [
            'convocatorias' => $convocatorias,
        ]);
    }

    /**
     * GET JSON: Lista de postulantes confirmados de una convocatoria
     */
    public function getPostulantes(int $convocatoriaId)
    {
        $postulantes = $this->db->table('selec_postulaciones p')
            ->select('
                p.pto_ide, p.pto_codigo, p.pto_confirmado,
                po.pos_ide, po.pos_nombres, po.pos_apellido_paterno, po.pos_apellido_materno,
                po.pos_documento,
                ca.car_denominacion AS cargo_nombre,
                ep.epo_nombre AS estado_nombre, ep.epo_codigo AS estado_codigo,
                e.eva_ide, e.eva_puntaje_total, e.eva_estado AS evaluacion_estado
            ')
            ->join('selec_postulantes po', 'po.pos_ide = p.pto_pos_ide', 'left')
            ->join('selec_convocatoria_cargos cc', 'cc.cco_ide = p.pto_cco_ide', 'left')
            ->join('selec_cargos ca', 'ca.car_ide = cc.cco_car_ide', 'left')
            ->join('selec_estados_postulacion ep', 'ep.epo_ide = p.pto_epo_ide', 'left')
            ->join('selec_evaluaciones e', 'e.eva_pto_ide = p.pto_ide', 'left')
            ->where('cc.cco_con_ide', $convocatoriaId)
            ->where('p.pto_confirmado', 1)
            ->where('p.deleted_at', null)
            ->orderBy('po.pos_apellido_paterno')
            ->get()
            ->getResultArray();

        return $this->jsonResponse('success', 'Postulantes cargados.', $postulantes);
    }

    /**
     * GET JSON: Formulario de evaluación con todos los datos del postulante
     * Corrección del bug: se eliminó la sobreescritura de $ficha y $evaluacion con cadenas vacías
     */
    public function getFormularioEvaluacion(int $pto_ide)
    {
        $postulacion = (new PostulacionModel())->find($pto_ide);
        if (!$postulacion) {
            return $this->jsonResponse('error', 'Postulación no encontrada.', [], 404);
        }

        // Obtener datos completos de postulación con joins
        $convocatoria = (new ConvocatoriaModel())
            ->join('selec_convocatoria_cargos', 'selec_convocatoria_cargos.cco_con_ide = selec_convocatorias.con_ide', 'left')
            ->join('selec_postulaciones', 'selec_postulaciones.pto_cco_ide = selec_convocatoria_cargos.cco_ide', 'left')
            ->join('selec_postulantes', 'selec_postulantes.pos_ide = selec_postulaciones.pto_pos_ide', 'left')
            ->join('selec_cargos', 'selec_cargos.car_ide = selec_convocatoria_cargos.cco_car_ide', 'left')
            ->where('pto_ide', $pto_ide)
            ->where('pto_confirmado', 1)
            ->first();

        if (!$convocatoria) {
            return $this->jsonResponse('error', 'Postulación no confirmada.', [], 422);
        }

        // Fichas de evaluación asignadas a esta convocatoria
        $fichas = (new FichaEvaluacionModel())
            ->where('fie_con_ide', $convocatoria['con_ide'])
            ->findAll();

        // Criterios de la primera ficha disponible (o todas)
        $criterios = [];
        if (!empty($fichas)) {
            $criterios = (new CriterioEvaluacionModel())
                ->join('selec_fichas_evaluacion', 'selec_fichas_evaluacion.fie_ide = selec_criterios_evaluacion.cri_fie_ide', 'inner')
                ->where('fie_con_ide', $convocatoria['con_ide'])
                ->findAll();
        }

        // Evaluación existente del postulante (CORRECCIÓN: ya no se sobreescribe con '')
        $evaluacion = $this->db->table('selec_evaluaciones')
            ->where('eva_pto_ide', $pto_ide)
            ->get()
            ->getRowArray();

        // Detalles por criterio de la evaluación existente
        $detalles = [];
        if ($evaluacion) {
            $detalles = $this->db->table('selec_evaluacion_detalles')
                ->where('evd_eva_ide', $evaluacion['eva_ide'])
                ->get()
                ->getResultArray();
        }

        return $this->jsonResponse('success', 'Formulario de evaluación cargado.', [
            'postulacion'  => $postulacion,
            'convocatoria' => $convocatoria,
            'fichas'       => $fichas,
            'criterios'    => $criterios,
            'evaluacion'   => $evaluacion,   // CORRECCIÓN: ahora retorna el objeto real o null
            'detalles'     => $detalles,
            'postulante'   => $this->obtener_expediente($convocatoria['pos_ide']),
        ]);
    }

    /**
     * Obtiene el expediente completo del postulante para revisión curricular
     */
    public function obtener_expediente(int $postulanteId): array
    {
        // CORRECCIÓN: Usa los modelos correctos del módulo en lugar de los inexistentes
        $profesiones = (new PostulanteProfesionModel())
            ->where('ppr_pos_ide', $postulanteId)
            ->join('selec_expediente_documentos', 'exd_ide = ppr_documento_ide', 'left')
            ->join('selec_profesiones', 'pro_ide = ppr_pro_ide', 'left')
            ->findAll();

        $formacion = (new PostulanteFormacionModel())
            ->where('pfo_pos_ide', $postulanteId)
            ->join('selec_niveles_formacion', 'nfo_ide = pfo_nfo_ide', 'left')
            ->join('selec_expediente_documentos', 'exd_ide = pfo_documento_ide', 'left')
            ->findAll();

        $capacitaciones = (new PostulanteCapacitacionModel())
            ->where('pca_pos_ide', $postulanteId)
            ->join('selec_expediente_documentos', 'exd_ide = pca_documento_ide', 'left')
            ->findAll();

        $experiencia = (new PostulanteExperienciaModel())
            ->where('pex_pos_ide', $postulanteId)
            ->join('selec_modalidades_vinculo', 'mvi_ide = pex_mvi_ide', 'left')
            ->join('selec_expediente_documentos', 'exd_ide = pex_documento_ide', 'left')
            ->findAll();

        $identificacion = (new PostulantesOtroModel())
            ->where('otr_pos_ide', $postulanteId)
            ->join('selec_expediente_documentos', 'exd_ide = otr_documento_ide', 'left')
            ->findAll();

        return [
            'profesion'      => $profesiones,
            'formacion'      => $formacion,
            'capacitaciones' => $capacitaciones,
            'experiencia'    => $experiencia,
            'identificacion' => $identificacion,
        ];
    }

    /**
     * POST: Guardar evaluación curricular con puntajes por criterio
     */
    public function guardarEvaluacion()
    {
        $rules = [
            'eva_pto_ide' => 'required|numeric',
            'eva_fie_ide' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return $this->jsonResponse('error', 'Datos de evaluación incompletos.', $this->validator->getErrors(), 422);
        }

        $pto_ide    = $this->request->getPost('eva_pto_ide');
        $fie_ide    = $this->request->getPost('eva_fie_ide');
        $criterios  = $this->request->getPost('criterios') ?? [];
        $observacion= $this->request->getPost('eva_observacion') ?? '';
        $estado     = $this->request->getPost('eva_estado') ?? 'EVALUADO';
        $userId     = $this->getAuditUserId();

        $this->db->transStart();

        // Calcular puntaje total
        $puntajeTotal = 0;
        foreach ($criterios as $data) {
            $puntajeTotal += floatval($data['puntaje'] ?? 0);
        }

        // Buscar evaluación existente
        $evaluacion = $this->db->table('selec_evaluaciones')
            ->where('eva_pto_ide', $pto_ide)
            ->get()
            ->getRowArray();

        $dataEvaluacion = [
            'eva_pto_ide'       => $pto_ide,
            'eva_fie_ide'       => $fie_ide,
            'eva_usu_ide'       => $userId ?? 1,
            'eva_estado'        => $estado,
            'eva_puntaje_total' => $puntajeTotal,
            'eva_observacion'   => $observacion,
            'updated_at'        => date('Y-m-d H:i:s'),
            'updated_by'        => $userId ?? 1,
        ];

        if ($evaluacion) {
            $eva_ide = $evaluacion['eva_ide'];
            $this->db->table('selec_evaluaciones')->where('eva_ide', $eva_ide)->update($dataEvaluacion);
            $this->db->table('selec_evaluacion_detalles')->where('evd_eva_ide', $eva_ide)->delete();
        } else {
            $dataEvaluacion['eva_fecha_inicio'] = date('Y-m-d H:i:s');
            $dataEvaluacion['eva_fecha_fin']    = date('Y-m-d H:i:s');
            $dataEvaluacion['created_at']       = date('Y-m-d H:i:s');
            $dataEvaluacion['created_by']       = $userId ?? 1;
            $this->db->table('selec_evaluaciones')->insert($dataEvaluacion);
            $eva_ide = $this->db->insertID();
        }

        // Insertar detalles por criterio
        foreach ($criterios as $cri_ide => $det) {
            $this->db->table('selec_evaluacion_detalles')->insert([
                'evd_eva_ide'     => $eva_ide,
                'evd_cri_ide'     => $cri_ide,
                'evd_cumple'      => isset($det['cumple']) ? 1 : 0,
                'evd_puntaje'     => floatval($det['puntaje'] ?? 0),
                'evd_observacion' => $det['observacion'] ?? '',
                'created_at'      => date('Y-m-d H:i:s'),
                'created_by'      => $userId ?? 1,
            ]);
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $this->jsonResponse('error', 'Error al guardar la calificación.', [], 500);
        }

        return $this->jsonResponse('success', 'Calificación curricular guardada correctamente.', ['eva_ide' => $eva_ide]);
    }

    /**
     * POST: Guardar calificación de entrevista/evaluación técnica
     */
    public function guardarEntrevista()
    {
        $rules = [
            'ent_pto_ide'        => 'required|numeric',
            'ent_puntaje_total'  => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return $this->jsonResponse('error', 'Datos de entrevista inválidos.', $this->validator->getErrors(), 422);
        }

        $pto_ide = $this->request->getPost('ent_pto_ide');
        $userId  = $this->getAuditUserId();

        // Buscar entrevista existente
        $entrevista = $this->db->table('selec_entrevistas')
            ->where('ent_pto_ide', $pto_ide)
            ->get()
            ->getRowArray();

        $data = [
            'ent_pto_ide'           => $pto_ide,
            'ent_puntaje_total'     => $this->request->getPost('ent_puntaje_total'),
            'ent_puntaje_capacidad' => $this->request->getPost('ent_puntaje_capacidad') ?? 0,
            'ent_puntaje_aptitud'   => $this->request->getPost('ent_puntaje_aptitud') ?? 0,
            'ent_puntaje_valores'   => $this->request->getPost('ent_puntaje_valores') ?? 0,
            'ent_observaciones'     => $this->request->getPost('ent_observaciones') ?? '',
            'ent_resultado'         => $this->request->getPost('ent_resultado') ?? 'PENDIENTE',
            'updated_at'            => date('Y-m-d H:i:s'),
            'updated_by'            => $userId ?? 1,
        ];

        if ($entrevista) {
            $this->db->table('selec_entrevistas')
                ->where('ent_ide', $entrevista['ent_ide'])
                ->update($data);
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = $userId ?? 1;
            $this->db->table('selec_entrevistas')->insert($data);
        }

        return $this->jsonResponse('success', 'Calificación de entrevista guardada correctamente.');
    }

    /**
     * GET JSON: Genera el cuadro de méritos consolidado de una convocatoria
     */
    public function getCuadroMeritos(int $convocatoriaId)
    {
        $cuadro = $this->db->table('selec_postulaciones p')
            ->select('
                p.pto_ide, p.pto_codigo,
                po.pos_nombres, po.pos_apellido_paterno, po.pos_apellido_materno, po.pos_documento,
                ca.car_denominacion AS cargo,
                e.eva_puntaje_total AS puntaje_curriculo,
                en.ent_puntaje_total AS puntaje_entrevista,
                COALESCE(e.eva_puntaje_total, 0) + COALESCE(en.ent_puntaje_total, 0) AS puntaje_final,
                e.eva_estado AS estado_curriculo, en.ent_resultado AS resultado_entrevista
            ')
            ->join('selec_postulantes po', 'po.pos_ide = p.pto_pos_ide', 'left')
            ->join('selec_convocatoria_cargos cc', 'cc.cco_ide = p.pto_cco_ide', 'left')
            ->join('selec_cargos ca', 'ca.car_ide = cc.cco_car_ide', 'left')
            ->join('selec_evaluaciones e', 'e.eva_pto_ide = p.pto_ide', 'left')
            ->join('selec_entrevistas en', 'en.ent_pto_ide = p.pto_ide', 'left')
            ->where('cc.cco_con_ide', $convocatoriaId)
            ->where('p.pto_confirmado', 1)
            ->where('p.deleted_at', null)
            ->orderBy('puntaje_final', 'DESC')
            ->get()
            ->getResultArray();

        // Asignar orden de mérito
        foreach ($cuadro as $index => &$item) {
            $item['orden_merito'] = $index + 1;
        }

        return $this->jsonResponse('success', 'Cuadro de méritos generado.', $cuadro);
    }

    /**
     * POST: Publicar cuadro de méritos
     */
    public function publicarCuadro(int $convocatoriaId)
    {
        $etapa  = strtoupper($this->request->getPost('etapa') ?? 'CURRICULO');
        $userId = $this->getAuditUserId();

        // Registrar/actualizar en cuadro de méritos
        $existe = $this->db->table('selec_cuadro_meritos')
            ->where('cme_con_ide', $convocatoriaId)
            ->where('cme_etapa', $etapa)
            ->get()->getRowArray();

        if ($existe) {
            $this->db->table('selec_cuadro_meritos')
                ->where('cme_ide', $existe['cme_ide'])
                ->update([
                    'cme_publicado'  => 1,
                    'cme_fecha_pub'  => date('Y-m-d H:i:s'),
                    'updated_at'     => date('Y-m-d H:i:s'),
                    'updated_by'     => $userId,
                ]);
        } else {
            $this->db->table('selec_cuadro_meritos')->insert([
                'cme_con_ide'    => $convocatoriaId,
                'cme_etapa'      => $etapa,
                'cme_publicado'  => 1,
                'cme_fecha_pub'  => date('Y-m-d H:i:s'),
                'created_at'     => date('Y-m-d H:i:s'),
                'created_by'     => $userId,
                'updated_at'     => date('Y-m-d H:i:s'),
            ]);
        }

        return $this->jsonResponse('success', "Cuadro de méritos etapa {$etapa} publicado correctamente.");
    }
}

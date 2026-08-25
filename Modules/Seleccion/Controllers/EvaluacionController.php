<?php

namespace Modules\Seleccion\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Seleccion\Services\EvaluacionService;
use Modules\Seleccion\Services\PostulacionService;
use Modules\Seleccion\Models\CalificacionPreviaModel;
use Modules\Seleccion\Services\CalificacionPreviaService;
use Modules\Seleccion\Models\PostulacionModel;
use Modules\Seleccion\Models\ConvocatoriaModel;
use Modules\Seleccion\Models\CriterioEvaluacionModel;
use Modules\Seleccion\Models\fichaEvaluacionModel;
use Modules\Seleccion\Services\PostulanteProfesionService;
use Modules\Seleccion\Models\PostulanteProfesionModel;
use Modules\Seleccion\Models\PostulanteCapacitacionModel;
use Modules\Seleccion\Models\PostulanteExperienciaModel;
use Modules\Seleccion\Models\PostulantesOtroModel;
use Modules\Seleccion\Models\PostulanteFormacionModel;
class EvaluacionController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Obtener convocatorias activas para el combo principal
        $convocatorias = $this->db->table('selec_convocatorias')
            ->select('con_ide, con_nombre')
            ->where('deleted_at', null)
            ->get()
            ->getResultArray();

        return view('Modules\Seleccion\Views\evaluacion\index', [
            'convocatorias' => $convocatorias
        ]);
    }

    public function getPostulantes($convocatoriaId)
    {
        $postulacionModel = new PostulacionModel();
        $convocatoriaModel = new ConvocatoriaModel();

        $postulante = $convocatoriaModel

            ->join('selec_convocatoria_cargos', 'selec_convocatoria_cargos.cco_con_ide = selec_convocatorias.con_ide', 'left')
            ->join('selec_postulaciones', 'selec_postulaciones.pto_cco_ide = selec_convocatoria_cargos.cco_ide', 'left')
            ->join('selec_postulantes', 'selec_postulantes.pos_ide = selec_postulaciones.pto_pos_ide', 'left')
            ->join('selec_cargos', 'selec_cargos.car_ide = selec_convocatoria_cargos.cco_car_ide', 'left')
            ->where('con_ide', $convocatoriaId)
            ->where('pto_confirmado',1)
            ->findAll();

        return $this->response->setJSON(['data' => $postulante]);
    }

    public function getFormularioEvaluacion($pto_ide)
    {
        $postulacionModel = New PostulacionModel();
        $postulacion = $postulacionModel->find($pto_ide);
        
        $convocatoriaModel = new ConvocatoriaModel();

        $convocatoria = $convocatoriaModel

            ->join('selec_convocatoria_cargos', 'selec_convocatoria_cargos.cco_con_ide = selec_convocatorias.con_ide', 'left')

            ->join('selec_postulaciones', 'selec_postulaciones.pto_cco_ide = selec_convocatoria_cargos.cco_ide', 'left')

            ->join('selec_postulantes', 'selec_postulantes.pos_ide = selec_postulaciones.pto_pos_ide', 'left')

            ->join('selec_cargos', 'selec_cargos.car_ide = selec_convocatoria_cargos.cco_car_ide', 'left')

            ->where('pto_ide', $pto_ide)
            ->where('pto_confirmado',1)
            ->first();
        
        $fichaModel = new FichaEvaluacionModel();

        $ficha = $fichaModel
            ->where('fie_con_ide',$convocatoria['con_ide'])
            ->findAll();

        $criterioModel = new CriterioEvaluacionModel();

        $criterio = $criterioModel
            ->join('selec_fichas_evaluacion', 'selec_fichas_evaluacion.fie_ide = selec_criterios_evaluacion.cri_fie_ide', 'join')
            ->where('fie_ide',$ficha[0]['fie_ide'])
            ->findAll();

        
        
        
        $ficha = '';
        $evaluacion = '';
        $detalles = '';
        

        return $this->response->setJSON([
            'postulacion' => $postulacion,
            'convocatoria' => $convocatoria,
            'ficha'       => $ficha,
            'criterios'   => $criterio,
            'evaluacion'  => $evaluacion,
            'detalles'    => $detalles,
            'postulante'    => $this->obtener_expediente($convocatoria['pos_ide']),

           
        ]);
    }

    public function obtener_expediente($postulanteId)
    {
        $postulante['pos_ide']=$postulanteId;
        $profesiones = (new PostulanteProfesionModel())->where('ppr_pos_ide', $postulante['pos_ide'] ?? 0)
                ->join('selec_expediente_documentos', 'exd_ide = ppr_documento_ide', 'left')
                ->join('selec_profesiones', 'pro_ide = ppr_pro_ide', 'left')
                ->findAll();
        $formacion = (new PostulanteFormacionModel())->where('pfo_pos_ide', $postulante['pos_ide'] ?? 0)
                ->join('selec_niveles_formacion', 'nfo_ide = pfo_nfo_ide', 'left')
                ->join('selec_expediente_documentos', 'exd_ide = pfo_documento_ide', 'left')
                ->findAll();
        $capacitacion = (new PostulanteCapacitacionModel())->where('pca_pos_ide', $postulante['pos_ide'] ?? 0)
                ->join('selec_expediente_documentos', 'exd_ide = pca_documento_ide', 'left')
                ->findAll();
        $experiencia = (new PostulanteExperienciaModel())->where('pex_pos_ide', $postulante['pos_ide'] ?? 0)->join('selec_modalidades_vinculo', 'mvi_ide = pex_mvi_ide', 'left')->join('selec_expediente_documentos', 'exd_ide = pex_documento_ide', 'left')
                ->findAll();

        $identificacion = (new PostulantesOtroModel())->where('otr_pos_ide', $postulante['pos_ide'] ?? 0)
                ->join('selec_expediente_documentos', 'exd_ide = otr_documento_ide', 'left')
                ->findAll();
        
        return [

            'profesion' => $profesiones,
            'formacion' => $formacion,
            'capacitaciones' => $capacitacion,
            'experiencia' => $experiencia,
            'identificacion' => $identificacion,
        ];

    }

    public function guardarEvaluacion()
    {
        $rules = [
            'eva_pto_ide' => 'required|numeric',
            'eva_fie_ide' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Datos de evaluación incompletos.']);
        }

        $postData = $this->request->getPost();
        $pto_ide = $postData['eva_pto_ide'];
        $fie_ide = $postData['eva_fie_ide'];
        $criterios = $postData['criterios'] ?? [];
        $observacion = $postData['eva_observacion'] ?? '';
        $estado = $postData['eva_estado'] ?? 'EVALUADO';

        $this->db->transStart();

        // Buscar si existe evaluación registrada
        $evaluacion = $this->db->table('selec_evaluaciones')
            ->where('eva_pto_ide', $pto_ide)
            ->get()
            ->getRowArray();

        $puntajeTotal = 0;
        foreach ($criterios as $cri_id => $data) {
            $puntajeTotal += floatval($data['puntaje'] ?? 0);
        }

        $dataEvaluacion = [
            'eva_pto_ide'       => $pto_ide,
            'eva_fie_ide'       => $fie_ide,
            'eva_usu_ide'       => session()->get('usu_ide') ?? 1,
            'eva_estado'        => $estado,
            'eva_puntaje_total' => $puntajeTotal,
            'eva_observacion'   => $observacion,
            'updated_at'        => date('Y-m-d H:i:s'),
            'updated_by'        => session()->get('usu_ide') ?? 1,
        ];

        if ($evaluacion) {
            $eva_ide = $evaluacion['eva_ide'];
            $this->db->table('selec_evaluaciones')->where('eva_ide', $eva_ide)->update($dataEvaluacion);
            $this->db->table('selec_evaluacion_detalles')->where('evd_eva_ide', $eva_ide)->delete();
        } else {
            $dataEvaluacion['eva_fecha_inicio'] = date('Y-m-d H:i:s');
            $dataEvaluacion['eva_fecha_fin']    = date('Y-m-d H:i:s');
            $dataEvaluacion['created_at']       = date('Y-m-d H:i:s');
            $dataEvaluacion['created_by']       = session()->get('usu_ide') ?? 1;

            $this->db->table('selec_evaluaciones')->insert($dataEvaluacion);
            $eva_ide = $this->db->insertID();
        }

        // Insertar detalles por cada criterio
        foreach ($criterios as $cri_ide => $det) {
            $this->db->table('selec_evaluacion_detalles')->insert([
                'evd_eva_ide'     => $eva_ide,
                'evd_cri_ide'     => $cri_ide,
                'evd_cumple'      => isset($det['cumple']) ? 1 : 0,
                'evd_puntaje'     => floatval($det['puntaje'] ?? 0),
                'evd_observacion' => $det['observacion'] ?? '',
                'created_at'      => date('Y-m-d H:i:s'),
                'created_by'      => session()->get('usu_ide') ?? 1,
            ]);
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error al guardar la calificación.']);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Calificación guardada correctamente.']);
   }

   /**
     * Endpoint AJAX POST para guardar la evaluación
     */
    public function guardar()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['success' => false, 'message' => 'Método no permitido.']);
        }

        $postData = $this->request->getPost();

        // Reglas de validación
        $rules = [
            'pto_ide'       => 'required|integer',
            'fie_ide'       => 'required|integer',
            'puntaje_total' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => $this->validator->getErrors()
            ]);
        }

        try {
            $this->evaluacionService->guardarEvaluacion($postData);

            return $this->response->setJSON([
                'success' => true,
                'message' => '¡Evaluación y experiencia registradas correctamente!'
            ]);

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}

<?php

namespace Modules\Seleccion\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Seleccion\Models\ConvocatoriaModel;
use Modules\Seleccion\Models\PostulacionModel;
use Modules\Seleccion\Models\ResultadoModel;
use Modules\Seleccion\Models\ReclamoModel;
use Modules\Seleccion\Services\ConvocatoriaService;

class AdminController extends BaseModuleController
{
    protected ConvocatoriaService $convocatoriaService;

    public function __construct()
    {
        $this->convocatoriaService = new ConvocatoriaService();
    }

    /**
     * Dashboard administrativo del proceso de selección
     */
    public function index()
    {
        $db = \Config\Database::connect();

        $totalConvocatorias = $db->table('selec_convocatorias')
            ->where('deleted_at', null)
            ->countAllResults();

        $totalPostulantes = $db->table('selec_postulantes')
            ->where('deleted_at', null)
            ->countAllResults();

        $totalPostulaciones = $db->table('selec_postulaciones')
            ->where('deleted_at', null)
            ->countAllResults();

        $totalReclamos = $db->table('selec_reclamos')
            ->where('deleted_at', null)
            ->countAllResults();

        $convocatoriasActivas = $db->table('selec_convocatorias c')
            ->select('c.*, ec.eco_nombre AS estado_nombre, ec.eco_codigo AS estado_codigo')
            ->join('selec_estados_convocatoria ec', 'ec.eco_ide = c.con_eco_ide', 'left')
            ->where('c.deleted_at', null)
            ->orderBy('c.created_at', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        return view('Modules\Seleccion\Views\admin\dashboard', [
            'titulo'               => 'Dashboard de Selección CAS',
            'totalConvocatorias'   => $totalConvocatorias,
            'totalPostulantes'     => $totalPostulantes,
            'totalPostulaciones'   => $totalPostulaciones,
            'totalReclamos'        => $totalReclamos,
            'convocatoriasActivas' => $convocatoriasActivas,
        ]);
    }

    /**
     * GET JSON: Lista de convocatorias publicadas para combos AJAX
     */
    public function convocatorias()
    {
        return $this->jsonResponse(
            'success',
            'Convocatorias cargadas.',
            $this->convocatoriaService->convocatoriasPublicadas()
        );
    }

    /**
     * GET JSON: Plazas de una convocatoria (para combo AJAX)
     */
    public function plazas(int $idConvocatoria)
    {
        return $this->jsonResponse(
            'success',
            'Plazas cargadas.',
            $this->convocatoriaService->plazasPorConvocatoria($idConvocatoria)
        );
    }

    /**
     * GET: Lista de postulaciones de una convocatoria
     */
    public function listarPostulaciones(int $idConvocatoria)
    {
        $db = \Config\Database::connect();

        $data = $db->table('selec_postulaciones p')
            ->select('
                p.pto_ide, p.pto_codigo, p.pto_confirmado, p.pto_fecha_confirmacion,
                po.pos_nombres, po.pos_apellido_paterno, po.pos_apellido_materno,
                po.pos_documento, po.pos_email, po.pos_telefono,
                c.car_denominacion AS cargo_nombre,
                ep.epo_nombre AS estado_nombre, ep.epo_codigo AS estado_codigo
            ')
            ->join('selec_postulantes po', 'po.pos_ide = p.pto_pos_ide', 'left')
            ->join('selec_convocatoria_cargos cc', 'cc.cco_ide = p.pto_cco_ide', 'left')
            ->join('selec_cargos c', 'c.car_ide = cc.cco_car_ide', 'left')
            ->join('selec_estados_postulacion ep', 'ep.epo_ide = p.pto_epo_ide', 'left')
            ->where('cc.cco_con_ide', $idConvocatoria)
            ->where('p.deleted_at', null)
            ->orderBy('po.pos_apellido_paterno')
            ->get()
            ->getResultArray();

        return $this->jsonResponse('success', 'Postulaciones cargadas.', $data);
    }

    /**
     * POST: Publicar resultados de una etapa del proceso
     * Etapas: CURRICULO, ENTREVISTA, FINAL
     */
    public function publicarResultados()
    {
        $convocatoriaId = $this->request->getPost('convocatoria_id');
        $etapa          = strtoupper($this->request->getPost('etapa') ?? '');
        $etapasValidas  = ['CURRICULO', 'ENTREVISTA', 'FINAL'];

        if (!$convocatoriaId || !in_array($etapa, $etapasValidas)) {
            return $this->jsonResponse('error', 'Parámetros de publicación inválidos.', [], 422);
        }

        $db = \Config\Database::connect();

        // Marcar cuadro de méritos de esta etapa como PUBLICADO
        $db->table('selec_cuadro_meritos')
            ->where('cme_con_ide', $convocatoriaId)
            ->where('cme_etapa', $etapa)
            ->update([
                'cme_publicado'    => 1,
                'cme_fecha_pub'    => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
                'updated_by'       => $this->getAuditUserId(),
            ]);

        // Registrar en auditoría
        log_message('info', "Resultados etapa {$etapa} publicados para convocatoria #{$convocatoriaId} por usuario #{$this->getAuditUserId()}");

        return $this->jsonResponse('success', "Resultados de {$etapa} publicados correctamente.");
    }

    /**
     * POST: Registrar una impugnación/reclamo sobre los resultados
     */
    public function registrarImpugnacion()
    {
        $rules = [
            'rec_pto_ide'      => 'required|numeric',
            'rec_etapa'        => 'required|in_list[CURRICULO,ENTREVISTA,FINAL]',
            'rec_motivo'       => 'required|min_length[20]|max_length[1000]',
        ];

        if (!$this->validate($rules)) {
            return $this->jsonResponse('error', 'Datos de reclamo inválidos.', $this->validator->getErrors(), 422);
        }

        $db = \Config\Database::connect();
        $db->table('selec_reclamos')->insert([
            'rec_pto_ide'   => $this->request->getPost('rec_pto_ide'),
            'rec_etapa'     => $this->request->getPost('rec_etapa'),
            'rec_motivo'    => $this->request->getPost('rec_motivo'),
            'rec_estado'    => 'PENDIENTE',
            'created_at'    => date('Y-m-d H:i:s'),
            'created_by'    => $this->getAuditUserId(),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        return $this->jsonResponse('success', 'Impugnación registrada. Se le notificará la resolución.');
    }

    /**
     * POST: Resolver una impugnación/reclamo
     */
    public function resolverImpugnacion(int $id)
    {
        $rules = [
            'rec_resolucion' => 'required|min_length[10]',
            'rec_estado'     => 'required|in_list[APROBADO,RECHAZADO]',
        ];

        if (!$this->validate($rules)) {
            return $this->jsonResponse('error', 'Datos de resolución inválidos.', $this->validator->getErrors(), 422);
        }

        $db = \Config\Database::connect();
        $db->table('selec_reclamos')
            ->where('rec_ide', $id)
            ->update([
                'rec_resolucion'    => $this->request->getPost('rec_resolucion'),
                'rec_estado'        => $this->request->getPost('rec_estado'),
                'rec_fecha_resol'   => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
                'updated_by'        => $this->getAuditUserId(),
            ]);

        return $this->jsonResponse('success', 'Impugnación resuelta correctamente.');
    }

    /**
     * GET JSON: Lista de impugnaciones de una convocatoria
     */
    public function listarImpugnaciones(int $convocatoriaId)
    {
        $db = \Config\Database::connect();

        $data = $db->table('selec_reclamos r')
            ->select('
                r.*,
                p.pto_codigo,
                po.pos_nombres, po.pos_apellido_paterno, po.pos_apellido_materno,
                po.pos_documento
            ')
            ->join('selec_postulaciones p', 'p.pto_ide = r.rec_pto_ide', 'left')
            ->join('selec_postulantes po', 'po.pos_ide = p.pto_pos_ide', 'left')
            ->join('selec_convocatoria_cargos cc', 'cc.cco_ide = p.pto_cco_ide', 'left')
            ->where('cc.cco_con_ide', $convocatoriaId)
            ->where('r.deleted_at', null)
            ->orderBy('r.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return $this->jsonResponse('success', 'Impugnaciones cargadas.', $data);
    }
}

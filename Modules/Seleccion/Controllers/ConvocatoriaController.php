<?php

namespace Modules\Seleccion\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Seleccion\Models\ConvocatoriaModel;
use Modules\Seleccion\Models\TipoConvocatoriaModel;
use Modules\Seleccion\Models\EstadoConvocatoriaModel;
use Modules\Seleccion\Services\ConvocatoriaService;
use CodeIgniter\API\ResponseTrait;

class ConvocatoriaController extends BaseModuleController
{
    use ResponseTrait;

    protected ConvocatoriaModel $convocatoriaModel;
    protected TipoConvocatoriaModel $tipoModel;
    protected EstadoConvocatoriaModel $estadoModel;

    public function __construct()
    {
        $this->convocatoriaModel = new ConvocatoriaModel();
        $this->tipoModel         = new TipoConvocatoriaModel();
        $this->estadoModel       = new EstadoConvocatoriaModel();
    }

    /**
     * Vista principal de convocatorias (Admin)
     */
    public function index()
    {
        return view('Modules\Seleccion\Views\convocatorias\index');
    }

    /**
     * Vista de convocatorias vigentes (Postulante)
     */
    public function vigentes()
    {
        return view('Modules\Seleccion\Views\postulante\index', [
            'titulo'                => 'Mis Postulaciones y Vacantes',
            'misPostulaciones'      => [],
            'convocatoriasAbiertas' => [],
        ]);
    }

    // ==========================================
    // ENDPOINTS AJAX / API
    // ==========================================

    /**
     * GET: Lista completa para DataTable (Admin)
     * Corrección: aliases de tablas con prefijos selec_ correctos
     */
    public function listarApi()
    {
        $db = \Config\Database::connect();

        $data = $db->table('selec_convocatorias c')
            ->select('
                c.con_ide,
                c.con_codigo,
                c.con_numero,
                c.con_nombre,
                c.con_anio,
                c.con_regimen_laboral,
                c.con_tco_ide,
                c.con_eco_ide,
                c.con_fecha_publicacion,
                c.con_fecha_inicio,
                c.con_fecha_cierre,
                c.con_observacion,
                c.con_bases_pdf,
                tc.tco_nombre AS tipo_nombre,
                ec.eco_nombre AS estado_nombre,
                ec.eco_codigo AS estado_codigo
            ')
            ->join('selec_tipos_convocatoria tc', 'tc.tco_ide = c.con_tco_ide', 'left')
            ->join('selec_estados_convocatoria ec', 'ec.eco_ide = c.con_eco_ide', 'left')
            ->where('c.deleted_at', null)
            ->orderBy('c.con_anio', 'DESC')
            ->orderBy('c.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return $this->jsonResponse('success', 'Convocatorias listadas.', $data);
    }

    /**
     * GET: Lista convocatorias vigentes/publicadas (Postulante)
     */
    public function listar()
    {
        $db = \Config\Database::connect();

        $data = $db->table('selec_convocatorias c')
            ->select('
                c.con_ide, c.con_codigo, c.con_numero, c.con_nombre, c.con_anio,
                c.con_regimen_laboral, c.con_fecha_inicio, c.con_fecha_cierre,
                c.con_descripcion,
                tc.tco_nombre AS tipo_nombre,
                ec.eco_nombre AS estado_nombre, ec.eco_codigo AS estado_codigo,
                COUNT(DISTINCT cc.cco_ide) AS total_plazas
            ')
            ->join('selec_tipos_convocatoria tc', 'tc.tco_ide = c.con_tco_ide', 'left')
            ->join('selec_estados_convocatoria ec', 'ec.eco_ide = c.con_eco_ide', 'left')
            ->join('selec_convocatoria_cargos cc', 'cc.cco_con_ide = c.con_ide', 'left')
            ->where('c.deleted_at', null)
            ->groupBy('c.con_ide')
            ->orderBy('c.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return $this->jsonResponse('success', 'Convocatorias listadas.', $data);
    }

    /**
     * GET: Lista convocatorias vigentes para el postulante
     */
    public function listarVigentes()
    {
        $db  = \Config\Database::connect();
        $uid = auth()->id() ?? session()->get('user_id');

        $data = $db->table('selec_convocatorias c')
            ->select('
                c.con_ide, c.con_codigo, c.con_numero, c.con_nombre, c.con_anio,
                c.con_regimen_laboral, c.con_fecha_inicio, c.con_fecha_cierre, c.con_descripcion,
                tc.tco_nombre AS tipo_nombre,
                ec.eco_codigo AS estado_codigo, ec.eco_nombre AS estado_nombre,
                COUNT(DISTINCT cc.cco_ide) AS total_plazas,
                MAX(p.pto_ide) AS mi_postulacion_ide
            ')
            ->join('selec_tipos_convocatoria tc', 'tc.tco_ide = c.con_tco_ide', 'left')
            ->join('selec_estados_convocatoria ec', 'ec.eco_ide = c.con_eco_ide', 'left')
            ->join('selec_convocatoria_cargos cc', 'cc.cco_con_ide = c.con_ide', 'left')
            ->join('selec_postulaciones p', "p.pto_cco_ide = cc.cco_ide AND p.pto_pos_ide IN (SELECT pos_ide FROM selec_postulantes WHERE pos_user_id = {$uid})", 'left')
            ->where('c.deleted_at', null)
            ->groupBy('c.con_ide')
            ->orderBy('c.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return $this->jsonResponse('success', 'Convocatorias vigentes.', $data);
    }

    /**
     * POST: Registrar nueva convocatoria
     */
    public function guardar()
    {
        $rules = [
            'con_codigo'  => 'required|max_length[50]',
            'con_numero'  => 'required|max_length[50]',
            'con_anio'    => 'required|numeric',
            'con_nombre'  => 'required|max_length[255]',
            'con_tco_ide' => 'required|numeric',
            'con_eco_ide' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return $this->jsonResponse('error', 'Datos inválidos.', $this->validator->getErrors(), 422);
        }

        $datos = $this->request->getPost();

        // Subida del PDF de bases si viene
        $basePdf = $this->request->getFile('con_bases_pdf');
        if ($basePdf && $basePdf->isValid() && !$basePdf->hasMoved()) {
            $ruta = $this->uploadDocument($basePdf, 'seleccion/bases', ['pdf']);
            if ($ruta) {
                $datos['con_bases_pdf'] = $ruta;
            }
        }

        unset($datos['con_bases_pdf']); // Eliminar el campo de archivo del post
        if (isset($ruta)) {
            $datos['con_bases_pdf'] = $ruta;
        }

        $datos['created_by'] = $this->getAuditUserId();

        if (!$this->convocatoriaModel->insert($datos)) {
            return $this->jsonResponse('error', 'Error al guardar la convocatoria.', [], 500);
        }

        return $this->jsonResponse('success', 'Convocatoria registrada con éxito.', ['id' => $this->convocatoriaModel->getInsertID()], 201);
    }

    /**
     * GET: Obtener datos de una convocatoria para edición
     */
    public function editar(int $id)
    {
        $convocatoria = $this->convocatoriaModel->find($id);
        if (!$convocatoria) {
            return $this->jsonResponse('error', 'Convocatoria no encontrada.', [], 404);
        }
        return $this->jsonResponse('success', 'Convocatoria cargada.', $convocatoria);
    }

    /**
     * POST: Actualizar convocatoria existente
     */
    public function actualizar(int $id)
    {
        $rules = [
            'con_codigo'  => 'required|max_length[50]',
            'con_numero'  => 'required|max_length[50]',
            'con_anio'    => 'required|numeric',
            'con_nombre'  => 'required|max_length[255]',
            'con_tco_ide' => 'required|numeric',
            'con_eco_ide' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return $this->jsonResponse('error', 'Datos inválidos.', $this->validator->getErrors(), 422);
        }

        $datos = $this->request->getPost();
        $datos['updated_by'] = $this->getAuditUserId();

        // Subida del PDF de bases si viene
        $basePdf = $this->request->getFile('con_bases_pdf');
        if ($basePdf && $basePdf->isValid() && !$basePdf->hasMoved()) {
            $ruta = $this->uploadDocument($basePdf, 'seleccion/bases', ['pdf']);
            if ($ruta) {
                $datos['con_bases_pdf'] = $ruta;
            }
        }
        unset($datos['con_bases_pdf_file']); // Limpiar campo de archivo

        if (!$this->convocatoriaModel->update($id, $datos)) {
            return $this->jsonResponse('error', 'Error al actualizar.', [], 500);
        }

        return $this->jsonResponse('success', 'Convocatoria actualizada correctamente.');
    }

    /**
     * POST: Eliminar convocatoria (soft delete)
     */
    public function eliminar(int $id)
    {
        if (!$this->convocatoriaModel->delete($id)) {
            return $this->jsonResponse('error', 'No se pudo eliminar la convocatoria.', [], 500);
        }
        return $this->jsonResponse('success', 'Convocatoria eliminada correctamente.');
    }

    // ==========================================
    // LOOKUPS / CATÁLOGOS
    // ==========================================

    public function tiposLookup()
    {
        $data = $this->tipoModel
            ->select('tco_ide AS id, tco_nombre AS nombre')
            ->findAll();
        return $this->jsonResponse('success', 'Tipos cargados.', $data);
    }

    public function estadosLookup()
    {
        $data = $this->estadoModel
            ->select('eco_ide AS id, eco_nombre AS nombre')
            ->findAll();
        return $this->jsonResponse('success', 'Estados cargados.', $data);
    }

    // Mantener API REST para compatibilidad hacia atrás
    public function listarApi()    { return $this->listar(); }
    public function crearApi()     { return $this->guardar(); }
    public function actualizarApi($id = null) { return $this->actualizar((int)$id); }
    public function eliminarApi($id = null)   { return $this->eliminar((int)$id); }
}

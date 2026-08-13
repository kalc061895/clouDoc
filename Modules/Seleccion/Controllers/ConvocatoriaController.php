<?php

namespace Modules\Seleccion\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Seleccion\Services\ConvocatoriaService;
use Modules\Seleccion\Models\ConvocatoriaModel;
use Modules\Seleccion\Models\TipoConvocatoriaModel;
use Modules\Seleccion\Models\EstadoConvocatoriaModel;

use CodeIgniter\API\ResponseTrait;

class ConvocatoriaController extends BaseController
{

    use ResponseTrait;

    protected $convocatoriaModel;
    protected $tipoModel;
    protected $estadoModel;

    public function __construct()
    {
        $this->convocatoriaModel = new ConvocatoriaModel();
        $this->tipoModel        = new TipoConvocatoriaModel();
        $this->estadoModel      = new EstadoConvocatoriaModel();
    }

    /**
     * Carga la Vista HTML principal
     */
    public function index()
    {
        return view('convocatorias/index');
    }

    // ==========================================
    // ENDPOINTS AJAX / API
    // ==========================================

    /**
     * GET: Lista completa con JOINs para DataTable
     */
    public function listarApi()
    {
        $data = $this->convocatoriaModel
            ->select('
                convocatoria.con_ide,
                convocatoria.con_codigo,
                convocatoria.con_numero,
                convocatoria.con_nombre,
                convocatoria.con_anio,
                convocatoria.con_tco_ide,
                convocatoria.con_eco_ide,
                convocatoria.con_fecha_publicacion,
                convocatoria.con_fecha_inicio,
                convocatoria.con_fecha_cierre,
                convocatoria.con_observacion,
                tipo.tco_nombre AS tipo_nombre,
                estado.eco_nombre AS estado_nombre
            ')
            ->join('tipo_convocatoria tipo', 'tipo.tco_ide = convocatoria.con_tco_ide', 'left')
            ->join('estado_convocatoria estado', 'estado.eco_ide = convocatoria.con_eco_ide', 'left')
            ->where('convocatoria.deleted_at', null)
            ->findAll();

        return $this->respond([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    /**
     * POST: Registrar nueva convocatoria
     */
    public function crearApi()
    {
        $rules = [
            'con_codigo'  => 'required|max_length[50]',
            'con_numero'  => 'required|max_length[50]',
            'con_anio'    => 'required|numeric',
            'con_nombre'  => 'required|max_length[255]',
            'con_tco_ide' => 'required|numeric',
            'con_eco_ide' => 'required|numeric',
            'con_responsable_ide' => 'permit_empty|is_not_unique[users.id]',
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors(), 400);
        }

        $datos = $this->request->getPost();

        if ($this->convocatoriaModel->insert($datos)) {
            return $this->respondCreated([
                'status'  => 'success',
                'message' => 'Convocatoria registrada con éxito.'
            ]);
        }

        return $this->failServerError('Error al intentar guardar el registro.');
    }

    /**
     * PUT: Actualizar convocatoria existente
     */
    public function actualizarApi($id = null)
    {
        $datos = $this->request->getRawInput();

        $rules = [
            'con_codigo'  => 'required|max_length[50]',
            'con_numero'  => 'required|max_length[50]',
            'con_anio'    => 'required|numeric',
            'con_nombre'  => 'required|max_length[255]',
            'con_tco_ide' => 'required|numeric',
            'con_eco_ide' => 'required|numeric',
            'con_responsable_ide' => 'permit_empty|is_not_unique[users.id]',
        ];

        if (!$this->validateData($datos, $rules)) {
            return $this->fail($this->validator->getErrors(), 400);
        }

        if ($this->convocatoriaModel->update($id, $datos)) {
            return $this->respond([
                'status'  => 'success',
                'message' => 'Convocatoria actualizada correctamente.'
            ]);
        }

        return $this->failServerError('Error al intentar actualizar el registro.');
    }

    /**
     * DELETE: Eliminar convocatoria
     */
    public function eliminarApi($id = null)
    {
        if ($this->convocatoriaModel->delete($id)) {
            return $this->respond([
                'status'  => 'success',
                'message' => 'Convocatoria eliminada correctamente.'
            ]);
        }

        return $this->failServerError('No se pudo eliminar el registro.');
    }

    // ==========================================
    // CATÁLOGOS / LOOKUPS
    // ==========================================

    /**
     * GET: Carga selector de Tipos
     */
    public function tiposLookup()
    {
        $data = $this->tipoModel
            ->select('tco_ide AS id, tco_nombre AS nombre')
            ->findAll();

        return $this->respond([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    /**
     * GET: Carga selector de Estados
     */
    public function estadosLookup()
    {
        $data = $this->estadoModel
            ->select('eco_ide AS id, eco_nombre AS nombre')
            ->findAll();

        return $this->respond([
            'status' => 'success',
            'data'   => $data
        ]);
    }
}

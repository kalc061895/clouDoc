<?php

namespace Modules\Asistencia\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use Modules\Asistencia\Models\LicenciaModel;
use Modules\Asistencia\Models\RegistroLicenciaModel;
use Modules\Asistencia\Services\LicenciaService;
use Modules\Asistencia\Services\RegistroLicenciaService;
use Modules\Asistencia\Models\RegistroLicenciaHistorialModel;

class LicenciaController extends BaseController
{
    use ResponseTrait;

    protected $licenciaModel;
    protected $registroLicenciaModel;
    protected $registroLicenciaService;
    protected $historialModel;
    protected $db;

    public function __construct()
    {
        $this->licenciaModel = new LicenciaModel();
        $this->registroLicenciaModel = new RegistroLicenciaModel();
        $this->registroLicenciaService = new RegistroLicenciaService();
        $this->historialModel = new RegistroLicenciaHistorialModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * GET: /api/v1/licencias/tipos-activos
     * Retorna el catálogo de tipos de licencias disponibles
     */
    public function tiposActivos()
    {
        $tipos = $this->licenciaModel->activos();
        return $this->respond([
            'status' => 200,
            'data' => $tipos
        ]);
    }

    /**
     * GET: /api/v1/licencias/personal/(:num)
     * Obtiene el listado de licencias/papeletas de un trabajador con filtros de Mes y Año
     */
    public function obtenerPorPersonal($perlIde)
    {
        $licenciaService = new RegistroLicenciaService();
        $mes  = $this->request->getGet('mes');
        $anio = $this->request->getGet('anio');

        // Delegamos la lógica de consulta de negocio al Service
        $licencias = $licenciaService->obtenerLicenciasPorPersonal((int)$perlIde, $mes, $anio);

        return $this->respond([
            'status' => 200,
            'data'   => $licencias
        ]);
    }

    /**
     * POST: /api/v1/licencias/guardar
     * Guarda un nuevo registro de licencia
     */
    public function guardar()
    {
        $registroLicenciaService = new RegistroLicenciaService();
        $usuarioId = session()->get('user_id') ?? session()->get('usu_ide') ?? 1;
        $ip = $this->request->getIPAddress();

        // Reglas de validación
        $rules = [
            'rl_perl_ide'     => 'required|integer',
            'rl_lic_ide'      => 'required|integer',
            'rl_fecha_inicio' => 'required|valid_date',
            'rl_fecha_fin'    => 'required|valid_date',
            'anexos.*'        => 'permit_empty|uploaded[anexos]|max_size[anexos,10240]|mime_in[anexos,application/pdf,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $datosInsert = [
            'rl_perl_ide'         => $this->request->getPost('rl_perl_ide'),
            'rl_lic_ide'          => $this->request->getPost('rl_lic_ide'),
            'rl_fecha_inicio'     => $this->request->getPost('rl_fecha_inicio'),
            'rl_fecha_fin'        => $this->request->getPost('rl_fecha_fin'),
            'rl_numero_documento' => $this->request->getPost('rl_numero_documento'),
            'rl_fecha_documento'  => $this->request->getPost('rl_fecha_documento') ?: null,
            'rl_motivo'           => $this->request->getPost('rl_motivo'),
            'rl_estado'           => 1,
            'created_by'          => $usuarioId
        ];

        // Obtener archivos adjuntos
        $archivos = $this->request->getFiles()['anexos'] ?? $this->request->getFile('anexos');

        try {
            // Llamada al Service (Valida el período e inserta)
            $rlIde = $registroLicenciaService->crearLicencia($datosInsert, $archivos, $ip, $usuarioId);

            return $this->respondCreated([
                'status'  => 201,
                'message' => 'Licencia y adjuntos registrados correctamente.',
                'id'      => $rlIde
            ]);
        } catch (\Exception $e) {
            // Retorna error 400 legible para el cliente AJAX/API cuando falla el período o la transacción
            return $this->fail($e->getMessage(), 400);
        }
    }

    /**
     * POST: /api/v1/licencias/eliminar/(:num)
     */
    public function eliminar($rlIde)
    {
        $licenciaService = new RegistroLicenciaService();
        try {
            $usuarioId    = session()->get('user_id') ?? session()->get('usu_ide') ?? 1;
            $ip           = $this->request->getIPAddress();
            $motivoCambio = $this->request->getPost('motivo_cambio') ?? 'Eliminación del registro';

            $licenciaService->eliminarLicencia((int) $rlIde, (int) $usuarioId, $ip, $motivoCambio);

            return $this->respond([
                'status'  => 200,
                'message' => 'Licencia eliminada y auditada con éxito.'
            ]);

        } catch (\Exception $e) {
            // Manejo de códigos HTTP específicos según la excepción
            if ($e->getCode() === 404) {
                return $this->failNotFound($e->getMessage());
            }

            return $this->failServerError($e->getMessage());
        }
    }
}

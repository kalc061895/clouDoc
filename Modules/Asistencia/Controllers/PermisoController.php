<?php

namespace Modules\Asistencia\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Asistencia\Services\RegistroPermisoService;
use CodeIgniter\API\ResponseTrait;
class PermisoController extends BaseController
{
    use ResponseTrait;

    protected $permisoService;

    public function __construct()
    {
        $this->permisoService = new RegistroPermisoService();
    }

    /**
     * GET /api/permisos/personal/{perIde}
     */
    public function listarPorPersonal($perIde = null)
    {
        if (!$perIde) {
            return $this->fail('Identificador de personal no proporcionado.', 400);
        }

        $mes  = $this->request->getGet('mes');
        $anio = $this->request->getGet('anio');

        $data = $this->permisoService->obtenerPermisosPorPersonal((int)$perIde, $mes ? (int)$mes : null, $anio ? (int)$anio : null);

        return $this->respond([
            'status' => true,
            'data'   => $data
        ]);
    }

    /**
     * POST /api/permisos/guardar
     */
    public function guardar()
    {
        // 1. Reglas de Validación de Inputs
        $rules = [
            'per_ide'          => 'required|integer',
            'tipo_permiso_id'  => 'required|integer',
            'perm_fecha'       => 'required|valid_date[Y-m-d]',
            'perm_hora_inicio' => 'required',
            'perm_hora_fin'    => 'required',
            'adjuntos.*'       => 'max_size[adjuntos,5120]|ext_in[adjuntos,pdf,png,jpg,jpeg]' // Máx 5MB por archivo
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors(), 422);
        }

        $datos = $this->request->getPost();
        $archivos = [
            'adjuntos' => $this->request->getFileMultiple('adjuntos')
        ];

        // 2. Ejecutar Guardado en Servicio
        $resultado = $this->permisoService->registrarPermiso($datos, $archivos);

        if (!$resultado['status']) {
            return $this->fail($resultado['message'], 400);
        }

        return $this->respondCreated([
            'status'  => true,
            'message' => $resultado['message'],
            'perm_ide' => $resultado['perm_ide']
        ]);
    }

    /**
     * POST /api/permisos/eliminar/{id}
     */
    public function eliminar($id = null)
    {
        if (!$id) {
            return $this->fail('ID de papeleta no válido.', 400);
        }

        $permisoModel = new PermisoModel();

        // Soft delete (desactivación lógica)
        $updated = $permisoModel->update($id, ['perm_estado' => 0]);

        if (!$updated) {
            return $this->fail('No se pudo anular el permiso.', 500);
        }

        return $this->respond([
            'status'  => true,
            'message' => 'Papeleta de permiso anulada correctamente.'
        ]);
    }
}

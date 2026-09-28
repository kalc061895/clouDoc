<?php

namespace Modules\Asistencia\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Asistencia\Services\RegistroPermisoService;
use Modules\Asistencia\Models\PermisoModel;
use CodeIgniter\API\ResponseTrait;

class PermisoController extends BaseController
{

    use ResponseTrait;

    protected $permisoModel;
    protected $registroPermisoService;

    public function __construct()
    {
        $this->permisoModel           = new PermisoModel();
        $this->registroPermisoService = new RegistroPermisoService();
    }

    /**
     * GET: /permiso/api/tipos-activos
     */
    public function tiposActivos()
    {
        $tipos = $this->permisoModel->activos();
        return $this->respond([
            'status' => 200,
            'data'   => $tipos
        ]);
    }

    /**
     * GET: /permiso/api/personal/(:num)
     */
    public function obtenerPorPersonal($perlIde)
    {
        $mes  = $this->request->getGet('mes');
        $anio = $this->request->getGet('anio');

        $permisos = $this->registroPermisoService->obtenerPermisosPorPersonal((int)$perlIde, $mes, $anio);

        return $this->respond([
            'status' => 200,
            'data'   => $permisos
        ]);
    }

    /**
     * POST: /permiso/api/guardar
     */
    public function guardar()
    {
        $usuarioId = session()->get('user_id') ?? session()->get('usu_ide') ?? 1;
        $ip        = $this->request->getIPAddress();

        $rules = [
            'rp_perl_ide'    => 'required|integer',
            'rp_pero_ide'    => 'required|integer',
            'rp_fecha'       => 'required|valid_date[Y-m-d]',
            'rp_hora_salida' => 'required',
            'rp_hora_retorno'    => 'required',
            'anexos.*'       => 'permit_empty|uploaded[anexos]|max_size[anexos,10240]|mime_in[anexos,application/pdf,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $horaInicio = $this->request->getPost('rp_hora_salida');
        $horaFin    = $this->request->getPost('rp_hora_retorno');

        if (strtotime($horaFin) <= strtotime($horaInicio)) {
            return $this->failValidationErrors([
                'rp_hora_retorno' => 'La hora fin debe ser posterior a la hora de inicio.'
            ]);
        }

        $datosInsert = [
            'rp_perl_ide'         => $this->request->getPost('rp_perl_ide'),
            'rp_pero_ide'         => $this->request->getPost('rp_pero_ide'),
            'rp_fecha'            => $this->request->getPost('rp_fecha'),
            'rp_hora_salida'      => $horaInicio,
            'rp_hora_retorno'         => $horaFin,
            'rp_numero_documento' => $this->request->getPost('rp_numero_documento'),
            'rp_fecha_documento'  => $this->request->getPost('rp_fecha_documento') ?: null,
            'rp_motivo'           => $this->request->getPost('rp_motivo'),
            'rp_estado'           => 1,
            'created_by'          => $usuarioId
        ];

        $archivos = $this->request->getFiles()['anexos'] ?? $this->request->getFile('anexos');

        try {
            $rpIde = $this->registroPermisoService->crearPermiso($datosInsert, $archivos, $ip, $usuarioId);

            return $this->respondCreated([
                'status'  => 201,
                'message' => 'Permiso / Papeleta registrada correctamente.',
                'id'      => $rpIde
            ]);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 400);
        }
    }

    /**
     * POST: /permiso/api/eliminar/(:num)
     */
    public function eliminar($rpIde)
    {
        try {
            $usuarioId    = session()->get('user_id') ?? session()->get('usu_ide') ?? 1;
            $ip           = $this->request->getIPAddress();
            $motivoCambio = $this->request->getPost('motivo_cambio');

            if (empty(trim($motivoCambio))) {
                return $this->failValidationErrors(['motivo_cambio' => 'El motivo de eliminación es obligatorio.']);
            }

            $this->registroPermisoService->eliminarPermiso((int) $rpIde, (int) $usuarioId, $ip, $motivoCambio);

            return $this->respond([
                'status'  => 200,
                'message' => 'Permiso eliminado y auditado con éxito.'
            ]);
        } catch (\Exception $e) {
            if ($e->getCode() === 404) {
                return $this->failNotFound($e->getMessage());
            }

            return $this->failServerError($e->getMessage());
        }
    }
}

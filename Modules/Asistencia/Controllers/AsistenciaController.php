<?php

namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Asistencia\Services\MarcacionService;
use Modules\Asistencia\Models\EstablecimientoModel;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AsistenciaController extends BaseModuleController
{
    protected MarcacionService $marcacionService;
    protected EstablecimientoModel $establecimientoModel;

    public function __construct()
    {
        $this->marcacionService     = new MarcacionService();
        $this->establecimientoModel = new EstablecimientoModel();
    }

    /**
     * Pantalla principal de consulta de registros de asistencia / marcaciones reales
     */
    public function index()
    {
        $establecimientos = $this->establecimientoModel
            ->select('est_ide, est_nombre, est_codigo')
            ->where('deleted_at', null)
            ->orderBy('est_nombre', 'ASC')
            ->findAll();

        return view('Modules\Asistencia\Views\asistencia\index', [
            'titulo'           => 'Registros de Asistencia y Marcaciones',
            'establecimientos' => $establecimientos,
            'fechaInicioDef'   => date('Y-m-01'),
            'fechaFinDef'      => date('Y-m-t'),
        ]);
    }

    /**
     * Endpoint Server-Side DataTables para listar marcaciones con filtros avanzados
     */
    public function apiListar()
    {
        $request = $this->request;

        $draw   = (int) ($request->getGet('draw') ?? 1);
        $start  = (int) ($request->getGet('start') ?? 0);
        $length = (int) ($request->getGet('length') ?? 25);
        $search = $request->getGet('search')['value'] ?? '';

        $orderArr = $request->getGet('order');
        $columnsArr = $request->getGet('columns');

        $orderColumnName = 'asi_fecha_hora';
        $orderDir = 'DESC';

        if (!empty($orderArr) && isset($orderArr[0]['column'])) {
            $colIdx = (int) $orderArr[0]['column'];
            $orderDir = $orderArr[0]['dir'] ?? 'DESC';
            if (isset($columnsArr[$colIdx]['data']) && !empty($columnsArr[$colIdx]['data'])) {
                $orderColumnName = $columnsArr[$colIdx]['data'];
            }
        }

        $filtros = [
            'fecha_inicio' => $request->getGet('fecha_inicio'),
            'fecha_fin'    => $request->getGet('fecha_fin'),
            'perl_ide'     => $request->getGet('perl_ide'),
            'dni'          => $request->getGet('dni'),
            'est_ide'      => $request->getGet('est_ide'),
            'dispositivo'  => $request->getGet('dispositivo'),
            'origen'       => $request->getGet('origen'),
            'tipo'         => $request->getGet('tipo'),
        ];

        $resultado = $this->marcacionService->obtenerMarcacionesDataTable(
            $filtros,
            $start,
            $length,
            $search,
            $orderColumnName,
            $orderDir
        );

        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $resultado['total'],
            'recordsFiltered' => $resultado['filtered'],
            'data'            => $resultado['data'],
            'csrf_token'      => csrf_token(),
            'csrf_hash'       => csrf_hash(),
        ]);
    }

    /**
     * Consulta detallada de marcaciones de un trabajador, contrastando contra turnos programados
     */
    public function apiDetalleTrabajador(int $perlIde)
    {
        $fechaInicio = $this->request->getGet('fecha_inicio') ?? date('Y-m-01');
        $fechaFin    = $this->request->getGet('fecha_fin') ?? date('Y-m-t');

        $detalle = $this->marcacionService->obtenerDetalleTrabajador($perlIde, $fechaInicio, $fechaFin);

        if (!$detalle) {
            return $this->jsonResponse('error', 'Trabajador no encontrado.', [], 404);
        }

        return $this->jsonResponse('success', 'Detalle de asistencia cargado.', $detalle);
    }

    /**
     * Registro manual de marcación con motivo obligatorio y auditoría
     */
    public function apiGuardarManual()
    {
        $reglas = [
            'asi_perl_ide'   => 'required|is_natural_no_zero',
            'asi_fecha_hora' => 'required|valid_date[Y-m-d H:i:s,Y-m-d H:i]',
            'asi_tipo'       => 'required|in_list[ENTRADA,SALIDA,REFRIGERIO_SALIDA,REFRIGERIO_RETORNO,OTRO]',
            'asi_motivo'     => 'required|min_length[5]|max_length[255]',
        ];

        if (!$this->validate($reglas)) {
            return $this->jsonResponse('error', 'Datos de marcación inválidos.', $this->validator->getErrors(), 422);
        }

        $datos = $this->request->getPost();
        $usuarioId = $this->getAuditUserId();

        $resp = $this->marcacionService->registrarMarcacionManual($datos, $usuarioId);

        if (!$resp['status']) {
            return $this->jsonResponse('error', $resp['message'], [], 400);
        }

        return $this->jsonResponse('success', $resp['message'], ['asi_ide' => $resp['asi_ide']]);
    }

    /**
     * Corrección de marcación existente con motivo obligatorio y auditoría
     */
    public function apiCorregir(int $asiIde)
    {
        $reglas = [
            'asi_fecha_hora' => 'required|valid_date[Y-m-d H:i:s,Y-m-d H:i]',
            'asi_tipo'       => 'required|in_list[ENTRADA,SALIDA,REFRIGERIO_SALIDA,REFRIGERIO_RETORNO,OTRO]',
            'asi_motivo'     => 'required|min_length[5]|max_length[255]',
        ];

        if (!$this->validate($reglas)) {
            return $this->jsonResponse('error', 'Datos de corrección inválidos.', $this->validator->getErrors(), 422);
        }

        $datos = $this->request->getPost();
        $usuarioId = $this->getAuditUserId();

        $resp = $this->marcacionService->corregirMarcacion($asiIde, $datos, $usuarioId);

        if (!$resp['status']) {
            return $this->jsonResponse('error', $resp['message'], [], 400);
        }

        return $this->jsonResponse('success', $resp['message']);
    }

    /**
     * Eliminación/Anulación lógica de marcación con motivo obligatorio
     */
    public function apiEliminar(int $asiIde)
    {
        $motivo = trim((string) $this->request->getPost('asi_motivo'));

        if (empty($motivo) || strlen($motivo) < 5) {
            return $this->jsonResponse('error', 'El motivo de anulación es obligatorio (mínimo 5 caracteres).', [], 422);
        }

        $usuarioId = $this->getAuditUserId();
        $resp = $this->marcacionService->eliminarMarcacion($asiIde, $motivo, $usuarioId);

        if (!$resp['status']) {
            return $this->jsonResponse('error', $resp['message'], [], 400);
        }

        return $this->jsonResponse('success', $resp['message']);
    }

    /**
     * Exportación a Excel de las marcaciones aplicando todos los filtros activos
     */
    public function exportarExcel()
    {
        $filtros = [
            'fecha_inicio' => $this->request->getGet('fecha_inicio'),
            'fecha_fin'    => $this->request->getGet('fecha_fin'),
            'perl_ide'     => $this->request->getGet('perl_ide'),
            'dni'          => $this->request->getGet('dni'),
            'est_ide'      => $this->request->getGet('est_ide'),
            'dispositivo'  => $this->request->getGet('dispositivo'),
            'origen'       => $this->request->getGet('origen'),
            'tipo'         => $this->request->getGet('tipo'),
        ];

        $spreadsheet = $this->marcacionService->exportarExcel($filtros);

        $nombreArchivo = 'Marcaciones_Asistencia_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$nombreArchivo}\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Métodos auxiliares para integración con Gestor de Personal
     */
    public function getPaneAsistencia(int $perlIde)
    {
        $calendario = new \Modules\Asistencia\Services\CalendarioPersonalService();
        try {
            [$fechaInicio, $fechaFin] = $calendario->periodo($this->request->getGet('fecha_inicio'));
        } catch (\InvalidArgumentException $e) {
            return $this->response->setStatusCode(400)->setBody($e->getMessage());
        }
        $detalle = $this->marcacionService->obtenerDetalleTrabajador($perlIde, $fechaInicio, $fechaFin);

        return view('Modules\Asistencia\Views\personal\modals\pane_asistencia_view', [
            'detalle' => $detalle,
            'fechaInicio' => $fechaInicio,
            'dias' => $calendario->dias($perlIde, $fechaInicio, $fechaFin, $detalle['dias'] ?? []),
            'perlIde' => $perlIde,
        ]);
    }

    public function getByPersonal(int $perlIde)
    {
        $fechaInicio = $this->request->getGet('fecha_inicio') ?? date('Y-m-01');
        $fechaFin    = $this->request->getGet('fecha_fin') ?? date('Y-m-t');

        $detalle = $this->marcacionService->obtenerDetalleTrabajador($perlIde, $fechaInicio, $fechaFin);
        return $this->jsonResponse('success', 'Asistencia cargada.', $detalle);
    }
}

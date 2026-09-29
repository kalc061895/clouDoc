<?php

namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Asistencia\Services\ProgramacionTurnoService;
use Modules\Asistencia\Models\EstablecimientoModel;
use Modules\Asistencia\Models\UpssModel;
use Modules\Asistencia\Models\UpssServicioModel;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProgramacionController extends BaseModuleController
{
    protected ProgramacionTurnoService $service;
    protected EstablecimientoModel $establecimientoModel;
    protected UpssModel $upssModel;
    protected UpssServicioModel $upssServicioModel;

    public function __construct()
    {
        $this->service              = new ProgramacionTurnoService();
        $this->establecimientoModel = new EstablecimientoModel();
        $this->upssModel            = new UpssModel();
        $this->upssServicioModel    = new UpssServicioModel();
    }

    /**
     * Pantalla mensual de programación de turnos (Vista Matriz y Calendario)
     */
    public function index()
    {
        $establecimientos = $this->establecimientoModel
            ->select('est_ide, est_nombre, est_codigo')
            ->where('deleted_at', null)
            ->orderBy('est_nombre', 'ASC')
            ->findAll();

        $upssList = $this->upssModel
            ->select('ups_ide, ups_nombre, ups_codigo')
            ->where('deleted_at', null)
            ->where('ups_estado', 1)
            ->orderBy('ups_nombre', 'ASC')
            ->findAll();

        $catalogoTurnos = $this->service->obtenerCatalogoTurnos();

        return view('Modules\Asistencia\Views\programacion\index', [
            'titulo'           => 'Programación Mensual de Turnos',
            'establecimientos' => $establecimientos,
            'upssList'         => $upssList,
            'oficinas' => (new \Modules\Asistencia\Models\OficinaModel())->orderBy('ofi_nombre')->findAll(),
            'serviciosUpss' => $this->upssServicioModel->where('uss_estado', 1)->orderBy('uss_nombre')->findAll(),
            'catalogoTurnos'   => $catalogoTurnos,
            'anioActual'       => (int) date('Y'),
            'mesActual'        => (int) date('n'),
        ]);
    }

    /**
     * Endpoint API para consultar la matriz mensual completa de programación
     */
    public function apiMatriz()
    {
        $anio    = (int) ($this->request->getGet('anio') ?? date('Y'));
        $mes     = (int) ($this->request->getGet('mes') ?? date('n'));
        $estIde  = !empty($this->request->getGet('est_ide')) ? (int) $this->request->getGet('est_ide') : null;
        $upsIde  = !empty($this->request->getGet('ups_ide')) ? (int) $this->request->getGet('ups_ide') : null;
        $ussIde  = !empty($this->request->getGet('uss_ide')) ? (int) $this->request->getGet('uss_ide') : null;
        $perlIde = !empty($this->request->getGet('perl_ide')) ? (int) $this->request->getGet('perl_ide') : null;

        if ($anio < 2000 || $anio > 2100 || $mes < 1 || $mes > 12) {
            return $this->jsonResponse('error', 'Año o mes inválido.', [], 422);
        }

        $datos = $this->service->obtenerMatrizMensual($anio, $mes, $estIde, $upsIde, $ussIde, $perlIde, (int) $this->request->getGet('ofi_ide') ?: null, trim((string) $this->request->getGet('dni')));

        return $this->jsonResponse('success', 'Matriz de programación cargada.', $datos);
    }

    /**
     * Endpoint API para FullCalendar de un trabajador o general
     */
    public function apiEventosCalendar()
    {
        $anio    = (int) ($this->request->getGet('anio') ?? date('Y'));
        $mes     = (int) ($this->request->getGet('mes') ?? date('n'));
        $perlIde = !empty($this->request->getGet('perl_ide')) ? (int) $this->request->getGet('perl_ide') : null;
        $estIde  = !empty($this->request->getGet('est_ide')) ? (int) $this->request->getGet('est_ide') : null;

        $matriz = $this->service->obtenerMatrizMensual($anio, $mes, $estIde, (int) $this->request->getGet('ups_ide') ?: null, (int) $this->request->getGet('uss_ide') ?: null, $perlIde, (int) $this->request->getGet('ofi_ide') ?: null, trim((string) $this->request->getGet('dni')));
        $eventos = [];

        foreach ($matriz['matriz'] as $trabajador) {
            foreach ($trabajador['dias'] as $dia => $turnos) {
                foreach ($turnos as $t) {
                    $fecha = $t['prog_fecha'];
                    $hIngreso = $t['th_hora_ingreso'] . ':00';
                    $hSalida  = $t['th_hora_salida'] . ':00';

                    $inicio = "{$fecha}T{$hIngreso}";
                    if ($hSalida <= $hIngreso) {
                        $fechaSig = date('Y-m-d', strtotime("{$fecha} +1 day"));
                        $fin = "{$fechaSig}T{$hSalida}";
                    } else {
                        $fin = "{$fecha}T{$hSalida}";
                    }

                    $eventos[] = [
                        'id'              => $t['prog_ide'],
                        'title'           => "{$t['tur_codigo']} - {$trabajador['trabajador']}" . ($t['prog_estado'] === 'CAMBIO TURNO' ? ' ? CAMBIO TURNO' : ''),
                        'start'           => $inicio,
                        'end'             => $fin,
                        'backgroundColor' => !empty($t['tur_color']) ? $t['tur_color'] : '#3B82F6',
                        'borderColor'     => !empty($t['tur_color']) ? $t['tur_color'] : '#3B82F6',
                        'extendedProps'   => [
                            'prog_ide'       => $t['prog_ide'],
                            'perl_ide'       => $trabajador['perl_ide'],
                            'trabajador'     => $trabajador['trabajador'],
                            'dni'            => $trabajador['dni'],
                            'tur_codigo'     => $t['tur_codigo'],
                            'tur_nombre'     => $t['tur_nombre'],
                            'horario'        => "{$t['th_hora_ingreso']} - {$t['th_hora_salida']}",
                            'duracion_horas' => $t['duracion_horas'],
                        ],
                    ];
                }
            }
        }

        return $this->response->setJSON($eventos);
    }

    /**
     * Asignación / Edición individual de turno con validación de cruces
     */
    public function apiGuardarIndividual()
    {
        $reglas = [
            'prog_perl_ide' => 'required|is_natural_no_zero',
            'prog_fecha'    => 'required|valid_date',
            'prog_th_ide'   => 'required|is_natural_no_zero',
        ];

        if (!$this->validate($reglas)) {
            return $this->jsonResponse('error', 'Datos de turno incompletos o inválidos.', $this->validator->getErrors(), 422);
        }

        $datos = $this->request->getPost();
        $usuarioId = $this->getAuditUserId();

        $resp = $this->service->asignarTurnoIndividual($datos, $usuarioId);

        if (!$resp['status']) {
            return $this->jsonResponse('error', $resp['message'], [], 400);
        }

        return $this->jsonResponse('success', $resp['message'], ['prog_ide' => $resp['prog_ide']]);
    }

    /**
     * Eliminación de turno asignado
     */
    public function apiEliminarIndividual(int $progIde)
    {
        $usuarioId = $this->getAuditUserId();
        $resp = $this->service->eliminarTurnoIndividual($progIde, $usuarioId);

        if (!$resp['status']) {
            return $this->jsonResponse('error', $resp['message'], [], 400);
        }

        return $this->jsonResponse('success', $resp['message']);
    }

    /**
     * Catálogo de turnos y horarios disponibles
     */
    public function apiTurnosCatalogo()
    {
        $catalogo = $this->service->obtenerCatalogoTurnos();
        return $this->jsonResponse('success', 'Catálogo de turnos cargado.', $catalogo);
    }

    /**
     * Descarga de plantilla Excel predeterminada para importación
     */
    public function descargarPlantilla()
    {
        $anio   = (int) ($this->request->getGet('anio') ?? date('Y'));
        $mes    = (int) ($this->request->getGet('mes') ?? date('n'));
        $estIde = !empty($this->request->getGet('est_ide')) ? (int) $this->request->getGet('est_ide') : null;

        $spreadsheet = $this->service->generarPlantillaExcel($anio, $mes, $estIde);

        $nombreArchivo = sprintf('Plantilla_Programacion_Turnos_%04d_%02d.xlsx', $anio, $mes);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$nombreArchivo}\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Previsualización interactiva de archivo Excel antes de aplicar importación
     */
    public function apiPrevisualizarExcel()
    {
        $archivo = $this->request->getFile('archivo_excel');
        $anio    = (int) $this->request->getPost('anio');
        $mes     = (int) $this->request->getPost('mes');
        $estIde  = !empty($this->request->getPost('est_ide')) ? (int) $this->request->getPost('est_ide') : null;

        if (!$archivo || !$archivo->isValid()) {
            return $this->jsonResponse('error', 'Debe seleccionar un archivo Excel válido (.xlsx).', [], 400);
        }

        $extension = strtolower($archivo->getClientExtension());
        if (!in_array($extension, ['xlsx', 'xls'])) {
            return $this->jsonResponse('error', 'Formato no admitido. Debe ser un archivo .xlsx de Excel.', [], 400);
        }

        // Mover a carpeta temporal segura
        $rutaTemp = WRITEPATH . 'uploads/' . $archivo->getRandomName();
        $archivo->move(WRITEPATH . 'uploads', basename($rutaTemp));

        $resultado = $this->service->previsualizarImportacionExcel($rutaTemp, $anio, $mes, $estIde);

        // Guardar temporalmente en sesión para procesar o descargar reporte de errores
        session()->set('temp_import_programacion', [
            'ruta_archivo'      => $rutaTemp,
            'anio'              => $anio,
            'mes'               => $mes,
            'est_ide'           => $estIde,
            'registros_validos' => $resultado['registros_validos'] ?? [],
            'errores'           => $resultado['errores'] ?? [],
        ]);

        if (!$resultado['status']) {
            if (file_exists($rutaTemp)) {
                @unlink($rutaTemp);
            }
            return $this->jsonResponse('error', $resultado['message'], [], 400);
        }

        return $this->jsonResponse('success', 'Archivo analizado correctamente.', $resultado);
    }

    /**
     * Confirmación y aplicación de la importación masiva en base de datos
     */
    public function apiProcesarImportacion()
    {
        $datosSesion = session()->get('temp_import_programacion');
        if (empty($datosSesion) || empty($datosSesion['registros_validos'])) {
            return $this->jsonResponse('error', 'No hay datos validados pendientes de importación. Suba el archivo nuevamente.', [], 400);
        }

        $reemplazar = (bool) $this->request->getPost('reemplazar_existentes');
        $estIde     = !empty($this->request->getPost('est_ide')) ? (int) $this->request->getPost('est_ide') : $datosSesion['est_ide'];
        $upsIde     = !empty($this->request->getPost('ups_ide')) ? (int) $this->request->getPost('ups_ide') : null;
        $ussIde     = !empty($this->request->getPost('uss_ide')) ? (int) $this->request->getPost('uss_ide') : null;
        $usuarioId  = $this->getAuditUserId();

        $resp = $this->service->procesarImportacionDefinitiva(
            $datosSesion['registros_validos'],
            $reemplazar,
            $estIde,
            $upsIde,
            $ussIde,
            $usuarioId
        );

        // Limpiar archivo temporal
        if (!empty($datosSesion['ruta_archivo']) && file_exists($datosSesion['ruta_archivo'])) {
            @unlink($datosSesion['ruta_archivo']);
        }
        session()->remove('temp_import_programacion');

        if (!$resp['status']) {
            return $this->jsonResponse('error', $resp['message'], [], 500);
        }

        return $this->jsonResponse('success', $resp['message'], $resp);
    }

    /**
     * Descarga de reporte Excel con los errores detectados en la validación
     */
    public function descargarReporteErrores()
    {
        $datosSesion = session()->get('temp_import_programacion');
        if (empty($datosSesion) || empty($datosSesion['errores'])) {
            return redirect()->back()->with('error', 'No hay reporte de errores disponible.');
        }

        $spreadsheet = $this->service->generarReporteErroresExcel(
            $datosSesion['errores'],
            $datosSesion['anio'],
            $datosSesion['mes']
        );

        $nombreArchivo = sprintf('Errores_Importacion_Turnos_%04d_%02d.xlsx', $datosSesion['anio'], $datosSesion['mes']);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$nombreArchivo}\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Exportación de la programación mensual en formato compatible con importación
     */
    public function exportarExcelImportable()
    {
        $anio   = (int) ($this->request->getGet('anio') ?? date('Y'));
        $mes    = (int) ($this->request->getGet('mes') ?? date('n'));
        $estIde = !empty($this->request->getGet('est_ide')) ? (int) $this->request->getGet('est_ide') : null;
        $upsIde = !empty($this->request->getGet('ups_ide')) ? (int) $this->request->getGet('ups_ide') : null;
        $ussIde = !empty($this->request->getGet('uss_ide')) ? (int) $this->request->getGet('uss_ide') : null;

        $spreadsheet = $this->service->exportarExcelImportable($anio, $mes, $estIde, $upsIde, $ussIde, (int) $this->request->getGet('ofi_ide') ?: null, trim((string) $this->request->getGet('dni')));
        $nombreArchivo = sprintf('Programacion_Turnos_Importable_%04d_%02d.xlsx', $anio, $mes);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$nombreArchivo}\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Exportación de la programación mensual en reporte institucional tipo matriz legible
     */
    public function exportarExcelReporte()
    {
        $anio   = (int) ($this->request->getGet('anio') ?? date('Y'));
        $mes    = (int) ($this->request->getGet('mes') ?? date('n'));
        $estIde = !empty($this->request->getGet('est_ide')) ? (int) $this->request->getGet('est_ide') : null;
        $upsIde = !empty($this->request->getGet('ups_ide')) ? (int) $this->request->getGet('ups_ide') : null;
        $ussIde = !empty($this->request->getGet('uss_ide')) ? (int) $this->request->getGet('uss_ide') : null;

        $spreadsheet = $this->service->exportarExcelReporteLegible($anio, $mes, $estIde, $upsIde, $ussIde, (int) $this->request->getGet('ofi_ide') ?: null, trim((string) $this->request->getGet('dni')));
        $nombreArchivo = sprintf('Reporte_Rol_Turnos_Mensual_%04d_%02d.xlsx', $anio, $mes);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$nombreArchivo}\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}

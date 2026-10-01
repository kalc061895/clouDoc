<?php

namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Asistencia\Services\ReporteMensualService;
use Modules\Asistencia\Services\ReporteMensualExporter;

class ReporteMensualController extends BaseModuleController
{
    public function index()
    {
        return view('Modules\Asistencia\Views\reportes\mensual', ['catalogos' => (new ReporteMensualService())->catalogos()]);
    }

    public function consultar()
    {
        try {
            $reporte = (new ReporteMensualService())->consultar($this->request->getGet());
            return $this->jsonResponse('success', 'Reporte consultado.', ['html' => view('Modules\Asistencia\Views\reportes\resultado', ['reporte' => $reporte]), 'total' => count($reporte['filas'])]);
        } catch (\Throwable $e) { return $this->error($e); }
    }

    public function exportar(string $formato)
    {
        try {
            if (! in_array($formato, ['excel', 'pdf', 'imprimir'], true)) throw new \InvalidArgumentException('Formato inválido.');
            $reporte = (new ReporteMensualService())->consultar($this->request->getGet());
            if ($formato === 'imprimir') return view('Modules\Asistencia\Views\reportes\imprimir', ['reporte' => $reporte, 'pdf' => false]);
            $exporter = new ReporteMensualExporter();
            $nombre = 'reporte-mensual-' . substr($reporte['inicio'], 0, 7);
            $bytes = $formato === 'pdf' ? $exporter->pdf($reporte) : $exporter->excel($reporte);
            return $this->response->setContentType($formato === 'pdf' ? 'application/pdf' : 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', '')
                ->setHeader('Content-Disposition', ($formato === 'pdf' ? 'inline' : 'attachment') . '; filename="' . $nombre . ($formato === 'pdf' ? '.pdf' : '.xlsx') . '"')
                ->setHeader('Cache-Control', 'private, no-store')->setHeader('X-Content-Type-Options', 'nosniff')->setBody($bytes);
        } catch (\Throwable $e) { return $this->error($e); }
    }

    private function error(\Throwable $e)
    {
        $code = $e instanceof \InvalidArgumentException ? 422 : 500;
        if ($code === 500) log_message('error', 'Reporte mensual: {message}', ['message' => $e->getMessage()]);
        return $this->jsonResponse('error', $code === 500 ? 'No se pudo generar el reporte. Revise el registro del servidor.' : $e->getMessage(), [], $code);
    }
}

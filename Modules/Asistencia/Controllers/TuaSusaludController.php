<?php

namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Asistencia\Services\TuaSusaludService;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TuaSusaludController extends BaseModuleController
{
    private function autorizado(): bool
    {
        return (new TuaSusaludService())->puedeAcceder();
    }

    public function index()
    {
        if (!$this->autorizado()) return $this->response->setStatusCode(403)->setBody('No tiene permiso para consultar TUA SUSALUD.');
        $service = new TuaSusaludService();
        $service = new TuaSusaludService();
        return view('Modules\\Asistencia\\Views\\reportes\\tua_susalud', ['establecimientoId' => (int)$this->request->getGet('establecimiento'), 'puedeConfigurar' => $service->puedeConfigurar()]);
    }

    public function catalogo()
    {
        if (!$this->autorizado()) return $this->response->setStatusCode(403)->setJSON(['ok' => false, 'message' => 'No tiene permiso para consultar este ámbito.']);
        return $this->response->setJSON(['ok' => true, 'establecimientos' => (new TuaSusaludService())->establecimientosAccesibles()]);
    }

    public function periodos()
    {
        if (!$this->autorizado()) return $this->response->setStatusCode(403)->setJSON(['ok' => false, 'message' => 'No tiene permiso para consultar períodos.']);
        try {
            return $this->response->setJSON(['ok' => true, 'periodos' => (new TuaSusaludService())->periodos((int)$this->request->getGet('establecimiento'))]);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(422)->setJSON(['ok' => false, 'message' => $e->getMessage()]);
        }
    }

    public function consultar()
    {
        if (!$this->autorizado()) return $this->response->setStatusCode(403)->setJSON(['ok' => false, 'message' => 'No tiene permiso para consultar TUA SUSALUD.']);
        try {
            $data = (new TuaSusaludService())->datos((int)$this->request->getGet('establecimiento'), trim((string)$this->request->getGet('periodo')));
            return $this->response->setJSON(['ok' => true, 'html' => view('Modules\\Asistencia\\Views\\reportes\\tua_susalud_tabla', ['reporte' => $data])]);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(422)->setJSON(['ok' => false, 'message' => $e->getMessage()]);
        }
    }

    public function excel()
    {
        if (!$this->autorizado()) return $this->response->setStatusCode(403)->setBody('No tiene permiso para exportar TUA SUSALUD.');
        try {
            $establecimiento = (int) $this->request->getGet('establecimiento');
            $mes = trim((string) $this->request->getGet('periodo'));
            $book = (new TuaSusaludService())->excel($establecimiento, $mes);
            ob_start();
            (new Xlsx($book))->save('php://output');
            $contenido = ob_get_clean();
            return $this->response->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                ->setHeader('Content-Disposition', 'attachment; filename="tua-susalud-' . $mes . '.xlsx"')->setHeader('Cache-Control', 'private, no-store')->setBody($contenido);
        } catch (\DomainException $e) {
            return $this->response->setStatusCode(422)->setBody(esc($e->getMessage()));
        } catch (\Throwable $e) {
            log_message('error', 'TUA SUSALUD Excel: {message}', ['message' => $e->getMessage()]);
            return $this->response->setStatusCode(500)->setBody('No se pudo generar el Excel.');
        }
    }

    public function asignaciones()
    {
        $service = new TuaSusaludService();
        if (!$service->puedeConfigurar()) return $this->response->setStatusCode(403)->setBody('No tiene permiso para configurar TUA SUSALUD.');
        if ($this->request->getMethod() === 'POST') {
            try {
                $id = $service->guardarRelacionServicioOficina($this->request->getPost());
                return $this->response->setJSON(['status' => 'success', 'message' => 'Relación guardada.', 'id' => $id, 'csrf' => ['name' => csrf_token(), 'hash' => csrf_hash()]]);
            } catch (\Throwable $e) {
                return $this->response->setStatusCode(422)->setJSON(['status' => 'error', 'message' => $e->getMessage(), 'messages' => ['error' => $e->getMessage()], 'csrf' => ['name' => csrf_token(), 'hash' => csrf_hash()]]);
            }
        }
        return view('Modules\\Asistencia\\Views\\reportes\\tua_susalud_asignaciones');
    }

    public function catalogoAsignacion()
    {
        $service = new TuaSusaludService();
        if (!$service->puedeConfigurar()) return $this->response->setStatusCode(403)->setJSON(['ok' => false, 'message' => 'No tiene permiso para configurar TUA SUSALUD.']);
        try {
            $est = (int)$this->request->getGet('establecimiento');
            return $this->response->setJSON(['ok' => true] + $service->catalogoAsignacion($est));
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(422)->setJSON(['ok' => false, 'message' => $e->getMessage()]);
        }
    }

    public function listarAsignaciones()
    {
        $service = new TuaSusaludService();
        if (!$service->puedeConfigurar()) return $this->response->setStatusCode(403)->setJSON(['data' => [], 'message' => 'No tiene permiso para consultar las asignaciones.']);
        try {
            return $this->response->setJSON(['data' => $service->listarAsignaciones((int)$this->request->getGet('establecimiento'))]);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(422)->setJSON(['data' => [], 'message' => $e->getMessage()]);
        }
    }

    public function eliminarAsignacion(int $id)
    {
        $service = new TuaSusaludService();
        if (!$service->puedeConfigurar()) return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'No tiene permiso para eliminar asignaciones.']);
        try {
            $service->eliminarRelacionServicioOficina($id);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Relación eliminada.']);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(422)->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}

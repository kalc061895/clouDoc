<?php

namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Asistencia\Services\GestionRolService;
use Modules\Asistencia\Services\RolEnvioService;
use Modules\Asistencia\Services\RolDocumentoService;

class RolEnvioController extends BaseModuleController
{
    public function index()
    {
        try {
            $service = new RolEnvioService();
            return view('Modules\\Asistencia\\Views\\gestion_roles\\envios', [
                'envios' => $service->listar(),
                'admin' => (new GestionRolService())->administrador(),
            ]);
        } catch (\Throwable $e) {
            return redirect()->to(site_url('asistencia/gestion-roles'))->with('error', $this->mensaje($e));
        }
    }

    public function detalle(int $id)
    {
        try {
            $service = new RolEnvioService();
            $envio = $service->detalle($id);
            $firma = \Config\Database::connect()->table('casis_rol_documento')->where('envio_id', $id)->get()->getRowArray();
            $gestion = new GestionRolService();
            return view('Modules\\Asistencia\\Views\\gestion_roles\\envio_detalle', [
                'envio' => $envio,
                'firmaDocumentoId' => $firma ? (int) $firma['id'] : null,
                'admin' => $gestion->administrador(),
                'puedeFirmar' => $firma && $envio['estado'] === 'APROBADO_DIRECCION' && $service->puedeFirmarDocumento((int) $firma['id'], (int) auth()->id()),
                'puedeRRHH' => $envio['estado'] === 'PENDIENTE_RRHH' && $this->tieneEtapa($gestion, 'RRHH', (int) $envio['id_oficina']) && (int) $envio['usuario_envio_id'] !== (int) auth()->id(),
                'puedeDireccion' => $envio['estado'] === 'VISTO_BUENO_RRHH' && $this->tieneEtapa($gestion, 'DIRECCION', (int) $envio['id_oficina']) && !in_array((int) auth()->id(), [(int) $envio['usuario_envio_id'], (int) $envio['usuario_rrhh_id']], true),
            ]);
        } catch (\Throwable $e) {
            return redirect()->to(site_url('asistencia/gestion-roles/envios'))->with('error', $this->mensaje($e));
        }
    }

    public function enviar(int $rolId)
    {
        try {
            $id = (new RolEnvioService())->enviar($rolId);
            return redirect()->to(site_url('asistencia/gestion-roles/envios/' . $id))->with('success', 'El rol se envió a Recursos Humanos y su snapshot quedó registrado.');
        } catch (\Throwable $e) {
            return redirect()->to(site_url('asistencia/gestion-roles'))->with('error', $this->mensaje($e));
        }
    }

    public function rrhh(int $id)
    {
        try {
            (new RolEnvioService())->revisarRRHH($id, (string) $this->request->getPost('accion'), (string) $this->request->getPost('comentario'));
            return redirect()->to(site_url('asistencia/gestion-roles/envios/' . $id))->with('success', 'La revisión de Recursos Humanos quedó registrada.');
        } catch (\Throwable $e) {
            return redirect()->to(site_url('asistencia/gestion-roles/envios/' . $id))->with('error', $this->mensaje($e));
        }
    }

    public function direccion(int $id)
    {
        try {
            (new RolEnvioService())->revisarDireccion($id, (string) $this->request->getPost('accion'), (string) $this->request->getPost('comentario'));
            return redirect()->to(site_url('asistencia/gestion-roles/envios/' . $id))->with('success', 'La decisión de Dirección quedó registrada.');
        } catch (\Throwable $e) {
            return redirect()->to(site_url('asistencia/gestion-roles/envios/' . $id))->with('error', $this->mensaje($e));
        }
    }

    public function pdf(int $id)
    {
        try {
            $flujo = new RolEnvioService();
            $envio = $flujo->oficial($id);
            $documentos = new RolDocumentoService();
            $existente = \Config\Database::connect()->table('casis_rol_documento')->where('envio_id', $id)->get()->getRowArray();
            if ($existente) {
                if ($existente['estado'] !== 'GENERADO') throw new \DomainException('El PDF oficial vinculado está anulado.');
                $archivo = $documentos->archivo((int) $existente['id']);
                return $this->response->setContentType('application/pdf')
                    ->setHeader('Content-Disposition', 'inline; filename="' . $archivo['nombre'] . '"')
                    ->setHeader('Cache-Control', 'private, no-store')
                    ->setBody(file_get_contents($archivo['ruta']));
            }
            $documento = $documentos->generarDesdeSnapshot($envio, (int) auth()->id());
            $archivo = $documentos->archivo((int) $documento['id']);
            return $this->response->setContentType('application/pdf')
                ->setHeader('Content-Disposition', 'inline; filename="' . $archivo['nombre'] . '"')
                ->setHeader('Cache-Control', 'private, no-store')
                ->setBody(file_get_contents($archivo['ruta']));
        } catch (\Throwable $e) {
            return redirect()->to(site_url('asistencia/gestion-roles/envios/' . $id))->with('error', $this->mensaje($e));
        }
    }

    private function tieneEtapa(GestionRolService $gestion, string $etapa, int $oficinaId): bool
    {
        $ids = $gestion->oficinas(true, $etapa);
        return $ids === null || in_array($oficinaId, $ids, true);
    }

    private function mensaje(\Throwable $e): string
    {
        if ($e instanceof \DomainException || $e instanceof \InvalidArgumentException || $e instanceof \OutOfBoundsException || $e instanceof \UnexpectedValueException) return $e->getMessage();
        log_message('error', 'Flujo de aprobación de roles: {message}', ['message' => $e->getMessage()]);
        return 'No se pudo completar la operación. Revise la configuración y el registro del servidor.';
    }
}

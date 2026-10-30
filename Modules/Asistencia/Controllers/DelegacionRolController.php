<?php

namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Asistencia\Services\DelegacionRolService;
use Modules\Asistencia\Services\GestionRolService;

class DelegacionRolController extends BaseModuleController
{
    public function index()
    {
        try {
            $gestion = new GestionRolService();
            $gestion->exigirAdministrador();
            $filtros = ['buscar' => trim((string) $this->request->getGet('buscar')), 'etapa' => (string) $this->request->getGet('etapa')];
            $db = \Config\Database::connect();
            return view('Modules\\Asistencia\\Views\\gestion_roles\\delegaciones', [
                'delegaciones' => (new DelegacionRolService())->listar($filtros['buscar'], $filtros['etapa']),
                'filtros' => $filtros,
                'usuarios' => $db->table(config('Auth')->tables['users'])->select('id, username, nombres, paterno, materno')->where('deleted_at', null)->where('active', 1)->orderBy('nombres')->orderBy('username')->get()->getResultArray(),
                'oficinas' => $db->table('casis_oficina')->select('ofi_ide, ofi_nombre, ofi_est_ide')->where('deleted_at', null)->where('ofi_estado', 1)->orderBy('ofi_nombre')->get()->getResultArray(),
            ]);
        } catch (\Throwable $e) {
            return redirect()->to(site_url('asistencia/gestion-roles'))->with('error', $this->mensaje($e));
        }
    }

    public function guardar()
    {
        try {
            $id = (new DelegacionRolService())->guardar($this->request->getPost());
            return redirect()->to(site_url('asistencia/gestion-roles/delegaciones'))->with('success', $this->request->getPost('id') ? 'La delegación fue actualizada.' : 'La delegación fue creada.')->with('editId', $id);
        } catch (\Throwable $e) {
            return redirect()->to(site_url('asistencia/gestion-roles/delegaciones'))->withInput()->with('error', $this->mensaje($e));
        }
    }

    public function eliminar(int $id)
    {
        try {
            (new DelegacionRolService())->eliminar($id);
            return redirect()->to(site_url('asistencia/gestion-roles/delegaciones'))->with('success', 'La delegación fue eliminada.');
        } catch (\Throwable $e) {
            return redirect()->to(site_url('asistencia/gestion-roles/delegaciones'))->with('error', $this->mensaje($e));
        }
    }

    private function mensaje(\Throwable $e): string
    {
        if ($e instanceof \DomainException || $e instanceof \InvalidArgumentException || $e instanceof \OutOfBoundsException) return $e->getMessage();
        log_message('error', 'CRUD de delegaciones de roles: {message}', ['message' => $e->getMessage()]);
        return 'No se pudo completar la operación. Revise el registro del servidor.';
    }
}

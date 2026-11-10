<?php

namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Asistencia\Services\GestionRolService;

class GestionRolController extends BaseModuleController
{
    public function index()
    {
        $g = new GestionRolService();
        $g->usuario();
        $g->exigirProgramador();
        $db = \Config\Database::connect();
        $ids = $g->oficinas();
        $roles = $db->table('casis_plan_rol r')->select('r.*, p.mes, p.apertura, p.aprobacion, p.cierre, o.ofi_nombre')->join('casis_plan_periodo p', 'p.id=r.periodo_id')->join('casis_oficina o', 'o.ofi_ide=r.oficina_id');
        if ($ids !== null) $roles->whereIn('r.oficina_id', $ids ?: [0]);
        return view('Modules\Asistencia\Views\gestion_roles\index', [
            'admin' => $g->administrador(),
            'roles' => $roles->orderBy('p.mes', 'DESC')->get()->getResultArray(),
        ]);
    }
    public function guardar(string $tipo)
    {
        $g = new GestionRolService();
        try {
            $g->exigirProgramador();
        } catch (\DomainException $e) {
            return redirect()->to(site_url('asistencia/gestion-roles/envios'))->with('error', $e->getMessage());
        }
        $d = $this->request->getPost();
        try {
            if ($tipo === 'periodo') (new \Modules\Asistencia\Services\ConfiguracionRolService())->guardarPeriodo($d);
            elseif ($tipo === 'responsable') $g->responsable($d);
            elseif ($tipo === 'estado') {
                if (($d['accion'] ?? '') === 'PRESENTAR') (new \Modules\Asistencia\Services\RolEnvioService())->enviar((int)($d['rol_id'] ?? 0));
                else $g->transicion((int)($d['rol_id'] ?? 0), (string)($d['accion'] ?? ''), (string)($d['motivo'] ?? ''));
            }
            else throw new \DomainException('Invalid action.');
            $destino = 'asistencia/gestion-roles';
            return redirect()->to(site_url($destino))->with('success', 'Operación guardada.');
        } catch (\DomainException | \JsonException $e) {
            $destino = 'asistencia/gestion-roles';
            return redirect()->to(site_url($destino))->with('error', $e->getMessage());
        }
    }
}

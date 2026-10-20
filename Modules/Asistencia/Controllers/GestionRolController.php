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
        $db = \Config\Database::connect();
        $ids = $g->oficinas();
        $oficinas = $db->table('casis_oficina')->where('deleted_at', null)->orderBy('ofi_nombre')->get()->getResultArray();
        if ($ids !== null) $oficinas = array_values(array_filter($oficinas, fn($o) => in_array((int)$o['ofi_ide'], $ids, true)));
        $roles = $db->table('casis_plan_rol r')->select('r.*, p.mes, p.apertura, p.aprobacion, p.cierre, o.ofi_nombre')->join('casis_plan_periodo p', 'p.id=r.periodo_id')->join('casis_oficina o', 'o.ofi_ide=r.oficina_id');
        if ($ids !== null) $roles->whereIn('r.oficina_id', $ids ?: [0]);
        $periodos = $db->table('casis_plan_periodo')->orderBy('mes', 'DESC')->get()->getResultArray();
        if ($ids !== null) $periodos = array_values(array_filter($periodos, fn($p) => in_array($p['est_id'], array_column($oficinas, 'ofi_est_ide'))));
        foreach ($periodos as &$p) $p['etapa'] = GestionRolService::etapa($p, $g->ahora());
        unset($p);
        return view('Modules\Asistencia\Views\gestion_roles\index', [
            'admin' => $g->administrador(),
            'periodos' => $periodos,
            'roles' => $roles->orderBy('p.mes', 'DESC')->get()->getResultArray(),
            'oficinas' => $oficinas,
            'establecimientos' => $db->table('casis_establecimiento')->where('deleted_at', null)->get()->getResultArray(),
            'responsables' => $g->administrador() ? $db->table('casis_plan_responsable')->orderBy('id', 'DESC')->get()->getResultArray() : [],
        ]);
    }
    public function guardar(string $tipo)
    {
        $g = new GestionRolService();
        $d = $this->request->getPost();
        try {
            if ($tipo === 'periodo') $g->crearPeriodo($d);
            elseif ($tipo === 'responsable') $g->responsable($d);
            elseif ($tipo === 'estado') $g->transicion((int)($d['rol_id'] ?? 0), (string)($d['accion'] ?? ''), (string)($d['motivo'] ?? ''));
            elseif ($tipo === 'modalidad') {
                $g->exigirAdministrador();
                if (!in_array($d['modalidad'] ?? '', ['ASISTENCIAL', 'ADMINISTRATIVO'], true)) throw new \DomainException('Modalidad inválida.');
                if (($d['entidad'] ?? '') !== 'turno') throw new \DomainException('Entidad inválida.');
                $g->transaccion(function () use ($g, $d) {
                    $db = \Config\Database::connect();
                    $table = 'casis_turno';
                    $prefix = 'tur';
                    $row = $db->table($table)->where($prefix . '_ide', (int)$d['id'])->where('deleted_at', null)->get()->getRowArray();
                    if (!$row) throw new \DomainException('Registro no encontrado.');
                    $db->table($table)->where($prefix . '_ide', (int)$d['id'])->update([$prefix . '_modalidad' => $d['modalidad']]);
                    $g->evento('CAMBIAR_MODALIDAD', ['entidad' => $table, 'id' => $d['id'], 'anterior' => $row[$prefix . '_modalidad'], 'nuevo' => $d['modalidad']]);
                });
            } else throw new \DomainException('Acción inválida.');
            return redirect()->to(site_url('asistencia/gestion-roles'))->with('success', 'Operación guardada.');
        } catch (\DomainException | \JsonException $e) {
            return redirect()->to(site_url('asistencia/gestion-roles'))->with('error', $e->getMessage());
        }
    }
}

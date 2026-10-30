<?php

namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Asistencia\Services\GestionRolService;

class GestionRolController extends BaseModuleController
{
    public function turnos()
    {
        $g = new GestionRolService();
        try {
            $g->exigirAdministrador();
            $turnos = \Config\Database::connect()->table('casis_turno')->select('tur_ide, tur_codigo, tur_nombre, tur_modalidad, tur_estado')->where('deleted_at', null)->orderBy('tur_codigo')->get()->getResultArray();
            return view('Modules\\Asistencia\\Views\\gestion_roles\\turnos', ['admin' => true, 'turnos' => $turnos]);
        } catch (\Throwable $e) {
            return redirect()->to(site_url('asistencia/gestion-roles'))->with('error', $e->getMessage());
        }
    }

    public function index()
    {
        $g = new GestionRolService();
        $g->usuario();
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
        $d = $this->request->getPost();
        try {
            if ($tipo === 'periodo') (new \Modules\Asistencia\Services\ConfiguracionRolService())->guardarPeriodo($d);
            elseif ($tipo === 'responsable') $g->responsable($d);
            elseif ($tipo === 'estado') {
                if (($d['accion'] ?? '') === 'PRESENTAR') (new \Modules\Asistencia\Services\RolEnvioService())->enviar((int)($d['rol_id'] ?? 0));
                else $g->transicion((int)($d['rol_id'] ?? 0), (string)($d['accion'] ?? ''), (string)($d['motivo'] ?? ''));
            }
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
            $destino = $tipo === 'modalidad' ? 'asistencia/gestion-roles/turnos' : 'asistencia/gestion-roles';
            return redirect()->to(site_url($destino))->with('success', 'Operación guardada.');
        } catch (\DomainException | \JsonException $e) {
            $destino = $tipo === 'modalidad' ? 'asistencia/gestion-roles/turnos' : 'asistencia/gestion-roles';
            return redirect()->to(site_url($destino))->with('error', $e->getMessage());
        }
    }
}

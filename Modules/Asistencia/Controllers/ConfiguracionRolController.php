<?php

namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Asistencia\Services\ConfiguracionRolService;
use Modules\Asistencia\Services\GrupoProgramacionRolService;

class ConfiguracionRolController extends BaseModuleController
{
    public function index(string $tipo = 'periodos')
    {
        $g = new ConfiguracionRolService();
        $g->usuario();
        if (!$g->administrador()) return $this->response->setStatusCode(403)->setBody('Solo el administrador de Asistencia puede configurar períodos y plantillas.');
        $db = db_connect();
        $periodos = $db->table('casis_plan_periodo p')->select('p.*, g.nombre AS grupo_nombre, g.nivel, g.entidad_id, e.est_nombre, m.mic_nombre, r.red_nombre, d.dir_nombre')
            ->join('casis_plan_grupo g','g.id=p.grupo_id','left')->join('casis_establecimiento e',"g.nivel='ESTABLECIMIENTO' AND e.est_ide=g.entidad_id",'left')
            ->join('casis_microred m',"g.nivel='MICRORED' AND m.mic_ide=g.entidad_id",'left')->join('casis_red r',"g.nivel='RED' AND r.red_ide=g.entidad_id",'left')->join('casis_diresa d',"g.nivel='DIRESA' AND d.dir_ide=g.entidad_id",'left')
            ->orderBy('mes', 'DESC')->get()->getResultArray();
        foreach ($periodos as &$p) $p['alcance_nombre'] = $p['est_nombre'] ?: ($p['mic_nombre'] ?: ($p['red_nombre'] ?: ($p['dir_nombre'] ?: 'Sin grupo')));
        foreach ($periodos as &$p) $p['etapa'] = $g::etapa($p, $g->ahora());
        foreach ($periodos as &$p) {
            $inicio = $p['mes'] . '-01';
            $fin = (new \DateTimeImmutable($inicio))->modify('last day of this month')->format('Y-m-d');
            $establecimientosPeriodo = (new GrupoProgramacionRolService($db))->establecimientosDePeriodo($p);
            $p['tiene_datos'] = $db->table('casis_plan_rol')->where('periodo_id', (int)$p['id'])->countAllResults() > 0
                || ($establecimientosPeriodo && $db->table('casis_programacion pr')->join('casis_personal pe', 'pe.perl_ide=pr.prog_perl_ide')->whereIn('pe.perl_est_ide', $establecimientosPeriodo)->where('pr.prog_fecha >=', $inicio)->where('pr.prog_fecha <=', $fin)->where('pr.deleted_at', null)->countAllResults() > 0);
        }
        unset($p);
        $plantillas = $db->table('casis_plan_plantilla')->where('deleted_at', null)->orderBy('nombre')->get()->getResultArray();
        $editar = null;
        $id = (int)$this->request->getGet('editar');
        foreach ($tipo === 'periodos' ? $periodos : $plantillas as $fila) if ((int)$fila['id'] === $id) $editar = $fila;
        if ($id && !$editar) return $this->response->setStatusCode(404)->setBody('Registro no encontrado.');
        return view('Modules\Asistencia\Views\gestion_roles\configuracion', [
            'tipo' => $tipo, 'editar' => $editar, 'periodos' => $periodos, 'plantillas' => $plantillas,
            'admin' => true,
            'grupos' => (new GrupoProgramacionRolService($db))->listar(),
            'establecimientos' => $db->table('casis_establecimiento')->where('deleted_at', null)->orderBy('est_nombre')->get()->getResultArray(),
            'oficinas' => $db->table('casis_oficina')->where('deleted_at', null)->orderBy('ofi_nombre')->get()->getResultArray(),
            'regimenes' => $db->table('casis_modalidad_contrato')->where('deleted_at', null)->orderBy('mco_nombre')->get()->getResultArray(),
            'turnos' => $db->table('casis_turno')->where('deleted_at', null)->orderBy('tur_codigo')->get()->getResultArray(),
        ]);
    }

    public function guardar(string $tipo, string $accion)
    {
        if (!in_array($tipo, ['periodos', 'plantillas'], true) || !in_array($accion, ['guardar', 'eliminar'], true)) return $this->response->setStatusCode(404);
        $g = new ConfiguracionRolService();
        $datos = $this->request->getPost();
        $id = (int)($datos['id'] ?? 0);
        $url = site_url('asistencia/gestion-roles/' . $tipo);
        try {
            if ($tipo === 'periodos') {
                if ($accion === 'eliminar') $g->eliminarPeriodo($id);
                else $g->guardarPeriodo($datos, $id);
            } else {
                if ($accion === 'eliminar') $g->eliminarPlantilla($id);
                else $g->guardarPlantilla($datos, $id);
            }
            return redirect()->to($url)->with('success', $accion === 'eliminar' ? 'Registro eliminado.' : 'Configuración guardada.');
        } catch (\DomainException | \JsonException $e) {
            return redirect()->to($url . ($accion === 'guardar' && $id ? '?editar=' . $id : ''))->withInput()->with('error', $e->getMessage());
        }
    }
}

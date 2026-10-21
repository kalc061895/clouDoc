<?php

namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Asistencia\Services\ConfiguracionRolService;

class ConfiguracionRolController extends BaseModuleController
{
    public function index(string $tipo = 'periodos')
    {
        $g = new ConfiguracionRolService();
        $g->usuario();
        if (!$g->administrador()) return $this->response->setStatusCode(403)->setBody('Solo el administrador de Asistencia puede configurar períodos y plantillas.');
        $db = db_connect();
        $periodos = $db->table('casis_plan_periodo p')->select('p.*, e.est_nombre')->join('casis_establecimiento e', 'e.est_ide = p.est_id')->orderBy('mes', 'DESC')->get()->getResultArray();
        foreach ($periodos as &$p) $p['etapa'] = $g::etapa($p, $g->ahora());
        unset($p);
        $plantillas = $db->table('casis_plan_plantilla')->where('deleted_at', null)->orderBy('nombre')->get()->getResultArray();
        $editar = null;
        $id = (int)$this->request->getGet('editar');
        foreach ($tipo === 'periodos' ? $periodos : $plantillas as $fila) if ((int)$fila['id'] === $id) $editar = $fila;
        if ($id && !$editar) return $this->response->setStatusCode(404)->setBody('Registro no encontrado.');
        return view('Modules\Asistencia\Views\gestion_roles\configuracion', [
            'tipo' => $tipo, 'editar' => $editar, 'periodos' => $periodos, 'plantillas' => $plantillas,
            'admin' => true,
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

<?php

namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Asistencia\Services\GrupoProgramacionRolService;

class GrupoProgramacionRolController extends BaseModuleController
{
    public function index()
    {
        $service = new GrupoProgramacionRolService();
        if (!auth()->loggedIn() || !auth()->user()->inGroup('superadmin','asistencia','asi_sua','asi_adm','asi_dm')) return $this->response->setStatusCode(403)->setBody('Sin permiso.');
        $db = db_connect();
        $grupos = $service->listar();
        foreach ($grupos as &$g) {
            $g['alcance_nombre'] = $g['est_nombre'] ?: ($g['mic_nombre'] ?: ($g['red_nombre'] ?: $g['dir_nombre']));
            $g['periodos'] = (int)$db->table('casis_plan_periodo')->where('grupo_id',(int)$g['id'])->countAllResults();
        }
        unset($g);
        return view('Modules\\Asistencia\\Views\\gestion_roles\\grupos', [
            'grupos'=>$grupos,
            'diresas'=>$db->table('casis_diresa')->where('deleted_at',null)->orderBy('dir_nombre')->get()->getResultArray(),
            'redes'=>$db->table('casis_red')->where('deleted_at',null)->orderBy('red_nombre')->get()->getResultArray(),
            'microredes'=>$db->table('casis_microred')->where('deleted_at',null)->orderBy('mic_nombre')->get()->getResultArray(),
            'establecimientos'=>$db->table('casis_establecimiento')->where('deleted_at',null)->orderBy('est_nombre')->get()->getResultArray(),
        ]);
    }

    public function guardar()
    {
        try {
            (new GrupoProgramacionRolService())->guardar($this->request->getPost(),(int)$this->request->getPost('id'));
            return redirect()->to(site_url('asistencia/gestion-roles/grupos'))->with('success','Grupo de programación guardado.');
        } catch (\Throwable $e) {
            return redirect()->to(site_url('asistencia/gestion-roles/grupos'))->withInput()->with('error',$e->getMessage());
        }
    }

    public function eliminar(int $id)
    {
        try {
            (new GrupoProgramacionRolService())->eliminar($id);
            return redirect()->to(site_url('asistencia/gestion-roles/grupos'))->with('success','Grupo de programación eliminado.');
        } catch (\Throwable $e) {
            return redirect()->to(site_url('asistencia/gestion-roles/grupos'))->with('error',$e->getMessage());
        }
    }
}

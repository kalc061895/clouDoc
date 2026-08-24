<?php

namespace Modules\Seleccion\Controllers;

use App\Controllers\BaseController;
use Modules\Seleccion\Services\ComisionService;

class ComisionConvocatoriaController extends BaseController
{
    private ComisionService $service;
    public function __construct()
    {
        $this->service = new ComisionService();
    }
    public function listar(int $conId)
    {
        return $this->response->setJSON(['ok' => true, 'data' => $this->service->listar($conId)]);
    }
    public function guardar()
    {
        if (!$this->validate(['com_con_ide' => 'required|is_natural_no_zero', 'com_numero' => 'required|max_length[50]', 'com_fecha_designacion' => 'required|valid_date[Y-m-d]', 'com_estado' => 'required|in_list[ACTIVA,INACTIVA]']))
            return $this->responder(['ok' => false, 'code' => 422, 'message' => implode(' ', $this->validator->getErrors())]);
        return $this->responder($this->service->guardar($this->request->getPost()));
    }
    public function eliminar(int $id)
    {
        return $this->responder($this->service->eliminar($id));
    }
    public function miembros(int $id)
    {
        return $this->response->setJSON(['ok' => true, 'data' => $this->service->miembros($id)]);
    }
    public function guardarMiembro()
    {
        if (!$this->validate(['cmi_com_ide' => 'required|is_natural_no_zero', 'cmi_usu_ide' => 'required|is_natural_no_zero', 'cmi_tipo' => 'required|in_list[PRESIDENTE,SECRETARIO,MIEMBRO]', 'cmi_fecha_inicio' => 'permit_empty|valid_date', 'cmi_fecha_fin' => 'permit_empty|valid_date']))
            return $this->responder(['ok' => false, 'code' => 422, 'message' => implode(' ', $this->validator->getErrors())]);
        return $this->responder($this->service->guardarMiembro($this->request->getPost()));
    }
    public function eliminarMiembro(int $id)
    {
        return $this->responder($this->service->eliminarMiembro($id));
    }
    public function usuarios()
    {
        $db = db_connect();

        $usuarios = $db->table('users u')
            ->select('u.id, u.username, u.nombres, u.paterno, u.materno, u.cargo')
            ->join('auth_groups_users agu', 'agu.user_id = u.id')
            ->where('agu.group', 'comision')
            ->orderBy('u.username', 'ASC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'ok'   => true,
            'data' => $usuarios,
        ]);
    }
    private function responder(array $r)
    {
        return $this->response->setStatusCode($r['code'])->setJSON($r);
    }
}

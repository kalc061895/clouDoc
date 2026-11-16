<?php

namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Asistencia\Services\GestionRolService;
use Modules\Asistencia\Services\UsuarioAsistenciaService;

class UsuarioController extends BaseModuleController
{
    public function index()
    {
        try { (new GestionRolService())->exigirAdministrador(); }
        catch (\DomainException $e) { return $this->response->setStatusCode(403)->setBody(esc($e->getMessage())); }
        return view('Modules\Asistencia\Views\usuarios\index');
    }

    private function responder(callable $accion)
    {
        try {
            (new GestionRolService())->exigirAdministrador();
            return $this->response->setJSON(['ok'=>true, 'data'=>$accion(new UsuarioAsistenciaService()), 'csrfHash'=>csrf_hash()]);
        } catch (\Throwable $e) {
            $esperado = $e instanceof \DomainException || $e instanceof \InvalidArgumentException;
            if (!$esperado) log_message('error', 'Usuarios de Asistencia: {error}', ['error'=>$e->getMessage()]);
            return $this->response->setStatusCode($e instanceof \DomainException ? 403 : ($esperado ? 422 : 500))->setJSON(['ok'=>false, 'message'=>$esperado ? $e->getMessage() : 'No se pudo completar la operación.', 'csrfHash'=>csrf_hash()]);
        }
    }

    public function datos() { return $this->responder(fn($s) => ['usuarios'=>$s->listar(), 'perfiles'=>$s->perfiles()]); }
    public function detalle(int $id) { return $this->responder(fn($s) => $s->detalle($id)); }
    public function dni() { return $this->responder(fn($s) => $s->buscarDni(trim((string)$this->request->getGet('dni')))); }
    public function guardar() { return $this->responder(fn($s) => ['id'=>$s->guardar($this->request->getPost(), (int)auth()->id())]); }
    public function eliminar(int $id) { return $this->responder(function($s) use ($id) { $s->eliminar($id, (int)auth()->id()); return ['id'=>$id]; }); }
}

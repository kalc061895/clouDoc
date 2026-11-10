<?php

namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Asistencia\Services\EstablecimientoUpssService;
use Modules\Asistencia\Services\AccesoAsistenciaService;

class EstablecimientoUpssController extends BaseModuleController
{
    public function index()
    {
        if(!(new AccesoAsistenciaService())->permitir('lectura_catalogos'))return $this->response->setStatusCode(403)->setBody('No tiene acceso a catálogos de Asistencia.');
        return view('Modules\\Asistencia\\Views\\database\\establecimiento_upss_view');
    }

    public function catalogos()
    {
        try{return $this->response->setJSON(['status'=>'success']+(new EstablecimientoUpssService())->catalogos());}
        catch(\Throwable $e){return $this->error($e);}
    }

    public function listar()
    {
        try{return $this->response->setJSON(['status'=>'success','data'=>(new EstablecimientoUpssService())->listar($this->request->getGet())]);}
        catch(\Throwable $e){return $this->error($e);}
    }

    public function crear()
    {
        try{$id=(new EstablecimientoUpssService())->crear($this->request->getPost());return $this->response->setStatusCode(201)->setJSON(['status'=>'success','message'=>'La UPSS fue vinculada al establecimiento.','id'=>$id,'csrfHash'=>csrf_hash()]);}
        catch(\Throwable $e){return $this->error($e);}
    }

    public function actualizar(int $id)
    {
        try{(new EstablecimientoUpssService())->actualizar($id,$this->request->getRawInput());return $this->response->setJSON(['status'=>'success','message'=>'La relación fue actualizada.','csrfHash'=>csrf_hash()]);}
        catch(\Throwable $e){return $this->error($e);}
    }

    public function eliminar(int $id)
    {
        try{(new EstablecimientoUpssService())->eliminar($id);return $this->response->setJSON(['status'=>'success','message'=>'La relación fue archivada.','csrfHash'=>csrf_hash()]);}
        catch(\Throwable $e){return $this->error($e);}
    }

    private function error(\Throwable $e)
    {
        $status=$e instanceof \DomainException ? 422 : 500;
        if($status===500)log_message('error','CRUD establecimiento-UPSS: {message}',['message'=>$e->getMessage()]);
        return $this->response->setStatusCode($status)->setJSON(['status'=>'error','message'=>$status===500?'No se pudo completar la operación.':$e->getMessage(),'csrfHash'=>csrf_hash()]);
    }
}

<?php

namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Asistencia\Services\AccesoAsistenciaService;
use Modules\Asistencia\Services\EstablecimientoUpssServicioService;

class EstablecimientoUpssServicioController extends BaseModuleController
{
    public function index()
    {
        if(!(new AccesoAsistenciaService())->permitir('lectura_catalogos'))return $this->response->setStatusCode(403)->setBody('No tiene acceso a catálogos de Asistencia.');
        return view('Modules\\Asistencia\\Views\\database\\establecimiento_upss_servicios_view');
    }
    public function catalogos(){try{return $this->response->setJSON(['status'=>'success']+(new EstablecimientoUpssServicioService())->catalogos());}catch(\Throwable $e){return $this->fallo($e);}}
    public function listar(){try{return $this->response->setJSON(['status'=>'success','data'=>(new EstablecimientoUpssServicioService())->listar($this->request->getGet())]);}catch(\Throwable $e){return $this->fallo($e);}}
    public function crear(){try{$id=(new EstablecimientoUpssServicioService())->crear($this->request->getPost());return $this->response->setStatusCode(201)->setJSON(['status'=>'success','message'=>'Servicio vinculado a la UPSS del establecimiento.','id'=>$id,'csrfHash'=>csrf_hash()]);}catch(\Throwable $e){return $this->fallo($e);}}
    public function actualizar(int $id){try{(new EstablecimientoUpssServicioService())->actualizar($id,$this->request->getRawInput());return $this->response->setJSON(['status'=>'success','message'=>'La relación fue actualizada.','csrfHash'=>csrf_hash()]);}catch(\Throwable $e){return $this->fallo($e);}}
    public function eliminar(int $id){try{(new EstablecimientoUpssServicioService())->eliminar($id);return $this->response->setJSON(['status'=>'success','message'=>'La relación fue archivada.','csrfHash'=>csrf_hash()]);}catch(\Throwable $e){return $this->fallo($e);}}
    private function fallo(\Throwable $e){$code=$e instanceof \DomainException?422:500;if($code===500)log_message('error','CRUD establecimiento-UPSS-servicio: {message}',['message'=>$e->getMessage()]);return $this->response->setStatusCode($code)->setJSON(['status'=>'error','message'=>$code===500?'No se pudo completar la operación.':$e->getMessage(),'csrfHash'=>csrf_hash()]);}
}

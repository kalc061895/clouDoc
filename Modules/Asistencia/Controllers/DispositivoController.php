<?php
namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Asistencia\Services\DispositivoService;

class DispositivoController extends BaseModuleController
{
    public function index()
    {
        $s=new DispositivoService();
        try {
            $s->usuario(true);
            $id=(int)$this->request->getGet('editar');
            return view('Modules\Asistencia\Views\dispositivos\configuracion',[
                'equipos'=>$s->listar(),'establecimientos'=>$s->establecimientos(),'editar'=>$id?$s->dispositivo($id):[],
            ]);
        } catch (\DomainException $e) { return $this->response->setStatusCode(403)->setBody(esc($e->getMessage())); }
    }
    public function guardar()
    {
        if (!$this->request->is('post') || service('router')->getMatchedRoute() === null) {
            return $this->response->setStatusCode(405)->setJSON(['status'=>'error', 'message'=>'Use el formulario de dispositivos.']);
        }
        try {
            $servicio = new DispositivoService();
            $servicio->guardar($this->request->getPost());
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status'=>'success', 'message'=>'Dispositivo guardado.',
                    'equipos'=>$servicio->listar(), 'csrf'=>['name'=>csrf_token(), 'hash'=>csrf_hash()],
                ]);
            }
            return redirect()->to(site_url('asistencia/dispositivos'))->with('success','Dispositivo guardado.');
        } catch (\DomainException $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(422)->setJSON([
                    'status'=>'error', 'message'=>$e->getMessage(), 'csrf'=>['name'=>csrf_token(), 'hash'=>csrf_hash()],
                ]);
            }
            return redirect()->to(site_url('asistencia/dispositivos'))->with('error',$e->getMessage());
        } catch (\Throwable $e) {
            log_message('error', 'Guardar dispositivo: {error}', ['error'=>$e->getMessage()]);
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(500)->setJSON([
                    'status'=>'error', 'message'=>'No se pudo completar la operación. Revise el listado antes de reintentar.',
                    'csrf'=>['name'=>csrf_token(), 'hash'=>csrf_hash()],
                ]);
            }
            return redirect()->to(site_url('asistencia/dispositivos'))->with('error','No se pudo guardar el dispositivo.');
        }
    }
    public function archivos()
    {
        $s=new DispositivoService();
        try {
            $s->usuario(); $pagina=max(1,(int)$this->request->getGet('pagina'));
            return view('Modules\Asistencia\Views\dispositivos\archivos',['equipos'=>$s->listar(),'historial'=>$s->historial($pagina),'pagina'=>$pagina]);
        } catch (\DomainException $e) { return $this->response->setStatusCode(403)->setBody(esc($e->getMessage())); }
    }
    public function cargar()
    {
        try {
            $s=new DispositivoService(); $s->usuario();
            $file=$this->request->getFile('archivo');
            if (!$file) throw new \DomainException('Seleccione el archivo del reloj.');
            $id=$s->recibir($file,(int)$this->request->getPost('dispositivo_id'));
            return redirect()->to(site_url('asistencia/dispositivos/cargas/'.$id));
        } catch (\DomainException $e) { return redirect()->to(site_url('asistencia/dispositivos/archivos'))->with('error',$e->getMessage()); }
    }
    public function detalle(int $id)
    {
        try {
            $carga=(new DispositivoService())->carga($id);
            return view('Modules\Asistencia\Views\dispositivos\detalle',['carga'=>$carga]);
        } catch (\DomainException $e) { return $this->response->setStatusCode(403)->setBody(esc($e->getMessage())); }
    }
    public function revisar(int $id)
    {
        try { (new DispositivoService())->revisar($id); }
        catch (\DomainException $e) { return redirect()->to(site_url('asistencia/dispositivos/archivos'))->with('error',$e->getMessage()); }
        return redirect()->to(site_url('asistencia/dispositivos/cargas/'.$id));
    }
    public function confirmar(int $id)
    {
        try {
            (new DispositivoService())->confirmar($id,$this->request->getIPAddress());
            return redirect()->to(site_url('asistencia/dispositivos/cargas/'.$id))->with('success','Marcaciones importadas. Se conservaron las ya existentes.');
        } catch (\DomainException $e) {
            return redirect()->to(site_url('asistencia/dispositivos/cargas/'.$id))->with('error',$e->getMessage());
        }
    }
    public function descargar(int $id)
    {
        try {
            $s=new DispositivoService(); $c=$s->carga($id);
            return $this->response->download($s->ruta($c),null)->setFileName($c['imp_codigo'].'.'.$c['imp_tipo_archivo']);
        } catch (\DomainException $e) { return $this->response->setStatusCode(403)->setBody(esc($e->getMessage())); }
    }
    // Compatibilidad con los dos accesos antiguos; ambos usan la misma vista.
    public function loadZtkeco() { return redirect()->to(site_url('asistencia/dispositivos/archivos')); }
    public function loadHikvision() { return redirect()->to(site_url('asistencia/dispositivos/archivos')); }
}

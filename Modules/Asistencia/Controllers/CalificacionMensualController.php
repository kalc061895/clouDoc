<?php
namespace Modules\Asistencia\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Asistencia\Services\CalificacionMensualService;

class CalificacionMensualController extends BaseModuleController
{
    public function index()
    {
        $s=new CalificacionMensualService();
        return view('Modules\Asistencia\Views\calificacion\index',$s->catalogos()+['historial'=>$s->historial(),'criterios'=>$s->criterios(),'admin'=>$s->administrador()]);
    }
    public function detalle(int $id)
    {
        $s=new CalificacionMensualService();
        try { return view('Modules\Asistencia\Views\calificacion\detalle',['c'=>$s->obtener($id),'eventos'=>$s->eventos($id),'pdfs'=>$s->pdfs($id)]); }
        catch (\DomainException|\OutOfBoundsException $e) { return $this->response->setStatusCode(403)->setBody(esc($e->getMessage())); }
    }
    public function guardar(string $accion, int $id=0)
    {
        // El proyecto mantiene autorouting legado: las escrituras solo usan rutas POST con CSRF.
        if (!$this->request->is('post') || service('router')->getMatchedRoute()===null) {
            return $this->response->setStatusCode(405)->setBody('Use el formulario de calificación.');
        }
        $s=new CalificacionMensualService();
        try {
            switch ($accion) {
                case 'generar': $id=$s->generar($this->request->getPost()); break;
                case 'criterios': $s->guardarCriterios($this->request->getPost()); break;
                case 'pdf': $s->generarPdf($id); break;
                default: $s->accion($id,strtoupper($accion),(string)$this->request->getPost('motivo')); break;
            }
            return redirect()->to(site_url('asistencia/calificacion'.($id?'/'.$id:'')))->with('success','Operación completada.');
        } catch (\InvalidArgumentException|\DomainException|\OutOfBoundsException $e) {
            return redirect()->to(site_url('asistencia/calificacion'.($id?'/'.$id:'')))->with('error',$e->getMessage());
        } catch (\Throwable $e) {
            log_message('error','Calificación mensual: {error}',['error'=>$e->getMessage()]);
            return redirect()->to(site_url('asistencia/calificacion'))->with('error','No se pudo completar la operación. Revise las migraciones y el registro del servidor.');
        }
    }
    public function pdf(int $id)
    {
        try {
            $p=(new CalificacionMensualService())->archivo($id);
            return $this->response->setContentType('application/pdf')->setHeader('Content-Disposition','inline; filename="calificacion-'.$id.'.pdf"')->setHeader('Cache-Control','private, no-store')->setBody($p['contenido']);
        } catch (\DomainException|\OutOfBoundsException $e) { return $this->response->setStatusCode(403)->setBody(esc($e->getMessage())); }
    }
}

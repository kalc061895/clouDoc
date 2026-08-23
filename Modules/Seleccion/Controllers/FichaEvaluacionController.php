<?php
namespace Modules\Seleccion\Controllers;
use App\Controllers\BaseController;
use Modules\Seleccion\Services\FichaEvaluacionService;
class FichaEvaluacionController extends BaseController
{
 private FichaEvaluacionService $service; public function __construct(){$this->service=new FichaEvaluacionService();}
 public function listar(int $id){return $this->response->setJSON(['ok'=>true,'data'=>$this->service->fichas($id)]);} public function guardar(){if(!$this->validate(['fie_con_ide'=>'required|is_natural_no_zero','fie_nombre'=>'required|max_length[255]','fie_tipo'=>'required|max_length[50]','fie_version'=>'required|is_natural_no_zero','fie_estado'=>'required|in_list[ACTIVA,INACTIVA]','fie_puntaje_maximo'=>'required|numeric|greater_than_equal_to[0]']))return $this->invalid();return $this->out($this->service->guardarFicha($this->request->getPost()));} public function eliminar(int $id){return $this->out($this->service->eliminarFicha($id));}
 public function criterios(int $id){return $this->response->setJSON(['ok'=>true,'data'=>$this->service->criterios($id)]);} public function guardarCriterio(){if(!$this->validate(['cri_fie_ide'=>'required|is_natural_no_zero','cri_nombre'=>'required|max_length[255]','cri_puntaje_maximo'=>'required|numeric|greater_than_equal_to[0]','cri_orden'=>'required|is_natural_no_zero']))return $this->invalid();return $this->out($this->service->guardarCriterio($this->request->getPost()));} public function eliminarCriterio(int $id){return $this->out($this->service->eliminarCriterio($id));}
 public function reglas(int $id){return $this->response->setJSON(['ok'=>true,'data'=>$this->service->reglas($id)]);} public function guardarRegla(){if(!$this->validate(['rpu_cri_ide'=>'required|is_natural_no_zero','rpu_puntaje'=>'required|numeric|greater_than_equal_to[0]','rpu_orden'=>'required|is_natural_no_zero','rpu_valor_min'=>'permit_empty|numeric','rpu_valor_max'=>'permit_empty|numeric']))return $this->invalid();return $this->out($this->service->guardarRegla($this->request->getPost()));} public function eliminarRegla(int $id){return $this->out($this->service->eliminarRegla($id));}
 private function out(array $r){return $this->response->setStatusCode($r['code'])->setJSON($r);}
 private function invalid(){return $this->out(['ok'=>false,'code'=>422,'message'=>implode(' ', $this->validator->getErrors())]);}
}

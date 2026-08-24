<?php

namespace Modules\Seleccion\Controllers;

use App\Controllers\BaseController;
use Modules\Seleccion\Models\{AnexoModel, ConvocatoriaCargoModel, PostulacionAnexoModel, PostulacionDeclaracionModel, PostulanteCapacitacionModel, PostulanteExperienciaModel, PostulanteFormacionModel, PostulanteProfesionModel, TipoDeclaracionModel, ProfesionModel, NivelFormacionModel, ModalidadVinculoModel,TipoDocumentoModel};
use Modules\Seleccion\Services\InscripcionService;

class InscripcionController extends BaseController
{
    private InscripcionService $inscripcion;
    public function __construct()
    {
        $this->inscripcion = new InscripcionService();
    }
    private function usuario(): int
    {
        $id = auth()->id() ?? session()->get('id') ?? session()->get('user_id');
        if (!$id)
            throw new \DomainException('Debe iniciar sesión para continuar.');
        return (int) $id;
    }
    private function json(callable $accion)
    {
        try {
            return $this->response->setJSON(['status' => true, 'message' => 'Operación realizada correctamente.', 'data' => $accion()]);
        } catch (\Throwable $e) {
            log_message('error', 'Inscripción: {message}', ['message' => $e->getMessage()]);
            return $this->response->setStatusCode($e instanceof \DomainException ? 422 : 500)->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function partial(int $convocatoriaId, string $tab)
    {
        try {
            $user = $this->usuario();
            $post = $this->inscripcion->postulacionActual($user, $convocatoriaId);
            $pos = $this->inscripcion->postulanteActual($user);
            $data = [
                'convocatoriaId' => $convocatoriaId,
                'postulacion' => $post,
                'postulante' => $pos,
                'editable' => !$post || (!(bool) $post['pto_confirmado'] && strtoupper($post['epo_codigo']) !== 'PRESENTADO')
            ];
            switch ($tab) {
                case 'plaza':
                    $data['plazas'] = (new ConvocatoriaCargoModel())->getCargosPorConvocatoria($convocatoriaId);
                    break;
                case 'datos':
                    $data['tipo_documentos'] = (
                        new TipoDocumentoModel())
                        ->where('tdo_estado', 'ACTIVO')
                        ->findAll();

                    break;
                case 'profesional':
                    $data['registros'] = (new PostulanteProfesionModel())->where('ppr_pos_ide', $pos['pos_ide'] ?? 0)
                        ->join('selec_expediente_documentos', 'exd_ide = ppr_documento_ide', 'left')
                        ->join('selec_profesiones', 'pro_ide = ppr_pro_ide', 'left')
                        ->findAll();
                    $data['profesion'] = (new ProfesionModel())->where('pro_estado', 'ACTIVO')->findAll();

                    break;
                case 'academica':
                    $data['registros'] = (new PostulanteFormacionModel())->where('pfo_pos_ide', $pos['pos_ide'] ?? 0)
                        ->join('selec_niveles_formacion', 'nfo_ide = pfo_nfo_ide', 'left')
                        ->join('selec_expediente_documentos', 'exd_ide = pfo_documento_ide', 'left')
                        ->findAll();
                    $data['niveles'] = (new NivelFormacionModel())->where('nfo_estado', 'ACTIVO')->findAll();
                    break;
                case 'experiencia':
                    $data['registros'] = (new PostulanteExperienciaModel())->where('pex_pos_ide', $pos['pos_ide'] ?? 0)->join('selec_modalidades_vinculo', 'mvi_ide = pex_mvi_ide', 'left')->join('selec_expediente_documentos', 'exd_ide = pex_documento_ide', 'left')
                        ->findAll();
                    $data['modalidades'] = (new ModalidadVinculoModel())->where('mvi_estado', 'ACTIVO')->findAll();
                    break;
                case 'capacitaciones':
                    $data['registros'] = (new PostulanteCapacitacionModel())->where('pca_pos_ide', $pos['pos_ide'] ?? 0)
                        ->join('selec_expediente_documentos', 'exd_ide = pca_documento_ide', 'left')
                        ->findAll();

                    break;
                case 'anexos':
                    $data['anexos'] = (new AnexoModel())->where('ane_con_ide', $convocatoriaId)
                        ->where('ane_estado', 'ACTIVO')->findAll();

                    $data['presentados'] = (new PostulacionAnexoModel())
                        ->where('pan_pto_ide', $post['pto_ide'])
                        ->join('selec_expediente_documentos', 'exd_ide = pan_exd_ide', 'left')
                        ->findAll();

                    break;
                case 'dj':
                    $data['declaraciones'] = (new TipoDeclaracionModel())->where('tde_estado', 'ACTIVO')->findAll();

                    $data['aceptadas'] = $post ? array_column((new PostulacionDeclaracionModel())->where('pde_pto_ide', $post['pto_ide'])->findAll(), 'pde_acepta', 'pde_tde_ide') : [];

                    break;
                case 'confirmacion':
                    $data['validaciones'] = $post ? $this->inscripcion->validar($user, $convocatoriaId) : [];
                    break;
                default:
                    throw new \DomainException('Sección no disponible.');
            }
            return view('Modules\\Seleccion\\Views\\postulante\\tabs\\' . $tab, $data);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(422)->setBody('<div class="alert alert-danger">' . esc($e->getMessage()) . '</div>');
        }
    }
    public function plaza(int $convocatoriaId)
    {
        return $this->json(fn() => $this->inscripcion->seleccionarPlaza($this->usuario(), $convocatoriaId, (int) $this->request->getPost('cco_ide')));
    }
    public function datos(int $convocatoriaId)
    {
        return $this->json(fn() => ['id' => $this->inscripcion->guardarDatosPersonales($this->usuario(), $convocatoriaId, $this->request->getPost())]);
    }
    public function guardar(int $convocatoriaId, string $tipo)
    {
        return $this->json(fn() => ['id' => $this->inscripcion->guardarRegistro($this->usuario(), $convocatoriaId, $tipo, $this->request->getPost(), $this->request->getFile('documento'))]);
    }
    public function eliminar(int $convocatoriaId, string $tipo, int $id)
    {
        return $this->json(function () use ($convocatoriaId, $tipo, $id) {
            $this->inscripcion->eliminarRegistro($this->usuario(), $convocatoriaId, $tipo, $id);
            return [];
        });
    }
    public function anexo(int $convocatoriaId, int $anexoId)
    {
        return $this->json(function () use ($convocatoriaId, $anexoId) {
            $this->inscripcion->guardarAnexo($this->usuario(), $convocatoriaId, $anexoId, $this->request->getFile('archivo'));
            return [];
        });
    }
    public function declaracion(int $convocatoriaId, int $id)
    {
        return $this->json(function () use ($convocatoriaId, $id) {
            $this->inscripcion->aceptarDeclaracion($this->usuario(), $convocatoriaId, $id, (bool) $this->request->getPost('acepta'));
            return [];
        });
    }
    public function validar(int $convocatoriaId)
    {
        return $this->json(fn() => $this->inscripcion->validar($this->usuario(), $convocatoriaId));
    }
    public function confirmar(int $convocatoriaId)
    {
        return $this->json(fn() => $this->inscripcion->confirmar($this->usuario(), $convocatoriaId));
    }
}

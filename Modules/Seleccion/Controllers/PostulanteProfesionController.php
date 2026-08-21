<?php

namespace Modules\Seleccion\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Seleccion\Services\PostulanteProfesionService;
use Modules\Seleccion\Models\PostulanteModel;
use Modules\Seleccion\Models\ProfesionModel;
use Modules\Seleccion\Models\PostulanteProfesionModel;


class PostulanteProfesionController extends BaseController
{
    protected $profesionService;
    protected $profesionModel;

    public function __construct()
    {
        $this->profesionService = new PostulanteProfesionService();
        $this->profesionModel = new ProfesionModel();
    }

    /**
     * Retorna la vista parcial (Partial AJAX)
     */
    public function formacionProfesional($convocatoriaId = null)
    {
        $postulanteModel = new PostulanteModel();
        $postulanteId = $postulanteModel->where('pos_user_id', auth()->id())->first()['pos_ide'] ?? null;

        $data = [
            'convocatoriaId' => $convocatoriaId,
            'profesion'      => $this->profesionService->getByPostulanteId($postulanteId),
        ];

        return view('Modules\Seleccion\Views\postulacion\partials\formacion_profesional', $data);
    }

    /**
     * Guarda la formación profesional vía POST
     */
    public function guardarProfesion()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['success' => false, 'message' => 'Acceso no permitido']);
        }

        $postulanteModel = new PostulanteModel();
        $postulanteId = $postulanteModel->where('pos_user_id', auth()->id())->first()['pos_ide'] ?? null;
        $postData = $this->request->getPost();

        // Reglas de validación
        $rules = [
            'ppr_pro_ide'     => 'required|integer',
            'ppr_institucion' => 'required|max_length[255]',
            'ppr_grado'       => 'required|max_length[150]',
            'ppr_titulo'      => 'required|max_length[150]',
            'ppr_fecha'       => 'required|valid_date[Y-m-d]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => $this->validator->getErrors()
            ]);
        }

        $guardado = $this->profesionService->guardar($postulanteId, $postData);

        if ($guardado) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Información profesional guardada correctamente.'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Ocurrió un error al intentar guardar los datos.'
        ]);
    }
}

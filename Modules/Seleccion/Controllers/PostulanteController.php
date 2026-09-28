<?php

namespace Modules\Seleccion\Controllers;

use App\Controllers\BaseController;
use Modules\Seleccion\Services\PostulanteService;
use Modules\Seleccion\Models\PostulanteModel;

class PostulanteController extends BaseController
{
    protected $postulanteService;
    protected $postulanteModel;

    public function __construct()
    {
        $this->postulanteService = new PostulanteService();
        $this->postulanteModel = new PostulanteModel();
    }

    // Retorna la vista parcial pasando el ID de Convocatoria
    public function datosPersonales()
    {
        // Obtener el ID del postulante logueado mediante la sesión
        $postulanteId = session()->get('id');

        // O si los datos están vinculados a la convocatoria actual:
        // $postulante = $this->postulanteModel->where('usuario_id', $postulanteId)->first();

        $data['postulante'] = $this->postulanteModel->where('pos_user_id', $postulanteId)->first();

        return view('Modules\Seleccion\Views\postulacion\partials\datos_personales', $data);
    }

    // Consulta datos del postulante y su estado en la convocatoria
    public function verDatos()
    {
        $userId         = auth()->id();
        $convocatoriaId = $this->request->getPost('convocatoria_id');

        $info = $this->postulanteService->obtenerInfoPostulanteYConvocatoria($userId, (int)$convocatoriaId);

        return $this->response->setJSON([
            'status'      => 'success',
            'postulante'  => $info['postulante'],
            'postulacion' => $info['postulacion'] // Datos de selec_postulaciones (si ya inició registro)
        ]);
    }

    public function guardarDatos()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Acceso no permitido']);
        }

        $userId = auth()->id();

        $data = [
            'pos_tdo_ide'          => $this->request->getPost('pos_tdo_ide'),
            'pos_documento'        => $this->request->getPost('pos_documento'),
            'pos_nombres'          => $this->request->getPost('pos_nombres'),
            'pos_apellido_paterno' => $this->request->getPost('pos_apellido_paterno'),
            'pos_apellido_materno' => $this->request->getPost('pos_apellido_materno'),
            'pos_fecha_nacimiento' => $this->request->getPost('pos_fecha_nacimiento'),
            'pos_sexo'             => $this->request->getPost('pos_sexo'),
            'pos_direccion'        => $this->request->getPost('pos_direccion'),
            'pos_telefono'         => $this->request->getPost('pos_telefono'),
            'pos_email'            => $this->request->getPost('pos_email'),
        ];

        try {
            $postulanteId = $this->postulanteService->guardarDatosPersonales($userId, $data);

            return $this->response->setJSON([
                'status'        => 'success',
                'message'       => 'Datos personales guardados correctamente.',
                'postulante_id' => $postulanteId
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}

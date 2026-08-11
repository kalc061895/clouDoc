<?php

namespace App\Services\Contratacion;

use App\Models\Contratacion\PostulanteModel;

class PostulanteService
{
    protected $postulanteModel;

    public function __construct()
    {
        $this->postulanteModel = new PostulanteModel();
    }

    public function guardarDatosPersonales(int $userId, array $data): int
    {
        // validar mínimos
        if (empty($data['dni']) || empty($data['nombres'])) {
            throw new \Exception('Datos incompletos');
        }

        // ¿ya existe postulante para este usuario?
        $postulante = $this->postulanteModel
            ->where('user_id', $userId)
            ->first();

        if ($postulante) {
            // actualizar
            $this->postulanteModel->update($postulante['id_postulante'], $data);
            return $postulante['id_postulante'];
        }

        // crear nuevo
        $data['user_id'] = $userId;

        return $this->postulanteModel->insert($data);
    }
    public function verDatosPersonales(int $userId)        
    {
        $postulante = $this->postulanteModel
            ->where('user_id', $userId)
            ->first();
        return $postulante;
    }
    

}

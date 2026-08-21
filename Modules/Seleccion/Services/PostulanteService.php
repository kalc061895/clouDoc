<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\PostulanteModel;
use CodeIgniter\Database\Config;

class PostulanteService
{
    protected $postulanteModel;
    protected $db;

    public function __construct()
    {
        $this->postulanteModel = new PostulanteModel();
        $this->db = Config::connect();
    }

    // Obtener postulante y verificar si ya existe postulación para la convocatoria
    public function obtenerInfoPostulanteYConvocatoria(int $userId, int $convocatoriaId): array
    {
        $postulante = $this->postulanteModel
            ->where('pos_user_id', $userId)
            ->first();

        $postulacion = null;

        if ($postulante) {
            $postulacion = $this->db->table('selec_postulaciones')
                ->where('pto_pos_ide', $postulante['pos_ide'])
                ->where('pto_cco_ide', $convocatoriaId)
                ->where('deleted_at IS NULL')
                ->get()
                ->getRowArray();
        }

        return [
            'postulante'  => $postulante,
            'postulacion' => $postulacion
        ];
    }

    public function guardarDatosPersonales(int $userId, array $data): int
    {
        if (empty($data['pos_documento']) || empty($data['pos_nombres'])) {
            throw new \Exception('El número de documento y nombres son obligatorios.');
        }

        $postulante = $this->postulanteModel
            ->where('pos_user_id', $userId)
            ->first();

        if ($postulante) {
            $this->postulanteModel->update($postulante['pos_ide'], $data);
            return (int) $postulante['pos_ide'];
        }

        $data['pos_user_id'] = $userId;
        return (int) $this->postulanteModel->insert($data);
    }
}

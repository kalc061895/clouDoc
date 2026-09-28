<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\PostulanteProfesionModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class PostulanteProfesionService
{
    protected $profesionModel;

    public function __construct()
    {
        $this->profesionModel = new PostulanteProfesionModel();
    }

    /**
     * Obtiene la formación profesional actual del postulante
     */
    public function getByPostulanteId(int $postulanteId): array
    {
        return $this->profesionModel
            ->select('selec_postulante_profesiones.*, selec_profesiones.pro_nombre as profesion_nombre')
            ->join('selec_profesiones', 'selec_profesiones.pro_ide = selec_postulante_profesiones.ppr_pro_ide', 'left')
            ->where('ppr_pos_ide', $postulanteId)
            ->first() ?? [];
    }

    /**
     * Guarda o actualiza los datos de profesión
     */
    public function guardar(int $postulanteId, array $data): bool
    {
        $existente = $this->getByPostulanteId($postulanteId);

        $payload = [
            'ppr_pos_ide'       => $postulanteId,
            'ppr_pro_ide'       => $data['ppr_pro_ide'] ?? null,
            'ppr_institucion'   => $data['ppr_institucion'] ?? null,
            'ppr_grado'         => $data['ppr_grado'] ?? null,
            'ppr_titulo'        => $data['ppr_titulo'] ?? null,
            'ppr_fecha'         => !empty($data['ppr_fecha']) ? $data['ppr_fecha'] : null,
            'ppr_colegiatura'   => $data['ppr_colegiatura'] ?? null,
            'ppr_habilitacion'  => $data['ppr_habilitacion'] ?? null,
        ];

        if (!empty($existente)) {
            return $this->profesionModel->update($existente['ppr_ide'], $payload);
        }

        return (bool) $this->profesionModel->insert($payload);
    }
}

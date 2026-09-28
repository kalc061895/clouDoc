<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\ProfesionModel;

class ProfesionService
{
    protected $profesionModel;

    public function __construct()
    {
        $this->profesionModel = new ProfesionModel();
    }

    /**
     * Listar todas las profesiones no eliminadas
     */
    public function listarProfesiones(?string $busqueda = null): array
    {
        $builder = $this->profesionModel->builder();
        $builder->where('deleted_at', null);

        if (!empty($busqueda)) {
            $builder->like('pro_nombre', $busqueda);
        }

        return $builder->orderBy('pro_nombre', 'ASC')->get()->getResultArray();
    }

    /**
     * Obtener profesiones activas para selectores
     */
    public function obtenerActivas(): array
    {
        return $this->profesionModel->activos();
    }

    public function crearProfesion(array $datos): bool|array
    {
        $datos['created_by'] = session()->get('user_id') ?? 1;
        $datos['pro_estado'] = $datos['pro_estado'] ?? 1;

        if ($this->profesionModel->insert($datos) === false) {
            return $this->profesionModel->errors();
        }

        return true;
    }

    public function actualizarProfesion(int $id, array $datos): bool|array
    {
        $datos['updated_by'] = session()->get('user_id') ?? 1;

        if ($this->profesionModel->update($id, $datos) === false) {
            return $this->profesionModel->errors();
        }

        return true;
    }

    public function eliminarProfesion(int $id): bool
    {
        return $this->profesionModel->delete($id) ? true : false;
    }
}

<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\CargoModel;

class CargoService
{
    protected $cargoModel;

    public function __construct()
    {
        $this->cargoModel = new CargoModel();
    }

    /**
     * Listar todos los cargos no eliminados
     */
    public function listarCargos(?string $busqueda = null): array
    {
        $builder = $this->cargoModel->builder();
        $builder->where('deleted_at', null);

        if (!empty($busqueda)) {
            $builder->like('car_nombre', $busqueda);
        }

        return $builder->orderBy('car_nombre', 'ASC')->get()->getResultArray();
    }

    /**
     * Obtener cargos activos para selectores
     */
    public function obtenerActivos(): array
    {
        return $this->cargoModel->activos();
    }

    public function crearCargo(array $datos): bool|array
    {
        $datos['created_by'] = session()->get('user_id') ?? 1;
        $datos['car_estado'] = $datos['car_estado'] ?? 1;

        if ($this->cargoModel->insert($datos) === false) {
            return $this->cargoModel->errors();
        }

        return true;
    }

    public function actualizarCargo(int $id, array $datos): bool|array
    {
        $datos['updated_by'] = session()->get('user_id') ?? 1;

        if ($this->cargoModel->update($id, $datos) === false) {
            return $this->cargoModel->errors();
        }

        return true;
    }

    public function eliminarCargo(int $id): bool
    {
        return $this->cargoModel->delete($id) ? true : false;
    }
}

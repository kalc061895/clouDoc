<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\SegundaEspecialidadModel;

class SegundaEspecialidadService
{
    protected $especialidadModel;

    public function __construct()
    {
        $this->especialidadModel = new SegundaEspecialidadModel();
    }

    /**
     * Listar especialidades con filtros de búsqueda y estado
     */
    public function listarEspecialidades(?string $busqueda = null, ?int $estado = null): array
    {
        $builder = $this->especialidadModel->builder();
        $builder->where('deleted_at', null);

        if ($estado !== null) {
            $builder->where('se_estado', $estado);
        }

        if (!empty($busqueda)) {
            $builder->groupStart()
                ->like('se_nombre', $busqueda)
                ->orLike('se_abreviatura', $busqueda)
                ->groupEnd();
        }

        return $builder->orderBy('se_nombre', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Registrar una nueva segunda especialidad
     */
    public function crearEspecialidad(array $datos): bool|array
    {
        $datos['se_nombre']      = mb_strtoupper(trim($datos['se_nombre']));
        $datos['se_abreviatura'] = !empty($datos['se_abreviatura']) ? mb_strtoupper(trim($datos['se_abreviatura'])) : null;
        $datos['se_estado']      = $datos['se_estado'] ?? 1;
        $datos['created_by']     = session()->get('user_id') ?? 1;

        if ($this->especialidadModel->insert($datos) === false) {
            return $this->especialidadModel->errors();
        }

        return true;
    }

    /**
     * Actualizar los datos de una especialidad
     */
    public function actualizarEspecialidad(int $id, array $datos): bool|array
    {
        if (isset($datos['se_nombre'])) {
            $datos['se_nombre'] = mb_strtoupper(trim($datos['se_nombre']));
        }
        if (!empty($datos['se_abreviatura'])) {
            $datos['se_abreviatura'] = mb_strtoupper(trim($datos['se_abreviatura']));
        }

        $datos['updated_by'] = session()->get('user_id') ?? 1;

        if ($this->especialidadModel->update($id, $datos) === false) {
            return $this->especialidadModel->errors();
        }

        return true;
    }

    /**
     * Soft Delete con persistencia del usuario auditor que ejecuta la acción
     */
    public function eliminarEspecialidad(int $id): bool
    {
        $this->especialidadModel->update($id, [
            'deleted_by' => session()->get('user_id') ?? 1
        ]);

        return $this->especialidadModel->delete($id) ? true : false;
    }
}

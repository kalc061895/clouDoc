<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\PermisoModel;

class PermisoService
{
    protected $permisoModel;

    public function __construct()
    {
        $this->permisoModel = new PermisoModel();
    }

    /**
     * Listar permisos con filtros de búsqueda, estado y tipo de remuneración
     */
    public function listarPermisos(?string $busqueda = null, ?int $estado = null, ?int $remunerado = null): array
    {
        $builder = $this->permisoModel->builder();
        $builder->where('deleted_at', null);

        if ($estado !== null) {
            $builder->where('pero_estado', $estado);
        }

        if ($remunerado !== null) {
            $builder->where('pero_remunerado', $remunerado);
        }

        if (!empty($busqueda)) {
            $builder->groupStart()
                ->like('pero_nombre', $busqueda)
                ->orLike('pero_abreviatura', $busqueda)
                ->groupEnd();
        }

        return $builder->orderBy('pero_nombre', 'ASC')->get()->getResultArray();
    }

    /**
     * Crear un nuevo tipo de permiso
     */
    public function crearPermiso(array $datos): bool|array
    {
        $datos['created_by'] = session()->get('user_id') ?? 1;
        $datos['pero_estado'] = $datos['pero_estado'] ?? 1;
        $datos['pero_remunerado'] = $datos['pero_remunerado'] ?? 0;

        // Validar abreviatura única
        if (!empty($datos['pero_abreviatura'])) {
            $existe = $this->permisoModel->where('pero_abreviatura', $datos['pero_abreviatura'])->first();
            if ($existe) {
                return ['pero_abreviatura' => 'La abreviatura ya está registrada para otro permiso.'];
            }
        }

        if ($this->permisoModel->insert($datos) === false) {
            return $this->permisoModel->errors();
        }

        return true;
    }

    /**
     * Actualizar un tipo de permiso existente
     */
    public function actualizarPermiso(int $id, array $datos): bool|array
    {
        $datos['updated_by'] = session()->get('user_id') ?? 1;

        if (!empty($datos['pero_abreviatura'])) {
            $existe = $this->permisoModel->where('pero_abreviatura', $datos['pero_abreviatura'])
                ->where('pero_ide !=', $id)
                ->first();
            if ($existe) {
                return ['pero_abreviatura' => 'Otro permiso ya utiliza esta abreviatura.'];
            }
        }

        if ($this->permisoModel->update($id, $datos) === false) {
            return $this->permisoModel->errors();
        }

        return true;
    }

    /**
     * Eliminar físicamente o por Soft Delete
     */
    public function eliminarPermiso(int $id): bool
    {
        return $this->permisoModel->delete($id) ? true : false;
    }
}

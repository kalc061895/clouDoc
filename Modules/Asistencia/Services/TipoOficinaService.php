<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\TipoOficinaModel;

class TipoOficinaService
{
    protected $tipoOficinaModel;

    public function __construct()
    {
        $this->tipoOficinaModel = new TipoOficinaModel();
    }

    /**
     * Listar todos los tipos de oficina no eliminados
     */
    public function listarTiposOficina(?string $busqueda = null): array
    {
        $builder = $this->tipoOficinaModel->builder();
        $builder->where('deleted_at', null);

        if (!empty($busqueda)) {
            $builder->like('tofi_nombre', $busqueda);
        }

        return $builder->orderBy('tofi_nombre', 'ASC')->get()->getResultArray();
    }

    /**
     * Obtener solo los activos (Útil para selects de otros formularios)
     */
    public function obtenerActivos(): array
    {
        return $this->tipoOficinaModel->activos();
    }

    public function crearTipoOficina(array $datos): bool|array
    {
        $datos['created_by'] = session()->get('user_id') ?? 1;
        $datos['tofi_estado'] = $datos['tofi_estado'] ?? 1;

        if ($this->tipoOficinaModel->insert($datos) === false) {
            return $this->tipoOficinaModel->errors();
        }

        return true;
    }

    public function actualizarTipoOficina(int $id, array $datos): bool|array
    {
        $datos['updated_by'] = session()->get('user_id') ?? 1;
        
        if ($this->tipoOficinaModel->update($id, $datos) === false) {
            return $this->tipoOficinaModel->errors();
        }

        return true;
    }

    public function eliminarTipoOficina(int $id): bool
    {
        return $this->tipoOficinaModel->delete($id) ? true : false;
    }
}
<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\DiresaModel;

class DiresaService
{
    protected $diresaModel;

    public function __construct()
    {
        $this->diresaModel = new DiresaModel();
    }

    /**
     * Listar Direcciones de Salud con búsqueda opcional
     */
    public function listarDiresas(?string $busqueda = null): array
    {
        $query = $this->diresaModel;

        if (!empty($busqueda)) {
            $query = $query->like('dir_nombre', $busqueda);
        }

        return $query->orderBy('dir_nombre', 'ASC')->findAll();
    }

    /**
     * Obtener por ID
     */
    public function obtenerPorId(int $id): ?array
    {
        return $this->diresaModel->find($id);
    }

    /**
     * Crear una Diresa
     */
    public function crearDiresa(array $datos): bool|array
    {
        $datos['created_by'] = session()->get('user_id') ?? 1;

        if ($this->diresaModel->insert($datos) === false) {
            return $this->diresaModel->errors();
        }

        return true;
    }

    /**
     * Actualizar una Diresa
     */
    public function actualizarDiresa(int $id, array $datos): bool|array
    {
        $datos['updated_by'] = session()->get('user_id') ?? 1;

        if ($this->diresaModel->update($id, $datos) === false) {
            return $this->diresaModel->errors();
        }

        return true;
    }

    /**
     * Aplicar Soft Delete (Dará de baja seteando deleted_at)
     */
    public function eliminarDiresa(int $id): bool
    {
        return $this->diresaModel->delete($id) ? true : false;
    }
}

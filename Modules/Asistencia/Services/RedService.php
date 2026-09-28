<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\RedModel;

class RedService
{
    protected $redModel;

    public function __construct()
    {
        $this->redModel = new RedModel();
    }

    /**
     * Listar Redes de Salud (Incluyendo el nombre de la DIRESA asociada)
     */
    public function listarRedes(?string $busqueda = null): array
    {
        $builder = $this->redModel->builder();
        
        // Seleccionamos campos de la red y el nombre de la DIRESA correspondiente
        $builder->select('casis_red.*, casis_diresa.dir_nombre as diresa_nombre')
                ->join('casis_diresa', 'casis_diresa.dir_ide = casis_red.red_dir_ide', 'left')
                ->where('casis_red.deleted_at', null); // Asegura respetar el Soft Delete

        if (!empty($busqueda)) {
            $builder->like('casis_red.red_nombre', $busqueda);
        }

        return $builder->orderBy('casis_red.red_nombre', 'ASC')->get()->getResultArray();
    }

    /**
     * Obtener por ID
     */
    public function obtenerPorId(int $id): ?array
    {
        return $this->redModel->find($id);
    }

    /**
     * Crear una Red de Salud
     */
    public function crearRed(array $datos): bool|array
    {
        $datos['created_by'] = session()->get('user_id') ?? 1;

        if ($this->redModel->insert($datos) === false) {
            return $this->redModel->errors();
        }

        return true;
    }

    /**
     * Actualizar una Red de Salud
     */
    public function actualizarRed(int $id, array $datos): bool|array
    {
        $datos['updated_by'] = session()->get('user_id') ?? 1;

        if ($this->redModel->update($id, $datos) === false) {
            return $this->redModel->errors();
        }

        return true;
    }

    /**
     * Eliminar (Soft Delete)
     */
    public function eliminarRed(int $id): bool
    {
        return $this->redModel->delete($id) ? true : false;
    }
}
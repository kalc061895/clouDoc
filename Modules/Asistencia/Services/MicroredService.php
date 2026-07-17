<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\MicroRedModel;

class MicroredService
{
    protected $microredModel;

    public function __construct()
    {
        $this->microredModel = new MicroRedModel();
    }

    /**
     * Listar Microredes con el nombre de su Red asociada
     */
    public function listarMicroredes(?string $busqueda = null): array
    {
        $builder = $this->microredModel->builder();

        // En MicroredService.php
        $builder->select('casis_microred.*, casis_red.red_nombre as red_nombre, casis_red.red_dir_ide') // <-- Agregamos el ID de la DIRESA
            ->join('casis_red', 'casis_red.red_ide = casis_microred.mic_red_ide', 'left')
            ->where('casis_microred.deleted_at', null);

        if (!empty($busqueda)) {
            $builder->like('casis_microred.mic_nombre', $busqueda);
        }

        return $builder->orderBy('casis_microred.mic_nombre', 'ASC')->get()->getResultArray();
    }

    public function obtenerPorId(int $id): ?array
    {
        return $this->microredModel->find($id);
    }

    public function crearMicrored(array $datos): bool|array
    {
        $datos['created_by'] = session()->get('user_id') ?? 1;

        if ($this->microredModel->insert($datos) === false) {
            return $this->microredModel->errors();
        }

        return true;
    }

    public function actualizarMicrored(int $id, array $datos): bool|array
    {
        $datos['updated_by'] = session()->get('user_id') ?? 1;

        if ($this->microredModel->update($id, $datos) === false) {
            return $this->microredModel->errors();
        }

        return true;
    }

    public function eliminarMicrored(int $id): bool
    {
        return $this->microredModel->delete($id) ? true : false;
    }
}

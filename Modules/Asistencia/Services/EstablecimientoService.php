<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\EstablecimientoModel;

class EstablecimientoService
{
    protected $establecimientoModel;

    public function __construct()
    {
        $this->establecimientoModel = new EstablecimientoModel();
    }

    /**
     * Listar Establecimientos con su cadena jerárquica completa
     */
    public function listarEstablecimientos(?string $busqueda = null): array
    {
        $builder = $this->establecimientoModel->builder();

        $builder->select('
                    casis_establecimiento.*, 
                    casis_microred.mic_nombre as microred_nombre,
                    casis_microred.mic_red_ide as red_ide,
                    casis_red.red_nombre as red_nombre,
                    casis_red.red_dir_ide as diresa_ide,
                    casis_diresa.dir_nombre as diresa_nombre
                ')
            ->join('casis_microred', 'casis_microred.mic_ide = casis_establecimiento.est_mic_ide', 'left')
            ->join('casis_red', 'casis_red.red_ide = casis_microred.mic_red_ide', 'left')
            ->join('casis_diresa', 'casis_diresa.dir_ide = casis_red.red_dir_ide', 'left')
            ->where('casis_establecimiento.deleted_at', null);

        if (!empty($busqueda)) {
            $builder->like('casis_establecimiento.est_nombre', $busqueda);
        }

        return $builder->orderBy('casis_establecimiento.est_nombre', 'ASC')->get()->getResultArray();
    }

    public function obtenerPorId(int $id): ?array
    {
        return $this->establecimientoModel->find($id);
    }

    public function crearEstablecimiento(array $datos): bool|array
    {
        $datos['created_by'] = session()->get('user_id') ?? 1;

        if ($this->establecimientoModel->insert($datos) === false) {
            return $this->establecimientoModel->errors();
        }

        return true;
    }

    public function actualizarEstablecimiento(int $id, array $datos): bool|array
    {
        $datos['updated_by'] = session()->get('user_id') ?? 1;

        if ($this->establecimientoModel->update($id, $datos) === false) {
            return $this->establecimientoModel->errors();
        }

        return true;
    }

    public function eliminarEstablecimiento(int $id): bool
    {
        return $this->establecimientoModel->delete($id) ? true : false;
    }
}

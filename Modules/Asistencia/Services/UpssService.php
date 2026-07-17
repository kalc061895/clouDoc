<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\UpssModel;

class UpssService
{
    protected $upssModel;

    public function __construct()
    {
        $this->upssModel = new UpssModel();
    }

    /**
     * Listar UPSS con filtros de búsqueda, estado y exclusión de eliminados
     */
    public function listarUpss(?string $busqueda = null, ?int $estado = null): array
    {
        $builder = $this->upssModel->builder();
        $builder->where('deleted_at', null);

        if ($estado !== null) {
            $builder->where('ups_estado', $estado);
        }

        if (!empty($busqueda)) {
            $builder->groupStart()
                ->like('ups_nombre', $busqueda)
                ->orLike('ups_codigo', $busqueda)
                ->orLike('ups_agrupacion', $busqueda)
                ->groupEnd();
        }

        return $builder->orderBy('ups_agrupacion', 'ASC')
            ->orderBy('ups_nombre', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Crear una nueva UPSS
     */
    public function crearUpss(array $datos): bool|array
    {
        $datos['created_by'] = session()->get('user_id') ?? 1;
        $datos['ups_estado'] = $datos['ups_estado'] ?? 1;

        // Validar código único si se provee
        if (!empty($datos['ups_codigo'])) {
            $existe = $this->upssModel->where('ups_codigo', $datos['ups_codigo'])->first();
            if ($existe) {
                return ['ups_codigo' => 'Este código de UPSS ya se encuentra registrado.'];
            }
        }

        if ($this->upssModel->insert($datos) === false) {
            return $this->upssModel->errors();
        }

        return true;
    }

    /**
     * Actualizar una UPSS existente
     */
    public function actualizarUpss(int $id, array $datos): bool|array
    {
        $datos['updated_by'] = session()->get('user_id') ?? 1;

        if (!empty($datos['ups_codigo'])) {
            $existe = $this->upssModel->where('ups_codigo', $datos['ups_codigo'])
                ->where('ups_ide !=', $id)
                ->first();
            if ($existe) {
                return ['ups_codigo' => 'Otra UPSS ya tiene asignado este código.'];
            }
        }

        if ($this->upssModel->update($id, $datos) === false) {
            return $this->upssModel->errors();
        }

        return true;
    }

    /**
     * Soft Delete de una UPSS
     */
    public function eliminarUpss(int $id): bool
    {
        $this->upssModel->update($id, ['deleted_by' => session()->get('user_id') ?? 1]);
        return $this->upssModel->delete($id) ? true : false;
    }
}

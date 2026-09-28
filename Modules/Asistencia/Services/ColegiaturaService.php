<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\ColegiaturaModel;

class ColegiaturaService
{
    protected $colegiaturaModel;

    public function __construct()
    {
        $this->colegiaturaModel = new ColegiaturaModel();
    }

    /**
     * Listar todas las colegiaturas con su profesión relacionada
     */
    public function listarColegiaturas(?string $busqueda = null): array
    {
        $builder = $this->colegiaturaModel->builder();
        $builder->select('casis_colegiatura.*, casis_profesion.pro_nombre as profesion_nombre');
        $builder->join('casis_profesion', 'casis_profesion.pro_ide = casis_colegiatura.col_pro_ide', 'left');
        $builder->where('casis_colegiatura.deleted_at', null);

        if (!empty($busqueda)) {
            $builder->groupStart()
                ->like('casis_colegiatura.col_nombre', $busqueda)
                ->orLike('casis_profesion.pro_nombre', $busqueda)
                ->groupEnd();
        }

        return $builder->orderBy('casis_colegiatura.col_nombre', 'ASC')->get()->getResultArray();
    }

    /**
     * Obtener colegiaturas activas
     */
    public function obtenerActivas(): array
    {
        return $this->colegiaturaModel->activos();
    }

    /**
     * Obtener colegiaturas por ID de Profesión
     */
    public function obtenerPorProfesion(int $proIde): array
    {
        return $this->colegiaturaModel->obtenerPorProfesion($proIde);
    }

    public function crearColegiatura(array $datos): bool|array
    {
        $datos['created_by'] = session()->get('user_id') ?? 1;
        $datos['col_estado'] = $datos['col_estado'] ?? 1;

        if ($this->colegiaturaModel->insert($datos) === false) {
            return $this->colegiaturaModel->errors();
        }

        return true;
    }

    public function actualizarColegiatura(int $id, array $datos): bool|array
    {
        $datos['updated_by'] = session()->get('user_id') ?? 1;

        if ($this->colegiaturaModel->update($id, $datos) === false) {
            return $this->colegiaturaModel->errors();
        }

        return true;
    }

    public function eliminarColegiatura(int $id): bool
    {
        return $this->colegiaturaModel->delete($id) ? true : false;
    }
}

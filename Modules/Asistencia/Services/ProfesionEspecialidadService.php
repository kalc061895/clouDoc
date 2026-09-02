<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\ProfesionEspecialidadModel;

class ProfesionEspecialidadService
{
    protected $pivoteModel;

    public function __construct()
    {
        $this->pivoteModel = new ProfesionEspecialidadModel();
    }

    /**
     * Listar la matriz completa de relaciones activas
     */
    public function listarRelaciones(?string $busqueda = null): array
    {
        $builder = $this->pivoteModel->builder();
        $builder->select('casis_profesion_especialidad.*, p.pro_nombre, p.pro_abreviatura, e.se_nombre, e.se_abreviatura')
            ->join('casis_profesion p', 'p.pro_ide = casis_profesion_especialidad.pes_pro_ide')
            ->join('casis_segunda_especialidad e', 'e.se_ide = casis_profesion_especialidad.pes_se_ide')
            ->where('casis_profesion_especialidad.deleted_at', null);

        if (!empty($busqueda)) {
            $builder->groupStart()
                ->like('p.pro_nombre', $busqueda)
                ->orLike('e.se_nombre', $busqueda)
                ->groupEnd();
        }

        return $builder->orderBy('p.pro_nombre', 'ASC')
            ->orderBy('e.se_nombre', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Asociar una especialidad a una profesión validando redundancia
     */
    public function asociarEspecialidad(array $datos): bool|array
    {
        $existe = $this->pivoteModel->where('pes_pro_ide', $datos['pes_pro_ide'])
            ->where('pes_se_ide', $datos['pes_se_ide'])
            ->first();
        if ($existe) {
            return ['combinacion' => 'Esta asociación ya existe en la matriz de configuraciones.'];
        }

        $datos['created_by'] = session()->get('user_id') ?? 1;

        if ($this->pivoteModel->insert($datos) === false) {
            return $this->pivoteModel->errors();
        }

        return true;
    }

    /**
     * Desvincular relación mediante baja lógica (Soft Delete)
     */
    public function eliminarAsociacion(int $id): bool
    {
        $this->pivoteModel->update($id, [
            'deleted_by' => session()->get('user_id') ?? 1
        ]);

        return $this->pivoteModel->delete($id) ? true : false;
    }
}

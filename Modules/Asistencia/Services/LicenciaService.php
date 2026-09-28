<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\LicenciaModel;

class LicenciaService
{
    protected $licenciaModel;

    public function __construct()
    {
        $this->licenciaModel = new LicenciaModel();
    }

    /**
     * Listar licencias aplicando filtros de estado, remuneración y búsqueda
     */
    public function listarLicencias(?string $busqueda = null, ?int $estado = null, ?int $remunerado = null): array
    {
        $builder = $this->licenciaModel->builder();
        $builder->where('deleted_at', null);

        if ($estado !== null) {
            $builder->where('lic_estado', $estado);
        }

        if ($remunerado !== null) {
            $builder->where('lic_remunerado', $remunerado);
        }

        if (!empty($busqueda)) {
            $builder->groupStart()
                ->like('lic_nombre', $busqueda)
                ->orLike('lic_abreviatura', $busqueda)
                ->groupEnd();
        }

        return $builder->orderBy('lic_nombre', 'ASC')->get()->getResultArray();
    }

    /**
     * Crear un nuevo tipo de licencia
     */
    public function crearLicencia(array $datos): bool|array
    {
        $datos['created_by'] = session()->get('user_id') ?? 1;
        $datos['lic_estado'] = $datos['lic_estado'] ?? 1;
        $datos['lic_remunerado'] = $datos['lic_remunerado'] ?? 0;

        // Validar que la abreviatura no se duplique
        if (!empty($datos['lic_abreviatura'])) {
            $existe = $this->licenciaModel->where('lic_abreviatura', $datos['lic_abreviatura'])->first();
            if ($existe) {
                return ['lic_abreviatura' => 'La abreviatura ya está siendo utilizada por otro concepto de licencia.'];
            }
        }

        if ($this->licenciaModel->insert($datos) === false) {
            return $this->licenciaModel->errors();
        }

        return true;
    }

    /**
     * Actualizar una licencia existente
     */
    public function actualizarLicencia(int $id, array $datos): bool|array
    {
        $datos['updated_by'] = session()->get('user_id') ?? 1;

        if (!empty($datos['lic_abreviatura'])) {
            $existe = $this->licenciaModel->where('lic_abreviatura', $datos['lic_abreviatura'])
                ->where('lic_ide !=', $id)
                ->first();
            if ($existe) {
                return ['lic_abreviatura' => 'Otro registro ya utiliza esta abreviatura.'];
            }
        }

        if ($this->licenciaModel->update($id, $datos) === false) {
            return $this->licenciaModel->errors();
        }

        return true;
    }

    /**
     * Eliminar de forma lógica (Soft Delete)
     */
    public function eliminarLicencia(int $id): bool
    {
        return $this->licenciaModel->delete($id) ? true : false;
    }
}

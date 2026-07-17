<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\ModalidadContratoModel;

class ModalidadContratoService
{
    protected $modalidadModel;

    public function __construct()
    {
        $this->modalidadModel = new ModalidadContratoModel();
    }

    /**
     * Listar las modalidades de contrato
     */
    public function listarModalidades(?string $busqueda = null, ?int $estado = null): array
    {
        $builder = $this->modalidadModel->builder();
        $builder->where('deleted_at', null);

        if ($estado !== null) {
            $builder->where('mco_estado', $estado);
        }

        if (!empty($busqueda)) {
            $builder->groupStart()
                ->like('mco_nombre', $busqueda)
                ->orLike('mco_codigo', $busqueda)
                ->orLike('mco_base_legal', $busqueda)
                ->groupEnd();
        }

        return $builder->orderBy('mco_nombre', 'ASC')->get()->getResultArray();
    }

    /**
     * Crear una nueva modalidad contractual
     */
    public function crearModalidad(array $datos): bool|array
    {
        $datos['created_by'] = session()->get('user_id') ?? 1;
        $datos['mco_estado'] = $datos['mco_estado'] ?? 1;

        // Validar que el código único no se duplique (excluyendo eliminados)
        if (!empty($datos['mco_codigo'])) {
            $existe = $this->modalidadModel->where('mco_codigo', $datos['mco_codigo'])->first();
            if ($existe) {
                return ['mco_codigo' => 'El código de modalidad ya se encuentra registrado.'];
            }
        }

        if ($this->modalidadModel->insert($datos) === false) {
            return $this->modalidadModel->errors();
        }

        return true;
    }

    /**
     * Actualizar una modalidad contractual existente
     */
    public function actualizarModalidad(int $id, array $datos): bool|array
    {
        $datos['updated_by'] = session()->get('user_id') ?? 1;

        if (!empty($datos['mco_codigo'])) {
            $existe = $this->modalidadModel->where('mco_codigo', $datos['mco_codigo'])
                ->where('mco_ide !=', $id)
                ->first();
            if ($existe) {
                return ['mco_codigo' => 'Otro registro ya utiliza este código de modalidad.'];
            }
        }

        if ($this->modalidadModel->update($id, $datos) === false) {
            return $this->modalidadModel->errors();
        }

        return true;
    }

    /**
     * Eliminar de manera lógica (Soft Delete)
     */
    public function eliminarModalidad(int $id): bool
    {
        return $this->modalidadModel->delete($id) ? true : false;
    }
}

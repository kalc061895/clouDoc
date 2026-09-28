<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\TipoDocumentoModel;

class TipoDocumentoService
{
    protected $tipoDocumentoModel;

    public function __construct()
    {
        $this->tipoDocumentoModel = new TipoDocumentoModel();
    }

    /**
     * Listar tipos de documentos con filtros de búsqueda y estado
     */
    public function listarDocumentos(?string $busqueda = null, ?int $estado = null): array
    {
        $builder = $this->tipoDocumentoModel->builder();
        $builder->where('deleted_at', null);

        if ($estado !== null) {
            $builder->where('tdi_estado', $estado);
        }

        if (!empty($busqueda)) {
            $builder->groupStart()
                ->like('tdi_nombre', $busqueda)
                ->orLike('tdi_abreviatura', $busqueda)
                ->groupEnd();
        }

        return $builder->orderBy('tdi_nombre', 'ASC')->get()->getResultArray();
    }

    /**
     * Crear un tipo de documento
     */
    public function crearDocumento(array $datos): bool|array
    {
        $datos['created_by'] = session()->get('user_id') ?? 1;
        $datos['tdi_estado'] = $datos['tdi_estado'] ?? 1;

        // Validar si la abreviatura ya existe
        if (!empty($datos['tdi_abreviatura'])) {
            $existe = $this->tipoDocumentoModel->where('tdi_abreviatura', $datos['tdi_abreviatura'])->first();
            if ($existe) {
                return ['tdi_abreviatura' => 'Esta abreviatura de documento ya está registrada.'];
            }
        }

        if ($this->tipoDocumentoModel->insert($datos) === false) {
            return $this->tipoDocumentoModel->errors();
        }

        return true;
    }

    /**
     * Actualizar tipo de documento
     */
    public function actualizarDocumento(int $id, array $datos): bool|array
    {
        $datos['updated_by'] = session()->get('user_id') ?? 1;

        if (!empty($datos['tdi_abreviatura'])) {
            $existe = $this->tipoDocumentoModel->where('tdi_abreviatura', $datos['tdi_abreviatura'])
                ->where('tdi_ide !=', $id)
                ->first();
            if ($existe) {
                return ['tdi_abreviatura' => 'Otro documento ya utiliza esta abreviatura.'];
            }
        }

        if ($this->tipoDocumentoModel->update($id, $datos) === false) {
            return $this->tipoDocumentoModel->errors();
        }

        return true;
    }

    /**
     * Soft delete
     */
    public function eliminarDocumento(int $id): bool
    {
        return $this->tipoDocumentoModel->delete($id) ? true : false;
    }
}

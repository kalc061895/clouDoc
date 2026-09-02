<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\PersonaModel;

class PersonaService
{
    protected $personaModel;

    public function __construct()
    {
        $this->personaModel = new PersonaModel();
    }

    /**
     * Listar personas con filtros de auditoría, excluyendo eliminados suaves
     */
    public function listarPersonas(?string $busqueda = null): array
    {
        $builder = $this->personaModel->builder();
        $builder->where('deleted_at', null);

        if (!empty($busqueda)) {
            $builder->groupStart()
                ->like('per_numero_documento', $busqueda)
                ->orLike('per_paterno', $busqueda)
                ->orLike('per_materno', $busqueda)
                ->orLike('per_nombre', $busqueda)
                ->groupEnd();
        }

        return $builder->orderBy('per_paterno', 'ASC')
            ->orderBy('per_materno', 'ASC')
            ->orderBy('per_nombre', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Registrar una nueva persona validando unicidad de identidad
     */
    public function crearPersona(array $datos): bool|array
    {
        // Forzar formato de texto uniforme para búsquedas precisas
        $datos['per_paterno'] = mb_strtoupper(trim($datos['per_paterno']));
        $datos['per_materno'] = mb_strtoupper(trim($datos['per_materno']));
        $datos['per_nombre']  = mb_strtoupper(trim($datos['per_nombre']));

        // Validar unicidad del documento de identidad
        if (!empty($datos['per_numero_documento'])) {
            $existe = $this->personaModel->where('per_numero_documento', $datos['per_numero_documento'])
                ->first();
            if ($existe) {
                return ['per_numero_documento' => 'Este número de documento ya está asignado a otra persona.'];
            }
        }

        if ($this->personaModel->insert($datos) === false) {
            return $this->personaModel->errors();
        }

        return true;
    }

    /**
     * Actualizar datos personales
     */
    public function actualizarPersona(int $id, array $datos): bool|array
    {
        if (isset($datos['per_paterno'])) $datos['per_paterno'] = mb_strtoupper(trim($datos['per_paterno']));
        if (isset($datos['per_materno'])) $datos['per_materno'] = mb_strtoupper(trim($datos['per_materno']));
        if (isset($datos['per_nombre']))  $datos['per_nombre']  = mb_strtoupper(trim($datos['per_nombre']));

        if (!empty($datos['per_numero_documento'])) {
            $existe = $this->personaModel->where('per_numero_documento', $datos['per_numero_documento'])
                ->where('per_ide !=', $id)
                ->first();
            if ($existe) {
                return ['per_numero_documento' => 'El documento ingresado ya le pertenece a otra persona registrada.'];
            }
        }

        if ($this->personaModel->update($id, $datos) === false) {
            return $this->personaModel->errors();
        }

        return true;
    }

    /**
     * Soft Delete: Conserva el registro en BD marcando deleted_at para auditoría
     */
    public function eliminarPersona(int $id): bool
    {
        // CodeIgniter autogestiona el timestamp al invocar delete() con useSoftDeletes activo
        return $this->personaModel->delete($id) ? true : false;
    }
}

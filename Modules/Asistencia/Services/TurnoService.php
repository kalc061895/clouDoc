<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\TurnoModel;

class TurnoService
{
    protected $turnoModel;

    public function __construct()
    {
        $this->turnoModel = new TurnoModel();
    }

    /**
     * Listar turnos con filtros de búsqueda, estado y exclusión de eliminados
     */
    public function listarTurnos(?string $busqueda = null, ?int $estado = null): array
    {
        $builder = $this->turnoModel->builder();
        $builder->where('deleted_at', null);

        if ($estado !== null) {
            $builder->where('tur_estado', $estado);
        }

        if (!empty($busqueda)) {
            $builder->groupStart()
                ->like('tur_nombre', $busqueda)
                ->orLike('tur_codigo', $busqueda)
                ->groupEnd();
        }

        return $builder->orderBy('tur_nombre', 'ASC')->get()->getResultArray();
    }

    /**
     * Crear un nuevo turno
     */
    public function crearTurno(array $datos): bool|array
    {
        $datos['created_by'] = session()->get('user_id') ?? 1;
        $datos['tur_estado'] = $datos['tur_estado'] ?? 1;
        $datos['tur_color']  = $datos['tur_color'] ?? '#3b82f6'; // Azul por defecto

        // Validar unicidad de código
        if (!empty($datos['tur_codigo'])) {
            $existe = $this->turnoModel->where('tur_codigo', $datos['tur_codigo'])->first();
            if ($existe) {
                return ['tur_codigo' => 'Este código de turno ya está registrado.'];
            }
        }

        if ($this->turnoModel->insert($datos) === false) {
            return $this->turnoModel->errors();
        }

        return true;
    }

    /**
     * Actualizar un turno
     */
    public function actualizarTurno(int $id, array $datos): bool|array
    {
        $datos['updated_by'] = session()->get('user_id') ?? 1;

        if (!empty($datos['tur_codigo'])) {
            $existe = $this->turnoModel->where('tur_codigo', $datos['tur_codigo'])
                ->where('tur_ide !=', $id)
                ->first();
            if ($existe) {
                return ['tur_codigo' => 'Otro turno ya tiene asignado este código.'];
            }
        }

        if ($this->turnoModel->update($id, $datos) === false) {
            return $this->turnoModel->errors();
        }

        return true;
    }

    /**
     * Eliminar físicamente o vía Soft Delete
     */
    public function eliminarTurno(int $id): bool
    {
        return $this->turnoModel->delete($id) ? true : false;
    }
}

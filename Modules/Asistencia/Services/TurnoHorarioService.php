<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\TurnoHorarioModel;

class TurnoHorarioService
{
    protected $turnoHorarioModel;

    public function __construct()
    {
        $this->turnoHorarioModel = new TurnoHorarioModel();
    }

    /**
     * Listar horarios de turnos con información del turno padre
     */
    public function listarHorarios(?string $busqueda = null, ?int $estado = null): array
    {
        $builder = $this->turnoHorarioModel->builder();
        $builder->select('casis_turno_horario.*, t.tur_nombre, t.tur_color, t.tur_codigo')
            ->join('casis_turno t', 't.tur_ide = casis_turno_horario.th_tur_ide', 'inner')
            ->where('casis_turno_horario.deleted_at', null);

        if ($estado !== null) {
            $builder->where('casis_turno_horario.th_estado', $estado);
        }

        if (!empty($busqueda)) {
            $builder->groupStart()
                ->like('casis_turno_horario.th_nombre', $busqueda)
                ->orLike('casis_turno_horario.th_codigo', $busqueda)
                ->orLike('t.tur_nombre', $busqueda)
                ->groupEnd();
        }

        return $builder->orderBy('t.tur_nombre', 'ASC')
            ->orderBy('casis_turno_horario.th_hora_ingreso', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Guardar un nuevo horario de turno
     */
    public function crearHorario(array $datos): bool|array
    {
        $datos['created_by'] = session()->get('user_id') ?? 1;
        $datos['th_estado']  = $datos['th_estado'] ?? 1;

        // Limpieza de campos opcionales (refrigerios) si vienen vacíos
        $datos['th_refrigerio_salida'] = !empty($datos['th_refrigerio_salida']) ? $datos['th_refrigerio_salida'] : null;
        $datos['th_refrigerio_retorno'] = !empty($datos['th_refrigerio_retorno']) ? $datos['th_refrigerio_retorno'] : null;

        // Validar unicidad del código de horario
        if (!empty($datos['th_codigo'])) {
            $existe = $this->turnoHorarioModel->where('th_codigo', $datos['th_codigo'])->first();
            if ($existe) {
                return ['th_codigo' => 'El código de horario ingresado ya se encuentra registrado.'];
            }
        }

        if ($this->turnoHorarioModel->insert($datos) === false) {
            return $this->turnoHorarioModel->errors();
        }

        return true;
    }

    /**
     * Actualizar horario existente
     */
    public function actualizarHorario(int $id, array $datos): bool|array
    {
        $datos['updated_by'] = session()->get('user_id') ?? 1;

        $datos['th_refrigerio_salida'] = !empty($datos['th_refrigerio_salida']) ? $datos['th_refrigerio_salida'] : null;
        $datos['th_refrigerio_retorno'] = !empty($datos['th_refrigerio_retorno']) ? $datos['th_refrigerio_retorno'] : null;

        if (!empty($datos['th_codigo'])) {
            $existe = $this->turnoHorarioModel->where('th_codigo', $datos['th_codigo'])
                ->where('th_ide !=', $id)
                ->first();
            if ($existe) {
                return ['th_codigo' => 'Otro horario ya tiene asignado este código.'];
            }
        }

        if ($this->turnoHorarioModel->update($id, $datos) === false) {
            return $this->turnoHorarioModel->errors();
        }

        return true;
    }

    /**
     * Soft Delete de horario
     */
    public function eliminarHorario(int $id): bool
    {
        return $this->turnoHorarioModel->delete($id) ? true : false;
    }
}

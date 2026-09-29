<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\TurnoModel;

class TurnoService
{
    protected $turnoModel;
    protected $db;

    public function __construct()
    {
        $this->turnoModel = new TurnoModel();
        $this->db         = \Config\Database::connect();
    }

    /**
     * Obtiene los turnos programados de un trabajador para el mes actual
     * con detalle de turno, horario y duración.
     *
     * @param int    $personalId  perl_ide del trabajador
     * @param string|null $fechaInicio Fecha inicio (default: primer día del mes actual)
     * @param string|null $fechaFin    Fecha fin (default: último día del mes actual)
     * @return array
     */
    public function getTurnosProgramados(int $personalId, ?string $fechaInicio = null, ?string $fechaFin = null): array
    {
        $fechaInicio = $fechaInicio ?? date('Y-m-01');
        $fechaFin    = $fechaFin    ?? date('Y-m-t');

        $programaciones = $this->db->table('casis_programacion pr')
            ->select('
                pr.prog_ide,
                pr.prog_fecha,
                pr.prog_estado,
                pr.prog_observacion,
                th.th_ide,
                th.th_codigo,
                th.th_nombre,
                th.th_hora_ingreso,
                th.th_hora_salida,
                t.tur_ide,
                t.tur_codigo,
                t.tur_nombre,
                t.tur_color
            ')
            ->join('casis_turno_horario th', 'th.th_ide = pr.prog_th_ide', 'inner')
            ->join('casis_turno t', 't.tur_ide = th.th_tur_ide', 'inner')
            ->where('pr.prog_perl_ide', $personalId)
            ->where('pr.prog_fecha >=', $fechaInicio)
            ->where('pr.prog_fecha <=', $fechaFin)
            ->where('pr.deleted_at', null)
            ->orderBy('pr.prog_fecha', 'ASC')
            ->get()
            ->getResultArray();

        // Agrupar por fecha con detalle
        $dias = [];
        $nombresDia = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];

        foreach ($programaciones as $p) {
            $fecha = $p['prog_fecha'];
            $diaNum = (int) date('w', strtotime($fecha));

            if (!isset($dias[$fecha])) {
                $dias[$fecha] = [
                    'fecha'      => $fecha,
                    'dia_nombre' => $nombresDia[$diaNum],
                    'es_finde'   => in_array($diaNum, [0, 6]),
                    'turnos'     => [],
                ];
            }

            // Calcular duración
            $ingreso = strtotime("1970-01-01 " . $p['th_hora_ingreso']);
            $salida  = strtotime("1970-01-01 " . $p['th_hora_salida']);
            if ($salida <= $ingreso) {
                $salida = strtotime("1970-01-02 " . $p['th_hora_salida']);
            }
            $duracion = round(($salida - $ingreso) / 3600, 2);

            $dias[$fecha]['turnos'][] = [
                'prog_ide'        => (int) $p['prog_ide'],
                'tur_codigo'      => $p['tur_codigo'],
                'tur_nombre'      => $p['tur_nombre'],
                'tur_color'       => $p['tur_color'] ?: '#3B82F6',
                'th_hora_ingreso' => substr($p['th_hora_ingreso'], 0, 5),
                'th_hora_salida'  => substr($p['th_hora_salida'], 0, 5),
                'duracion_horas'  => $duracion,
                'estado'          => $p['prog_estado'],
                'observacion'     => $p['prog_observacion'],
            ];
        }

        return [
            'fecha_inicio' => $fechaInicio,
            'fecha_fin'    => $fechaFin,
            'total_turnos' => count($programaciones),
            'dias'         => array_values($dias),
        ];
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

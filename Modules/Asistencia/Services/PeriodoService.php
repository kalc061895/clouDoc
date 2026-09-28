<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\PeriodoModel;
use Modules\Asistencia\Models\GrupoCorteModel;


class PeriodoService
{
    protected $periodoModel;
    protected $grupoCorteModel;

    public function __construct()
    {
        $this->grupoCorteModel = new GrupoCorteModel();
        $this->periodoModel = new PeriodoModel();
    }

    public function listarPeriodos($search = null, $grupoIde = null, $estado = null)
    {
        $builder = $this->periodoModel
            ->select('casis_periodos.*, gc.gco_nombre')
            ->join('casis_grupos_corte gc', 'gc.gco_ide = casis_periodos.gco_ide', 'left');

        if ($grupoIde) {
            $builder->where('casis_periodos.gco_ide', $grupoIde);
        }

        if ($estado !== null && $estado !== '') {
            $builder->where('casis_periodos.per_estado', $estado);
        }

        if ($search) {
            $builder->groupStart()
                ->like('casis_periodos.per_nombre', $search)
                ->orLike('casis_periodos.per_anio', $search)
                ->groupEnd();
        }

        return $builder->orderBy('per_anio', 'DESC')
            ->orderBy('per_mes', 'DESC')
            ->findAll();
    }

    public function listarPorGrupoCorte(int $grupoIde)
    {
        return $this->periodoModel
            ->select('casis_periodos.*, gc.gco_nombre')
            ->join('casis_grupos_corte gc', 'gc.gco_ide = casis_periodos.gco_ide', 'left')
            ->where('casis_periodos.gco_ide', $grupoIde)
            ->findAll();
    }

    public function crearPeriodo(array $datos)
    {
        if (!$this->periodoModel->insert($datos)) {
            return $this->periodoModel->errors();
        }
        return true;
    }

    public function actualizarPeriodo(int $id, array $datos)
    {
        if (!$this->periodoModel->update($id, $datos)) {
            return $this->periodoModel->errors();
        }
        return true;
    }

    public function eliminarPeriodo(int $id)
    {
        return $this->periodoModel->delete($id);
    }

    /**
     * Calcula fechas de inicio y fin sugeridas basadas en la regla del grupo de corte
     */
    public function calcularFechasPeriodo(int $grupoIde, int $anio, int $mes): array
    {
        $db = \Config\Database::connect();
        $grupo = $db->table('casis_grupos_corte')->where('gco_ide', $grupoIde)->get()->getRowArray();

        if (!$grupo) {
            throw new \Exception("Grupo de corte no encontrado.");
        }

        $diaInicioConfig = (int) $grupo['gco_dia_inicio'];
        $diaFinConfig = (int) $grupo['gco_dia_fin'];
        $desfase = (int) ($grupo['gco_mes_desfasado'] ?? 0);

        // 1. Calcular Fecha de Inicio
        if ($desfase === 1) {
            // Fijamos el día en 1 para evitar saltos de mes automáticos al restar
            $dtInicio = new \DateTime();
            $dtInicio->setDate($anio, $mes, 1);
            $dtInicio->modify('-1 month');

            $diasEnMesInicio = (int) $dtInicio->format('t'); // 't' obtiene los días del mes (28..31)
            $diaInicioReal = min($diaInicioConfig, $diasEnMesInicio);

            $dtInicio->setDate((int) $dtInicio->format('Y'), (int) $dtInicio->format('n'), $diaInicioReal);
        } else {
            $dtInicio = new \DateTime();
            $dtInicio->setDate($anio, $mes, 1);

            $diasEnMesInicio = (int) $dtInicio->format('t');
            $diaInicioReal = min($diaInicioConfig, $diasEnMesInicio);

            $dtInicio->setDate($anio, $mes, $diaInicioReal);
        }

        // 2. Calcular Fecha de Fin
        $dtFin = new \DateTime();
        $dtFin->setDate($anio, $mes, 1);

        $diasEnMesFin = (int) $dtFin->format('t');
        $diaFinReal = min($diaFinConfig, $diasEnMesFin);

        $dtFin->setDate($anio, $mes, $diaFinReal);

        return [
            'fecha_inicio' => $dtInicio->format('Y-m-d'),
            'fecha_fin' => $dtFin->format('Y-m-d')
        ];
    }

    // =========================================================================
    // SERVICIOS PARA GRUPOS DE CORTE
    // =========================================================================

    public function listarGruposCorte($search = null, $estado = null)
    {
        $builder = $this->grupoCorteModel;

        if ($estado !== null && $estado !== '') {
            $builder->where('gco_estado', $estado);
        }

        if ($search) {
            $builder->groupStart()
                ->like('gco_nombre', $search)
                ->orLike('gco_mco_ide', $search)
                ->groupEnd();
        }

        return $builder->orderBy('gco_ide', 'DESC')->findAll();
    }

    public function crearGrupoCorte(array $datos)
    {
        if (!$this->grupoCorteModel->insert($datos)) {
            return $this->grupoCorteModel->errors();
        }
        return true;
    }

    public function actualizarGrupoCorte(int $id, array $datos)
    {
        if (!$this->grupoCorteModel->update($id, $datos)) {
            return $this->grupoCorteModel->errors();
        }
        return true;
    }

    public function eliminarGrupoCorte(int $id)
    {
        return $this->grupoCorteModel->delete($id);
    }

    public function validarPeriodo(int $personalId, string $fecha): array
    {

        // 1. Resolver el período jerárquico
        $periodoModel = new PeriodoModel();
        $periodo = $periodoModel->resolverPeriodoPorPersonalYFecha($personalId, $fecha);

        // 2. Escenario A: No existe ningún período aperturado en la jerarquía
        if (!$periodo) {
            return [
                'permitido' => false,
                'codigo'    => 'PERIODO_NO_EXISTE',
                'mensaje'   => "No existe un período de corte configurado para la fecha {$fecha} en el establecimiento del trabajador ni en sus instancias superiores."
            ];
        }
        // 3. Escenario B: El período existe pero está CERRADO o EN_PROCESO
        $estado = $periodo['per_estado'] ?? '';

        switch ($estado) {
            case 'ABIERTO':
                return [
                    'permitido'  => true,
                    'codigo'     => 'OK',
                    'mensaje'    => 'Operación permitida.',
                    'messages'    => 'Operación permitida.',
                    'periodo_id' => $periodo['per_ide'],
                    'periodo'    => $periodo
                ];

            case 'REABIERTO':
                return [
                    'permitido'  => true,
                    'codigo'     => 'PERIODO_REABIERTO',
                    'mensaje'    => "Atención: El período '{$periodo['per_nombre']}' está REABIERTO. Las modificaciones quedarán registradas en auditoría.",
                    'messages'    => "Atención: El período '{$periodo['per_nombre']}' está REABIERTO. Las modificaciones quedarán registradas en auditoría.",
                    'periodo_id' => $periodo['per_ide'],
                    'periodo'    => $periodo
                ];

            case 'PROGRAMADO':
                return [
                    'permitido'  => false,
                    'codigo'     => 'PERIODO_PROGRAMADO',
                    'estado'     => $estado,
                    'mensaje'    => "El período '{$periodo['per_nombre']}' aún está PROGRAMADO y no ha sido aperturado para el registro de asistencias.",
                    'messages'    => "El período '{$periodo['per_nombre']}' aún está PROGRAMADO y no ha sido aperturado para el registro de asistencias.",
                    'periodo_id' => $periodo['per_ide']
                ];

            case 'EN_EVALUACION':
                return [
                    'permitido'  => false, // Cambiar a true si el usuario actual es Administrador/Jefe
                    'codigo'     => 'PERIODO_EN_EVALUACION',
                    'estado'     => $estado,
                    'mensaje'    => "El período '{$periodo['per_nombre']}' está EN EVALUACIÓN por la oficina de RRHH. Registro temporalmente bloqueado para revisión.",
                    'messages'    => "El período '{$periodo['per_nombre']}' está EN EVALUACIÓN por la oficina de RRHH. Registro temporalmente bloqueado para revisión.",
                    'periodo_id' => $periodo['per_ide']
                ];

            case 'CERRADO':
            default:
                return [
                    'permitido'  => false,
                    'codigo'     => 'PERIODO_CERRADO',
                    'estado'     => $estado,
                    'mensaje'    => "El período '{$periodo['per_nombre']}' se encuentra CERRADO. No se permiten modificaciones en la fecha {$fecha}.",
                    'messages'    => "El período '{$periodo['per_nombre']}' se encuentra CERRADO. No se permiten modificaciones en la fecha {$fecha}.",
                    'periodo_id' => $periodo['per_ide']
                ];
        }
    }

    public function validarPermisoPeriodo(int $perlIde, string $fecha): void
    {
        
        // Tu método actual que devuelve el array ['permitido' => ..., 'mensaje' => ...]
        $res = $this->validarPeriodo($perlIde, $fecha);

        if (!$res['permitido']) {
            throw new \Exception($res['mensaje']);
        }
    }
}

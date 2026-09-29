<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\ProgramacionModel;
use Modules\Asistencia\Models\TurnoModel;
use Modules\Asistencia\Models\TurnoHorarioModel;
use Modules\Asistencia\Models\PersonalModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class ProgramacionTurnoService
{
    protected ProgramacionModel $programacionModel;
    protected TurnoModel $turnoModel;
    protected TurnoHorarioModel $turnoHorarioModel;
    protected PersonalModel $personalModel;
    protected $db;

    public function __construct()
    {
        $this->programacionModel = new ProgramacionModel();
        $this->turnoModel        = new TurnoModel();
        $this->turnoHorarioModel = new TurnoHorarioModel();
        $this->personalModel     = new PersonalModel();
        $this->db                = \Config\Database::connect();
    }

    /**
     * Obtiene los días del mes sin depender de la extensión Calendar.
     */
    protected function obtenerDiasDelMes(int $anio, int $mes): int
    {
        if ($anio < 1 || $anio > 9999 || $mes < 1 || $mes > 12) {
            throw new \InvalidArgumentException('El año debe estar entre 1 y 9999 y el mes entre 1 y 12.');
        }

        $fecha = new \DateTimeImmutable(sprintf('%04d-%02d-01', $anio, $mes));

        return (int) $fecha->format('t');
    }

    /**
     * Calcula la duración en horas de un turno a partir de sus horas de ingreso y salida.
     * Maneja correctamente los turnos que cruzan la medianoche (ej. 19:00 a 07:00).
     */
    public function calcularDuracionHoras(string $horaIngreso, string $horaSalida): float
    {
        $ingreso = strtotime("1970-01-01 " . $horaIngreso);
        $salida  = strtotime("1970-01-01 " . $horaSalida);

        if ($salida <= $ingreso) {
            // Cruza la medianoche: salida al día siguiente
            $salida = strtotime("1970-01-02 " . $horaSalida);
        }

        $segundos = $salida - $ingreso;
        return round($segundos / 3600, 2);
    }

    /**
     * Obtiene el catálogo completo de turnos con sus horarios asociados para leyendas y combos
     */
    public function obtenerCatalogoTurnos(): array
    {
        $turnos = $this->db->table('casis_turno t')
            ->select('
                t.tur_ide,
                t.tur_codigo,
                t.tur_nombre,
                t.tur_color,
                t.tur_estado,
                th.th_ide,
                th.th_codigo,
                th.th_nombre,
                th.th_hora_ingreso,
                th.th_hora_salida
            ')
            ->join('casis_turno_horario th', 'th.th_tur_ide = t.tur_ide AND th.deleted_at IS NULL', 'left')
            ->where('t.deleted_at', null)
            ->where('t.tur_estado', 1)
            ->orderBy('t.tur_codigo', 'ASC')
            ->get()
            ->getResultArray();

        $catalogo = [];
        foreach ($turnos as $row) {
            $turIde = $row['tur_ide'];
            if (!isset($catalogo[$turIde])) {
                $catalogo[$turIde] = [
                    'tur_ide'    => (int) $row['tur_ide'],
                    'tur_codigo' => $row['tur_codigo'],
                    'tur_nombre' => $row['tur_nombre'],
                    'tur_color'  => !empty($row['tur_color']) ? $row['tur_color'] : '#3B82F6',
                    'horarios'   => [],
                ];
            }
            if (!empty($row['th_ide'])) {
                $duracion = $this->calcularDuracionHoras($row['th_hora_ingreso'], $row['th_hora_salida']);
                $catalogo[$turIde]['horarios'][] = [
                    'th_ide'          => (int) $row['th_ide'],
                    'th_codigo'       => $row['th_codigo'],
                    'th_nombre'       => $row['th_nombre'],
                    'th_hora_ingreso' => substr($row['th_hora_ingreso'], 0, 5),
                    'th_hora_salida'  => substr($row['th_hora_salida'], 0, 5),
                    'duracion_horas'  => $duracion,
                    'es_nocturno'     => ($row['th_hora_salida'] <= $row['th_hora_ingreso']),
                ];
            }
        }

        return array_values($catalogo);
    }

    /**
     * Obtiene el mapa rápido de código de turno a th_ide y duración
     */
    public function obtenerMapaCodigosTurno(): array
    {
        $horarios = $this->db->table('casis_turno_horario th')
            ->select('
                th.th_ide,
                th.th_codigo,
                th.th_hora_ingreso,
                th.th_hora_salida,
                t.tur_ide,
                t.tur_codigo,
                t.tur_nombre,
                t.tur_color
            ')
            ->join('casis_turno t', 't.tur_ide = th.th_tur_ide', 'inner')
            ->where('th.deleted_at', null)
            ->where('t.deleted_at', null)
            ->where('th.th_estado', 1)
            ->where('t.tur_estado', 1)
            ->get()
            ->getResultArray();

        $mapa = [];
        foreach ($horarios as $h) {
            $duracion = $this->calcularDuracionHoras($h['th_hora_ingreso'], $h['th_hora_salida']);
            $info = [
                'th_ide'          => (int) $h['th_ide'],
                'tur_ide'         => (int) $h['tur_ide'],
                'tur_codigo'      => strtoupper(trim($h['tur_codigo'])),
                'th_codigo'       => strtoupper(trim($h['th_codigo'])),
                'tur_nombre'      => $h['tur_nombre'],
                'tur_color'       => $h['tur_color'] ?? '#3B82F6',
                'th_hora_ingreso' => $h['th_hora_ingreso'],
                'th_hora_salida'  => $h['th_hora_salida'],
                'duracion_horas'  => $duracion,
                'es_nocturno'     => ($h['th_hora_salida'] <= $h['th_hora_ingreso']),
            ];
            // Mapear por tur_codigo y por th_codigo
            $mapa[strtoupper(trim($h['tur_codigo']))] = $info;
            $mapa[strtoupper(trim($h['th_codigo']))]  = $info;
        }

        return $mapa;
    }

    /**
     * Consulta la matriz mensual completa de programación de turnos
     */
    public function obtenerMatrizMensual(int $anio, int $mes, ?int $estIde = null, ?int $upsIde = null, ?int $ussIde = null, ?int $perlIde = null, ?int $ofiIde = null, ?string $dni = null): array
    {
        $diasMes = $this->obtenerDiasDelMes($anio, $mes);
        $fechaInicioMes = sprintf('%04d-%02d-01', $anio, $mes);
        $fechaFinMes    = sprintf('%04d-%02d-%02d', $anio, $mes, $diasMes);

        // 1. Obtener lista de trabajadores según filtros de contexto
        $builderPersonal = $this->db->table('casis_personal p')
            ->select('
                p.perl_ide,
                p.perl_codigo,
                p.perl_est_ide,
                pe.per_numero_documento,
                pe.per_paterno,
                pe.per_materno,
                pe.per_nombre,
                car.car_nombre,
                est.est_nombre
            ')
            ->join('casis_persona pe', 'pe.per_ide = p.perl_per_ide', 'inner')
            ->join('casis_establecimiento est', 'est.est_ide = p.perl_est_ide', 'left')
            ->join('casis_cargo car', 'car.car_ide = p.perl_car_ide', 'left')
            ->where('p.deleted_at', null)
            ->where('p.perl_estado', 1); // Solo personal activo

        if (!empty($estIde)) {
            $builderPersonal->where('p.perl_est_ide', $estIde);
        }
        if (!empty($perlIde)) {
            $builderPersonal->where('p.perl_ide', $perlIde);
        }
        if ($ofiIde) $builderPersonal->where('p.perl_ofi_ide', $ofiIde);
        if (trim($dni ?? '') !== '') $builderPersonal->where('pe.per_numero_documento', trim($dni));

        $trabajadores = $builderPersonal
            ->orderBy('pe.per_paterno', 'ASC')
            ->orderBy('pe.per_materno', 'ASC')
            ->get()
            ->getResultArray();

        if (empty($trabajadores)) {
            return [
                'anio'        => $anio,
                'mes'         => $mes,
                'dias_mes'    => $diasMes,
                'dias'        => $this->obtenerCabeceraDias($anio, $mes, $diasMes),
                'matriz'      => [],
                'catalogos'   => $this->obtenerCatalogoTurnos(),
            ];
        }

        $perlIds = array_column($trabajadores, 'perl_ide');

        // 2. Obtener programaciones del mes para estos trabajadores
        $builderProg = $this->db->table('casis_programacion pr')
            ->select('
                pr.prog_ide,
                pr.prog_perl_ide,
                pr.prog_fecha,
                pr.prog_estado,
                pr.prog_observacion,
                pr.prog_eup_ide,
                pr.prog_eus_ide,
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
            ->whereIn('pr.prog_perl_ide', $perlIds)
            ->where('pr.prog_fecha >=', $fechaInicioMes)
            ->where('pr.prog_fecha <=', $fechaFinMes)
            ->where('pr.deleted_at', null)
            ->orderBy('pr.prog_fecha', 'ASC');

        if ($upsIde) {
            $builderProg->join('casis_establecimiento_upss eup', 'eup.eup_ide = pr.prog_eup_ide', 'inner')
                ->where('eup.eup_ups_ide', $upsIde)->where('eup.deleted_at', null);
        }
        if ($ussIde) {
            $builderProg->join('casis_establecimiento_upss_servicio eus', 'eus.eus_ide = pr.prog_eus_ide', 'inner')
                ->where('eus.eus_uss_ide', $ussIde)->where('eus.deleted_at', null);
        }

        $programaciones = $builderProg->get()->getResultArray();

        // Indexar programaciones por [perl_ide][dia]
        $progIndex = [];
        foreach ($programaciones as $p) {
            $pId = (int) $p['prog_perl_ide'];
            $dia = (int) date('j', strtotime($p['prog_fecha']));
            $duracion = $this->calcularDuracionHoras($p['th_hora_ingreso'], $p['th_hora_salida']);

            $p['duracion_horas']  = $duracion;
            $p['th_hora_ingreso'] = substr($p['th_hora_ingreso'], 0, 5);
            $p['th_hora_salida']  = substr($p['th_hora_salida'], 0, 5);

            if (!isset($progIndex[$pId][$dia])) {
                $progIndex[$pId][$dia] = [];
            }
            $progIndex[$pId][$dia][] = $p;
        }

        // 3. Ensamblar la matriz final
        $matriz = [];
        foreach ($trabajadores as $t) {
            $pId = (int) $t['perl_ide'];
            if (($upsIde || $ussIde) && empty($progIndex[$pId])) continue;
            $diasAsignados = [];
            $totalHorasMes = 0.0;
            $totalTurnosMes = 0;

            for ($d = 1; $d <= $diasMes; $d++) {
                $turnosDia = $progIndex[$pId][$d] ?? [];
                $diasAsignados[$d] = $turnosDia;

                foreach ($turnosDia as $td) {
                    $totalHorasMes += $td['duracion_horas'];
                    $totalTurnosMes++;
                }
            }

            $matriz[] = [
                'perl_ide'              => $pId,
                'dni'                   => $t['per_numero_documento'],
                'trabajador'            => trim($t['per_paterno'] . ' ' . $t['per_materno'] . ' ' . $t['per_nombre']),
                'cargo'                 => $t['car_nombre'] ?? 'Sin Cargo',
                'establecimiento'       => $t['est_nombre'] ?? '',
                'dias'                  => $diasAsignados,
                'total_horas'           => round($totalHorasMes, 2),
                'total_turnos'          => $totalTurnosMes,
            ];
        }

        return [
            'anio'        => $anio,
            'mes'         => $mes,
            'dias_mes'    => $diasMes,
            'dias'        => $this->obtenerCabeceraDias($anio, $mes, $diasMes),
            'matriz'      => $matriz,
            'catalogos'   => $this->obtenerCatalogoTurnos(),
        ];
    }

    /**
     * Construye metadatos de los días del mes (número, día de la semana, fin de semana)
     */
    protected function obtenerCabeceraDias(int $anio, int $mes, int $diasMes): array
    {
        $dias = [];
        $nombresCortos = [1 => 'L', 2 => 'M', 3 => 'M', 4 => 'J', 5 => 'V', 6 => 'S', 7 => 'D'];

        for ($d = 1; $d <= $diasMes; $d++) {
            $ts = strtotime(sprintf('%04d-%02d-%02d', $anio, $mes, $d));
            $n = (int) date('N', $ts);
            $dias[] = [
                'dia'             => $d,
                'letra'           => $nombresCortos[$n] ?? '',
                'dia_semana'      => $n,
                'es_fin_de_semana'=> ($n === 6 || $n === 7),
            ];
        }
        return $dias;
    }

    /**
     * Valida y detecta colisiones de horario para un trabajador y turno propuesto
     */
    public function validarCruceHorarios(int $perlIde, string $fecha, int $thIde, ?int $progIdeExcluir = null): array
    {
        // 1. Obtener horario del turno propuesto
        $horarioPropuesto = $this->turnoHorarioModel->find($thIde);
        if (!$horarioPropuesto) {
            return ['cruce' => true, 'mensaje' => 'El horario seleccionado no existe.'];
        }

        $hIngreso = $horarioPropuesto['th_hora_ingreso'];
        $hSalida  = $horarioPropuesto['th_hora_salida'];

        // Calcular intervalo absoluto de fechas/horas
        $inicioTs = strtotime("{$fecha} {$hIngreso}");
        if ($hSalida <= $hIngreso) {
            // Cruza la medianoche
            $finTs = strtotime(date('Y-m-d', strtotime("{$fecha} +1 day")) . " {$hSalida}");
        } else {
            $finTs = strtotime("{$fecha} {$hSalida}");
        }

        // Buscar turnos existentes del trabajador en [fecha - 1 día, fecha, fecha + 1 día]
        $fechaAyer   = date('Y-m-d', strtotime("{$fecha} -1 day"));
        $fechaManana = date('Y-m-d', strtotime("{$fecha} +1 day"));

        $builder = $this->db->table('casis_programacion pr')
            ->select('
                pr.prog_ide,
                pr.prog_fecha,
                th.th_codigo,
                th.th_nombre,
                th.th_hora_ingreso,
                th.th_hora_salida,
                t.tur_codigo
            ')
            ->join('casis_turno_horario th', 'th.th_ide = pr.prog_th_ide', 'inner')
            ->join('casis_turno t', 't.tur_ide = th.th_tur_ide', 'inner')
            ->where('pr.prog_perl_ide', $perlIde)
            ->whereIn('pr.prog_fecha', [$fechaAyer, $fecha, $fechaManana])
            ->where('pr.deleted_at', null);

        if ($progIdeExcluir) {
            $builder->where('pr.prog_ide !=', $progIdeExcluir);
        }

        $turnosExistentes = $builder->get()->getResultArray();

        foreach ($turnosExistentes as $ex) {
            $fEx = $ex['prog_fecha'];
            $exIngreso = $ex['th_hora_ingreso'];
            $exSalida  = $ex['th_hora_salida'];

            $exInicioTs = strtotime("{$fEx} {$exIngreso}");
            if ($exSalida <= $exIngreso) {
                $exFinTs = strtotime(date('Y-m-d', strtotime("{$fEx} +1 day")) . " {$exSalida}");
            } else {
                $exFinTs = strtotime("{$fEx} {$exSalida}");
            }

            // Dos intervalos [A, B] y [C, D] se solapan si A < D y B > C
            if ($inicioTs < $exFinTs && $finTs > $exInicioTs) {
                return [
                    'cruce'   => true,
                    'mensaje' => sprintf(
                        'Cruce de horarios detectado con el turno %s (%s a %s) asignado el %s.',
                        $ex['tur_codigo'],
                        substr($exIngreso, 0, 5),
                        substr($exSalida, 0, 5),
                        $fEx
                    ),
                ];
            }
        }

        return ['cruce' => false, 'mensaje' => ''];
    }

    /**
     * Asigna un turno individual a un trabajador en una fecha determinada
     */
    public function asignarTurnoIndividual(array $datos, ?int $usuarioId = null): array
    {
        $perlIde = (int) ($datos['prog_perl_ide'] ?? 0);
        $fecha   = trim((string) ($datos['prog_fecha'] ?? ''));
        $thIde   = (int) ($datos['prog_th_ide'] ?? 0);
        $progIde = !empty($datos['prog_ide']) ? (int) $datos['prog_ide'] : null;

        if ($perlIde <= 0) {
            return ['status' => false, 'message' => 'Trabajador no válido.'];
        }
        if (empty($fecha) || !strtotime($fecha)) {
            return ['status' => false, 'message' => 'Fecha no válida.'];
        }
        if ($thIde <= 0) {
            return ['status' => false, 'message' => 'Debe seleccionar un turno/horario válido.'];
        }

        // Validar cruce de horario
        $validacion = $this->validarCruceHorarios($perlIde, $fecha, $thIde, $progIde);
        if ($validacion['cruce']) {
            return ['status' => false, 'message' => $validacion['mensaje']];
        }

        // Resolver eup_ide y eus_ide si se proporcionan est_ide, ups_ide, uss_ide
        $eupIde = null;
        $eusIde = null;

        if (!empty($datos['est_ide']) && !empty($datos['ups_ide'])) {
            $eupIde = $this->obtenerOCrearEstablecimientoUpss((int) $datos['est_ide'], (int) $datos['ups_ide'], $usuarioId);
            if ($eupIde && !empty($datos['uss_ide'])) {
                $eusIde = $this->obtenerOCrearEstablecimientoUpssServicio($eupIde, (int) $datos['uss_ide'], $usuarioId);
            }
        }

        $dataSave = [
            'prog_perl_ide'    => $perlIde,
            'prog_fecha'       => $fecha,
            'prog_th_ide'      => $thIde,
            'prog_eup_ide'     => $eupIde,
            'prog_eus_ide'     => $eusIde,
            'prog_estado'      => $datos['prog_estado'] ?? 'PROGRAMADO',
            'prog_observacion' => $datos['prog_observacion'] ?? null,
        ];

        if ($progIde) {
            $dataSave['updated_by'] = $usuarioId;
            $dataSave['updated_at'] = date('Y-m-d H:i:s');
            $this->programacionModel->update($progIde, $dataSave);
            $id = $progIde;
            $mensaje = 'Turno actualizado correctamente.';
        } else {
            $dataSave['created_by'] = $usuarioId;
            $dataSave['created_at'] = date('Y-m-d H:i:s');
            $this->programacionModel->insert($dataSave);
            $id = $this->programacionModel->getInsertID();
            $mensaje = 'Turno asignado correctamente.';
        }

        return [
            'status'   => true,
            'message'  => $mensaje,
            'prog_ide' => $id,
        ];
    }

    /**
     * Elimina un turno asignado (soft delete)
     */
    public function eliminarTurnoIndividual(int $progIde, ?int $usuarioId = null): array
    {
        $prog = $this->programacionModel->find($progIde);
        if (!$prog) {
            return ['status' => false, 'message' => 'Asignación de turno no encontrada.'];
        }

        $this->db->table('casis_programacion')
            ->where('prog_ide', $progIde)
            ->update([
                'deleted_by' => $usuarioId,
                'deleted_at' => date('Y-m-d H:i:s'),
            ]);

        return [
            'status'  => true,
            'message' => 'Turno eliminado de la programación correctamente.',
        ];
    }

    /**
     * Helper para vincular o crear relación Establecimiento-UPSS
     */
    public function obtenerOCrearEstablecimientoUpss(int $estIde, int $upsIde, ?int $usuarioId = null): ?int
    {
        $row = $this->db->table('casis_establecimiento_upss')
            ->where('eup_est_ide', $estIde)
            ->where('eup_ups_ide', $upsIde)
            ->where('deleted_at', null)
            ->get()
            ->getRowArray();

        if ($row) {
            return (int) $row['eup_ide'];
        }

        $this->db->table('casis_establecimiento_upss')->insert([
            'eup_est_ide' => $estIde,
            'eup_ups_ide' => $upsIde,
            'eup_estado'  => 1,
            'created_by'  => $usuarioId,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        return (int) $this->db->insertID();
    }

    /**
     * Helper para vincular o crear relación Establecimiento-UPSS-Servicio
     */
    public function obtenerOCrearEstablecimientoUpssServicio(int $eupIde, int $ussIde, ?int $usuarioId = null): ?int
    {
        $row = $this->db->table('casis_establecimiento_upss_servicio')
            ->where('eus_eup_ide', $eupIde)
            ->where('eus_uss_ide', $ussIde)
            ->where('deleted_at', null)
            ->get()
            ->getRowArray();

        if ($row) {
            return (int) $row['eus_ide'];
        }

        $this->db->table('casis_establecimiento_upss_servicio')->insert([
            'eus_eup_ide' => $eupIde,
            'eus_uss_ide' => $ussIde,
            'eus_estado'  => 1,
            'created_by'  => $usuarioId,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        return (int) $this->db->insertID();
    }

    /**
     * Genera la plantilla Excel .xlsx predeterminada para importación masiva
     */
    public function generarPlantillaExcel(int $anio, int $mes, ?int $estIde = null): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();

        // -------------------------------------------------------------
        // HOJA 1: PROGRAMACION
        // -------------------------------------------------------------
        $sheetProg = $spreadsheet->getActiveSheet();
        $sheetProg->setTitle('Programacion');
        $sheetProg->setShowGridLines(true);

        $diasMes = $this->obtenerDiasDelMes($anio, $mes);

        // Encabezados requeridos: DNI | AÑO | MES | DIA_01 | ... | DIA_31
        $sheetProg->setCellValue('A1', 'DNI');
        $sheetProg->setCellValue('B1', 'ANIO');
        $sheetProg->setCellValue('C1', 'MES');

        $colIdx = 4;
        for ($d = 1; $d <= 31; $d++) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx);
            $sheetProg->setCellValue("{$colLetter}1", sprintf('DIA_%02d', $d));
            $colIdx++;
        }

        $totalCols = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(34);
        $headerRange = "A1:{$totalCols}1";

        $sheetProg->getStyle($headerRange)->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => '1C3254']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'CBD5E1']]],
        ]);
        $sheetProg->getRowDimension(1)->setRowHeight(26);

        // Pre-cargar trabajadores si hay establecimiento seleccionado
        $builder = $this->db->table('casis_personal p')
            ->select('pe.per_numero_documento, pe.per_paterno, pe.per_materno, pe.per_nombre')
            ->join('casis_persona pe', 'pe.per_ide = p.perl_per_ide', 'inner')
            ->where('p.deleted_at', null)
            ->where('p.perl_estado', 1);

        if (!empty($estIde)) {
            $builder->where('p.perl_est_ide', $estIde);
        }

        $personal = $builder->orderBy('pe.per_paterno', 'ASC')->get()->getResultArray();

        $rowNum = 2;
        foreach ($personal as $p) {
            $dni = trim($p['per_numero_documento']);
            $sheetProg->setCellValueExplicit("A{$rowNum}", $dni, DataType::TYPE_STRING);
            $sheetProg->setCellValue("B{$rowNum}", $anio);
            $sheetProg->setCellValue("C{$rowNum}", $mes);

            // Centrar DNI, Año y Mes
            $sheetProg->getStyle("A{$rowNum}:C{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Resaltar celdas de días no existentes en el mes (ej. día 29, 30, 31 si mes=febrero)
            for ($d = $diasMes + 1; $d <= 31; $d++) {
                $cLet = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($d + 3);
                $sheetProg->getStyle("{$cLet}{$rowNum}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('F1F5F9');
            }

            $rowNum++;
        }

        // Formato de texto para columna A (DNI)
        $sheetProg->getStyle('A1:A' . max($rowNum, 100))->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

        // Auto-dimensionar columnas de programación
        $sheetProg->getColumnDimension('A')->setWidth(14);
        $sheetProg->getColumnDimension('B')->setWidth(8);
        $sheetProg->getColumnDimension('C')->setWidth(8);
        for ($c = 4; $c <= 34; $c++) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c);
            $sheetProg->getColumnDimension($colLetter)->setWidth(9);
        }

        // -------------------------------------------------------------
        // HOJA 2: INSTRUCCIONES
        // -------------------------------------------------------------
        $sheetInst = $spreadsheet->createSheet();
        $sheetInst->setTitle('Instrucciones');
        $sheetInst->setShowGridLines(true);

        $sheetInst->setCellValue('A1', 'INSTRUCCIONES PARA LA IMPORTACIÓN MASIVA DE TURNOS');
        $sheetInst->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1C3254'));

        $instrucciones = [
            '1. La hoja "Programacion" contiene una fila por cada trabajador.',
            '2. La columna "DNI" debe conservarse en formato Texto para preservar ceros a la izquierda.',
            '3. Las columnas "ANIO" y "MES" indican el periodo a programar (ej. 2026 y 9).',
            '4. En cada celda de día (DIA_01 a DIA_31), ingrese el CÓDIGO DEL TURNO deseado según la hoja "Catalogo_Turnos".',
            '5. Las celdas en blanco NO borran la programación previa existente.',
            '6. Si el mes tiene menos de 31 días (ej. febrero 28 días, o meses de 30 días), deje en blanco las columnas de días sobrantes.',
            '7. Para turnos de guardia que cruzan medianoche, el sistema calculará automáticamente la duración y validará cruces.',
            '8. Antes de aplicar cambios definitivos, el sistema mostrará una previsualización con registros válidos y errores detallados.',
            '9. Durante la confirmación, usted podrá decidir si desea sobreescribir la programación existente o solo llenar días libres.',
        ];

        $instRow = 3;
        foreach ($instrucciones as $inst) {
            $sheetInst->setCellValue("A{$instRow}", $inst);
            $sheetInst->getStyle("A{$instRow}")->getFont()->setSize(11);
            $instRow++;
        }
        $sheetInst->getColumnDimension('A')->setWidth(100);

        // -------------------------------------------------------------
        // HOJA 3: CATALOGO DE TURNOS
        // -------------------------------------------------------------
        $sheetCat = $spreadsheet->createSheet();
        $sheetCat->setTitle('Catalogo_Turnos');
        $sheetCat->setShowGridLines(true);

        $sheetCat->setCellValue('A1', 'CÓDIGOS DE TURNO DISPONIBLES EN EL SISTEMA');
        $sheetCat->getStyle('A1')->getFont()->setBold(true)->setSize(13)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1C3254'));

        $sheetCat->setCellValue('A3', 'CÓDIGO TURNO');
        $sheetCat->setCellValue('B3', 'DENOMINACIÓN');
        $sheetCat->setCellValue('C3', 'HORA ENTRADA');
        $sheetCat->setCellValue('D3', 'HORA SALIDA');
        $sheetCat->setCellValue('E3', 'DURACIÓN (HORAS)');
        $sheetCat->setCellValue('F3', 'CRUZA MEDIANOCHE');

        $sheetCat->getStyle('A3:F3')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => '2563EB']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'CBD5E1']]],
        ]);

        $catalogo = $this->obtenerCatalogoTurnos();
        $catRow = 4;
        foreach ($catalogo as $t) {
            foreach ($t['horarios'] as $h) {
                $sheetCat->setCellValueExplicit("A{$catRow}", $t['tur_codigo'], DataType::TYPE_STRING);
                $sheetCat->setCellValue("B{$catRow}", $t['tur_nombre']);
                $sheetCat->setCellValue("C{$catRow}", $h['th_hora_ingreso']);
                $sheetCat->setCellValue("D{$catRow}", $h['th_hora_salida']);
                $sheetCat->setCellValue("E{$catRow}", $h['duracion_horas']);
                $sheetCat->setCellValue("F{$catRow}", $h['es_nocturno'] ? 'SÍ' : 'NO');

                $sheetCat->getStyle("A{$catRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheetCat->getStyle("C{$catRow}:F{$catRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $catRow++;
            }
        }

        foreach (range('A', 'F') as $col) {
            $sheetCat->getColumnDimension($col)->setAutoSize(true);
        }

        $spreadsheet->setActiveSheetIndex(0);
        return $spreadsheet;
    }

    /**
     * Valida el archivo Excel subido y genera una previsualización interactiva completa
     */
    public function previsualizarImportacionExcel(string $rutaArchivo, int $anioEsperado, int $mesEsperado, ?int $estIde = null): array
    {
        try {
            $spreadsheet = IOFactory::load($rutaArchivo);
        } catch (\Throwable $e) {
            return [
                'status'  => false,
                'message' => 'No se pudo leer el archivo Excel: ' . $e->getMessage(),
            ];
        }

        // Buscar hoja de Programación
        $sheet = $spreadsheet->getSheetByName('Programacion') ?? $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestDataRow();
        $highestCol = $sheet->getHighestDataColumn();

        if ($highestRow < 2) {
            return [
                'status'  => false,
                'message' => 'El archivo no contiene filas de datos para procesar.',
            ];
        }

        // 1. Validar encabezados en Fila 1
        $headerDni  = trim((string) $sheet->getCell('A1')->getValue());
        $headerAnio = trim((string) $sheet->getCell('B1')->getValue());
        $headerMes  = trim((string) $sheet->getCell('C1')->getValue());

        if (strtoupper($headerDni) !== 'DNI' || strtoupper($headerAnio) !== 'ANIO' || strtoupper($headerMes) !== 'MES') {
            return [
                'status'  => false,
                'message' => 'Estructura de encabezados inválida. Las primeras 3 columnas deben ser DNI, ANIO y MES.',
            ];
        }

        $mapaTurnos = $this->obtenerMapaCodigosTurno();
        $diasMes    = $this->obtenerDiasDelMes($anioEsperado, $mesEsperado);

        // Pre-cargar trabajadores indexados por DNI
        $trabajadores = $this->db->table('casis_personal p')
            ->select('
                p.perl_ide,
                p.perl_est_ide,
                pe.per_numero_documento,
                pe.per_paterno,
                pe.per_materno,
                pe.per_nombre
            ')
            ->join('casis_persona pe', 'pe.per_ide = p.perl_per_ide', 'inner')
            ->where('p.deleted_at', null)
            ->where('p.perl_estado', 1)
            ->get()
            ->getResultArray();

        $trabajadoresPorDni = [];
        foreach ($trabajadores as $t) {
            $trabajadoresPorDni[trim($t['per_numero_documento'])] = $t;
        }

        // Pre-cargar programaciones existentes en el mes
        $fechaInicioMes = sprintf('%04d-%02d-01', $anioEsperado, $mesEsperado);
        $fechaFinMes    = sprintf('%04d-%02d-%02d', $anioEsperado, $mesEsperado, $diasMes);

        $existentes = $this->db->table('casis_programacion pr')
            ->select('pr.prog_ide, pr.prog_perl_ide, pr.prog_fecha, th.th_codigo, t.tur_codigo')
            ->join('casis_turno_horario th', 'th.th_ide = pr.prog_th_ide', 'inner')
            ->join('casis_turno t', 't.tur_ide = th.th_tur_ide', 'inner')
            ->where('pr.prog_fecha >=', $fechaInicioMes)
            ->where('pr.prog_fecha <=', $fechaFinMes)
            ->where('pr.deleted_at', null)
            ->get()
            ->getResultArray();

        $progExistenteIndex = [];
        foreach ($existentes as $ex) {
            $progExistenteIndex[$ex['prog_perl_ide']][$ex['prog_fecha']] = $ex;
        }

        $registrosValidos   = [];
        $errores            = [];
        $dnisProcesados     = [];
        $totalCeldasTurnos  = 0;
        $totalExistentes    = 0;

        for ($row = 2; $row <= $highestRow; $row++) {
            $dni = trim((string) $sheet->getCell("A{$row}")->getValue());
            $anio = (int) $sheet->getCell("B{$row}")->getValue();
            $mes  = (int) $sheet->getCell("C{$row}")->getValue();

            // Omitir filas totalmente vacías
            if (empty($dni) && empty($anio) && empty($mes)) {
                continue;
            }

            // Validar DNI
            if (empty($dni)) {
                $errores[] = [
                    'fila'       => $row,
                    'dni'        => '-',
                    'trabajador' => '-',
                    'dia'        => '-',
                    'codigo'     => '-',
                    'error'      => 'DNI vacío en la fila.',
                ];
                continue;
            }

            // Validar filas repetidas del mismo DNI en el archivo
            if (isset($dnisProcesados[$dni])) {
                $errores[] = [
                    'fila'       => $row,
                    'dni'        => $dni,
                    'trabajador' => '-',
                    'dia'        => '-',
                    'codigo'     => '-',
                    'error'      => "DNI {$dni} repetido en el archivo (visto previamente en la fila {$dnisProcesados[$dni]}).",
                ];
                continue;
            }
            $dnisProcesados[$dni] = $row;

            // Validar existencia de trabajador
            if (!isset($trabajadoresPorDni[$dni])) {
                $errores[] = [
                    'fila'       => $row,
                    'dni'        => $dni,
                    'trabajador' => '-',
                    'dia'        => '-',
                    'codigo'     => '-',
                    'error'      => "El trabajador con DNI {$dni} no existe o no se encuentra activo en el personal.",
                ];
                continue;
            }

            $trabajador = $trabajadoresPorDni[$dni];
            $perlIde    = (int) $trabajador['perl_ide'];
            $nombreTrab = trim("{$trabajador['per_paterno']} {$trabajador['per_materno']} {$trabajador['per_nombre']}");

            // Validar pertenencia al establecimiento si se filtró
            if (!empty($estIde) && (int) $trabajador['perl_est_ide'] !== $estIde) {
                $errores[] = [
                    'fila'       => $row,
                    'dni'        => $dni,
                    'trabajador' => $nombreTrab,
                    'dia'        => '-',
                    'codigo'     => '-',
                    'error'      => "El trabajador no pertenece al establecimiento seleccionado para la programación.",
                ];
                continue;
            }

            // Validar Año y Mes
            if ($anio !== $anioEsperado || $mes !== $mesEsperado) {
                $errores[] = [
                    'fila'       => $row,
                    'dni'        => $dni,
                    'trabajador' => $nombreTrab,
                    'dia'        => '-',
                    'codigo'     => '-',
                    'error'      => "El periodo especificado ({$anio}-{$mes}) no coincide con el periodo de importación ({$anioEsperado}-{$mesEsperado}).",
                ];
                continue;
            }

            // Procesar cada día de DIA_01 a DIA_31
            $turnosFilaTrabajador = [];

            for ($d = 1; $d <= 31; $d++) {
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($d + 3);
                $codTurno  = strtoupper(trim((string) $sheet->getCell("{$colLetter}{$row}")->getValue()));

                if (empty($codTurno)) {
                    continue; // Celda vacía, no se procesa
                }

                // Validar día real del mes
                if ($d > $diasMes) {
                    $errores[] = [
                        'fila'       => $row,
                        'dni'        => $dni,
                        'trabajador' => $nombreTrab,
                        'dia'        => sprintf('Día %02d', $d),
                        'codigo'     => $codTurno,
                        'error'      => "El mes {$mesEsperado}/{$anioEsperado} solo tiene {$diasMes} días. El turno no puede asignarse al día {$d}.",
                    ];
                    continue;
                }

                // Validar código de turno
                if (!isset($mapaTurnos[$codTurno])) {
                    $errores[] = [
                        'fila'       => $row,
                        'dni'        => $dni,
                        'trabajador' => $nombreTrab,
                        'dia'        => sprintf('Día %02d', $d),
                        'codigo'     => $codTurno,
                        'error'      => "Código de turno '{$codTurno}' no reconocido en el catálogo.",
                    ];
                    continue;
                }

                $infoTurno = $mapaTurnos[$codTurno];
                $fechaDia  = sprintf('%04d-%02d-%02d', $anioEsperado, $mesEsperado, $d);

                // Detectar si ya existe programación en ese día
                $existeProg = isset($progExistenteIndex[$perlIde][$fechaDia]);
                if ($existeProg) {
                    $totalExistentes++;
                }

                $turnosFilaTrabajador[$d] = [
                    'dia'               => $d,
                    'fecha'             => $fechaDia,
                    'codigo'            => $codTurno,
                    'th_ide'            => $infoTurno['th_ide'],
                    'tur_codigo'        => $infoTurno['tur_codigo'],
                    'tur_nombre'        => $infoTurno['tur_nombre'],
                    'tur_color'         => $infoTurno['tur_color'],
                    'duracion_horas'    => $infoTurno['duracion_horas'],
                    'ya_existe'         => $existeProg,
                    'prog_ide_existente'=> $existeProg ? $progExistenteIndex[$perlIde][$fechaDia]['prog_ide'] : null,
                ];
                $totalCeldasTurnos++;
            }

            // Validar cruces de horarios entre turnos consecutivos de la misma fila
            ksort($turnosFilaTrabajador);
            $diasAsignados = array_keys($turnosFilaTrabajador);

            for ($i = 0; $i < count($diasAsignados) - 1; $i++) {
                $dActual = $diasAsignados[$i];
                $dSig    = $diasAsignados[$i + 1];

                if ($dSig === $dActual + 1) {
                    $tActual = $turnosFilaTrabajador[$dActual];
                    $tSig    = $turnosFilaTrabajador[$dSig];

                    $hActual = $mapaTurnos[$tActual['codigo']];
                    $hSig    = $mapaTurnos[$tSig['codigo']];

                    if ($hActual['es_nocturno']) {
                        // El turno nocturno cruza al día siguiente y termina a las hActual['th_hora_salida']
                        $finNocturno = strtotime($tSig['fecha'] . ' ' . $hActual['th_hora_salida']);
                        $inicioSig   = strtotime($tSig['fecha'] . ' ' . $hSig['th_hora_ingreso']);

                        if ($finNocturno > $inicioSig) {
                            $errores[] = [
                                'fila'       => $row,
                                'dni'        => $dni,
                                'trabajador' => $nombreTrab,
                                'dia'        => sprintf('Días %02d y %02d', $dActual, $dSig),
                                'codigo'     => "{$tActual['codigo']} -> {$tSig['codigo']}",
                                'error'      => "Cruce horario: El turno nocturno del día {$dActual} concluye a las {$hActual['th_hora_salida']} del día {$dSig}, solapándose con el turno {$tSig['codigo']} que inicia a las {$hSig['th_hora_ingreso']}.",
                            ];
                        }
                    }
                }
            }

            if (!empty($turnosFilaTrabajador)) {
                $registrosValidos[] = [
                    'fila'        => $row,
                    'perl_ide'    => $perlIde,
                    'dni'         => $dni,
                    'trabajador'  => $nombreTrab,
                    'turnos'      => $turnosFilaTrabajador,
                    'total_turnos'=> count($turnosFilaTrabajador),
                ];
            }
        }

        return [
            'status'            => true,
            'anio'              => $anioEsperado,
            'mes'               => $mesEsperado,
            'dias_mes'          => $diasMes,
            'total_filas'       => count($dnisProcesados),
            'total_turnos'      => $totalCeldasTurnos,
            'total_existentes'  => $totalExistentes,
            'total_errores'     => count($errores),
            'registros_validos' => $registrosValidos,
            'errores'           => $errores,
        ];
    }

    /**
     * Aplica la importación masiva definitiva dentro de una transacción de base de datos
     */
    public function procesarImportacionDefinitiva(array $registrosValidos, bool $reemplazarExistentes, ?int $estIde = null, ?int $upsIde = null, ?int $ussIde = null, ?int $usuarioId = null): array
    {
        if (empty($registrosValidos)) {
            return ['status' => false, 'message' => 'No hay registros válidos para importar.'];
        }

        // Resolver eupIde y eusIde si se proporcionaron
        $eupIde = null;
        $eusIde = null;
        if (!empty($estIde) && !empty($upsIde)) {
            $eupIde = $this->obtenerOCrearEstablecimientoUpss($estIde, $upsIde, $usuarioId);
            if ($eupIde && !empty($ussIde)) {
                $eusIde = $this->obtenerOCrearEstablecimientoUpssServicio($eupIde, $ussIde, $usuarioId);
            }
        }

        $this->db->transStart();

        $insertados   = 0;
        $actualizados = 0;
        $omitidos     = 0;

        foreach ($registrosValidos as $reg) {
            $perlIde = (int) $reg['perl_ide'];

            foreach ($reg['turnos'] as $t) {
                $fecha  = $t['fecha'];
                $thIde  = (int) $t['th_ide'];
                $existe = !empty($t['ya_existe']);

                if ($existe) {
                    if ($reemplazarExistentes) {
                        // Actualizar turno existente
                        $this->db->table('casis_programacion')
                            ->where('prog_perl_ide', $perlIde)
                            ->where('prog_fecha', $fecha)
                            ->where('deleted_at', null)
                            ->update([
                                'prog_th_ide'  => $thIde,
                                'prog_eup_ide' => $eupIde,
                                'prog_eus_ide' => $eusIde,
                                'prog_estado'  => 'PROGRAMADO',
                                'updated_by'   => $usuarioId,
                                'updated_at'   => date('Y-m-d H:i:s'),
                            ]);
                        $actualizados++;
                    } else {
                        // Se conserva el existente, se omite
                        $omitidos++;
                    }
                } else {
                    // Insertar nueva asignación
                    $this->db->table('casis_programacion')->insert([
                        'prog_perl_ide'    => $perlIde,
                        'prog_fecha'       => $fecha,
                        'prog_th_ide'      => $thIde,
                        'prog_eup_ide'     => $eupIde,
                        'prog_eus_ide'     => $eusIde,
                        'prog_estado'      => 'PROGRAMADO',
                        'prog_observacion' => 'IMPORTADO_EXCEL',
                        'created_by'       => $usuarioId,
                        'created_at'       => date('Y-m-d H:i:s'),
                    ]);
                    $insertados++;
                }
            }
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return [
                'status'  => false,
                'message' => 'Ocurrió un error en la base de datos durante la importación. Se revirtieron todos los cambios.',
            ];
        }

        return [
            'status'       => true,
            'message'      => sprintf(
                'Importación completada con éxito: %d turnos creados, %d actualizados, %d conservados sin cambios.',
                $insertados,
                $actualizados,
                $omitidos
            ),
            'insertados'   => $insertados,
            'actualizados' => $actualizados,
            'omitidos'     => $omitidos,
        ];
    }

    /**
     * Genera un reporte Excel de errores detectados en la previsualización
     */
    public function generarReporteErroresExcel(array $errores, int $anio, int $mes): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Errores_Validacion');
        $sheet->setShowGridLines(true);

        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', "REPORTE DE OBSERVACIONES / ERRORES DE IMPORTACIÓN ({$mes}/{$anio})");
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(13)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DC2626');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $headers = [
            'A3' => 'FILA',
            'B3' => 'DNI',
            'C3' => 'TRABAJADOR',
            'D3' => 'DÍA / COLUMNA',
            'E3' => 'VALOR INGRESADO',
            'F3' => 'DESCRIPCIÓN DEL ERROR / OBSERVACIÓN',
        ];

        foreach ($headers as $c => $t) {
            $sheet->setCellValue($c, $t);
        }

        $sheet->getStyle('A3:F3')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => '1C3254']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'CBD5E1']]],
        ]);

        $rowNum = 4;
        foreach ($errores as $err) {
            $sheet->setCellValue("A{$rowNum}", $err['fila']);
            $sheet->setCellValueExplicit("B{$rowNum}", (string) $err['dni'], DataType::TYPE_STRING);
            $sheet->setCellValue("C{$rowNum}", $err['trabajador']);
            $sheet->setCellValue("D{$rowNum}", $err['dia']);
            $sheet->setCellValue("E{$rowNum}", $err['codigo']);
            $sheet->setCellValue("F{$rowNum}", $err['error']);

            $sheet->getStyle("A{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("E{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $rowNum++;
        }

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return $spreadsheet;
    }

    /**
     * Exporta la programación mensual en formato compatible con la importación (DNI | ANIO | MES | DIA_01 ... DIA_31)
     */
    public function exportarExcelImportable(int $anio, int $mes, ?int $estIde = null, ?int $upsIde = null, ?int $ussIde = null, ?int $ofiIde = null, ?string $dni = null): Spreadsheet
    {
        $matrizData = $this->obtenerMatrizMensual($anio, $mes, $estIde, $upsIde, $ussIde, null, $ofiIde, $dni);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Programacion');
        $sheet->setShowGridLines(true);

        // Encabezados
        $sheet->setCellValue('A1', 'DNI');
        $sheet->setCellValue('B1', 'ANIO');
        $sheet->setCellValue('C1', 'MES');

        for ($d = 1; $d <= 31; $d++) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($d + 3);
            $sheet->setCellValue("{$colLetter}1", sprintf('DIA_%02d', $d));
        }

        $totalCols = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(34);
        $sheet->getStyle("A1:{$totalCols}1")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => '1C3254']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'CBD5E1']]],
        ]);

        $rowNum = 2;
        foreach ($matrizData['matriz'] as $fila) {
            $sheet->setCellValueExplicit("A{$rowNum}", $fila['dni'], DataType::TYPE_STRING);
            $sheet->setCellValue("B{$rowNum}", $anio);
            $sheet->setCellValue("C{$rowNum}", $mes);

            $sheet->getStyle("A{$rowNum}:C{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            for ($d = 1; $d <= 31; $d++) {
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($d + 3);
                $turnos = $fila['dias'][$d] ?? [];
                if (!empty($turnos)) {
                    $codigos = array_map(fn($t) => $t['tur_codigo'], $turnos);
                    $sheet->setCellValue("{$colLetter}{$rowNum}", implode('/', $codigos));
                }
                $sheet->getStyle("{$colLetter}{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
            $rowNum++;
        }

        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return $spreadsheet;
    }

    /**
     * Exporta el reporte institucional mensual de programación en matriz legible
     */
    protected function nombresFiltrosReporte(?int $estIde, ?int $upsIde, ?int $ussIde, ?int $ofiIde, ?string $dni): array
    {
        $filtros = [];
        foreach ([
            ['Establecimiento', 'casis_establecimiento', 'est_ide', 'est_nombre', $estIde],
            ['Oficina', 'casis_oficina', 'ofi_ide', 'ofi_nombre', $ofiIde],
            ['UPSS', 'casis_upss', 'ups_ide', 'ups_nombre', $upsIde],
            ['Servicio UPSS', 'casis_upss_servicio', 'uss_ide', 'uss_nombre', $ussIde],
        ] as [$etiqueta, $tabla, $clave, $nombre, $id]) {
            $fila = $id ? $this->db->table($tabla)->select($nombre)->where($clave, $id)->get()->getRowArray() : null;
            $filtros[$etiqueta] = $id ? ($fila[$nombre] ?? 'No encontrado') : 'Todos';
        }
        $filtros['DNI / documento'] = trim($dni ?? '') !== '' ? trim($dni) : 'Todos';
        return $filtros;
    }

    public function exportarExcelReporteLegible(int $anio, int $mes, ?int $estIde = null, ?int $upsIde = null, ?int $ussIde = null, ?int $ofiIde = null, ?string $dni = null): Spreadsheet
    {
        $matrizData = $this->obtenerMatrizMensual($anio, $mes, $estIde, $upsIde, $ussIde, null, $ofiIde, $dni);
        $diasMes = $matrizData['dias_mes'];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rol_Turnos_Mensual');
        $sheet->setShowGridLines(true);

        $lastColIndex = 4 + $diasMes; // N°, DNI, NOMBRES, CARGO + días + TOTAL HORAS
        $lastColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastColIndex + 1);

        // Título
        $sheet->mergeCells("A1:{$lastColLetter}1");
        $sheet->setCellValue('A1', "ROL MENSUAL DE PROGRAMACIÓN DE TURNOS - PERIODO {$mes}/{$anio}");
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('1C3254');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Cabeceras fijas
        $sheet->setCellValue('A3', 'N°');
        $sheet->setCellValue('B3', 'DNI');
        $sheet->setCellValue('C3', 'APELLIDOS Y NOMBRES');
        $sheet->setCellValue('D3', 'CARGO');

        $colIdx = 5;
        foreach ($matrizData['dias'] as $d) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx);
            $sheet->setCellValue("{$colLetter}3", $d['dia']);
            $colIdx++;
        }
        $colTotalHoras = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx);
        $sheet->setCellValue("{$colTotalHoras}3", 'TOTAL HORAS');

        $sheet->getStyle("A3:{$colTotalHoras}3")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFF'], 'size' => 10],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => '2563EB']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'CBD5E1']]],
        ]);

        $rowNum = 4;
        $num = 1;
        foreach ($matrizData['matriz'] as $fila) {
            $sheet->setCellValue("A{$rowNum}", $num);
            $sheet->setCellValueExplicit("B{$rowNum}", $fila['dni'], DataType::TYPE_STRING);
            $sheet->setCellValue("C{$rowNum}", $fila['trabajador']);
            $sheet->setCellValue("D{$rowNum}", $fila['cargo']);

            $sheet->getStyle("A{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $cIdx = 5;
            for ($d = 1; $d <= $diasMes; $d++) {
                $cLet = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cIdx);
                $turnos = $fila['dias'][$d] ?? [];

                if (!empty($turnos)) {
                    $codigos = array_map(fn($t) => $t['tur_codigo'], $turnos);
                    $sheet->setCellValue("{$cLet}{$rowNum}", implode('/', $codigos));

                    // Colorear celda con el color del turno si es único
                    if (count($turnos) === 1 && !empty($turnos[0]['tur_color'])) {
                        $colorHex = ltrim($turnos[0]['tur_color'], '#');
                        if (strlen($colorHex) === 6) {
                            $sheet->getStyle("{$cLet}{$rowNum}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF' . $colorHex);
                            $sheet->getStyle("{$cLet}{$rowNum}")->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'))->setBold(true);
                        }
                    }
                }
                $sheet->getStyle("{$cLet}{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $cIdx++;
            }

            // Total horas
            $sheet->setCellValue("{$colTotalHoras}{$rowNum}", $fila['total_horas']);
            $sheet->getStyle("{$colTotalHoras}{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("{$colTotalHoras}{$rowNum}")->getFont()->setBold(true);

            $rowNum++;
            $num++;
        }

        // Bordes de la matriz
        if ($rowNum > 4) {
            $sheet->getStyle("A4:{$colTotalHoras}" . ($rowNum - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('E2E8F0');
        }

        // Auto-dimensionar columnas de identificación
        $sheet->getColumnDimension('A')->setWidth(6);
        $sheet->getColumnDimension('B')->setWidth(14);
        $sheet->getColumnDimension('C')->setWidth(34);
        $sheet->getColumnDimension('D')->setWidth(24);
        for ($c = 5; $c < $colIdx; $c++) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c);
            $sheet->getColumnDimension($colLetter)->setWidth(5.5);
        }
        $sheet->getColumnDimension($colTotalHoras)->setWidth(15);

        // Reservar espacio para los filtros sin alterar los datos de la matriz.
        $sheet->insertNewRowBefore(3, 6);
        $filaFiltro = 3;
        foreach ($this->nombresFiltrosReporte($estIde, $upsIde, $ussIde, $ofiIde, $dni) as $etiqueta => $valor) {
            $sheet->mergeCells("A{$filaFiltro}:{$lastColLetter}{$filaFiltro}");
            $sheet->setCellValueExplicit("A{$filaFiltro}", $etiqueta . ': ' . $valor, DataType::TYPE_STRING);
            $sheet->getStyle("A{$filaFiltro}")->getFont()->setSize(11);
            $sheet->getStyle("A{$filaFiltro}")->getAlignment()->setWrapText(true);
            $sheet->getRowDimension($filaFiltro)->setRowHeight(30);
            $filaFiltro++;
        }
        $sheet->mergeCells("A8:{$lastColLetter}8");
        $sheet->setCellValue('A8', 'Generado: ' . date('d/m/Y H:i') . ' | Trabajadores: ' . count($matrizData['matriz']));
        $sheet->getRowDimension(1)->setRowHeight(28);
        $sheet->freezePane('E10');
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A3)
            ->setFitToWidth(1)->setFitToHeight(0)->setRowsToRepeatAtTopByStartAndEnd(1, 9);
        $sheet->getPageSetup()->setPrintArea('A1:' . $lastColLetter . max(9, $rowNum + 5));

        return $spreadsheet;
    }
}

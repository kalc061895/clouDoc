<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\CasisAsistenciaModel;
use Modules\Asistencia\Models\PersonalModel;
use Modules\Asistencia\Models\ProgramacionModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MarcacionService
{
    protected CasisAsistenciaModel $asistenciaModel;
    protected PersonalModel $personalModel;
    protected ProgramacionModel $programacionModel;
    protected $db;

    public function __construct()
    {
        $this->asistenciaModel   = new CasisAsistenciaModel();
        $this->personalModel     = new PersonalModel();
        $this->programacionModel = new ProgramacionModel();
        $this->db                = \Config\Database::connect();
    }

    /**
     * Consulta con paginación, búsqueda y filtros para Server-Side DataTables
     */
    public function obtenerMarcacionesDataTable(
        array $filtros,
        int $start = 0,
        int $length = 25,
        string $search = '',
        string $orderColumn = 'asi_fecha_hora',
        string $orderDir = 'DESC'
    ): array {
        $builder = $this->db->table('casis_asistencia a');

        $this->aplicarJoins($builder);
        $builder->where('a.deleted_at', null);

        // Total sin filtros aplicados
        $totalRecords = (clone $builder)->countAllResults(false);

        // Aplicar filtros dinámicos
        $this->aplicarFiltros($builder, $filtros);

        // Búsqueda global de DataTables
        if (!empty($search)) {
            $search = trim($search);
            $builder->groupStart()
                ->like('pe.per_numero_documento', $search)
                ->orLike('pe.per_paterno', $search)
                ->orLike('pe.per_materno', $search)
                ->orLike('pe.per_nombre', $search)
                ->orLike('est.est_nombre', $search)
                ->orLike('a.asi_dispositivo', $search)
                ->orLike('a.asi_origen', $search)
                ->orLike('a.asi_tipo', $search)
                ->groupEnd();
        }

        // Total con filtros aplicados
        $filteredRecords = (clone $builder)->countAllResults(false);

        // Columnas permitidas para ordenamiento
        $columnasPermitidas = [
            'asi_fecha_hora'      => 'a.asi_fecha_hora',
            'per_numero_documento'=> 'pe.per_numero_documento',
            'trabajador'          => 'pe.per_paterno',
            'est_nombre'          => 'est.est_nombre',
            'asi_dispositivo'     => 'a.asi_dispositivo',
            'asi_origen'          => 'a.asi_origen',
            'asi_tipo'            => 'a.asi_tipo',
        ];

        $columnaSql = $columnasPermitidas[$orderColumn] ?? 'a.asi_fecha_hora';
        $orderDir   = strtoupper($orderDir) === 'ASC' ? 'ASC' : 'DESC';
        $builder->orderBy($columnaSql, $orderDir);

        if ($length > 0) {
            $builder->limit($length, $start);
        }

        $registros = $builder->get()->getResultArray();

        // Enriquecer registros contrastando contra la programación de turnos de esa fecha
        $fechaCache = [];
        foreach ($registros as &$reg) {
            $fecha = date('Y-m-d', strtotime($reg['asi_fecha_hora']));
            $perlId = (int) $reg['asi_perl_ide'];
            $cacheKey = "{$perlId}_{$fecha}";

            if (!isset($fechaCache[$cacheKey])) {
                $fechaCache[$cacheKey] = $this->obtenerTurnoProgramadoFecha($perlId, $fecha);
            }
            $reg['turno_programado'] = $fechaCache[$cacheKey];
        }

        return [
            'total'    => $totalRecords,
            'filtered' => $filteredRecords,
            'data'     => $registros,
        ];
    }

    /**
     * Aplica los JOINs estándar para obtener información completa del personal
     */
    protected function aplicarJoins($builder)
    {
        $builder->select('
            a.asi_ide,
            a.asi_imp_ide,
            a.asi_perl_ide,
            a.asi_numero_documento,
            a.asi_fecha_hora,
            a.asi_dispositivo,
            a.asi_origen,
            a.asi_tipo,
            a.asi_motivo,
            a.asi_ip_log,
            a.created_at,
            pe.per_ide,
            pe.per_numero_documento,
            pe.per_paterno,
            pe.per_materno,
            pe.per_nombre,
            p.perl_codigo,
            est.est_ide,
            est.est_nombre,
            car.car_nombre,
            mco.mco_nombre
        ')
        ->join('casis_personal p', 'p.perl_ide = a.asi_perl_ide', 'left')
        ->join('casis_persona pe', 'pe.per_ide = p.perl_per_ide', 'left')
        ->join('casis_establecimiento est', 'est.est_ide = p.perl_est_ide', 'left')
        ->join('casis_cargo car', 'car.car_ide = p.perl_car_ide', 'left')
        ->join('casis_modalidad_contrato mco', 'mco.mco_ide = p.perl_mco_ide', 'left');
    }

    /**
     * Aplica los filtros a la consulta
     */
    protected function aplicarFiltros($builder, array $filtros)
    {
        if (!empty($filtros['fecha_inicio'])) {
            $builder->where('a.asi_fecha_hora >=', $filtros['fecha_inicio'] . ' 00:00:00');
        }
        if (!empty($filtros['fecha_fin'])) {
            $builder->where('a.asi_fecha_hora <=', $filtros['fecha_fin'] . ' 23:59:59');
        }
        if (!empty($filtros['perl_ide'])) {
            $builder->where('a.asi_perl_ide', (int) $filtros['perl_ide']);
        }
        if (!empty($filtros['dni'])) {
            $dni = trim($filtros['dni']);
            $builder->groupStart()
                ->where('a.asi_numero_documento', $dni)
                ->orWhere('pe.per_numero_documento', $dni)
                ->groupEnd();
        }
        if (!empty($filtros['est_ide'])) {
            $builder->where('p.perl_est_ide', (int) $filtros['est_ide']);
        }
        if (!empty($filtros['dispositivo'])) {
            $builder->where('a.asi_dispositivo', $filtros['dispositivo']);
        }
        if (!empty($filtros['origen'])) {
            $builder->where('a.asi_origen', $filtros['origen']);
        }
        if (!empty($filtros['tipo'])) {
            $builder->where('a.asi_tipo', $filtros['tipo']);
        }
    }

    /**
     * Obtiene el turno programado de un trabajador para una fecha dada
     */
    public function obtenerTurnoProgramadoFecha(int $perlIde, string $fecha): ?array
    {
        return $this->db->table('casis_programacion pr')
            ->select('
                pr.prog_ide,
                pr.prog_fecha,
                pr.prog_estado,
                t.tur_codigo,
                t.tur_nombre,
                t.tur_color,
                th.th_codigo,
                th.th_nombre,
                th.th_hora_ingreso,
                th.th_hora_salida
            ')
            ->join('casis_turno_horario th', 'th.th_ide = pr.prog_th_ide', 'inner')
            ->join('casis_turno t', 't.tur_ide = th.th_tur_ide', 'inner')
            ->where('pr.prog_perl_ide', $perlIde)
            ->where('pr.prog_fecha', $fecha)
            ->where('pr.deleted_at', null)
            ->get()
            ->getRowArray();
    }

    /**
     * Consulta detallada de marcaciones de un trabajador en un rango de fechas,
     * contrastando cada día con su turno programado.
     */
    public function obtenerDetalleTrabajador(int $perlIde, string $fechaInicio, string $fechaFin): ?array
    {
        $trabajador = $this->db->table('casis_personal p')
            ->select('
                p.perl_ide,
                p.perl_codigo,
                pe.per_numero_documento,
                pe.per_paterno,
                pe.per_materno,
                pe.per_nombre,
                pe.per_email,
                pe.per_telefono,
                est.est_nombre,
                car.car_nombre,
                mco.mco_nombre
            ')
            ->join('casis_persona pe', 'pe.per_ide = p.perl_per_ide', 'left')
            ->join('casis_establecimiento est', 'est.est_ide = p.perl_est_ide', 'left')
            ->join('casis_cargo car', 'car.car_ide = p.perl_car_ide', 'left')
            ->join('casis_modalidad_contrato mco', 'mco.mco_ide = p.perl_mco_ide', 'left')
            ->where('p.perl_ide', $perlIde)
            ->where('p.deleted_at', null)
            ->get()
            ->getRowArray();

        if (!$trabajador) {
            return null;
        }

        // Obtener todas las marcaciones reales en el rango
        $marcaciones = $this->db->table('casis_asistencia a')
            ->select('
                a.asi_ide,
                a.asi_fecha_hora,
                a.asi_dispositivo,
                a.asi_origen,
                a.asi_tipo,
                a.asi_motivo,
                a.created_at
            ')
            ->where('a.asi_perl_ide', $perlIde)
            ->where('a.asi_fecha_hora >=', $fechaInicio . ' 00:00:00')
            ->where('a.asi_fecha_hora <=', $fechaFin . ' 23:59:59')
            ->where('a.deleted_at', null)
            ->orderBy('a.asi_fecha_hora', 'ASC')
            ->get()
            ->getResultArray();

        // Agrupar marcaciones por fecha Y-m-d
        $marcasPorDia = [];
        foreach ($marcaciones as $m) {
            $dia = date('Y-m-d', strtotime($m['asi_fecha_hora']));
            $marcasPorDia[$dia][] = $m;
        }

        // Obtener programaciones en el rango
        $programaciones = $this->db->table('casis_programacion pr')
            ->select('
                pr.prog_ide,
                pr.prog_fecha,
                pr.prog_estado,
                t.tur_codigo,
                t.tur_nombre,
                t.tur_color,
                th.th_codigo,
                th.th_nombre,
                th.th_hora_ingreso,
                th.th_hora_salida
            ')
            ->join('casis_turno_horario th', 'th.th_ide = pr.prog_th_ide', 'inner')
            ->join('casis_turno t', 't.tur_ide = th.th_tur_ide', 'inner')
            ->where('pr.prog_perl_ide', $perlIde)
            ->where('pr.prog_fecha >=', $fechaInicio)
            ->where('pr.prog_fecha <=', $fechaFin)
            ->where('pr.deleted_at', null)
            ->get()
            ->getResultArray();

        $progPorDia = [];
        foreach ($programaciones as $p) {
            $progPorDia[$p['prog_fecha']][] = $p;
        }

        // Construir calendario diario del rango
        $diasReporte = [];
        $inicioTs = strtotime($fechaInicio);
        $finTs    = strtotime($fechaFin);

        for ($ts = $inicioTs; $ts <= $finTs; $ts += 86400) {
            $dia = date('Y-m-d', $ts);
            $diasReporte[] = [
                'fecha'           => $dia,
                'dia_semana'      => date('N', $ts), // 1=Lunes, 7=Domingo
                'dia_nombre'      => $this->obtenerNombreDia(date('N', $ts)),
                'turnos'          => $progPorDia[$dia] ?? [],
                'marcaciones'     => $marcasPorDia[$dia] ?? [],
                'tiene_turno'     => !empty($progPorDia[$dia]),
                'tiene_marca'     => !empty($marcasPorDia[$dia]),
            ];
        }

        return [
            'trabajador'   => $trabajador,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin'    => $fechaFin,
            'dias'         => $diasReporte,
            'total_marcas' => count($marcaciones),
        ];
    }

    /**
     * Registro manual de marcación con motivo obligatorio y auditoría
     */
    public function registrarMarcacionManual(array $datos, ?int $usuarioId = null): array
    {
        $perlIde   = (int) ($datos['asi_perl_ide'] ?? 0);
        $fechaHora = trim((string) ($datos['asi_fecha_hora'] ?? ''));
        $tipo      = trim((string) ($datos['asi_tipo'] ?? 'ENTRADA'));
        $motivo    = trim((string) ($datos['asi_motivo'] ?? ''));

        if ($perlIde <= 0) {
            return ['status' => false, 'message' => 'Debe seleccionar un trabajador válido.'];
        }
        if (empty($fechaHora) || !strtotime($fechaHora)) {
            return ['status' => false, 'message' => 'Fecha y hora de marcación no válida.'];
        }
        if (empty($motivo)) {
            return ['status' => false, 'message' => 'El motivo de la marcación manual es obligatorio.'];
        }

        // Obtener DNI del personal
        $personal = $this->db->table('casis_personal p')
            ->select('pe.per_numero_documento')
            ->join('casis_persona pe', 'pe.per_ide = p.perl_per_ide', 'inner')
            ->where('p.perl_ide', $perlIde)
            ->get()
            ->getRowArray();

        $dni = $personal['per_numero_documento'] ?? null;

        // Validar si ya existe marcación exacta
        $existe = $this->db->table('casis_asistencia')
            ->where('asi_perl_ide', $perlIde)
            ->where('asi_fecha_hora', $fechaHora)
            ->where('deleted_at', null)
            ->countAllResults();

        if ($existe > 0) {
            return ['status' => false, 'message' => 'Ya existe una marcación registrada para este trabajador en la misma fecha y hora.'];
        }

        $ip = service('request')->getIPAddress();

        $dataInsert = [
            'asi_perl_ide'         => $perlIde,
            'asi_numero_documento' => $dni,
            'asi_fecha_hora'       => $fechaHora,
            'asi_dispositivo'      => 'REGISTRO_MANUAL',
            'asi_origen'           => 'MANUAL',
            'asi_tipo'             => $tipo,
            'asi_motivo'           => $motivo,
            'asi_ip_log'           => substr($ip, 0, 50),
            'asi_user_ide'         => $usuarioId,
            'created_by'           => $usuarioId,
            'created_at'           => date('Y-m-d H:i:s'),
        ];

        $this->asistenciaModel->insert($dataInsert);
        $insertId = $this->asistenciaModel->getInsertID();

        return [
            'status'  => true,
            'message' => 'Marcación manual registrada correctamente.',
            'asi_ide' => $insertId,
        ];
    }

    /**
     * Corrección de marcación existente con motivo obligatorio y auditoría
     */
    public function corregirMarcacion(int $asiIde, array $datos, ?int $usuarioId = null): array
    {
        $marcacion = $this->asistenciaModel->find($asiIde);
        if (!$marcacion) {
            return ['status' => false, 'message' => 'Marcación no encontrada.'];
        }

        $fechaHora = trim((string) ($datos['asi_fecha_hora'] ?? ''));
        $tipo      = trim((string) ($datos['asi_tipo'] ?? ''));
        $motivo    = trim((string) ($datos['asi_motivo'] ?? ''));

        if (empty($fechaHora) || !strtotime($fechaHora)) {
            return ['status' => false, 'message' => 'Fecha y hora no válida.'];
        }
        if (empty($motivo)) {
            return ['status' => false, 'message' => 'El motivo de corrección es obligatorio.'];
        }

        $motivoCompleto = "CORRECCIÓN: " . $motivo;
        if (!empty($marcacion['asi_motivo'])) {
            $motivoCompleto .= " | Anterior: " . $marcacion['asi_motivo'];
        }

        $dataUpdate = [
            'asi_fecha_hora' => $fechaHora,
            'asi_tipo'       => !empty($tipo) ? $tipo : $marcacion['asi_tipo'],
            'asi_motivo'     => substr($motivoCompleto, 0, 255),
            'updated_by'     => $usuarioId,
            'updated_at'     => date('Y-m-d H:i:s'),
        ];

        $this->asistenciaModel->update($asiIde, $dataUpdate);

        return [
            'status'  => true,
            'message' => 'Marcación corregida correctamente.',
        ];
    }

    /**
     * Eliminación lógica (soft delete) con motivo obligatorio y auditoría
     */
    public function eliminarMarcacion(int $asiIde, string $motivo, ?int $usuarioId = null): array
    {
        $marcacion = $this->asistenciaModel->find($asiIde);
        if (!$marcacion) {
            return ['status' => false, 'message' => 'Marcación no encontrada.'];
        }

        $motivo = trim($motivo);
        if (empty($motivo)) {
            return ['status' => false, 'message' => 'Debe ingresar el motivo de anulación/eliminación.'];
        }

        $dataUpdate = [
            'asi_motivo' => substr("ANULADO: {$motivo}", 0, 255),
            'deleted_by' => $usuarioId,
            'deleted_at' => date('Y-m-d H:i:s'),
        ];

        $this->db->table('casis_asistencia')
            ->where('asi_ide', $asiIde)
            ->update($dataUpdate);

        return [
            'status'  => true,
            'message' => 'Marcación anulada correctamente.',
        ];
    }

    /**
     * Exporta a Excel respetando todos los filtros aplicados
     */
    public function exportarExcel(array $filtros): Spreadsheet
    {
        $builder = $this->db->table('casis_asistencia a');
        $this->aplicarJoins($builder);
        $builder->where('a.deleted_at', null);
        $this->aplicarFiltros($builder, $filtros);
        $builder->orderBy('a.asi_fecha_hora', 'ASC');

        $registros = $builder->get()->getResultArray();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Marcaciones');

        // Estilos
        $sheet->setShowGridLines(true);

        // Encabezado institucional
        $sheet->mergeCells('A1:K1');
        $sheet->setCellValue('A1', 'REPORTE DE MARCACIONES REALES DE ASISTENCIA');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('1C3254');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Subtítulo con filtros
        $filtroTexto = "Generado: " . date('d/m/Y H:i:s');
        if (!empty($filtros['fecha_inicio']) && !empty($filtros['fecha_fin'])) {
            $filtroTexto .= " | Rango: {$filtros['fecha_inicio']} al {$filtros['fecha_fin']}";
        }
        $sheet->mergeCells('A2:K2');
        $sheet->setCellValue('A2', $filtroTexto);
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Columnas
        $headers = [
            'A3' => 'N°',
            'B3' => 'FECHA',
            'C3' => 'HORA',
            'D3' => 'DNI',
            'E3' => 'APELLIDOS Y NOMBRES',
            'F3' => 'CARGO',
            'G3' => 'ESTABLECIMIENTO',
            'H3' => 'DISPOSITIVO',
            'I3' => 'ORIGEN',
            'J3' => 'TIPO MARCACIÓN',
            'K3' => 'TURNO PROGRAMADO',
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => '2563EB']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'CBD5E1']]],
        ];
        $sheet->getStyle('A3:K3')->applyFromArray($headerStyle);
        $sheet->getRowDimension(3)->setRowHeight(24);

        $rowNum = 4;
        $i = 1;
        $fechaCache = [];

        foreach ($registros as $r) {
            $fecha = date('Y-m-d', strtotime($r['asi_fecha_hora']));
            $hora  = date('H:i:s', strtotime($r['asi_fecha_hora']));
            $perlId = (int) $r['asi_perl_ide'];
            $cacheKey = "{$perlId}_{$fecha}";

            if (!isset($fechaCache[$cacheKey])) {
                $fechaCache[$cacheKey] = $this->obtenerTurnoProgramadoFecha($perlId, $fecha);
            }
            $tp = $fechaCache[$cacheKey];
            $turnoTexto = $tp ? ($tp['tur_codigo'] . ' (' . substr($tp['th_hora_ingreso'], 0, 5) . '-' . substr($tp['th_hora_salida'], 0, 5) . ')') : 'Sin Programar';

            $nombreCompleto = trim($r['per_paterno'] . ' ' . $r['per_materno'] . ' ' . $r['per_nombre']);
            $dni = $this->sanitizarTextoExcel($r['per_numero_documento'] ?? $r['asi_numero_documento'] ?? '');

            $sheet->setCellValue('A' . $rowNum, $i);
            $sheet->setCellValue('B' . $rowNum, $fecha);
            $sheet->setCellValue('C' . $rowNum, $hora);
            $sheet->setCellValueExplicit('D' . $rowNum, $dni, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('E' . $rowNum, $this->sanitizarTextoExcel($nombreCompleto));
            $sheet->setCellValue('F' . $rowNum, $this->sanitizarTextoExcel($r['car_nombre'] ?? ''));
            $sheet->setCellValue('G' . $rowNum, $this->sanitizarTextoExcel($r['est_nombre'] ?? ''));
            $sheet->setCellValue('H' . $rowNum, $this->sanitizarTextoExcel($r['asi_dispositivo'] ?? ''));
            $sheet->setCellValue('I' . $rowNum, $this->sanitizarTextoExcel($r['asi_origen'] ?? ''));
            $sheet->setCellValue('J' . $rowNum, $this->sanitizarTextoExcel($r['asi_tipo'] ?? ''));
            $sheet->setCellValue('K' . $rowNum, $this->sanitizarTextoExcel($turnoTexto));

            // Alineaciones
            $sheet->getStyle("A{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("H{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("I{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("J{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("K{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Resaltar sin turno programado
            if (!$tp) {
                $sheet->getStyle("K{$rowNum}")->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('DC2626'));
            } else {
                $sheet->getStyle("K{$rowNum}")->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('16A34A'));
            }

            $rowNum++;
            $i++;
        }

        // Bordes de datos
        if ($rowNum > 4) {
            $sheet->getStyle('A4:K' . ($rowNum - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('E2E8F0');
        }

        // Auto-dimensionar columnas
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return $spreadsheet;
    }

    /**
     * Previene inyección de fórmulas en Excel (=, +, -, @)
     */
    protected function sanitizarTextoExcel(?string $valor): string
    {
        if ($valor === null || $valor === '') {
            return '';
        }
        $primerCaracter = substr($valor, 0, 1);
        if (in_array($primerCaracter, ['=', '+', '-', '@'], true)) {
            return "'" . $valor;
        }
        return $valor;
    }

    protected function obtenerNombreDia(int $diaNumero): string
    {
        $dias = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo',
        ];
        return $dias[$diaNumero] ?? '';
    }
}

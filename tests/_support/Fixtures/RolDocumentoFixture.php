<?php

namespace Tests\Support\Fixtures;

use Modules\Asistencia\Services\RolDocumentoData;

class RolDocumentoFixture
{
    public static function matriz(int $anio = 2026, int $mes = 5, int $cantidad = 16): array
    {
        $diasMes = (int) (new \DateTimeImmutable(sprintf('%04d-%02d-01', $anio, $mes)))->format('t');
        $dias = [];
        for ($d = 1; $d <= $diasMes; $d++) {
            $n = (int) (new \DateTimeImmutable(sprintf('%04d-%02d-%02d', $anio, $mes, $d)))->format('N');
            $dias[] = ['dia' => $d, 'dia_semana' => $n, 'es_fin_de_semana' => $n >= 6];
        }
        $tipos = [
            ['GD', 'Guardia diurna', '07:00', '19:00', 12, '#FFE08A'],
            ['GN', 'Guardia nocturna', '19:00', '07:00', 12, '#24466B'],
            ['M', 'Mañana', '07:00', '13:00', 6, '#C7EAD8'],
            ['T', 'Tarde', '13:00', '19:00', 6, '#B6DDF4'],
            ['N', 'Noche', '19:00', '01:00', 6, '#CBBCEB'],
            ['MT', 'Mañana y tarde', '07:00', '19:00', 12, '#f99'],
        ];
        $personas = [];
        for ($p = 1; $p <= $cantidad; $p++) {
            $asignados = [];
            $horas = 0;
            $total = 0;
            for ($d = 1; $d <= $diasMes; $d++) {
                $asignados[$d] = [];
                if ($d % 4 === 0) continue;
                [$codigo, $nombre, $inicio, $fin, $duracion, $color] = $tipos[($d + $p) % count($tipos)];
                $asignados[$d][] = ['prog_ide' => $p * 100 + $d, 'tur_codigo' => $codigo, 'tur_nombre' => $nombre, 'tur_color' => $color, 'th_hora_ingreso' => $inicio, 'th_hora_salida' => $fin, 'duracion_horas' => $duracion];
                $horas += $duracion;
                $total++;
            }
            $personas[] = ['perl_ide' => $p, 'dni' => sprintf('%08d', $p), 'trabajador' => 'APELLIDO DE PRUEBA ' . $p . ' MARÍA DEL CARMEN', 'cargo' => 'TÉCNICO EN ENFERMERÍA', 'dias' => $asignados, 'total_horas' => $horas, 'total_turnos' => $total];
        }
        return ['dias' => $dias, 'dias_mes' => $diasMes, 'matriz' => $personas];
    }

    public static function documento(int $cantidad = 16): array
    {
        $filtros = RolDocumentoData::filtros(['anio' => 2026, 'mes' => 5, 'est_ide' => 1, 'ofi_ide' => 10, 'incluir_hijos' => 1]);
        return RolDocumentoData::preparar(self::matriz(2026, 5, $cantidad), [
            'est_nombre' => 'HOSPITAL DE DEMOSTRACIÓN - DATOS FICTICIOS', 'est_ipress' => '00000001', 'est_categoria' => 'II-2',
            'dir_nombre' => 'DIRESA DE DEMOSTRACIÓN', 'red_nombre' => 'RED DE SALUD DE DEMOSTRACIÓN', 'mic_nombre' => 'MICRORED DE DEMOSTRACIÓN',
            'departamento' => 'ENFERMERÍA', 'servicio_area' => 'EMERGENCIA (UPSS)', 'ambito' => 'DEPARTAMENTO: ENFERMERÍA / UPSS: EMERGENCIA',
        ], $filtros) + ['codigo' => 'ROL-202605-DEMO', 'generado_at' => '2026-09-30 12:00:00'];
    }
}

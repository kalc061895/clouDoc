<?php

namespace Modules\Asistencia\Services;

use DateTimeImmutable;

class AdvertenciasRol
{
    public static function evaluar(array $turnos, DateTimeImmutable $inicio, ?int $maxDias, ?float $maxHoras, array $diasConAusencia = []): array
    {
        $fin = $inicio->modify('first day of next month');
        $dias = [];
        $semanas = [];
        foreach ($turnos as $turno) {
            $fecha = $turno['prog_fecha'];
            $desde = new DateTimeImmutable($fecha . ' ' . $turno['th_hora_ingreso'], $inicio->getTimezone());
            $hasta = new DateTimeImmutable($fecha . ' ' . $turno['th_hora_salida'], $inicio->getTimezone());
            if ($hasta <= $desde) {
                $hasta = $hasta->modify('+1 day');
            }
            $desde = max($desde, $inicio);
            $hasta = min($hasta, $fin);
            while ($desde < $hasta) {
                $corte = min($hasta, $desde->setTime(0, 0)->modify('+1 day'));
                $dias[$desde->format('Y-m-d')] = true;
                $lunes = $desde->modify('monday this week')->format('Y-m-d');
                $semanas[$lunes] = ($semanas[$lunes] ?? 0) + ($corte->getTimestamp() - $desde->getTimestamp());
                $desde = $corte;
            }
        }

        $alertas = [];
        $racha = 0;
        $mayorRacha = 0;
        for ($dia = $inicio; $dia < $fin; $dia = $dia->modify('+1 day')) {
            $racha = isset($dias[$dia->format('Y-m-d')]) || isset($diasConAusencia[(int) $dia->format('j')]) ? 0 : $racha + 1;
            $mayorRacha = max($mayorRacha, $racha);
        }
        if ($maxDias !== null && $mayorRacha > $maxDias) {
            $alertas[] = "Hasta {$mayorRacha} días consecutivos sin turno (máx. {$maxDias}).";
        }
        ksort($semanas);
        foreach ($semanas as $lunes => $segundos) {
            if ($maxHoras !== null && $segundos > round($maxHoras * 3600)) {
                $fecha = new DateTimeImmutable($lunes, $inicio->getTimezone());
                $desde = max($inicio, $fecha)->format('d/m');
                $hasta = min($fin->modify('-1 day'), $fecha->modify('+6 days'))->format('d/m');
                $alertas[] = sprintf('%s al %s: %.2f h programadas (máx. %.2f h).', $desde, $hasta, $segundos / 3600, $maxHoras);
            }
        }
        return $alertas;
    }
}

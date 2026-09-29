<?php
$totalHoras = 0;
foreach ($turnos['dias'] ?? [] as $dia) {
    $totalHoras += array_sum(array_column($dia['turnos'], 'duracion_horas'));
}
?>
<?= view('Modules\\Asistencia\\Views\\personal\\modals\\pane_calendario_view', [
    'tipo' => 'turnos', 'dias' => $dias,
    'personalId' => $personal_id, 'fechaInicio' => $turnos['fecha_inicio'], 'resumen' => ($turnos['total_turnos'] ?? 0) . ' turnos programados · ' . round($totalHoras, 1) . ' horas',
]) ?>

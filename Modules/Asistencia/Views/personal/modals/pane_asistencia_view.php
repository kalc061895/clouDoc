<?= view('Modules\\Asistencia\\Views\\personal\\modals\\pane_calendario_view', [
    'tipo' => 'asistencia', 'dias' => $dias,
    'personalId' => $perlIde, 'fechaInicio' => $fechaInicio, 'resumen' => ($detalle['total_marcas'] ?? 0) . ' marcaciones',
]) ?>

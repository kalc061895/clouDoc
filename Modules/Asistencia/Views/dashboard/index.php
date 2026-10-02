<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Dashboard de asistencia<?= $this->endSection() ?>
<?= $this->section('pageStyles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/asistencia-dashboard.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div id="asistencia-dashboard">
<div class="dash-hero d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div class="d-flex align-items-center gap-3"><span class="dash-hero-icon"><i class="ti ti-chart-bar" aria-hidden="true"></i></span><div><span class="dash-eyebrow">GESTIÓN DEL PERSONAL</span><h2 class="mt-1 mb-1">Dashboard de asistencia</h2><p class="mb-0">Cobertura, incidencias y composición de tu equipo.</p></div></div>
    <a href="<?= base_url('asistencia/reportes/mensual') ?>" class="btn btn-light"><i class="ti ti-report-analytics me-2" aria-hidden="true"></i>Reporte mensual</a>
</div>
<form method="get" class="card card-body"><div class="row g-3 align-items-end">
    <div class="col-md-3"><label for="dash-fecha" class="form-label">Fecha de consulta</label><input id="dash-fecha" class="form-control" type="date" name="fecha" value="<?= esc($fecha, 'attr') ?>" max="<?= (new DateTimeImmutable('now', new DateTimeZone('America/Lima')))->format('Y-m-d') ?>" required></div>
    <div class="col-md-6"><label for="dash-est" class="form-label">Establecimiento</label><select id="dash-est" name="est_ide" class="form-select"><option value="">Todos los establecimientos</option><?php foreach ($establecimientos as $e): ?><option value="<?= (int) $e['est_ide'] ?>" <?= (string) $est === (string) $e['est_ide'] ? 'selected' : '' ?>><?= esc($e['est_nombre']) ?></option><?php endforeach ?></select></div>
    <div class="col-md-3"><button class="btn btn-primary w-100"><i class="ti ti-refresh me-2" aria-hidden="true"></i>Actualizar dashboard</button></div>
</div></form>
<?php if ($error): ?><div class="alert alert-danger" role="alert"><?= esc($error) ?></div><?php endif ?>
<?php if ($dashboard): $s = $dashboard['stats']; ?>
<?php
$metricIcons = ['personal' => ['users', 'primary'], 'programados' => ['calendar-event', 'secondary'], 'ingresaron' => ['user-check', 'success'], 'pendientes' => ['clock', 'warning'], 'posibles_faltas' => ['alert-triangle', 'danger'], 'justificados' => ['calendar-off', 'info'], 'futuro' => ['calendar-time', 'secondary'], 'sin_programacion' => ['calendar-plus', 'primary']];
$listIcons = ['pendientes' => ['clock', 'warning'], 'licencias' => ['clipboard', 'info'], 'vacaciones' => ['sun', 'success'], 'comisiones' => ['briefcase', 'secondary'], 'cumpleanos' => ['cake', 'danger'], 'papeletas' => ['file-text', 'primary']];
$groupIcons = ['unidad' => 'building', 'servicio' => 'heart-rate-monitor', 'profesion' => 'stethoscope', 'sexo' => 'users', 'modalidad' => 'briefcase'];
?>
<div class="d-flex justify-content-between flex-wrap mb-3 text-muted"><span>Fecha: <?= esc($dashboard['fecha']) ?></span><span>Actualizado: <?= esc($dashboard['generado']) ?> · hora de Lima</span></div>
<?php if (!$s['personal']): ?><div class="alert alert-info">No hay personal activo para los filtros seleccionados.</div><?php endif ?>
<div class="row g-3 mb-4">
<?php foreach (['personal' => ['Personal activo', 'Dotación registrada'], 'programados' => ['Personal programado', 'Personas con turno en la fecha'], 'ingresaron' => ['Ingresaron', 'Programados con entrada identificada'], 'pendientes' => ['Ingreso pendiente', 'Turnos iniciados sin entrada'], 'posibles_faltas' => ['Posibles faltas', 'Turno concluido; requiere revisión'], 'justificados' => ['Licencia o vacaciones', 'Programados con registro vigente'], 'futuro' => ['Turno por iniciar', 'Sin pendientes de turnos iniciados'], 'sin_programacion' => ['Ingreso sin programación', 'Entrada sin turno en la fecha']] as $key => [$label, $note]): ?>
<div class="col-6 col-xl-3"><div class="card dash-metric h-100 mb-0"><div class="card-body"><div class="d-flex justify-content-between align-items-start gap-2"><span class="dash-metric-label"><?= $label ?></span><span class="dash-icon bg-<?= $metricIcons[$key][1] ?>-subtle text-<?= $metricIcons[$key][1] ?>"><i class="ti ti-<?= $metricIcons[$key][0] ?>" aria-hidden="true"></i></span></div><div class="dash-number"><?= number_format($s[$key]) ?></div><small class="text-muted"><?= $note ?></small></div></div></div>
<?php endforeach ?>
</div>
<div class="dash-section-title"><h4>Panorama de la jornada</h4><p>Indicadores para orientar el seguimiento del día.</p></div>
<div class="row g-4 mb-4">
    <div class="col-lg-4"><section class="card h-100 mb-0"><div class="card-body"><h5><i class="ti ti-target me-2 text-success" aria-hidden="true"></i>Cobertura de ingreso</h5><p class="text-muted small">Ingresaron respecto al personal programado.</p><div id="dash-chart-cobertura" class="dash-chart" role="img" aria-label="Cobertura: <?= $s['cobertura'] === null ? 'sin programación' : $s['cobertura'] . ' por ciento' ?>"><p class="dash-chart-fallback"><?= $s['cobertura'] === null ? 'Sin programación' : $s['cobertura'] . '%' ?></p></div><div class="dash-coverage-caption"><strong><?= $s['ingresaron'] ?></strong> de <strong><?= $s['programados'] ?></strong> personas programadas registraron entrada.</div></div></section></div>
    <div class="col-lg-8"><section class="card h-100 mb-0"><div class="card-body"><h5><i class="ti ti-chart-bar me-2 text-primary" aria-hidden="true"></i>Seguimiento de asistencia</h5><p class="text-muted small">Una persona puede figurar en más de una categoría.</p><div id="dash-chart-jornada" class="dash-chart" role="img" aria-label="Comparación de los indicadores de asistencia de las tarjetas superiores"><p class="dash-chart-fallback">Los valores están disponibles en las tarjetas superiores.</p></div></div></section></div>
</div>
<div class="dash-section-title"><h4>Composición y cobertura del equipo</h4><p>Explora las distribuciones y abre el detalle para consultar todos los valores.</p></div>
<div class="row g-4 mb-4">
<?php foreach (['sexo' => 'Distribución por sexo', 'modalidad' => 'Modalidad de contratación', 'unidad' => 'Personal por unidad / oficina', 'servicio' => 'Cobertura por UPSS / servicio', 'profesion' => 'Personal por profesión'] as $key => $label): $group = $dashboard['grupos'][$key]; ?>
<div class="<?= $key === 'profesion' ? 'col-12' : 'col-lg-6' ?>"><section class="card h-100 mb-0"><div class="card-body"><h5><i class="ti ti-<?= $groupIcons[$key] ?> me-2 text-primary" aria-hidden="true"></i><?= $label ?></h5><p class="text-muted small"><?= $key === 'servicio' ? 'Programados e ingresos identificados por servicio.' : 'Dotación activa e ingresos de personal programado.' ?><?= count($group) > 8 ? ' Se muestran las 8 categorías con más personal; el detalle incluye todas.' : '' ?></p><div id="dash-chart-<?= $key ?>" class="dash-chart" role="img" aria-label="<?= esc($label, 'attr') ?>. Valores disponibles en Ver detalle."><p class="dash-chart-fallback"><?= $group ? 'Consulta los valores en el detalle.' : 'Sin datos disponibles.' ?></p></div>
<details class="dash-details"><summary><i class="ti ti-table me-2" aria-hidden="true"></i>Ver detalle <span class="text-muted">(<?= count($group) ?> categorías)</span></summary><div class="table-responsive dash-table-scroll"><table class="table align-middle mb-0"><caption class="visually-hidden"><?= $label ?></caption><thead><tr><th scope="col">Distribución</th><th scope="col" class="text-end"><?= $key === 'servicio' ? 'Programados' : 'Activos' ?></th><th scope="col" class="text-end">Ingresaron</th></tr></thead><tbody><?php foreach ($group as $labelGroup => $r): ?><tr><th scope="row" class="fw-normal"><?= esc($labelGroup) ?></th><td class="text-end"><?= $r['total'] ?></td><td class="text-end"><?= $r['ingresos'] ?></td></tr><?php endforeach ?><?php if (!$group): ?><tr><td colspan="3" class="text-muted">Sin datos disponibles.</td></tr><?php endif ?></tbody></table></div></details></div></section></div>
<?php endforeach ?>
</div>
<div class="dash-section-title"><h4>Agenda e incidencias</h4><p>Personas y registros que requieren seguimiento.</p></div>
<div class="row g-4">
<?php foreach (['pendientes' => 'Personal sin ingreso registrado', 'licencias' => 'Licencias vigentes', 'vacaciones' => 'Personal de vacaciones', 'comisiones' => 'Comisiones registradas', 'cumpleanos' => 'Cumpleaños del día', 'papeletas' => 'Papeletas del día'] as $key => $label): $items = $dashboard['listas'][$key]; ?>
<div class="col-lg-6 col-xxl-4"><section class="card h-100 mb-0"><div class="card-body"><div class="d-flex align-items-center gap-2 mb-3"><span class="dash-icon bg-<?= $listIcons[$key][1] ?>-subtle text-<?= $listIcons[$key][1] ?>"><i class="ti ti-<?= $listIcons[$key][0] ?>" aria-hidden="true"></i></span><h5 class="mb-0 flex-grow-1"><?= $label ?></h5><span class="badge bg-<?= $listIcons[$key][1] ?>-subtle text-<?= $listIcons[$key][1] ?> rounded-pill"><?= count($items) ?></span></div><div class="dash-list" tabindex="0" aria-label="<?= esc($label, 'attr') ?>"><?php if (!$items): ?><div class="dash-empty"><i class="ti ti-circle-check" aria-hidden="true"></i><p class="mb-0">Sin registros para esta fecha.</p></div><?php endif ?><?php foreach ($items as $item): ?><div class="dash-person"><span class="dash-avatar bg-primary-subtle text-primary" aria-hidden="true"><?= esc(mb_strtoupper(mb_substr(trim($item['nombre']), 0, 1))) ?></span><div><strong><?= esc($item['nombre']) ?></strong><div class="small text-muted"><?= esc($item['detalle']) ?></div></div></div><?php endforeach ?></div><?php if ($key === 'comisiones'): ?><small class="text-muted d-block mt-2">Licencias vigentes y papeletas registradas o aprobadas cuyo tipo contiene “comisión”.</small><?php endif ?></div></section></div>
<?php endforeach ?>
</div>
<details class="card card-body mt-4 dash-method"><summary><i class="ti ti-info-circle me-2 text-primary" aria-hidden="true"></i>Criterios de lectura de los indicadores</summary><p class="text-muted mt-3 mb-0">Los ingresos requieren marcación ENTRADA/INGRESO entre dos horas antes del inicio y el fin del turno. Se incluyen turnos nocturnos en su fecha de inicio. Los pendientes y posibles faltas son alertas para revisar; las papeletas pendientes no justifican automáticamente ausencias. <?= $s['marcaciones_sin_tipo'] ?> marcaciones del día no tienen tipo identificado. La dotación y ubicación corresponden al registro activo actual; los ingresos no certifican presencia física en este momento. Una persona puede aparecer en varias incidencias o servicios.</p></details>
<script type="application/json" id="dash-chart-data"><?= json_encode(['stats' => $s, 'grupos' => $dashboard['grupos']], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?></script>
<?php endif ?>
</div>
<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
<script src="<?= base_url('assets/js/asistencia/dashboard.js') ?>"></script>
<?= $this->endSection() ?>


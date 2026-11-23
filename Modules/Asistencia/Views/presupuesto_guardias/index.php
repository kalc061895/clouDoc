<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Presupuesto de guardias<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?php
use Modules\Asistencia\Services\PresupuestoGuardiasService as Presupuesto;
$periodos = [];
foreach ($reporte['periodos'] as $periodo) {
    $periodos[$periodo['id']] = $periodo['mes'] . ($periodo['grupo'] ? ' · ' . $periodo['grupo'] : '');
}
?>
<div class="container-fluid py-3">
    <h1 class="h3 mb-3">Presupuesto de guardias</h1>
    <?php if ($error): ?>
        <?= view('components/alert', ['variant' => 'danger', 'message' => $error]) ?>
    <?php endif ?>
    <?php if (!$periodos): ?>
        <?= view('components/alert', ['variant' => 'info', 'message' => 'No hay períodos con roles aprobados por Dirección dentro de su ámbito.']) ?>
    <?php endif ?>
    <form method="get" action="<?= site_url($ruta) ?>" class="card card-body mb-3" id="filtros-presupuesto-guardias">
        <div class="row">
            <div class="col-md-4">
                <?= view('components/form_field', [
                    'id' => 'periodo-guardias', 'name' => 'periodo', 'type' => 'select', 'label' => 'Período de rol aprobado',
                    'placeholder' => 'Seleccione un período', 'options' => $periodos, 'value' => $filtros['periodo'] ?? '', 'required' => true,
                ]) ?>
            </div>
            <?php foreach (Presupuesto::FILTROS as $campo => $label): ?>
                <div class="col-md-4">
                    <?= view('components/form_field', [
                        'id' => 'filtro-guardias-' . $campo, 'name' => $campo, 'type' => 'select', 'label' => $label,
                        'placeholder' => 'Todos', 'options' => $reporte['opciones'][$campo], 'value' => $filtros[$campo] ?? '',
                        'required' => false, 'disabled' => !$reporte['periodo'], 'attributes' => ['data-filtro-guardias' => '1'],
                    ]) ?>
                </div>
            <?php endforeach ?>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" <?= !$periodos ? 'disabled' : '' ?>>Consultar</button>
            <?php if ($reporte['periodo']): ?>
                <a class="btn btn-outline-secondary" href="<?= site_url($ruta) . '?periodo=' . (int) $reporte['periodo']['id'] ?>">Limpiar filtros</a>
            <?php endif ?>
        </div>
    </form>

    <?php if ($reporte['periodo']): ?>
        <div class="card card-body mb-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                <h2 class="h5 mb-0">Guardias programadas en roles aprobados</h2>
                <?php if ($reporte['filas']): ?>
                    <form method="post" action="<?= site_url($ruta . '/exportar') ?>" class="d-flex gap-2">
                        <?= csrf_field() ?>
                        <?php foreach ($filtros as $campo => $valor): ?>
                            <input type="hidden" name="<?= esc($campo, 'attr') ?>" value="<?= (int) $valor ?>">
                        <?php endforeach ?>
                        <button type="submit" name="formato" value="xlsx" class="btn btn-outline-success">Exportar Excel</button>
                        <button type="submit" name="formato" value="pdf" class="btn btn-outline-primary">Exportar PDF</button>
                    </form>
                <?php endif ?>
            </div>
            <p class="small text-muted">GDF y GNF agrupan domingos y feriados. Se usa la fecha en que comienza cada guardia y la tarifa de su tipo de día.</p>
            <?php if ($reporte['pendientes']): ?>
                <?= view('components/alert', [
                    'variant' => 'warning',
                    'message' => 'Hay ' . $reporte['pendientes'] . ' filas pendientes de valorizar. La consulta y las exportaciones muestran un total parcial hasta completar las tarifas.',
                ]) ?>
            <?php endif ?>
            <div class="table-responsive">
                <table class="table table-sm table-striped align-middle" id="tabla-presupuesto-guardias">
                    <thead><tr><?php foreach (Presupuesto::COLUMNAS as $columna): ?><th scope="col"><?= esc($columna) ?></th><?php endforeach ?></tr></thead>
                    <tbody>
                        <?php foreach ($reporte['filas'] as $i => $fila): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <?php foreach (array_slice($fila['valores'], 0, 9) as $valor): ?><td><?= esc((string) $valor) ?></td><?php endforeach ?>
                                <?php $monto = $fila['valores'][9]; ?>
                                <td class="text-end" data-order="<?= $monto ?? -1 ?>"><?= $monto === null ? 'Sin tarifa' : number_format($monto / 100, 2) ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                    <tfoot><tr>
                        <th colspan="5" scope="row"><?= $reporte['pendientes'] ? 'Total valorizado parcial' : 'Total' ?></th>
                        <?php foreach (array_slice($reporte['totales'], 0, 5) as $cantidad): ?><th><?= (int) $cantidad ?></th><?php endforeach ?>
                        <th class="text-end"><?= number_format($reporte['totales'][5] / 100, 2) ?></th>
                    </tr></tfoot>
                </table>
            </div>
        </div>
    <?php endif ?>

    <?php if ($historial): ?>
        <div class="card card-body">
            <h2 class="h5">Exportaciones recientes</h2>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead><tr><th>Código</th><th>Fecha y hora</th><th>Usuario</th><th>Archivo</th></tr></thead>
                    <tbody><?php foreach ($historial as $archivo): ?>
                        <tr>
                            <td class="font-monospace"><?= esc($archivo['codigo']) ?></td>
                            <td><?= esc($archivo['generado_at']) ?></td>
                            <td><?= (int) $archivo['usuario_id'] ?></td>
                            <td><a href="<?= site_url($ruta . '/archivo/' . $archivo['codigo']) ?>">Descargar <?= esc(strtoupper($archivo['formato'])) ?></a></td>
                        </tr>
                    <?php endforeach ?></tbody>
                </table>
            </div>
        </div>
    <?php endif ?>
</div>
<?= $this->section('pageScripts') ?>
<script>
    document.getElementById('periodo-guardias').addEventListener('change', () => {
        document.querySelectorAll('[data-filtro-guardias]').forEach(campo => { campo.value = '' });
    });
    if (window.jQuery?.fn.DataTable && document.getElementById('tabla-presupuesto-guardias')) {
        $('#tabla-presupuesto-guardias').DataTable({
            order: [], pageLength: 25, autoWidth: false, searching: false,
            language: {
                emptyTable: 'No hay personal con estos filtros',
                info: 'Mostrando _START_ a _END_ de _TOTAL_', infoEmpty: 'Sin resultados',
                lengthMenu: 'Mostrar _MENU_ filas', zeroRecords: 'Sin coincidencias',
                paginate: { previous: 'Anterior', next: 'Siguiente' }
            }
        });
    }
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>

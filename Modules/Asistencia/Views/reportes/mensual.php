<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Reporte mensual de asistencia<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div id="reporte-app" data-base="<?= esc(base_url('asistencia/reportes/mensual'), 'attr') ?>">
    <h3>Reporte mensual de asistencia</h3>
    <p class="text-muted">Resumen por trabajador y detalle diario de programación, marcaciones, licencias, permisos y vacaciones.</p>
    <form id="reporte-filtros" class="card card-body">
        <div class="row g-3">
            <div class="col-md-3"><label for="reporte-anio">Año</label><input id="reporte-anio" name="anio" class="form-control" type="number" min="2000" max="2100" value="<?= date('Y') ?>" required></div>
            <div class="col-md-3"><label for="reporte-mes">Mes</label><select id="reporte-mes" name="mes" class="form-select" required><?php foreach (['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'] as $i => $mes): ?><option value="<?= $i + 1 ?>" <?= $i + 1 === (int) date('n') ? 'selected' : '' ?>><?= $mes ?></option><?php endforeach ?></select></div>
            <?php foreach (['dir_ide' => 'DIRESA', 'red_ide' => 'Red', 'mic_ide' => 'Microred', 'est_ide' => 'Establecimiento', 'ups_ide' => 'UPSS', 'uss_ide' => 'Servicio', 'tofi_ide' => 'Tipo de oficina', 'ofi_ide' => 'Oficina'] as $key => $label): ?>
            <div class="col-md-3"><label for="reporte-<?= $key ?>"><?= $label ?></label><select id="reporte-<?= $key ?>" name="<?= $key ?>" class="form-select"><option value="">Todos</option></select></div>
            <?php endforeach ?>
            <div class="col-md-3"><label for="reporte-dni">Documento de identidad</label><input id="reporte-dni" name="dni" class="form-control" maxlength="20" placeholder="Opcional"></div>
            <div class="col-md-3 align-self-end"><label><input type="checkbox" name="incluir_hijos" value="1"> Incluir oficinas dependientes</label></div>
            <div class="col-md-6 align-self-end"><label><input type="checkbox" name="detalle" value="1"> Incluir detalle diario en PDF e impresión</label></div>
        </div>
        <div class="mt-3"><button type="submit" class="btn btn-primary">Consultar reporte</button></div>
    </form>
    <div id="reporte-mensaje" role="status" aria-live="polite"></div>
    <div id="reporte-exportaciones" class="d-flex gap-2 mb-3 d-none">
        <a id="reporte-excel" class="btn btn-success">Exportar Excel</a><a id="reporte-pdf" class="btn btn-danger" target="_blank" rel="noopener">Exportar PDF</a><a id="reporte-imprimir" class="btn btn-outline-primary" target="_blank" rel="noopener">Vista imprimible</a>
    </div>
    <div id="reporte-resultado"></div>
</div>
<script type="application/json" id="reporte-catalogos"><?= json_encode($catalogos, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script>
<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?><script src="<?= base_url('assets/js/asistencia/reporte-mensual.js') ?>"></script><?= $this->endSection() ?>

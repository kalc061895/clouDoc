<!-- SECCIÓN DE DOCUMENTOS GENERADOS POR EL SISTEMA -->
<div class="card border-primary mb-4 shadow-sm">
    <div class="card-header bg-primary text-white d-flex align-items-center gap-2">
        <iconify-icon icon="solar:document-bold-duotone" width="20"></iconify-icon>
        <h6 class="mb-0">Formatos y Documentos Generados</h6>
    </div>
    <div class="card-body">
        <p class="text-muted small mb-3">
            Descargue, imprima, firme y adjunte estos documentos según corresponda en su expediente de postulación:
        </p>

        <div class="list-group list-group-flush">
            <!-- 1. Solicitud de Postulación -->
            <a href="<?= base_url('seleccion/postulacion/generar-solicitud/' . $convocatoriaId) ?>"
                target="_blank"
                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-3">
                <div class="d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:file-text-bold" class="text-danger" width="22"></iconify-icon>
                    <div>
                        <strong>Solicitud de Postulación</strong>
                        <div class="text-muted small">Formato oficial de solicitud para el proceso.</div>
                    </div>
                </div>
                <span class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                    <iconify-icon icon="solar:download-minimalistic-bold"></iconify-icon> Descargar PDF
                </span>
            </a>

            <!-- 2. Ficha Única de Datos -->
            <a href="<?= base_url('seleccion/postulacion/generar-ficha-unica/' . $convocatoriaId) ?>"
                target="_blank"
                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-3">
                <div class="d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:user-id-bold" class="text-primary" width="22"></iconify-icon>
                    <div>
                        <strong>Ficha Única de Datos del Postulante</strong>
                        <div class="text-muted small">Resumen de datos personales, formación y experiencia declarada.</div>
                    </div>
                </div>
                <span class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                    <iconify-icon icon="solar:download-minimalistic-bold"></iconify-icon> Descargar PDF
                </span>
            </a>

            <!-- 3. Ficha de Autoevaluación -->
            <a href="<?= base_url('seleccion/postulacion/generar-ficha-autoevaluacion/' . $convocatoriaId) ?>"
                target="_blank"
                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-3">
                <div class="d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:checklist-bold" class="text-success" width="22"></iconify-icon>
                    <div>
                        <strong>Ficha de Autoevaluación y Calificación</strong>
                        <div class="text-muted small">Cuadro de evaluación de méritos para la convocatoria.</div>
                    </div>
                </div>
                <span class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                    <iconify-icon icon="solar:download-minimalistic-bold"></iconify-icon> Descargar PDF
                </span>
            </a>
        </div>
    </div>
</div>

<!-- TUS OTROS ANEXOS EXISTENTES -->
<h5 class="mb-1 mt-4">Otros anexos</h5>
<p class="text-muted small">Adjunte los documentos solicitados para esta convocatoria.</p>
<!-- ... (aquí continúa tu tabla de anexos original) ... -->
<h5 class="mb-1">Otros anexos</h5>
<p class="text-muted small">Adjunte los documentos solicitados para esta convocatoria.</p>
<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>Anexo</th>
                <th>Descripción / condición</th>
                <th>Obligatorio</th>
                <th>Estado</th>
                <th>Archivo</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($anexos as $a):
                $presentado = false;
                $rutaBase64 = '';
                foreach ($presentados as $p) {
                    if ($p['pan_ane_ide'] == $a['ane_ide']) {
                        $presentado = true;
                        $rutaBase64 = base64_encode($p['exd_ruta'] ?? '');

                        break;
                    }
                }
            ?>
                <tr>
                    <td><strong><?= esc($a['ane_codigo']) ?></strong><br><?= esc($a['ane_nombre']) ?></td>
                    <td class="small"><?= esc($a['ane_descripcion']) ?><br><?= esc($a['ane_condicion']) ?></td>
                    <td><?= $a['ane_obligatorio'] ? 'Sí' : 'No' ?></td>
                    <td><span
                            class="badge <?= $presentado ? 'bg-success' : 'bg-warning text-dark' ?>"><?= $presentado ? 'Presentado' : 'Pendiente' ?></span>
                        <?php if (!empty($rutaBase64)): ?>
                            <a href="<?= base_url('seleccion/ver/documento?path=' . $rutaBase64) ?>" target="_blank"
                                class="btn btn-outline-info btn-xs d-inline-flex align-items-center gap-1"
                                title="<?= esc($r['exd_nombre_original'] ?? 'Ver Documento') ?>">
                                <iconify-icon icon="solar:file-text-bold"></iconify-icon>
                                Ver
                            </a>
                        <?php else: ?>
                            <span class="badge bg-light text-secondary border">Sin adjunto</span>
                        <?php endif; ?>
                    </td>
                    <td><?php if ($editable): ?>
                            <form class="form-anexo" data-id="<?= $a['ane_ide'] ?>"><input type="file" name="archivo"
                                    accept=".pdf" required class="form-control form-control-sm"><button
                                    class="btn btn-sm btn-outline-primary mt-1">Subir / reemplazar</button></form>
                            <?php else: ?>—<?php endif ?>
                    </td>
                </tr><?php endforeach ?><?php if (!$anexos): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted">No hay anexos configurados.</td>
                </tr><?php endif ?>
        </tbody>
    </table>
</div>
<script>
    $('.form-anexo').on('submit', function(e) {
        e.preventDefault();
        const f = this;
        $.ajax({
            url: '<?= base_url('seleccion/postulacion/inscripcion/anexo/' . $convocatoriaId) ?>/' + $(f).data('id'),
            type: 'POST',
            data: new FormData(f),
            processData: false,
            contentType: false
        }).done(r => {
            r.status ? (toastr.success(r.message), recargarTabPostulacion('anexos')) : toastr.error(r.message)
        }).fail(x => toastr.error(x.responseJSON?.message || 'No se pudo subir el anexo'));
    });
</script>
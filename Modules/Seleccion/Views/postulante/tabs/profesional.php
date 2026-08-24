<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-1 fw-bold">Formación profesional</h5>
        <small class="text-muted">Ingrese su historial de educación superior o profesional. Formato admitido para el
            adjunto: PDF (máx. 10 MB).</small>
    </div>
    <?php if ($editable): ?>
        <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal"
            data-bs-target="#modal-profesional">
            <iconify-icon icon="solar:add-circle-bold" class="fs-5"></iconify-icon>
            Agregar Formación
        </button>
    <?php endif; ?>
</div>

<!-- TABLA DE REGISTROS -->
<div class="table-responsive">
    <table class="table table-hover table-sm align-middle">
        <thead class="table-light">
            <tr>
                <th>Profesión</th>
                <th>Institución</th>
                <th>Grado</th>
                <th>Título</th>
                <th>Fecha Expedición</th>
                <th>N° Colegiatura</th>
                <th>Habilitación</th>
                <th class="text-center">Documento</th>
                <th class="text-end">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registros as $r): ?>
                <?php
                // Obtención y codificación de la ruta del documento a Base64
                $rutaArchivo = $r['exd_ruta'] ?? $r['path_documento'] ?? null;
                $rutaBase64 = !empty($rutaArchivo) ? urlencode(base64_encode($rutaArchivo)) : '';
                ?>
                <tr>
                    <td><?= esc($r['pro_nombre']  ?? '-') ?></td>
                    <td><?= esc($r['ppr_institucion'] ?? '-') ?></td>
                    <td><?= esc($r['ppr_grado'] ?? '-') ?></td>
                    <td><?= esc($r['ppr_titulo'] ?? '-') ?></td>
                    <td><?= !empty($r['ppr_fecha']) ? date('d/m/Y', strtotime($r['ppr_fecha'])) : '-' ?></td>
                    <td><?= esc($r['ppr_colegiatura'] ?? '-') ?></td>
                    <td><?= esc($r['ppr_habilitacion'] ?? '-') ?></td>

                    <!-- Columna Documento Sustentatorio -->
                    <td class="text-center">
                        <?php if (!empty($rutaBase64)): ?>
                            <a href="<?= base_url('seleccion/ver/documento?path=' . $rutaBase64) ?>" target="_blank"
                                class="btn btn-outline-primary btn-xs d-inline-flex align-items-center gap-1"
                                title="<?= esc($r['exd_nombre_original'] ?? 'Ver Documento') ?>">
                                <iconify-icon icon="lucide:file-text"></iconify-icon>
                                Ver Adjunto
                            </a>
                        <?php else: ?>
                            <span class="badge bg-light text-secondary border">Sin archivo</span>
                        <?php endif; ?>
                    </td>

                    <!-- Columna Acciones -->
                    <td class="text-end">
                        <?php if ($editable): ?>
                            <button type="button"
                                class="btn btn-outline-danger btn-sm btn-eliminar d-inline-flex align-items-center gap-1"
                                data-id="<?= esc($r['ppr_ide']) ?>">
                                <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon>
                                Eliminar
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($registros)): ?>
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        <iconify-icon icon="solar:inbox-line"
                            class="fs-1 d-block mb-1 text-secondary opacity-50"></iconify-icon>
                        Aún no ha registrado información en formación profesional.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- MODAL DE REGISTRO -->
<?php if ($editable): ?>
    <div class="modal fade" id="modal-profesional" tabindex="-1" aria-labelledby="modalLabel-profesional"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalLabel-profesional">Agregar Formación Profesional</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-profesional" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="ppr_ide">

                        <div class="row g-3">
                            <!-- Profesión (Select) -->
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Profesión <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" name="ppr_pro_ide" required>
                                    <option value="">-- Seleccione Profesión --</option>
                                    <?php if (!empty($profesion) && is_array($profesion)): ?>
                                        <?php foreach ($profesion as $p): ?>
                                            <option value="<?= esc($p['pro_ide']) ?>">
                                                <?= esc($p['pro_denominacion'] ?? $p['pro_nombre'] ?? '') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <!-- Institución -->
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Institución <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="ppr_institucion"
                                    placeholder="Ej. Universidad Nacional..." required>
                            </div>

                            <!-- Grado -->
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Grado <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="ppr_grado"
                                    placeholder="Ej. Bachiller, Licenciado, Magíster..." required>
                            </div>

                            <!-- Título -->
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Título <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="ppr_titulo"
                                    placeholder="Ej. Ingeniero de Sistemas" required>
                            </div>

                            <!-- Fecha de Expedición -->
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Fecha de Expedición <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="ppr_fecha" required>
                            </div>

                            <!-- Colegiatura -->
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">N° Colegiatura</label>
                                <input type="text" class="form-control" name="ppr_colegiatura" placeholder="Ej. 123456">
                            </div>

                            <!-- Habilitación -->
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Habilitación</label>
                                <input type="text" class="form-control" name="ppr_habilitacion"
                                    placeholder="Ej. Habilitado">
                            </div>

                            <!-- Documento Adjunto -->
                            <div class="col-md-12">
                                <label class="form-label small fw-semibold">Documento Sustentatorio (.pdf)</label>
                                <input class="form-control" name="documento" type="file" accept=".pdf">
                                <div class="form-text small">Adjunte copia digitalizada legible del título/grado en formato
                                    PDF (máx. 10 MB).</div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success d-flex align-items-center gap-1"
                            id="btnGuardar-profesional">
                            <iconify-icon icon="solar:diskette-bold"></iconify-icon>
                            Guardar Información
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- SCRIPTS DE MANEJO AJAX PARA ESTA VISTA -->
<script>
    (function () {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        // Guardar vía AJAX
        $('#form-profesional').on('submit', function (e) {
            e.preventDefault();
            const form = this;
            const $btn = $('#btnGuardar-profesional');
            const originalBtnHtml = $btn.html();

            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Guardando...');

            $.ajax({
                url: '<?= base_url('seleccion/postulacion/inscripcion/registro/' . $convocatoriaId . '/profesional') ?>',
                type: 'POST',
                data: new FormData(form),
                processData: false,
                contentType: false
            })
                .done(r => {
                    if (r.status) {
                        Toast.fire({
                            icon: 'success',
                            title: r.message || 'Registro guardado con éxito'
                        });

                        const modalEl = document.getElementById('modal-profesional');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();
                        form.reset();

                        // Función personalizada para recargar la pestaña o sección
                        if (typeof recargarTabPostulacion === 'function') {
                            recargarTabPostulacion('profesional');
                        } else {
                            location.reload();
                        }
                    } else {
                        Swal.fire('Atención', r.message || 'Ocurrió un inconveniente al guardar.', 'warning');
                    }
                })
                .fail(x => {
                    const msg = x.responseJSON?.message || 'Ocurrió un error al procesar la solicitud.';
                    Swal.fire('Error', msg, 'error');
                })
                .always(() => {
                    $btn.prop('disabled', false).html(originalBtnHtml);
                });
        });

        // Eliminar vía AJAX
        $('.btn-eliminar').on('click', function () {
            const id = $(this).data('id');

            Swal.fire({
                title: '¿Eliminar registro?',
                text: "Esta acción no se podrá deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('seleccion/postulacion/inscripcion/registro/' . $convocatoriaId . '/profesional') ?>/' + id,
                        type: 'DELETE'
                    })
                        .done(r => {
                            if (r.status) {
                                Toast.fire({
                                    icon: 'success',
                                    title: r.message || 'Registro eliminado correctamente'
                                });
                                if (typeof recargarTabPostulacion === 'function') {
                                    recargarTabPostulacion('profesional');
                                } else {
                                    location.reload();
                                }
                            } else {
                                Swal.fire('Atención', r.message || 'No se pudo eliminar el registro.', 'warning');
                            }
                        })
                        .fail(x => {
                            const msg = x.responseJSON?.message || 'Error al intentar eliminar el registro.';
                            Swal.fire('Error', msg, 'error');
                        });
                }
            });
        });
    })();
</script>
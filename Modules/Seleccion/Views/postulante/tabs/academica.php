<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-1 fw-bold">Formación académica</h5>
        <small class="text-muted">Puede registrar varios antecedentes académicos. Formatos admitidos: PDF, JPG, PNG
            (máx. 10 MB).</small>
    </div>
    <?php if ($editable): ?>
        <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal"
            data-bs-target="#modal-academica">
            <iconify-icon icon="solar:add-circle-bold" class="fs-5"></iconify-icon>
            Agregar Registro
        </button>
    <?php endif; ?>
</div>

<!-- TABLA DE REGISTROS -->
<div class="table-responsive">
    <table class="table table-hover table-sm align-middle">
        <thead class="table-light">
            <tr>
                <th>Nivel de formación</th>
                <th>Institución</th>
                <th>Carrera</th>
                <th>Grado</th>
                <th>Fecha inicio</th>
                <th>Fecha culminación</th>
                <th>Fecha obtención</th>
                <th class="text-center">Documento</th>
                <th class="text-end">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registros as $r): ?>
                <?php
                // Obtención y codificación de la ruta del documento
                $rutaArchivo = $r['pfo_ruta'] ?? $r['exd_ruta'] ?? $r['path_documento'] ?? null;
                $rutaBase64 = !empty($rutaArchivo) ? urlencode(base64_encode($rutaArchivo)) : '';
                ?>
                <tr>
                    <td><?= esc($r['nfo_nombre']  ?? '-') ?></td>
                    <td><?= esc($r['pfo_institucion'] ?? '-') ?></td>
                    <td><?= esc($r['pfo_carrera'] ?? '-') ?></td>
                    <td><?= esc($r['pfo_grado'] ?? '-') ?></td>
                    <td><?= !empty($r['pfo_fecha_inicio']) ? date('d/m/Y', strtotime($r['pfo_fecha_inicio'])) : '-' ?></td>
                    <td><?= !empty($r['pfo_fecha_culminacion']) ? date('d/m/Y', strtotime($r['pfo_fecha_culminacion'])) : '-' ?>
                    </td>
                    <td><?= !empty($r['pfo_fecha_obtencion']) ? date('d/m/Y', strtotime($r['pfo_fecha_obtencion'])) : '-' ?>
                    </td>

                    <!-- Columna Documento Sustentatorio -->
                    <td class="text-center">
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

                    <!-- Columna Acciones -->
                    <td class="text-end">
                        <?php if ($editable): ?>
                            <button type="button"
                                class="btn btn-outline-danger btn-sm btn-eliminar d-inline-flex align-items-center gap-1"
                                data-id="<?= esc($r['pfo_ide']) ?>">
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
                        Aún no ha registrado información en este apartado.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- MODAL DE REGISTRO -->
<?php if ($editable): ?>
    <div class="modal fade" id="modal-academica" tabindex="-1" aria-labelledby="modalLabel-academica" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalLabel-academica">Agregar Formación académica</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-academica" class="form-registro" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="pfo_ide">

                        <div class="row g-3">
                            <!-- Nivel de Formación (Select) -->
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nivel de formación <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" name="pfo_nfo_ide" required>
                                    <option value="">-- Seleccione Nivel --</option>
                                    <?php
                                    // Acepta $nivelesFormacion o $niveles provistos desde el controlador
                                    $listaNiveles = $nivelesFormacion ?? $niveles ?? [];
                                    ?>
                                    <?php if (!empty($listaNiveles) && is_array($listaNiveles)): ?>
                                        <?php foreach ($listaNiveles as $n): ?>
                                            <option value="<?= esc($n['nfo_ide']) ?>">
                                                <?= esc( $n['nfo_nombre'] ?? '') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <!-- Institución -->
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Institución <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="pfo_institucion"
                                    placeholder="Ej. Universidad Nacional..." required>
                            </div>

                            <!-- Carrera -->
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Carrera <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="pfo_carrera"
                                    placeholder="Ej. Administración de Empresas" required>
                            </div>

                            <!-- Grado -->
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Grado</label>
                                <input class="form-control" type="text" name="pfo_grado"
                                    placeholder="Ej. Egresado, Bachiller, Titulado">
                            </div>

                            <!-- Fecha Inicio -->
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Fecha inicio</label>
                                <input class="form-control" type="date" name="pfo_fecha_inicio">
                            </div>

                            <!-- Fecha Culminación -->
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Fecha culminación</label>
                                <input class="form-control" type="date" name="pfo_fecha_culminacion">
                            </div>

                            <!-- Fecha Obtención -->
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Fecha obtención</label>
                                <input class="form-control" type="date" name="pfo_fecha_obtencion">
                            </div>

                            <!-- Documento Sustentatorio -->
                            <div class="col-md-12">
                                <label class="form-label small fw-semibold">Documento Sustentatorio</label>
                                <input class="form-control" name="documento" type="file" accept=".pdf">
                                <div class="form-text small">Max. 10 MB (.pdf)</div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success d-flex align-items-center gap-1"
                            id="btnGuardar-academica">
                            <iconify-icon icon="solar:diskette-bold"></iconify-icon>
                            Guardar Información
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- SCRIPT DE INTERACCIÓN AJAX -->
<script>
    (function () {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        // Guardar / Registrar vía AJAX
        $('#form-academica').on('submit', function (e) {
            e.preventDefault();
            const form = this;
            const $btn = $('#btnGuardar-academica');
            const originalBtnHtml = $btn.html();

            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Guardando...');

            $.ajax({
                url: '<?= base_url('seleccion/postulacion/inscripcion/registro/' . $convocatoriaId . '/academica') ?>',
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

                        const modalEl = document.getElementById('modal-academica');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();
                        form.reset();

                        if (typeof recargarTabPostulacion === 'function') {
                            recargarTabPostulacion('academica');
                        } else {
                            location.reload();
                        }
                    } else {
                        Swal.fire('Atención', r.message || 'Ocurrió un inconveniente al guardar.', 'warning');
                    }
                })
                .fail(x => {
                    const msg = x.responseJSON?.message || 'Ocurrió un error al procesar el envío.';
                    Swal.fire('Error', msg, 'error');
                })
                .always(() => {
                    $btn.prop('disabled', false).html(originalBtnHtml);
                });
        });

        // Eliminar registro
        $('.btn-eliminar').on('click', function () {
            const id = $(this).data('id');

            Swal.fire({
                title: '¿Eliminar registro?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('seleccion/postulacion/inscripcion/registro/' . $convocatoriaId . '/academica') ?>/' + id,
                        type: 'DELETE'
                    })
                        .done(r => {
                            if (r.status) {
                                Toast.fire({
                                    icon: 'success',
                                    title: r.message || 'Registro eliminado correctamente'
                                });
                                if (typeof recargarTabPostulacion === 'function') {
                                    recargarTabPostulacion('academica');
                                } else {
                                    location.reload();
                                }
                            } else {
                                Swal.fire('Atención', r.message || 'No se pudo eliminar el registro', 'warning');
                            }
                        })
                        .fail(x => {
                            const msg = x.responseJSON?.message || 'Error al intentar eliminar el registro';
                            Swal.fire('Error', msg, 'error');
                        });
                }
            });
        });
    })();
</script>
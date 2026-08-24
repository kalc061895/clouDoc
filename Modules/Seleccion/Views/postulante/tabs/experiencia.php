<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-1 fw-bold">Experiencia laboral</h5>
        <small class="text-muted">Puede registrar sus antecedentes de experiencia laboral general y específica. Formatos
            admitidos: PDF, JPG, PNG (máx. 10 MB).</small>
    </div>
    <?php if ($editable): ?>
        <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal"
            data-bs-target="#modal-experiencia">
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
                <th>Institución / Empresa</th>
                <th>Cargo</th>
                <th>Área</th>
                <th>Modalidad</th>
                <th>Fecha inicio</th>
                <th>Fecha término</th>
                <th>Tiempo acum.</th>
                <th class="text-center">Documento</th>
                <th class="text-end">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registros as $r): ?>
                <?php
                // Obtención de la ruta del documento y codificación a Base64
                $rutaArchivo = $r['pex_ruta'] ?? $r['exd_ruta'] ?? $r['path_documento'] ?? null;
                $rutaBase64 = !empty($rutaArchivo) ? urlencode(base64_encode($rutaArchivo)) : '';

                // Cálculo del tiempo de experiencia acumulado
                $tiempoAcumulado = '-';
                if (!empty($r['pex_fecha_inicio']) && !empty($r['pex_fecha_termino'])) {
                    try {
                        $fInicio = new DateTime($r['pex_fecha_inicio']);
                        $fTermino = new DateTime($r['pex_fecha_termino']);
                        $diferencia = $fInicio->diff($fTermino);

                        $partes = [];
                        if ($diferencia->y > 0)
                            $partes[] = $diferencia->y . ($diferencia->y == 1 ? ' año' : ' años');
                        if ($diferencia->m > 0)
                            $partes[] = $diferencia->m . ($diferencia->m == 1 ? ' mes' : ' meses');
                        if ($diferencia->d > 0)
                            $partes[] = $diferencia->d . ($diferencia->d == 1 ? ' día' : ' días');

                        $tiempoAcumulado = !empty($partes) ? implode(', ', $partes) : '0 días';
                    } catch (Exception $e) {
                        $tiempoAcumulado = '-';
                    }
                }
                ?>
                <tr>
                    <td>
                        <strong class="d-block text-dark"><?= esc($r['pex_institucion'] ?? '-') ?></strong>
                        <?php if (!empty($r['pex_descripcion'])): ?>
                            <small class="text-muted d-block text-truncate" style="max-width: 200px;"
                                title="<?= esc($r['pex_descripcion']) ?>">
                                <?= esc($r['pex_descripcion']) ?>
                            </small>
                        <?php endif; ?>
                    </td>
                    <td><?= esc($r['pex_cargo'] ?? '-') ?></td>
                    <td><?= esc($r['pex_area'] ?? '-') ?></td>
                    <td>
                        <span class="badge bg-light text-dark border">
                            <?= esc($r['modalidad_nombre'] ?? $r['mvi_denominacion'] ?? $r['mvi_nombre'] ?? $r['pex_mvi_ide'] ?? '-') ?>
                        </span>
                    </td>
                    <td><?= !empty($r['pex_fecha_inicio']) ? date('d/m/Y', strtotime($r['pex_fecha_inicio'])) : '-' ?></td>
                    <td><?= !empty($r['pex_fecha_termino']) ? date('d/m/Y', strtotime($r['pex_fecha_termino'])) : '-' ?>
                    </td>
                    <td><small class="fw-semibold text-primary"><?= esc($tiempoAcumulado) ?></small></td>

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
                                data-id="<?= esc($r['pex_ide']) ?>">
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
    <div class="modal fade" id="modal-experiencia" tabindex="-1" aria-labelledby="modalLabel-experiencia"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalLabel-experiencia">Agregar Experiencia laboral</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-experiencia" class="form-registro" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="pex_ide">

                        <div class="row g-3">
                            <!-- Institución / Empresa -->
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Institución / Empresa <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="pex_institucion"
                                    placeholder="Ej. Ministerio de Educación..." required>
                            </div>

                            <!-- Cargo -->
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Cargo <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="pex_cargo"
                                    placeholder="Ej. Analista de Sistemas..." required>
                            </div>

                            <!-- Área -->
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Área</label>
                                <input class="form-control" type="text" name="pex_area"
                                    placeholder="Ej. Oficina de Tecnologías de la Información">
                            </div>

                            <!-- Modalidad de Vinculación (Select) -->
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Modalidad de Vinculación</label>
                                <select class="form-select" name="pex_mvi_ide">
                                    <option value="">-- Seleccione Modalidad --</option>
                                    <?php
                                    // Acepta $modalidades o $modalidadesVinculacion desde el controlador
                                    $listaModalidades = $modalidades ?? $modalidadesVinculacion ?? [];
                                    ?>
                                    <?php if (!empty($listaModalidades) && is_array($listaModalidades)): ?>
                                        <?php foreach ($listaModalidades as $m): ?>
                                            <option value="<?= esc($m['mvi_ide']) ?>">
                                                <?= esc($m['mvi_denominacion'] ?? $m['mvi_nombre'] ?? '') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <!-- Fecha Inicio -->
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Fecha inicio <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="date" name="pex_fecha_inicio" required>
                            </div>

                            <!-- Fecha Término -->
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Fecha término <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="date" name="pex_fecha_termino" required>
                            </div>

                            <!-- Descripción de Funciones -->
                            <div class="col-md-12">
                                <label class="form-label small fw-semibold">Descripción de funciones y/o logros</label>
                                <textarea class="form-control" name="pex_descripcion" rows="3"
                                    placeholder="Resumen breve de las actividades o funciones desempeñadas..."></textarea>
                            </div>

                            <!-- Documento Sustentatorio -->
                            <div class="col-md-12">
                                <label class="form-label small fw-semibold">Documento Sustentatorio</label>
                                <input class="form-control" name="documento" type="file" accept=".pdf">
                                <div class="form-text small">Max. 10 MB (.pdf) - Certificados de trabajo,
                                    constancias, etc.</div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success d-flex align-items-center gap-1"
                            id="btnGuardar-experiencia">
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
        $('#form-experiencia').on('submit', function (e) {
            e.preventDefault();
            const form = this;
            const $btn = $('#btnGuardar-experiencia');
            const originalBtnHtml = $btn.html();

            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Guardando...');

            $.ajax({
                url: '<?= base_url('seleccion/postulacion/inscripcion/registro/' . $convocatoriaId . '/experiencia') ?>',
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

                        const modalEl = document.getElementById('modal-experiencia');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();
                        form.reset();

                        if (typeof recargarTabPostulacion === 'function') {
                            recargarTabPostulacion('experiencia');
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
                        url: '<?= base_url('seleccion/postulacion/inscripcion/registro/' . $convocatoriaId . '/experiencia') ?>/' + id,
                        type: 'DELETE'
                    })
                        .done(r => {
                            if (r.status) {
                                Toast.fire({
                                    icon: 'success',
                                    title: r.message || 'Registro eliminado correctamente'
                                });
                                if (typeof recargarTabPostulacion === 'function') {
                                    recargarTabPostulacion('experiencia');
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
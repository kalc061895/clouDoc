<div class="card bg-white p-4">
    <!-- CABECERA -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:document-text-bold" class="me-2 text-primary" style="font-size: 1.5rem;">
                </iconify-icon>
                Anexos
            </h4>
            <p class="text-muted mb-0 small">
                Define los anexos requeridos para la convocatoria, su carácter,
                condición y documento asociado.
            </p>
        </div>
        <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center"
            onclick="abrirModalAnexo()">
            <iconify-icon icon="lucide:plus" class="me-1" style="font-size: 1.1rem;">
            </iconify-icon>
            Agregar Anexo
        </button>
    </div>
    <!-- RESUMEN -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="border rounded p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="me-3 text-primary">
                        <iconify-icon icon="solar:documents-bold" style="font-size: 1.6rem;">
                        </iconify-icon>
                    </div>
                    <div>
                        <div class="text-muted small">
                            Anexos registrados
                        </div>
                        <div class="fs-5 fw-bold text-dark" id="totalAnexos">
                            0
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="border rounded p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="me-3 text-danger">
                        <iconify-icon icon="solar:document-add-bold" style="font-size: 1.6rem;">
                        </iconify-icon>
                    </div>
                    <div>
                        <div class="text-muted small">
                            Anexos obligatorios
                        </div>
                        <div class="fs-5 fw-bold text-danger" id="totalAnexosObligatorios">
                            0
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="border rounded p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="me-3 text-success">
                        <iconify-icon icon="solar:document-text-bold" style="font-size: 1.6rem;">
                        </iconify-icon>
                    </div>
                    <div>
                        <div class="text-muted small">
                            Anexos opcionales
                        </div>
                        <div class="fs-5 fw-bold text-success" id="totalAnexosOpcionales">
                            0
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- TABLA -->
    <div class="table-responsive">
        <table id="tablaAnexos" class="table table-hover align-middle w-100">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="width: 120px;">
                        Código
                    </th>
                    <th>
                        Anexo
                    </th>
                    <th class="text-center" style="width: 130px;">
                        Carácter
                    </th>
                    <th class="text-center" style="width: 180px;">
                        Condición
                    </th>
                    <th class="text-center" style="width: 130px;">
                        Documento
                    </th>
                    <th class="text-end" style="width: 110px;">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody id="tbodyAnexos">
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <div class="mb-2">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                            </div>
                        </div>
                        <div class="small">
                            Cargando anexos...
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!-- ========================================================= -->
<!-- MODAL ANEXO -->
<!-- ========================================================= -->
<div class="modal fade" id="modalAnexo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalAnexoTitle">
                    Nuevo Anexo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar">
                </button>
            </div>
            <form id="formAnexo" onsubmit="guardarAnexo(event)">
                <div class="modal-body py-3">
                    <input type="hidden" id="ane_ide" name="ane_ide">
                    <input type="hidden" id="ane_con_ide" name="ane_con_ide" value="<?= $convocatoriaId ?>">

                    <!-- CÓDIGO + NOMBRE -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold" for="ane_codigo">
                                Código
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="ane_codigo" name="ane_codigo"
                                placeholder="Ej. ANX-01" maxlength="50" required>
                        </div>

                        <div class="col-md-9">
                            <label class="form-label small fw-bold" for="ane_nombre">
                                Nombre del anexo
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="ane_nombre" name="ane_nombre"
                                placeholder="Ej. Declaración Jurada de Datos" maxlength="255" required>
                        </div>
                    </div>

                    <!-- DESCRIPCIÓN -->
                    <div class="row g-3 mb-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold" for="ane_descripcion">
                                Descripción
                            </label>
                            <textarea class="form-control" id="ane_descripcion" name="ane_descripcion" rows="2"
                                placeholder="Describe brevemente el propósito o contenido del anexo."></textarea>
                        </div>
                    </div>

                    <!-- OBLIGATORIO -->
                    <div class="row g-3 mb-3">
                        <div class="col-12">
                            <div class="border rounded p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="small fw-bold text-dark">
                                            Carácter de presentación
                                        </div>
                                        <div class="small text-muted">
                                            Determina si el anexo debe ser presentado
                                            obligatoriamente por el postulante.
                                        </div>
                                    </div>
                                    <div class="form-check form-switch mb-0">
                                        <input type="hidden" name="ane_obligatorio" value="0">
                                        <input class="form-check-input" type="checkbox" id="ane_obligatorio"
                                            name="ane_obligatorio" value="1" checked>
                                        <label class="form-check-label fw-semibold" for="ane_obligatorio"
                                            id="labelAnexoObligatorio">
                                            Obligatorio
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CONDICIÓN -->
                    <div class="row g-3 mb-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold" for="ane_condicion">
                                Condición de presentación
                            </label>
                            <input type="text" class="form-control" id="ane_condicion" name="ane_condicion"
                                maxlength="255" placeholder="Ej. Solo para postulantes con hijos menores de edad">
                            <div class="form-text small">
                                Puedes indicar una condición para determinar cuándo
                                corresponde presentar este anexo.
                            </div>
                        </div>
                    </div>

                    <!-- DOCUMENTO ASOCIADO -->
                    <div class="row g-3 mb-3">
                        <div class="col-8">
                            <label class="form-label small fw-bold" for="ane_archivo_ide">
                                Documento asociado
                            </label>
                            <select class="form-select" id="ane_archivo_ide" name="ane_archivo_ide">
                                <option value="">
                                    Seleccione un documento
                                </option>
                            </select>
                            <div class="form-text small">
                                Opcional. Permite asociar el anexo con un documento
                                o plantilla previamente registrada.
                            </div>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-bold" for="ane_archivo_ide">
                                Estado
                            </label>
                            <select class="form-select" id="ane_archivo_ide" name="ane_estado">
                                <option value="ACTIVO">
                                    Activo
                                </option>
                                <option value="INACTIVO">
                                    Inactivo
                                </option>
                            </select>

                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm px-3" id="btnGuardarAnexo">
                        <i class="bi bi-save me-1"></i>
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    (function () {
        const convocatoriaId = <?= json_encode($convocatoriaId) ?>;

        /* =========================================================
           TOAST
        ========================================================= */
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        /* =========================================================
           SWITCH OBLIGATORIO
        ========================================================= */
        $('#ane_obligatorio').on('change', function () {
            $('#labelAnexoObligatorio').text(
                this.checked ? 'Obligatorio' : 'Opcional'
            );
        });

        /* =========================================================
           ABRIR MODAL - NUEVO
        ========================================================= */
        window.abrirModalAnexo = function () {
            $('#formAnexo')[0].reset();
            $('#ane_ide').val('');
            $('#ane_con_ide').val(convocatoriaId);
            $('#ane_obligatorio')
                .prop('checked', true)
                .trigger('change');
            $('#modalAnexoTitle').text('Nuevo Anexo');
            cargarDocumentos();
            if (window.modalAnexoBs) {
                window.modalAnexoBs.show();
            }
        };

        /* =========================================================
           EDITAR
        ========================================================= */
        window.editarAnexo = function (anexo) {
            $('#formAnexo')[0].reset();
            $('#ane_ide').val(anexo.ane_ide);
            $('#ane_con_ide').val(anexo.ane_con_ide);
            $('#ane_codigo').val(anexo.ane_codigo || '');
            $('#ane_nombre').val(anexo.ane_nombre || '');
            $('#ane_descripcion').val(anexo.ane_descripcion || '');
            $('#ane_condicion').val(anexo.ane_condicion || '');
            const esObligatorio =
                parseInt(anexo.ane_obligatorio) === 1;
            $('#ane_obligatorio')
                .prop('checked', esObligatorio)
                .trigger('change');

            cargarDocumentos(anexo.ane_archivo_ide);
            $('#modalAnexoTitle').text('Editar Anexo');

            if (window.modalAnexoBs) {
                window.modalAnexoBs.show();
            }
        };

        /* =========================================================
           GUARDAR
        ========================================================= */
        window.guardarAnexo = function (e) {
            e.preventDefault();
            const $btn = $('#btnGuardarAnexo');
            $btn
                .prop('disabled', true)
                .html(`
                <span class="spinner-border spinner-border-sm me-1"
                    role="status"></span>
                Guardando...
            `);

            const formData =
                new FormData($('#formAnexo')[0]);

            $.ajax({
                url: '<?= base_url('seleccion/admin/anexos/guardar') ?>',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (r) {
                    if (r.ok || r.status) {
                        if (window.modalAnexoBs) {
                            window.modalAnexoBs.hide();
                        }
                        listarAnexos();

                        Toast.fire({
                            icon: 'success',
                            title: r.message ||
                                'Anexo guardado correctamente'
                        });
                    } else {
                        Swal.fire(
                            'Atención',
                            r.message ||
                            'No se pudo guardar el anexo.',
                            'warning'
                        );
                    }
                },
                error: function (xhr) {
                    const res =
                        xhr.responseJSON || {};
                    Swal.fire(
                        'Error',
                        res.message ||
                        'Ocurrió un error en el servidor.',
                        'error'
                    );
                },
                complete: function () {
                    $btn
                        .prop('disabled', false)
                        .html(
                            '<i class="bi bi-save me-1"></i> Guardar'
                        );
                }
            });
        };
        /* =========================================================
           ELIMINAR
        ========================================================= */
        window.eliminarAnexo = function (id) {
            Swal.fire({
                title: '¿Eliminar anexo?',
                text: 'El anexo dejará de estar disponible para esta convocatoria.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(function (result) {
                if (!result.isConfirmed) {
                    return;
                }
                $.post(
                    '<?= base_url('seleccion/admin/anexos/eliminar/') ?>' + id
                )
                    .done(function (r) {
                        if (r.ok || r.status) {
                            listarAnexos();
                            Toast.fire({
                                icon: 'success',
                                title: r.message ||
                                    'Anexo eliminado correctamente'
                            });
                        } else {
                            Swal.fire(
                                'Error',
                                r.message ||
                                'No se pudo eliminar el anexo.',
                                'error'
                            );
                        }
                    })
                    .fail(function (xhr) {
                        const res =
                            xhr.responseJSON || {};
                        Swal.fire(
                            'Error',
                            res.message ||
                            'No se pudo procesar la eliminación.',
                            'error'
                        );
                    });
            });
        };
        /* =========================================================
           ESCAPAR HTML
        ========================================================= */
        function escapeHtml(text) {
            return $('<div>')
                .text(text || '')
                .html();
        }
        /* =========================================================
           BADGE OBLIGATORIO
        ========================================================= */
        function getBadgeObligatorio(valor) {
            if (parseInt(valor) === 1) {
                return `
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                    OBLIGATORIO
                </span>
            `;
            }
            return `
            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                OPCIONAL
            </span>
        `;
        }
        /* =========================================================
           CARGAR DOCUMENTOS
           
           Se usa para ane_archivo_ide
        ========================================================= */
        function cargarDocumentos(selectedId = '') {
            const $select = $('#ane_archivo_ide');
            $select.html(`
            <option value="">
                Cargando documentos...
            </option>
        `);
            $.get(
                '<?= base_url('seleccion/admin/documentos-convocatoria/listar/') ?>'
                + convocatoriaId,
                function (r) {
                    const data = r.data || r;
                    let options = `
                    <option value="">
                        Sin documento asociado
                    </option>
                `;
                    if (Array.isArray(data)) {
                        data.forEach(function (doc) {
                            const selected =
                                String(doc.cod_ide) ===
                                    String(selectedId)
                                    ? 'selected'
                                    : '';
                            options += `
                            <option value="${doc.cod_ide}" ${selected}>
                                ${escapeHtml(doc.cod_nombre)}
                            </option>
                        `;
                        });
                    }
                    $select.html(options);
                }
            ).fail(function () {
                $select.html(`
                <option value="">
                    No se pudieron cargar los documentos
                </option>
            `);
            });
        }
        /* =========================================================
           LISTAR ANEXOS
        ========================================================= */
        function listarAnexos() {
            $.get(
                '<?= base_url('seleccion/admin/anexos/listar/') ?>'
                + convocatoriaId,
                function (r) {
                    const data = r.data || r;
                    let rows = '';
                    let obligatorios = 0;
                    let opcionales = 0;
                    if (
                        Array.isArray(data) &&
                        data.length > 0
                    ) {
                        data.forEach(function (item, index) {
                            const esObligatorio =
                                parseInt(item.ane_obligatorio) === 1;
                            if (esObligatorio) {
                                obligatorios++;
                            } else {
                                opcionales++;
                            }
                            let documento = `
                            <span class="text-muted small">
                                Sin documento
                            </span>
                        `;
                            if (item.ane_archivo_ide) {
                                const rutaBase64 = item.cod_ruta ?
                                    encodeURIComponent(btoa(item.cod_ruta)) :
                                    '';

                                documento = item.cod_ruta ?
                                    `
                                <a
                                    href="<?= base_url('seleccion/ver/documento') ?>?path=${rutaBase64}"
                                    target="_blank"
                                    class="btn btn-sm btn-outline-primary py-0 px-2"
                                    title="Ver ">
                                    <iconify-icon icon="lucide:file-text" class="me-1"></iconify-icon>
                                    Ver
                                </a>
                            ` :
                                    '<span class="text-muted small">Sin archivo</span>';
                            }
                            rows += `
                            <tr>
                            <td class="text-muted fw-semibold">
                                    ${index + 1}
                                </td>
                                <td>
                                <span class="badge bg-light text-dark border">
                                        ${escapeHtml(item.ane_codigo)}
                                    </span>
                                    </td>
                                <td>
                                <div class="fw-bold text-dark">
                                        ${escapeHtml(item.ane_nombre)}
                                    </div>
                                    ${item.ane_descripcion
                                    ? `
                                                <small class="text-muted">
                                                    ${escapeHtml(item.ane_descripcion)}
                                                </small>
                                            `
                                    : ''
                                }
                                    </td>
                                <td class="text-center">
                                ${getBadgeObligatorio(
                                    item.ane_obligatorio
                                )}
                                </td>
                                <td class="text-center">
                                ${item.ane_condicion
                                    ? `
                                                <small class="text-muted">
                                                    ${escapeHtml(
                                        item.ane_condicion
                                    )}
                                                </small>
                                            `
                                    : `
                                                <span class="text-muted small">
                                                    Sin condición
                                                </span>
                                            `
                                }
                                            </td>
                                <td class="text-center">
                                ${documento}
                                    </td>
                                <td class="text-end">
                                <button
                                        type="button"
                                        class="btn btn-outline-warning btn-sm rounded-circle p-1 me-1 d-inline-flex align-items-center justify-content-center"
                                        onclick='editarAnexo(${JSON.stringify(item)})'
                                        title="Editar"
                                        style="width:32px;height:32px;">
                                        <iconify-icon
                                            icon="lucide:edit"
                                            style="font-size:1rem;">
                                        </iconify-icon>
                                        </button>
                                    <button
                                        type="button"
                                        class="btn btn-outline-danger btn-sm rounded-circle p-1 d-inline-flex align-items-center justify-content-center"
                                        onclick="eliminarAnexo(${item.ane_ide})"
                                        title="Eliminar"
                                        style="width:32px;height:32px;">
                                        <iconify-icon
                                            icon="lucide:trash-2"
                                            style="font-size:1rem;">
                                        </iconify-icon>
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                        $('#totalAnexos').text(data.length);
                        $('#totalAnexosObligatorios')
                            .text(obligatorios);
                        $('#totalAnexosOpcionales')
                            .text(opcionales);
                    } else {
                        rows = `
                        <tr>
                        <td colspan="7"
                                class="text-center py-5 text-muted">
                                <div class="mb-2">
                                <iconify-icon
                                        icon="solar:document-text-broken"
                                        style="font-size:2rem;">
                                    </iconify-icon>
                                    </div>
                                <div class="small">
                                    No se han registrado anexos
                                    para esta convocatoria.
                                </div>
                                </td>
                            </tr>
                        `;
                        $('#totalAnexos').text(0);
                        $('#totalAnexosObligatorios').text(0);
                        $('#totalAnexosOpcionales').text(0);
                    }
                    $('#tbodyAnexos').html(rows);
                }
            ).fail(function () {
                $('#tbodyAnexos').html(`
                <tr>
                <td colspan="7"
                        class="text-center py-5 text-danger">
                        <iconify-icon
                            icon="solar:danger-triangle-bold"
                            class="me-1">
                        </iconify-icon>
                        Error al obtener los anexos.
                        </td>
                    </tr>
                `);
            });
        }
        /* =========================================================
           INICIALIZAR MODAL
        ========================================================= */
        const modalEl =
            document.getElementById('modalAnexo');
        if (modalEl) {
            window.modalAnexoBs =
                bootstrap.Modal.getInstance(modalEl) ||
                new bootstrap.Modal(modalEl);
        }
        /* =========================================================
           CARGA INICIAL
        ========================================================= */
        listarAnexos();
    })();
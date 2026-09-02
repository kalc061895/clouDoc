<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?>
Matriz de Competencias Profesionales
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:tuning-bold-duotone" class="me-2 text-primary" style="font-size: 1.5rem;"></iconify-icon>
                Matriz de Profesión - Segunda Especialidad
            </h4>
            <p class="text-muted mb-0 small">Asigne y restrinja qué Segundas Especialidades pertenecen a cada Profesión general para evitar errores en las fichas del personal.</p>
        </div>
        <div>
            <button class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center" onclick="abrirModalVincular()">
                <iconify-icon icon="lucide:link" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
                Vincular Competencia
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table id="tablaRelaciones" class="table table-hover align-middle w-100">
            <thead>
                <tr>
                    <th style="width: 80px;">Pivote ID</th>
                    <th>Profesión Core / Base</th>
                    <th>
                        <iconify-icon icon="lucide:arrow-right-left" class="text-muted me-1"></iconify-icon>
                        Segunda Especialidad Autorizada
                    </th>
                    <th style="width: 150px;">Auditoría</th>
                    <th class="text-end" style="width: 100px;">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- MODAL: VINCULAR RELACIÓN -->
<div class="modal fade" id="modalRelacion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Vincular Nueva Especialidad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formRelacion">
                <div class="modal-body py-3">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">1. Seleccione la Profesión Base</label>
                            <select class="form-select select2-setup" id="pes_pro_ide" required>
                                <option value="" selected disabled>Elija una profesión...</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">2. Seleccione Segunda Especialidad a Permitir</label>
                            <select class="form-select select2-setup" id="pes_se_ide" required>
                                <option value="" selected disabled>Elija una especialidad...</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm px-3" id="btnGuardar">Establecer Vínculo</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script>
    const urlBaseApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/profesion-especialidades';
    const urlProfesionApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/profesiones'; // Ajustar según endpoint real de profesiones
    const urlEspecialidadApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/segundas-especialidades';

    let tabla;
    let modalRelacion = new bootstrap.Modal(document.getElementById('modalRelacion'));

    $(document).ready(function() {
        // Inicializar tabla estructurada
        tabla = $('#tablaRelaciones').DataTable({
            "ajax": {
                "url": urlBaseApi,
                "type": "GET",
                "dataSrc": "data"
            },
            "columns": [{
                    "data": "pes_ide",
                    "className": "text-muted font-monospace small"
                },
                {
                    "data": "pro_nombre",
                    "className": "text-dark fw-semibold text-uppercase",
                    "render": function(data, type, row) {
                        return `<div>${data} <span class="badge bg-light text-secondary border font-monospace ms-1" style="font-size:0.7rem;">${row.pro_abreviatura || 'BASE'}</span></div>`;
                    }
                },
                {
                    "data": "se_nombre",
                    "className": "text-primary fw-bold text-uppercase",
                    "render": function(data, type, row) {
                        return `<div><iconify-icon icon="solar:diploma-bold" class="me-1 text-warning"></iconify-icon>${data} ${row.se_abreviatura ? `<small class="text-muted font-monospace">[${row.se_abreviatura}]</small>` : ''}</div>`;
                    }
                },
                {
                    "data": "created_at",
                    "className": "small text-muted font-monospace",
                    "render": function(data) {
                        return data ? data.split(' ')[0] : '-';
                    }
                },
                {
                    "data": null,
                    "orderable": false,
                    "className": "text-end",
                    "render": function(data, type, row) {
                        return `
                            <button class="btn btn-outline-danger btn-sm rounded-circle p-1 d-inline-flex align-items-center justify-content-center" 
                                    onclick="eliminarVinculo(${row.pes_ide})" title="Romper Relación" style="width: 32px; height: 32px;">
                                <iconify-icon icon="lucide:link-2-off" style="font-size: 1.1rem;"></iconify-icon>
                            </button>
                        `;
                    }
                }
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            }
        });

        $('#formRelacion').on('submit', function(e) {
            e.preventDefault();
            guardarRelacion();
        });
    });

    function abrirModalVincular() {
        $('#formRelacion')[0].reset();

        // Carga síncrona/paralela de los diccionarios para los selects
        Promise.all([
            $.get(urlProfesionApi),
            $.get(urlEspecialidadApi, {
                estado: 1
            })
        ]).then(([resProfesiones, resEspecialidades]) => {

            const selectPro = $('#pes_pro_ide');
            selectPro.find('option:not(:first)').remove();
            resProfesiones.data.forEach(p => {
                selectPro.append(`<option value="${p.pro_ide}">${p.pro_nombre} [${p.pro_abreviatura || ''}]</option>`);
            });

            const selectEsp = $('#pes_se_ide');
            selectEsp.find('option:not(:first)').remove();
            resEspecialidades.data.forEach(e => {
                selectEsp.append(`<option value="${e.se_ide}">${e.se_nombre}</option>`);
            });

            modalRelacion.show();
        }).catch(() => {
            toastr.error('No se pudieron recuperar los catálogos base para la vinculación.');
        });
    }

    function guardarRelacion() {
        $('#btnGuardar').prop('disabled', true).text('Vinculando...');

        const datos = {
            pes_pro_ide: $('#pes_pro_ide').val(),
            pes_se_ide: $('#pes_se_ide').val()
        };

        $.ajax({
            url: urlBaseApi,
            type: 'POST',
            data: datos,
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    modalRelacion.hide();
                    tabla.ajax.reload(null, false);
                }
            },
            error: function(xhr) {
                let errorMsg = 'Error al cruzar datos.';
                if (xhr.responseJSON && xhr.responseJSON.messages) {
                    errorMsg = Object.values(xhr.responseJSON.messages).join('<br>');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Imposible Procesar',
                    html: errorMsg
                });
            },
            complete: function() {
                $('#btnGuardar').prop('disabled', false).text('Establecer Vínculo');
            }
        });
    }

    function eliminarVinculo(id) {
        Swal.fire({
            title: '¿Revocar este permiso?',
            text: "Al romper este nodo de la matriz, ya no se podrá asignar esta especialidad a futuros registros que tengan dicha profesión base.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonText: 'Mantener',
            confirmButtonText: 'Sí, revocar vínculo'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `${urlBaseApi}/${id}`,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.status === 'success') {
                            toastr.success(response.message);
                            tabla.ajax.reload(null, false);
                        }
                    }
                });
            }
        });
    }
</script>
<?= $this->endSection() ?>
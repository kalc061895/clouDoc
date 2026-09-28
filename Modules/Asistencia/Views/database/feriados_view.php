<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?>
Feriados y Días No Laborables
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:calendar-bold" class="me-2 text-primary" style="font-size: 1.5rem;"></iconify-icon>
                Feriados y Días No Laborables
            </h4>
            <p class="text-muted mb-0 small">Configuración del calendario oficial anual para el cálculo correcto de la asistencia y recargos</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <!-- Selector de año rápido -->
            <select class="form-select form-select-sm rounded-pill px-3" id="filtroAnio" style="width: 120px;" onchange="filtrarPorAnio()">
                <?php
                $anioActual = date('Y');
                for ($i = $anioActual - 2; $i <= $anioActual + 2; $i++): ?>
                    <option value="<?= $i ?>" <?= $i == $anioActual ? 'selected' : '' ?>><?= $i ?></option>
                <?php endfor; ?>
                <option value="">Todos</option>
            </select>

            <button class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center" onclick="abrirModalCrear()">
                <iconify-icon icon="lucide:plus" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
                Nuevo Feriado
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table id="tablaFeriados" class="table table-hover align-middle w-100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Nombre del Feriado</th>
                    <th>Tipo</th>
                    <th>Anual / Repetitivo</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- MODAL: CREAR / EDITAR -->
<div class="modal fade" id="modalFeriado" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitulo">Nuevo Feriado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formFeriado">
                <div class="modal-body py-3">
                    <input type="hidden" id="df_ide">

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Nombre del Feriado / Festividad</label>
                            <input type="text" class="form-control" id="df_nombre" required maxlength="100" placeholder="Ej. Año Nuevo, Combate de Angamos">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Fecha</label>
                            <input type="date" class="form-control" id="df_fecha" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Tipo de Feriado</label>
                            <select class="form-select" id="df_tipo" required>
                                <option value="NACIONAL">Nacional</option>
                                <option value="REGIONAL">Regional</option>
                                <option value="LOCAL">Local / Institucional</option>
                                <option value="NO LABORABLE">Día No Laborable</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="df_repetitivo" value="1">
                                <label class="form-check-label small fw-bold" for="df_repetitivo">
                                    Feriado Repetitivo Anualmente
                                </label>
                                <div class="form-text text-muted" style="font-size: 0.75rem;">
                                    Si se activa, el sistema considerará este día como feriado todos los años (basándose en el día y mes).
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm px-3" id="btnGuardar">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script>
    const urlBaseApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/feriados';

    let tabla;
    let modalFeriado = new bootstrap.Modal(document.getElementById('modalFeriado'));

    $(document).ready(function() {
        const anioInicial = $('#filtroAnio').val();

        tabla = $('#tablaFeriados').DataTable({
            "ajax": {
                "url": urlBaseApi,
                "type": "GET",
                "data": function(d) {
                    d.anio = $('#filtroAnio').val();
                },
                "dataSrc": "data"
            },
            "columns": [{
                    "data": "df_ide"
                },
                {
                    "data": "df_fecha",
                    "className": "fw-bold text-dark font-monospace",
                    "render": function(data) {
                        if (!data) return '-';
                        // Formatear la fecha para visualización en español (DD/MM/AAAA)
                        const partes = data.split('-');
                        return `${partes[2]}/${partes[1]}/${partes[0]}`;
                    }
                },
                {
                    "data": "df_nombre",
                    "className": "fw-bold text-dark"
                },
                {
                    "data": "df_tipo",
                    "render": function(data) {
                        const badges = {
                            'NACIONAL': 'bg-danger-subtle text-danger border-danger-subtle',
                            'REGIONAL': 'bg-warning-subtle text-warning border-warning-subtle',
                            'LOCAL': 'bg-info-subtle text-info border-info-subtle',
                            'NO LABORABLE': 'bg-dark-subtle text-dark border-dark-subtle'
                        };
                        return `<span class="badge ${badges[data] || 'bg-light'} border text-uppercase" style="font-size: 0.75rem;">${data}</span>`;
                    }
                },
                {
                    "data": "df_repetitivo",
                    "className": "text-center",
                    "render": function(data) {
                        return data == 1 ?
                            '<span class="badge bg-success-subtle text-success border border-success-subtle"><iconify-icon icon="lucide:refresh-cw" class="me-1"></iconify-icon>Sí</span>' :
                            '<span class="badge bg-light text-secondary border">No</span>';
                    }
                },
                {
                    "data": null,
                    "orderable": false,
                    "className": "text-end",
                    "render": function(data, type, row) {
                        return `
                            <button class="btn btn-outline-warning btn-sm me-1 rounded-circle p-1 d-inline-flex align-items-center justify-content-center" 
                                    onclick='abrirModalEditar(${JSON.stringify(row)})' title="Editar" style="width: 32px; height: 32px;">
                                <iconify-icon icon="lucide:edit" style="font-size: 1.1rem;"></iconify-icon>
                            </button>
                            <button class="btn btn-outline-danger btn-sm rounded-circle p-1 d-inline-flex align-items-center justify-content-center" 
                                    onclick="eliminarFeriado(${row.df_ide})" title="Eliminar" style="width: 32px; height: 32px;">
                                <iconify-icon icon="lucide:trash-2" style="font-size: 1.1rem;"></iconify-icon>
                            </button>
                        `;
                    }
                }
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            "order": [
                [1, "asc"]
            ] // Ordenar por fecha por defecto
        });

        $('#formFeriado').on('submit', function(e) {
            e.preventDefault();
            guardarFeriado();
        });
    });

    function filtrarPorAnio() {
        tabla.ajax.reload(null, false);
    }

    function abrirModalCrear() {
        $('#formFeriado')[0].reset();
        $('#df_ide').val('');
        $('#df_repetitivo').prop('checked', false);
        $('#modalTitulo').text('Nuevo Feriado');
        modalFeriado.show();
    }

    function abrirModalEditar(data) {
        $('#formFeriado')[0].reset();
        $('#df_ide').val(data.df_ide);
        $('#df_nombre').val(data.df_nombre);
        $('#df_fecha').val(data.df_fecha);
        $('#df_tipo').val(data.df_tipo);
        $('#df_repetitivo').prop('checked', data.df_repetitivo == 1);

        $('#modalTitulo').text('Editar Feriado');
        modalFeriado.show();
    }

    function guardarFeriado() {
        const id = $('#df_ide').val();
        const esEditar = id !== "";
        const url = esEditar ? `${urlBaseApi}/${id}` : urlBaseApi;
        const metodo = esEditar ? 'PUT' : 'POST';

        $('#btnGuardar').prop('disabled', true).text('Guardando...');

        const datos = {
            df_nombre: $('#df_nombre').val(),
            df_fecha: $('#df_fecha').val(),
            df_tipo: $('#df_tipo').val(),
            df_repetitivo: $('#df_repetitivo').is(':checked') ? 1 : 0
        };

        $.ajax({
            url: url,
            type: metodo,
            data: datos,
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    modalFeriado.hide();
                    tabla.ajax.reload(null, false);
                }
            },
            error: function(xhr) {
                let errorMsg = 'Error en el servidor.';
                if (xhr.responseJSON && xhr.responseJSON.messages) {
                    errorMsg = Object.values(xhr.responseJSON.messages).join('<br>');
                }
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    html: errorMsg
                });
            },
            complete: function() {
                $('#btnGuardar').prop('disabled', false).text('Guardar');
            }
        });
    }

    function eliminarFeriado(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "El feriado se enviará a la papelera de reciclaje.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sí, eliminar'
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
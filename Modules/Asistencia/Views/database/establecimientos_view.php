<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?>
Establecimientos de Salud
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:hospital-bold" class="me-2 text-primary" style="font-size: 1.5rem;"></iconify-icon>
                Establecimientos de Salud (IPRESS)
            </h4>
            <p class="text-muted mb-0 small">Gestión de centros de salud, categorías y coordenadas de geocercas</p>
        </div>
        <button class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center" onclick="abrirModalCrear()">
            <iconify-icon icon="lucide:plus" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
            Nuevo Establecimiento
        </button>
    </div>

    <div class="table-responsive">
        <table id="tablaEstablecimientos" class="table table-hover align-middle w-100">
            <thead >
                <tr>
                    <th>ID</th>
                    <th>Ubicación Jerárquica</th>
                    <th>Código</th>
                    <th>IPRESS</th>
                    <th>Denominación</th>
                    <th>Categoría</th>
                    <th>Geocerca</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalEstablecimiento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitulo">Nuevo Establecimiento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEstablecimiento">
                <div class="modal-body py-3">
                    <input type="hidden" id="est_ide">

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">1. DIRESA</label>
                            <select class="form-select" id="filtro_diresa" required>
                                <option value="">Seleccione DIRESA...</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">2. Red de Salud</label>
                            <select class="form-select" id="filtro_red" required disabled>
                                <option value="">Seleccione DIRESA primero...</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">3. Microred Asignada</label>
                            <select class="form-select" id="est_mic_ide" required disabled>
                                <option value="">Seleccione Red primero...</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Denominación del Establecimiento</label>
                            <input type="text" class="form-control" id="est_denominacion" required placeholder="Ej. C.S. CONO SUR">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Categoría</label>
                            <input type="text" class="form-control" id="est_categoria" placeholder="Ej. I-3, I-4">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Código Interno</label>
                            <input type="text" class="form-control" id="est_codigo" placeholder="Ej. EST-05">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Código IPRESS (RENAES)</label>
                            <input type="text" class="form-control" id="est_ipress" placeholder="Ej. 00004512">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Tipo</label>
                            <input type="text" class="form-control" id="est_tipo" placeholder="Ej. Centro de Salud">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Ubigeo</label>
                            <input type="text" class="form-control" id="est_ubigeo" placeholder="Ej. 210201">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Latitud</label>
                            <input type="text" class="form-control" id="est_latitud" placeholder="-15.4951">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Longitud</label>
                            <input type="text" class="form-control" id="est_longitud" placeholder="-70.1312">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Radio de Marcación (Metros)</label>
                            <input type="number" class="form-control" id="est_radio" value="50" min="10">
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
    const urlBaseApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/establecimientos';
    const urlDiresasApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/diresas';
    const urlRedesApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/redes';
    const urlMicroredApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/microredes';

    let tabla;
    let modalElement = document.getElementById('modalEstablishment');
    let modalEstablecimiento = new bootstrap.Modal(document.getElementById('modalEstablecimiento'));

    $(document).ready(function() {
        tabla = $('#tablaEstablecimientos').DataTable({
            "ajax": {
                "url": urlBaseApi,
                "type": "GET",
                "dataSrc": "data"
            },
            "columns": [{
                    "data": "est_ide"
                },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return `<small class="text-muted d-block">${row.diresa_nombre || 'Sin DIRESA'}</small>
                                <span class="fw-semibold text-dark">${row.red_nombre || 'Sin Red'}</span>
                                <small class="text-primary d-block">➔ ${row.microred_nombre || 'Sin Microred'}</small>`;
                    }
                },
                {
                    "data": "est_codigo"
                },
                {
                    "data": "est_ipress"
                },
                {
                    "data": "est_denominacion",
                    "className": "fw-bold"
                },
                {
                    "data": "est_categoria"
                },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return row.est_latitud ?
                            `<span class="badge bg-info-subtle text-info border border-info-subtle"><iconify-icon icon="lucide:map-pin" class="me-1"></iconify-icon>${row.est_radio}m</span>` :
                            '<span class="text-muted small">No configurada</span>';
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
                                    onclick="eliminarEstablecimiento(${row.est_ide})" title="Eliminar" style="width: 32px; height: 32px;">
                                <iconify-icon icon="lucide:trash-2" style="font-size: 1.1rem;"></iconify-icon>
                            </button>
                        `;
                    }
                }
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            }
        });

        cargarDiresas();

        // Escucha 1: Cambio de DIRESA -> Carga Redes
        $('#filtro_diresa').on('change', function() {
            cargarRedesPorDiresa($(this).val());
        });

        // Escucha 2: Cambio de Red -> Carga Microredes
        $('#filtro_red').on('change', function() {
            cargarMicroredesPorRed($(this).val());
        });

        $('#formEstablecimiento').on('submit', function(e) {
            e.preventDefault();
            guardarEstablecimiento();
        });
    });

    // Cargar Catálogo Base (DIRESAs)
    function cargarDiresas() {
        $.ajax({
            url: urlDiresasApi,
            type: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    let select = $('#filtro_diresa');
                    select.empty().append('<option value="">Seleccione DIRESA...</option>');
                    response.data.forEach(item => {
                        select.append(`<option value="${item.dir_ide}">${item.dir_nombre}</option>`);
                    });
                }
            }
        });
    }

    // Nivel 2: Cargar Redes Filtradas (Retorna Promesa)
    function cargarRedesPorDiresa(diresaIde, redSeleccionada = null) {
        let selectRed = $('#filtro_red');
        let selectMic = $('#est_mic_ide');

        selectMic.empty().append('<option value="">Seleccione Red primero...</option>').prop('disabled', true);

        if (!diresaIde) {
            selectRed.empty().append('<option value="">Seleccione DIRESA primero...</option>').prop('disabled', true);
            return Promise.resolve();
        }

        selectRed.empty().append('<option value="">Cargando redes...</option>').prop('disabled', true);

        return $.ajax({
            url: urlRedesApi,
            type: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    const filtradas = response.data.filter(r => r.red_dir_ide == diresaIde);
                    selectRed.empty().append('<option value="">Seleccione Red...</option>');

                    filtradas.forEach(item => {
                        selectRed.append(`<option value="${item.red_ide}">${item.red_nombre}</option>`);
                    });
                    selectRed.prop('disabled', false);

                    if (redSeleccionada) {
                        selectRed.val(redSeleccionada);
                    }
                }
            }
        });
    }

    // Nivel 3: Cargar Microredes Filtradas (Retorna Promesa)
    function cargarMicroredesPorRed(redIde, microredSeleccionada = null) {
        let selectMic = $('#est_mic_ide');

        if (!redIde) {
            selectMic.empty().append('<option value="">Seleccione Red primero...</option>').prop('disabled', true);
            return Promise.resolve();
        }

        selectMic.empty().append('<option value="">Cargando microredes...</option>').prop('disabled', true);

        return $.ajax({
            url: urlMicroredApi,
            type: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    const filtradas = response.data.filter(m => m.mic_red_ide == redIde);
                    selectMic.empty().append('<option value="">Seleccione Microred...</option>');

                    filtradas.forEach(item => {
                        selectMic.append(`<option value="${item.mic_ide}">${item.mic_nombre}</option>`);
                    });
                    selectMic.prop('disabled', false);

                    if (microredSeleccionada) {
                        selectMic.val(microredSeleccionada);
                    }
                }
            }
        });
    }

    function abrirModalCrear() {
        $('#formEstablecimiento')[0].reset();
        $('#est_ide').val('');
        $('#filtro_red').empty().append('<option value="">Seleccione DIRESA primero...</option>').prop('disabled', true);
        $('#est_mic_ide').empty().append('<option value="">Seleccione Red primero...</option>').prop('disabled', true);
        $('#modalTitulo').text('Nuevo Establecimiento');
        modalEstablecimiento.show();
    }

    // RESOLUCIÓN DE ASINCRONÍA SECUENCIAL AL EDITAR
    function abrirModalEditar(data) {
        $('#formEstablecimiento')[0].reset();
        $('#est_ide').val(data.est_ide);
        $('#est_denominacion').val(data.est_denominacion);
        $('#est_categoria').val(data.est_categoria);
        $('#est_codigo').val(data.est_codigo);
        $('#est_ipress').val(data.est_ipress);
        $('#est_tipo').val(data.est_tipo);
        $('#est_ubigeo').val(data.est_ubigeo);
        $('#est_latitud').val(data.est_latitud);
        $('#est_longitud').val(data.est_longitud);
        $('#est_radio').val(data.est_radio);

        $('#modalTitulo').text('Editar Establecimiento');

        if (data.diresa_ide) {
            // 1. Forzar visualización de DIRESA
            $('#filtro_diresa').val(data.diresa_ide);

            // 2. Cargar Redes -> Esperar éxito -> Cargar Microredes
            cargarRedesPorDiresa(data.diresa_ide, data.red_ide).then(function() {
                return cargarMicroredesPorRed(data.red_ide, data.est_mic_ide);
            }).then(function() {
                modalEstablecimiento.show();
            });
        } else {
            modalEstablecimiento.show();
        }
    }

    function guardarEstablecimiento() {
        const id = $('#est_ide').val();
        const esEditar = id !== "";
        const url = esEditar ? `${urlBaseApi}/${id}` : urlBaseApi;
        const metodo = esEditar ? 'PUT' : 'POST';

        $('#btnGuardar').prop('disabled', true).text('Guardando...');

        const datos = {
            est_mic_ide: $('#est_mic_ide').val(),
            est_codigo: $('#est_codigo').val(),
            est_ipress: $('#est_ipress').val(),
            est_tipo: $('#est_tipo').val(),
            est_denominacion: $('#est_denominacion').val(),
            est_categoria: $('#est_categoria').val(),
            est_ubigeo: $('#est_ubigeo').val(),
            est_latitud: $('#est_latitud').val(),
            est_longitud: $('#est_longitud').val(),
            est_radio: $('#est_radio').val()
        };

        $.ajax({
            url: url,
            type: metodo,
            data: datos,
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    modalEstablecimiento.hide();
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

    function eliminarEstablecimiento(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "El establecimiento se enviará a la papelera.",
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
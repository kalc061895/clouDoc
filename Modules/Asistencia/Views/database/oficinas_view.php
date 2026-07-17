<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?>
Oficinas y Áreas
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:structure-bold" class="me-2 text-primary" style="font-size: 1.5rem;"></iconify-icon>
                Oficinas y Áreas
            </h4>
            <p class="text-muted mb-0 small">Mantenimiento de la estructura orgánica y jerárquica de cada Establecimiento</p>
        </div>
        <button class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center" onclick="abrirModalCrear()">
            <iconify-icon icon="lucide:plus" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
            Nueva Oficina
        </button>
    </div>

    <div class="table-responsive">
        <table id="tablaOficinas" class="table table-striped table-hover align-middle w-100">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Establecimiento</th>
                    <th>Oficina / Área</th>
                    <th>Tipo</th>
                    <th>Dependencia Interna (Padre)</th>
                    <th>Encargado</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalOficina" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitulo">Nueva Oficina</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formOficina">
                <div class="modal-body py-3">
                    <input type="hidden" id="ofi_ide">

                    <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">1. Ubicación y Tipo</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">DIRESA</label>
                            <select class="form-select" id="filtro_diresa" required>
                                <option value="">Seleccione...</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Red</label>
                            <select class="form-select" id="filtro_red" required disabled>
                                <option value="">Seleccione DIRESA...</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Microred</label>
                            <select class="form-select" id="filtro_microred" required disabled>
                                <option value="">Seleccione Red...</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Establecimiento</label>
                            <select class="form-select" id="ofi_est_ide" required disabled>
                                <option value="">Seleccione Microred...</option>
                            </select>
                        </div>
                    </div>

                    <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">2. Datos de la Oficina</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Tipo de Oficina</label>
                            <select class="form-select" id="ofi_tofi_ide" required>
                                <option value="">Cargando tipos...</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-bold">Nombre de la Oficina</label>
                            <input type="text" class="form-control" id="ofi_nombre" required maxlength="255" placeholder="Ej. Unidad de Recursos Humanos">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Abreviatura / Siglas</label>
                            <input type="text" class="form-control" id="ofi_abreviatura" placeholder="Ej. URH">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Código de Referencia</label>
                            <input type="text" class="form-control" id="ofi_codigo_referencia" placeholder="Ej. COD-URH">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Oficina Superior (Dependencia)</label>
                            <select class="form-select" id="ofi_padre_ide" disabled>
                                <option value="">Seleccione establecimiento primero...</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Rango / Nivel Jerárquico</label>
                            <input type="number" class="form-control" id="ofi_rango" placeholder="Ej. 1" min="1">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Descripción de Funciones</label>
                            <textarea class="form-control" id="ofi_descripcion" rows="2" placeholder="Breve descripción..."></textarea>
                        </div>
                    </div>

                    <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">3. Información del Encargado / Jefe</h6>
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Título</label>
                            <input type="text" class="form-control" id="ofi_titulo_encargado" placeholder="Ej. Dr., Mg., Lic.">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-bold">Nombres Completos</label>
                            <input type="text" class="form-control" id="ofi_nombres_encargado" placeholder="Ej. Juan Pérez">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Cargo</label>
                            <input type="text" class="form-control" id="ofi_cargo_encargado" placeholder="Ej. Jefe de Unidad">
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="ofi_estado" checked>
                                <label class="form-check-label small fw-bold" for="ofi_estado">Oficina Activa / Operativa</label>
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
    const urlBaseApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/oficinas';
    const urlDiresasApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/diresas';
    const urlRedesApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/redes';
    const urlMicroredApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/microredes';
    const urlEstableApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/establecimientos';
    const urlTofiLookup = '<?= base_url() ?>' + '/asistencia/gestordb/api/tipos-oficina-lookup';

    let tabla;
    let modalOficina = new bootstrap.Modal(document.getElementById('modalOficina'));

    $(document).ready(function() {
        tabla = $('#tablaOficinas').DataTable({
            "ajax": {
                "url": urlBaseApi,
                "type": "GET",
                "dataSrc": "data"
            },
            "columns": [{
                    "data": "ofi_ide"
                },
                {
                    "data": "establecimiento_nombre",
                    "className": "fw-bold text-dark"
                },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        const abrev = row.ofi_abreviatura ? ` <span class="text-muted">(${row.ofi_abreviatura})</span>` : '';
                        return `<span class="fw-semibold">${row.ofi_nombre}</span>${abrev}`;
                    }
                },
                {
                    "data": "tipo_oficina_nombre",
                    "render": function(data) {
                        return data ? `<span class="badge bg-light text-secondary border">${data}</span>` : '<span class="text-muted">-</span>';
                    }
                },
                {
                    "data": "padre_nombre",
                    "render": function(data) {
                        return data ? `<span class="text-primary"><iconify-icon icon="lucide:corner-down-right" class="me-1"></iconify-icon>${data}</span>` : '<span class="text-muted small">Nivel Raíz</span>';
                    }
                },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        if (!row.ofi_nombres_encargado) return '<span class="text-muted small">Sin designar</span>';
                        const titulo = row.ofi_titulo_encargado ? `${row.ofi_titulo_encargado} ` : '';
                        const cargo = row.ofi_cargo_encargado ? `<small class="d-block text-muted">${row.ofi_cargo_encargado}</small>` : '';
                        return `<div>${titulo}${row.ofi_nombres_encargado}${cargo}</div>`;
                    }
                },
                {
                    "data": "ofi_estado",
                    "render": function(data) {
                        return data == 1 ?
                            '<span class="badge bg-success-subtle text-success border border-success-subtle">Activa</span>' :
                            '<span class="badge bg-danger-subtle text-danger border border-danger-subtle">Inactiva</span>';
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
                                    onclick="eliminarOficina(${row.ofi_ide})" title="Eliminar" style="width: 32px; height: 32px;">
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

        // Inicializar Catálogos Base
        cargarDiresas();
        cargarTiposOficina();

        // Escuchas del flujo de cascada
        $('#filtro_diresa').on('change', function() {
            cargarRedesPorDiresa($(this).val());
        });
        $('#filtro_red').on('change', function() {
            cargarMicroredesPorRed($(this).val());
        });
        $('#filtro_microred').on('change', function() {
            cargarEstablecimientosPorMicrored($(this).val());
        });
        $('#ofi_est_ide').on('change', function() {
            cargarOficinasPadre($(this).val());
        });

        $('#formOficina').on('submit', function(e) {
            e.preventDefault();
            guardarOficina();
        });
    });

    // Carga de DIRESAs
    function cargarDiresas() {
        $.ajax({
            url: urlDiresasApi,
            type: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    let select = $('#filtro_diresa');
                    select.empty().append('<option value="">Seleccione...</option>');
                    response.data.forEach(item => {
                        select.append(`<option value="${item.dir_ide}">${item.dir_nombre}</option>`);
                    });
                }
            }
        });
    }

    // Cargar Tipos de Oficina
    function cargarTiposOficina() {
        $.ajax({
            url: urlTofiLookup,
            type: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    let select = $('#ofi_tofi_ide');
                    select.empty().append('<option value="">Seleccione...</option>');
                    response.data.forEach(item => {
                        select.append(`<option value="${item.id}">${item.nombre}</option>`);
                    });
                }
            }
        });
    }

    // Nivel 2: Redes (Retorna Promesa)
    function cargarRedesPorDiresa(diresaIde, redSeleccionada = null) {
        let selectRed = $('#filtro_red');
        $('#filtro_microred').empty().append('<option value="">Seleccione Red...</option>').prop('disabled', true);
        $('#ofi_est_ide').empty().append('<option value="">Seleccione Microred...</option>').prop('disabled', true);
        $('#ofi_padre_ide').empty().append('<option value="">Seleccione establecimiento...</option>').prop('disabled', true);

        if (!diresaIde) {
            selectRed.empty().append('<option value="">Seleccione DIRESA...</option>').prop('disabled', true);
            return Promise.resolve();
        }

        selectRed.empty().append('<option value="">Cargando...</option>').prop('disabled', true);

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

                    if (redSeleccionada) selectRed.val(redSeleccionada);
                }
            }
        });
    }

    // Nivel 3: Microredes (Retorna Promesa)
    function cargarMicroredesPorRed(redIde, microredSeleccionada = null) {
        let selectMic = $('#filtro_microred');
        $('#ofi_est_ide').empty().append('<option value="">Seleccione Microred...</option>').prop('disabled', true);
        $('#ofi_padre_ide').empty().append('<option value="">Seleccione establecimiento...</option>').prop('disabled', true);

        if (!redIde) {
            selectMic.empty().append('<option value="">Seleccione Red...</option>').prop('disabled', true);
            return Promise.resolve();
        }

        selectMic.empty().append('<option value="">Cargando...</option>').prop('disabled', true);

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

                    if (microredSeleccionada) selectMic.val(microredSeleccionada);
                }
            }
        });
    }

    // Nivel 4: Establecimientos (Retorna Promesa)
    function cargarEstablecimientosPorMicrored(microredIde, estSeleccionado = null) {
        let selectEst = $('#ofi_est_ide');
        $('#ofi_padre_ide').empty().append('<option value="">Seleccione establecimiento...</option>').prop('disabled', true);

        if (!microredIde) {
            selectEst.empty().append('<option value="">Seleccione Microred...</option>').prop('disabled', true);
            return Promise.resolve();
        }

        selectEst.empty().append('<option value="">Cargando...</option>').prop('disabled', true);

        return $.ajax({
            url: urlEstableApi,
            type: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    const filtradas = response.data.filter(e => e.est_mic_ide == microredIde);
                    selectEst.empty().append('<option value="">Seleccione Establecimiento...</option>');
                    filtradas.forEach(item => {
                        selectEst.append(`<option value="${item.est_ide}">${item.est_denominacion}</option>`);
                    });
                    selectEst.prop('disabled', false);

                    if (estSeleccionado) selectEst.val(estSeleccionado);
                }
            }
        });
    }

    // Nivel 5: Carga de Oficinas Padre (Mismo establecimiento)
    function cargarOficinasPadre(estIde, padreSeleccionado = null) {
        let selectPadre = $('#ofi_padre_ide');

        if (!estIde) {
            selectPadre.empty().append('<option value="">Seleccione establecimiento...</option>').prop('disabled', true);
            return Promise.resolve();
        }

        selectPadre.empty().append('<option value="">Cargando...</option>').prop('disabled', true);

        return $.ajax({
            url: `<?= base_url() ?>/asistencia/gestordb/api/oficinas-establecimiento/${estIde}`,
            type: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    selectPadre.empty().append('<option value="">Ninguna (Nivel Raíz)</option>');

                    // Excluimos la oficina actual para evitar referencias circulares si estamos editando
                    const actualIde = $('#ofi_ide').val();
                    const filtradas = response.data.filter(o => o.ofi_ide != actualIde);

                    filtradas.forEach(item => {
                        selectPadre.append(`<option value="${item.ofi_ide}">${item.ofi_nombre}</option>`);
                    });
                    selectPadre.prop('disabled', false);

                    if (padreSeleccionado) selectPadre.val(padreSeleccionado);
                }
            }
        });
    }

    function abrirModalCrear() {
        $('#formOficina')[0].reset();
        $('#ofi_ide').val('');
        $('#filtro_red').empty().append('<option value="">Seleccione DIRESA...</option>').prop('disabled', true);
        $('#filtro_microred').empty().append('<option value="">Seleccione Red...</option>').prop('disabled', true);
        $('#ofi_est_ide').empty().append('<option value="">Seleccione Microred...</option>').prop('disabled', true);
        $('#ofi_padre_ide').empty().append('<option value="">Seleccione establecimiento...</option>').prop('disabled', true);
        $('#ofi_estado').prop('checked', true);
        $('#modalTitulo').text('Nueva Oficina');
        modalOficina.show();
    }

    // RESOLUCIÓN DE ASINCRONÍA SECUENCIAL AL EDITAR
    function abrirModalEditar(data) {
        $('#formOficina')[0].reset();
        $('#ofi_ide').val(data.ofi_ide);
        $('#ofi_tofi_ide').val(data.ofi_tofi_ide);
        $('#ofi_nombre').val(data.ofi_nombre);
        $('#ofi_abreviatura').val(data.ofi_abreviatura);
        $('#ofi_codigo_referencia').val(data.ofi_codigo_referencia);
        $('#ofi_titulo_encargado').val(data.ofi_titulo_encargado);
        $('#ofi_nombres_encargado').val(data.ofi_nombres_encargado);
        $('#ofi_cargo_encargado').val(data.ofi_cargo_encargado);
        $('#ofi_descripcion').val(data.ofi_descripcion);
        $('#ofi_rango').val(data.ofi_rango);
        $('#ofi_estado').prop('checked', data.ofi_estado == 1);

        $('#modalTitulo').text('Editar Oficina');

        if (data.diresa_ide) {
            $('#filtro_diresa').val(data.diresa_ide);

            // Secuencia rigurosa de promesas encadenadas .then()
            cargarRedesPorDiresa(data.diresa_ide, data.red_ide)
                .then(() => cargarMicroredesPorRed(data.red_ide, data.microred_ide))
                .then(() => cargarEstablecimientosPorMicrored(data.microred_ide, data.ofi_est_ide))
                .then(() => cargarOficinasPadre(data.ofi_est_ide, data.ofi_padre_ide))
                .then(() => modalOficina.show());
        } else {
            modalOficina.show();
        }
    }

    function guardarOficina() {
        const id = $('#ofi_ide').val();
        const esEditar = id !== "";
        const url = esEditar ? `${urlBaseApi}/${id}` : urlBaseApi;
        const metodo = esEditar ? 'PUT' : 'POST';

        $('#btnGuardar').prop('disabled', true).text('Guardando...');

        const datos = {
            ofi_est_ide: $('#ofi_est_ide').val(),
            ofi_tofi_ide: $('#ofi_tofi_ide').val(),
            ofi_nombre: $('#ofi_nombre').val(),
            ofi_abreviatura: $('#ofi_abreviatura').val(),
            ofi_codigo_referencia: $('#ofi_codigo_referencia').val(),
            ofi_titulo_encargado: $('#ofi_titulo_encargado').val(),
            ofi_nombres_encargado: $('#ofi_nombres_encargado').val(),
            ofi_cargo_encargado: $('#ofi_cargo_encargado').val(),
            ofi_descripcion: $('#ofi_descripcion').val(),
            ofi_rango: $('#ofi_rango').val(),
            ofi_padre_ide: $('#ofi_padre_ide').val(),
            ofi_estado: $('#ofi_estado').is(':checked') ? 1 : 0
        };

        $.ajax({
            url: url,
            type: metodo,
            data: datos,
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    modalOficina.hide();
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

    function eliminarOficina(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "La oficina seleccionada se enviará a la papelera.",
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
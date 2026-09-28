<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?>
Microredes de Salud
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:globus-bold" class="me-2 text-primary" style="font-size: 1.5rem;"></iconify-icon>
                Microredes de Salud
            </h4>
            <p class="text-muted mb-0 small">Mantenimiento de las Microredes de Salud del sistema y su Red de dependencia</p>
        </div>
        <button class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center" onclick="abrirModalCrear()">
            <iconify-icon icon="lucide:plus" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
            Nueva Microred
        </button>
    </div>

    <div class="table-responsive">
        <table id="tablaMicroredes" class="table table-hover align-middle w-100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Red de Salud</th>
                    <th>Código</th>
                    <th>Nombre Microred</th>
                    <th>Director / Jefe</th>
                    <th>Ubicación</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalMicrored" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitulo">Nueva Microred</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formMicrored">
                <div class="modal-body py-3">
                    <input type="hidden" id="mic_ide">

                    <div class="mb-3">
                        <label class="form-label small fw-bold">1. Seleccione DIRESA</label>
                        <select class="form-select" id="mic_diresa_filtro" required>
                            <option value="">Seleccione DIRESA...</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">2. Red de Dependencia</label>
                        <select class="form-select" id="mic_red_ide" required disabled>
                            <option value="">Primero seleccione una DIRESA...</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold">Código</label>
                            <input type="text" class="form-control" id="mic_codigo" placeholder="Ej. MR-02">
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label small fw-bold">Nombre de la Microred</label>
                            <input type="text" class="form-control" id="mic_nombre" required maxlength="150" placeholder="Ej. MICRORED CONO SUR">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Director / Jefe de Microred</label>
                        <input type="text" class="form-control" id="mic_director" placeholder="Nombre del médico o encargado">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Dirección / Ubicación</label>
                        <input type="text" class="form-control" id="mic_ubicacion" placeholder="Ubicación de la sede principal">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Teléfono</label>
                            <input type="text" class="form-control" id="mic_telefono" placeholder="Contacto">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Email</label>
                            <input type="email" class="form-control" id="mic_email" placeholder="correo@microred.gob.pe">
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
    const urlBaseApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/microredes';
    const urlDiresasApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/diresas';
    const urlRedesApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/redes'; // Endpoint general de redes

    let tabla;
    let modalElement = document.getElementById('modalMicrored');
    let modalMicrored = new bootstrap.Modal(modalElement);

    $(document).ready(function() {
        tabla = $('#tablaMicroredes').DataTable({
            "ajax": {
                "url": urlBaseApi,
                "type": "GET",
                "dataSrc": "data"
            },
            "columns": [{
                    "data": "mic_ide"
                },
                {
                    "data": "red_nombre",
                    "render": function(data) {
                        return data ? `<span class="badge bg-light text-dark border border-secondary-subtle">${data}</span>` : '<span class="text-danger">Sin Red</span>';
                    }
                },
                {
                    "data": "mic_codigo"
                },
                {
                    "data": "mic_nombre"
                },
                {
                    "data": "mic_director",
                    "render": function(data) {
                        return data ? data : '<span class="text-muted">No asignado</span>';
                    }
                },
                {
                    "data": "mic_ubicacion"
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
                                    onclick="eliminarMicrored(${row.mic_ide})" title="Eliminar" style="width: 32px; height: 32px;">
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

        cargarRedes();

        $('#formMicrored').on('submit', function(e) {
            e.preventDefault();
            guardarMicrored();
        });
        // Cargar el catálogo de DIRESAs al iniciar la página
        cargarDiresasFiltro();

        // EVENTO CLAVE: Cuando cambie la DIRESA, cargamos sus Redes correspondientes
        $('#mic_diresa_filtro').on('change', function() {
            const diresaIde = $(this).val();
            cargarRedesPorDiresa(diresaIde);
        });


    });

    function cargarRedes() {
        $.ajax({
            url: urlRedesApi,
            type: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    let select = $('#mic_red_ide');
                    select.empty().append('<option value="">Seleccione Red...</option>');
                    response.data.forEach(function(item) {
                        select.append(`<option value="${item.id}">${item.nombre}</option>`);
                    });
                }
            },
            error: function() {
                toastr.error('No se pudieron cargar las Redes de Salud.');
            }
        });
    }

    // 1. Cargar el primer nivel (DIRESAs)
    function cargarDiresasFiltro() {
        $.ajax({
            url: urlDiresasApi,
            type: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    let select = $('#mic_diresa_filtro');
                    select.empty().append('<option value="">Seleccione DIRESA...</option>');
                    response.data.forEach(function(item) {
                        select.append(`<option value="${item.dir_ide}">${item.dir_nombre}</option>`);
                    });
                }
            }
        });
    }

    // MODIFICADO: Ahora retorna una Promesa para controlar la asincronía
    function cargarRedesPorDiresa(diresaIde, redSeleccionada = null) {
        let selectRed = $('#mic_red_ide');

        if (!diresaIde) {
            selectRed.empty().append('<option value="">Primero seleccione una DIRESA...</option>').prop('disabled', true);
            return Promise.resolve(); // Retorna una promesa resuelta inmediatamente
        }

        selectRed.empty().append('<option value="">Cargando redes...</option>').prop('disabled', true);

        // Retornamos el objeto $.ajax que funciona como una Promesa en jQuery
        return $.ajax({
            url: urlRedesApi,
            type: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    // Filtrar las redes que pertenecen a la DIRESA seleccionada
                    const redesFiltradas = response.data.filter(red => red.red_dir_ide == diresaIde);

                    selectRed.empty().append('<option value="">Seleccione Red...</option>');

                    if (redesFiltradas.length === 0) {
                        selectRed.append('<option value="">No hay redes en esta DIRESA</option>');
                    } else {
                        redesFiltradas.forEach(function(item) {
                            selectRed.append(`<option value="${item.red_ide}">${item.red_nombre}</option>`);
                        });
                        selectRed.prop('disabled', false);
                    }

                    // Si se pasó una red para seleccionar, la seleccionamos AQUÍ adentro
                    if (redSeleccionada) {
                        selectRed.val(redSeleccionada);
                    }
                }
            },
            error: function() {
                toastr.error('Error al cargar las Redes.');
                selectRed.empty().append('<option value="">Error al cargar</option>');
            }
        });
    }

    // MODIFICADO: Controla el flujo secuencial al editar
    function abrirModalEditar(data) {
        // 1. Limpiar el formulario y cargar campos básicos
        $('#formMicrored')[0].reset();
        $('#mic_ide').val(data.mic_ide);
        $('#mic_codigo').val(data.mic_codigo);
        $('#mic_nombre').val(data.mic_nombre);
        $('#mic_director').val(data.mic_director);
        $('#mic_ubicacion').val(data.mic_ubicacion);
        $('#mic_telefono').val(data.mic_telefono);
        $('#mic_email').val(data.mic_email);

        $('#modalTitulo').text('Editar Microred');

        // 2. Primero seleccionamos de golpe la DIRESA en el interfaz
        if (data.red_dir_ide) {
            $('#mic_diresa_filtro').val(data.red_dir_ide);

            // 3. Llamamos a cargar las redes pasándole el ID de la red elegida.
            // Al estar controlado dentro del éxito del AJAX, se seleccionará automáticamente.
            cargarRedesPorDiresa(data.red_dir_ide, data.mic_red_ide);
        } else {
            $('#mic_diresa_filtro').val('');
            $('#mic_red_ide').empty().append('<option value="">Primero seleccione una DIRESA...</option>').prop('disabled', true);
        }

        // 4. Mostramos el modal una vez mapeados los selectores en cascada
        modalMicrored.show();
    }

    function abrirModalCrear() {
        $('#formMicrored')[0].reset();
        $('#mic_ide').val('');
        $('#mic_red_ide').empty().append('<option value="">Primero seleccione una DIRESA...</option>').prop('disabled', true);
        $('#modalTitulo').text('Nueva Microred');
        modalMicrored.show();
    }


    function abrirModalEditar(data) {
        $('#mic_ide').val(data.mic_ide);
        $('#mic_red_ide').val(data.mic_red_ide);
        $('#mic_codigo').val(data.mic_codigo);
        $('#mic_nombre').val(data.mic_nombre);
        $('#mic_director').val(data.mic_director);
        $('#mic_ubicacion').val(data.mic_ubicacion);
        $('#mic_telefono').val(data.mic_telefono);
        $('#mic_email').val(data.mic_email);

        $('#modalTitulo').text('Editar Microred');
        modalMicrored.show();
    }

    function guardarMicrored() {
        const id = $('#mic_ide').val();
        const esEditar = id !== "";
        const url = esEditar ? `${urlBaseApi}/${id}` : urlBaseApi;
        const metodo = esEditar ? 'PUT' : 'POST';

        $('#btnGuardar').prop('disabled', true).text('Guardando...');

        const datos = {
            mic_red_ide: $('#mic_red_ide').val(),
            mic_codigo: $('#mic_codigo').val(),
            mic_nombre: $('#mic_nombre').val(),
            mic_director: $('#mic_director').val(),
            mic_ubicacion: $('#mic_ubicacion').val(),
            mic_telefono: $('#mic_telefono').val(),
            mic_email: $('#mic_email').val()
        };

        $.ajax({
            url: url,
            type: metodo,
            data: datos,
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    modalMicrored.hide();
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

    function eliminarMicrored(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta microred se enviará a la papelera.",
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
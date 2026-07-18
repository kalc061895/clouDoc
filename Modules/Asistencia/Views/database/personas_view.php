<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?>
Gestión de Personal
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="me-2 text-primary" style="font-size: 1.5rem;"></iconify-icon>
                Maestro de Personal
            </h4>
            <p class="text-muted mb-0 small">Administración integral del personal. Los cambios guardan rastro de auditoría temporal (Soft Delete habilitado).</p>
        </div>
        <div>
            <button class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center" onclick="abrirModalCrear()">
                <iconify-icon icon="lucide:user-plus" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
                Registrar Persona
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table id="tablaPersonas" class="table table-hover align-middle w-100">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th style="width: 130px;">Documento</th>
                    <th>Apellidos y Nombres</th>
                    <th style="width: 110px;">Teléfono</th>
                    <th>Email</th>
                    <th style="width: 120px;">F. Ingreso</th>
                    <th class="text-end" style="width: 110px;">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- MODAL ESTRUCTURADO CON TABS -->
<div class="modal fade" id="modalPersona" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitulo">Registrar Persona</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Navegación de pestañas internas -->
            <ul class="nav nav-tabs px-3 mt-2" id="personaTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active small fw-bold" id="identidad-tab" data-bs-toggle="tab" data-bs-target="#tabIdentidad" type="button" role="tab">1. Identidad</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link small fw-bold" id="contacto-tab" data-bs-toggle="tab" data-bs-target="#tabContacto" type="button" role="tab">2. Contacto y Residencia</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link small fw-bold" id="laboral-tab" data-bs-toggle="tab" data-bs-target="#tabLaboral" type="button" role="tab">3. Datos de Control</button>
                </li>
            </ul>

            <form id="formPersona">
                <div class="modal-body py-3">
                    <input type="hidden" id="per_ide">

                    <div class="tab-content" id="personaTabsContent">
                        <!-- TAB 1: IDENTIDAD -->
                        <div class="tab-pane fade show active" id="tabIdentidad" role="tabpanel">
                            <div class="row g-3 mt-1">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Tipo Doc.</label>
                                    <select class="form-select" id="per_tipo_documento" required>
                                        <option value="DNI" selected>DNI</option>
                                        <option value="CE">Carnet de Extranjería</option>
                                        <option value="PASAPORTE">Pasaporte</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Nro. Documento</label>
                                    <input type="text" class="form-control font-monospace fw-bold" id="per_numero_documento" required placeholder="Ej. 44556677">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">RUC (Opcional)</label>
                                    <input type="text" class="form-control font-monospace" id="per_ruc" maxlength="15" placeholder="10445566771">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Apellido Paterno</label>
                                    <input type="text" class="form-control" id="per_paterno" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Apellido Materno</label>
                                    <input type="text" class="form-control" id="per_materno" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Nombres</label>
                                    <input type="text" class="form-control" id="per_nombre" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Sexo</label>
                                    <select class="form-select" id="per_sexo">
                                        <option value="M">Masculino</option>
                                        <option value="F">Femenino</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Fecha Nacimiento</label>
                                    <input type="date" class="form-control" id="per_fecha_nacimiento">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Estado Civil</label>
                                    <select class="form-select" id="per_estadocivil">
                                        <option value="SOLTERO/A">Soltero/a</option>
                                        <option value="CASADO/A">Casado/a</option>
                                        <option value="DIVORCIADO/A">Divorciado/a</option>
                                        <option value="VIUDO/A">Viudo/a</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 2: CONTACTO -->
                        <div class="tab-pane fade" id="tabContacto" role="tabpanel">
                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Celular / Teléfono</label>
                                    <input type="text" class="form-control" id="per_telefono" placeholder="987654321">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Email Personal / Institucional</label>
                                    <input type="email" class="form-control" id="per_email" placeholder="usuario@salud.gob.pe">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Lugar de Nacimiento (Ubigeo/Ciudad)</label>
                                    <input type="text" class="form-control" id="per_lugar_nacimiento" placeholder="Ej. Juliaca - Puno">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Dirección de Residencia Actual</label>
                                    <input type="text" class="form-control" id="per_residencia" placeholder="Ej. Av. Miraflores Nro. 450">
                                </div>
                            </div>
                        </div>

                        <!-- TAB 3: LABORAL -->
                        <div class="tab-pane fade" id="tabLaboral" role="tabpanel">
                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Fecha de Ingreso a la Institución</label>
                                    <input type="date" class="form-control" id="per_ingreso">
                                </div>
                                <div class="col-md-6 text-center d-flex flex-column align-items-center justify-content-center border rounded p-3 bg-light">
                                    <iconify-icon icon="solar:shield-check-bold" class="text-success" style="font-size: 2rem;"></iconify-icon>
                                    <p class="mb-0 small fw-bold mt-1 text-dark">Registro sujeto a Trazabilidad</p>
                                    <span class="text-muted" style="font-size: 0.75rem;">Las altas, modificaciones y bajas lógicas registrarán automáticamente fecha y operador.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-2">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4" id="btnGuardar">Guardar Ficha</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script>
    const urlBaseApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/personas';
    let tabla;
    let modalPersona = new bootstrap.Modal(document.getElementById('modalPersona'));

    $(document).ready(function() {
        tabla = $('#tablaPersonas').DataTable({
            "ajax": {
                "url": urlBaseApi,
                "type": "GET",
                "dataSrc": "data"
            },
            "columns": [{
                    "data": "per_ide"
                },
                {
                    "data": null,
                    "className": "font-monospace",
                    "render": function(data, type, row) {
                        return `<small class="text-muted fw-bold">${row.per_tipo_documento}:</small> <span class="fw-bold text-dark">${row.per_numero_documento}</span>`;
                    }
                },
                {
                    "data": null,
                    "className": "fw-bold text-dark text-uppercase",
                    "render": function(data, type, row) {
                        return `${row.per_paterno} ${row.per_materno}, ${row.per_nombre}`;
                    }
                },
                {
                    "data": "per_telefono",
                    "render": function(data) {
                        return data || '-';
                    }
                },
                {
                    "data": "per_email",
                    "className": "small",
                    "render": function(data) {
                        return data || '-';
                    }
                },
                {
                    "data": "per_ingreso",
                    "className": "text-center",
                    "render": function(data) {
                        return data ? `<span class="badge bg-light text-dark border">${data}</span>` : '<span class="text-muted">-</span>';
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
                                    onclick="eliminarPersona(${row.per_ide})" title="Archivar" style="width: 32px; height: 32px;">
                                <iconify-icon icon="lucide:user-x" style="font-size: 1.1rem;"></iconify-icon>
                            </button>
                        `;
                    }
                }
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            }
        });

        $('#formPersona').on('submit', function(e) {
            e.preventDefault();
            guardarPersona();
        });
    });

    function abrirModalCrear() {
        $('#formPersona')[0].reset();
        $('#per_ide').val('');
        // Forzar regreso al primer Tab
        bootstrap.Tab.getInstance(document.getElementById('identidad-tab')).show();
        $('#modalTitulo').text('Registrar Nueva Persona');
        modalPersona.show();
    }

    function abrirModalEditar(data) {
        $('#formPersona')[0].reset();
        bootstrap.Tab.getInstance(document.getElementById('identidad-tab')).show();

        $('#per_ide').val(data.per_ide);
        $('#per_tipo_documento').val(data.per_tipo_documento);
        $('#per_numero_documento').val(data.per_numero_documento);
        $('#per_paterno').val(data.per_paterno);
        $('#per_materno').val(data.per_materno);
        $('#per_nombre').val(data.per_nombre);
        $('#per_sexo').val(data.per_sexo);
        $('#per_lugar_nacimiento').val(data.per_lugar_nacimiento);
        $('#per_fecha_nacimiento').val(data.per_fecha_nacimiento);
        $('#per_residencia').val(data.per_residencia);
        $('#per_ruc').val(data.per_ruc);
        $('#per_telefono').val(data.per_telefono);
        $('#per_email').val(data.per_email);
        $('#per_estadocivil').val(data.per_estadocivil);
        $('#per_ingreso').val(data.per_ingreso);

        $('#modalTitulo').text('Editar Ficha de Personal');
        modalPersona.show();
    }

    function guardarPersona() {
        const id = $('#per_ide').val();
        const esEditar = id !== "";
        const url = esEditar ? `${urlBaseApi}/${id}` : urlBaseApi;
        const metodo = esEditar ? 'PUT' : 'POST';

        $('#btnGuardar').prop('disabled', true).text('Guardando...');

        const datos = {
            per_tipo_documento: $('#per_tipo_documento').val(),
            per_numero_documento: $('#per_numero_documento').val().trim(),
            per_paterno: $('#per_paterno').val(),
            per_materno: $('#per_materno').val(),
            per_nombre: $('#per_nombre').val(),
            per_sexo: $('#per_sexo').val(),
            per_lugar_nacimiento: $('#per_lugar_nacimiento').val(),
            per_fecha_nacimiento: $('#per_fecha_nacimiento').val(),
            per_residencia: $('#per_residencia').val(),
            per_ruc: $('#per_ruc').val().trim(),
            per_telefono: $('#per_telefono').val().trim(),
            per_email: $('#per_email').val().trim(),
            per_estadocivil: $('#per_estadocivil').val(),
            per_ingreso: $('#per_ingreso').val()
        };

        $.ajax({
            url: url,
            type: metodo,
            data: datos,
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    modalPersona.hide();
                    tabla.ajax.reload(null, false);
                }
            },
            error: function(xhr) {
                let errorMsg = 'Error al procesar el formulario.';
                if (xhr.responseJSON && xhr.responseJSON.messages) {
                    errorMsg = Object.values(xhr.responseJSON.messages).join('<br>');
                }
                Swal.fire({
                    icon: 'error',
                    title: '¡Validación fallida!',
                    html: errorMsg
                });
            },
            complete: function() {
                $('#btnGuardar').prop('disabled', false).text('Guardar Ficha');
            }
        });
    }

    function eliminarPersona(id) {
        Swal.fire({
            title: '¿Archivar registro?',
            text: "La persona ya no aparecerá en las listas activas, pero sus marcas e historial histórico se mantendrán íntegros por auditoría.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sí, archivar'
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
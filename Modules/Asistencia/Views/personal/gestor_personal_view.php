<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?> Padrón de Personal <?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="card-title mb-0">Padrón General de Personal</h4>
                <h6 class="card-subtitle text-muted small">Gestión de datos personales, laborales y asistenciales.</h6>
            </div>
            <a href="<?= base_url('asistencia/personal/nuevo') ?>" class="btn btn-primary d-flex align-items-center">
                <iconify-icon icon="solar:user-plus-bold" class="me-2" style="font-size: 1.2rem;"></iconify-icon>
                Registrar Nuevo
            </a>
        </div>

        <div class="table-responsive mb-2" style="height: 680px;">
            <table id="tablaPersonal" class="table table-sm table-hover table-bordered align-middle w-100">
                <thead>
                    <tr>
                        <th style="width: 12%;">N° Documento</th>
                        <th>Apellidos y Nombres</th>
                        <th>Modalidad</th>
                        <th>Establecimiento</th>
                        <th>Oficina</th>
                        <th>Cargo</th>
                        <th style="width: 10%;" class="text-center">Estado</th>
                        <th style="width: 15%;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Unificado de Gestión del Trabajador -->
<div class="modal fade" id="modalGestionPersonal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <div>
                    <h5 class="modal-title fw-bold" id="lblNombrePersonal">Cargando...</h5>
                    <small class="text-muted" id="lblDetallePersonal"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Pestañas de Navegación de Módulos -->
                <ul class="nav nav-pills nav-fill mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active d-flex align-items-center" id="tab-datos" data-bs-toggle="pill"
                            data-bs-target="#pane-datos" type="button">
                            <iconify-icon icon="solar:user-id-bold" class="me-1"></iconify-icon> Datos Generales
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center" id="tab-asistencia" data-bs-toggle="pill"
                            data-bs-target="#pane-asistencia" type="button">
                            <iconify-icon icon="solar:calendar-mark-bold" class="me-1"></iconify-icon> Asistencia
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center" id="tab-turnos" data-bs-toggle="pill"
                            data-bs-target="#pane-turnos" type="button">
                            <iconify-icon icon="solar:clock-circle-bold" class="me-1"></iconify-icon> Turnos
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center" id="tab-incidencias" data-bs-toggle="pill"
                            data-bs-target="#pane-incidencias" type="button">
                            <iconify-icon icon="solar:document-text-bold" class="me-1"></iconify-icon> Incidencias /
                            Papeletas
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center" id="tab-vacaciones" data-bs-toggle="pill"
                            data-bs-target="#pane-vacaciones" type="button">
                            <iconify-icon icon="solar:palmtree-bold" class="me-1"></iconify-icon> Vacaciones
                        </button>
                    </li>
                </ul>

                <!-- Contenido Dinámico según Tab -->
                <div class="tab-content border rounded p-3 bg-white" id="pills-tabContent" style="min-height: 300px;">
                    <div class="tab-pane fade show active" id="pane-datos">Cargando información...</div>
                    <div class="tab-pane fade" id="pane-asistencia">Cargando registros de asistencia...</div>
                    <div class="tab-pane fade" id="pane-turnos">Cargando rol de turnos...</div>
                    <div class="tab-pane fade" id="pane-incidencias">Cargando incidencias y papeletas...</div>
                    <div class="tab-pane fade" id="pane-vacaciones">Cargando historial de vacaciones...</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script>
    const URL_BASE = '<?= base_url('asistencia/personal') ?>';
    let idPersonalSeleccionado = null;

    $(document).ready(function () {
        const tabla = $('#tablaPersonal').DataTable({
            processing: true,
            serverSide: true, // HABILITADO
            ajax: {
                url: `${URL_BASE}/api/fetch`,
                type: 'GET'
            },
            columns: [
                { data: 'documento' },
                { data: 'nombre_completo' },
                { data: 'modalidad' },
                { data: 'establecimiento' },
                { data: 'oficina' },
                { data: 'cargo', defaultContent: '<span class="text-muted small">NO ASIGNADO</span>' },
                {
                    data: 'estado',
                    className: 'text-center',
                    render: function (data) {
                        return data == 1
                            ? '<span class="badge bg-light-success text-success fw-bold px-2 py-1">ACTIVO</span>'
                            : '<span class="badge bg-light-danger text-danger fw-bold px-2 py-1">DE BAJA</span>';
                    }
                },
                {
                    data: 'id',
                    className: 'text-center',
                    orderable: false,
                    render: function (data, type, row) {
                        let btnBaja = '';
                        if (row.estado == 1) {
                            btnBaja = `
                                <li>
                                    <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="confirmarBaja(${data})">
                                        <iconify-icon icon="solar:user-block-rounded-bold" class="me-1"></iconify-icon> Dar de Baja
                                    </a>
                                </li>
                            `;
                        }

                        return `
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-light-primary text-primary px-2" onclick="abrirModalGestion(${data}, 'pane-datos', '${row.nombre_completo}', '${row.documento}')" title="Ver Datos">
                                    <iconify-icon icon="solar:eye-bold" style="font-size: 1.1rem; vertical-align: middle;"></iconify-icon>
                                </button>
                                <button type="button" class="btn btn-sm btn-light-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="visually-hidden">Opciones</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="abrirModalGestion(${data}, 'pane-asistencia', '${row.nombre_completo}', '${row.documento}')">
                                            <iconify-icon icon="solar:calendar-mark-bold" class="me-1 text-primary"></iconify-icon> Asistencia
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="abrirModalGestion(${data}, 'pane-turnos', '${row.nombre_completo}', '${row.documento}')">
                                            <iconify-icon icon="solar:clock-circle-bold" class="me-1 text-danger"></iconify-icon> Turnos
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="abrirModalGestion(${data}, 'pane-incidencias', '${row.nombre_completo}', '${row.documento}')">
                                            <iconify-icon icon="solar:document-text-bold" class="me-1 text-warning"></iconify-icon> Incidencias / Papeletas
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="abrirModalGestion(${data}, 'pane-vacaciones', '${row.nombre_completo}', '${row.documento}')">
                                            <iconify-icon icon="solar:palmtree-bold" class="me-1 text-success"></iconify-icon> Vacaciones
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="${URL_BASE}/editar/${data}">
                                            <iconify-icon icon="solar:pen-bold" class="me-1 text-info"></iconify-icon> Editar Formulario
                                        </a>
                                    </li>
                                    ${btnBaja}
                                </ul>
                            </div>
                        `;
                    }
                }
            ],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            }
        });
    });

    // Función para abrir el modal y activar el Tab deseado cargando los datos por AJAX
    function abrirModalGestion(id, targetPaneId, nombre, dni) {
        idPersonalSeleccionado = id;

        // 1. Setear encabezados
        $('#lblNombrePersonal').text(nombre);
        $('#lblDetallePersonal').text(`DNI: ${dni} | ID Personal: ${id}`);

        // 2. Mostrar Modal
        const modal = new bootstrap.Modal(document.getElementById('modalGestionPersonal'));
        modal.show();

        // 3. Activar Pestaña correspondient
        const tabBtn = document.querySelector(`[data-bs-target="#${targetPaneId}"]`);
        if (tabBtn) {
            const trigger = new bootstrap.Tab(tabBtn);
            trigger.show();
        }

        // 4. Cargar contenido Ajax para la pestaña seleccionada
        cargarContenidoTab(id, targetPaneId);
    }

    // Escuchar cambios manuales de pestañas dentro del Modal
    $('button[data-bs-toggle="pill"]').on('shown.bs.tab', function (e) {
        const targetPaneId = $(e.target).attr('data-bs-target').replace('#', '');
        if (idPersonalSeleccionado) {
            cargarContenidoTab(idPersonalSeleccionado, targetPaneId);
        }
    });

    // Carga centralizada de respuestas JSON
    function cargarContenidoTab(id, paneId) {
        const $pane = $(`#${paneId}`);

        // 1. Loader visual mientras descarga la estructura del Pane
        $pane.html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary spinner-border-sm" role="status"></div>
            <p class="mt-2 text-muted small mb-0">Cargando módulo...</p>
        </div>
    `);

        // Extrae el nombre del módulo: 'pane-incidencias' -> 'pane-incidencias' o 'incidencias'
        const endpoint = paneId.replace('pane-', '');

        // 2. Carga la vista parcial (HTML + JS interno)
        $.ajax({
            url: `${URL_BASE}/${endpoint}/${id}`,
            type: 'GET',
            dataType: 'html', // IMPORTANTE: Recibimos la vista PHP renderizada
            success: function (htmlView) {
                // Se inyecta el HTML y jQuery ejecuta automáticamente el <script> interno del pane
                $pane.html(htmlView);
            },
            error: function (xhr, status, error) {
                console.error(`Error al cargar el módulo ${endpoint}:`, error);
                $pane.html(`
                <div class="alert alert-danger d-flex align-items-center mb-0 p-3" role="alert">
                    <iconify-icon icon="solar:danger-triangle-bold" class="fs-4 me-2"></iconify-icon>
                    <div>
                        <strong>Error de carga:</strong> No se pudo obtener la vista del módulo. Verifique su conexión o intente nuevamente.
                    </div>
                </div>
            `);
            }
        });
    }

    // Confirmación de Baja con SweetAlert2
    function confirmarBaja(id) {
        Swal.fire({
            title: '¿Confirmar Cese o Baja?',
            text: "El empleado pasará al estado inactivo y no figurará en los controles de asistencia vigentes.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, dar de baja',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#d33',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    url: `${URL_BASE}/api/baja/${id}`,
                    type: 'POST',
                    dataType: 'json'
                }).catch(xhr => {
                    let msg = 'Error al procesar la baja.';
                    if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                    Swal.showValidationMessage(msg);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                toastr.warning('El estado del trabajador se actualizó a INACTIVO.');
                $('#tablaPersonal').DataTable().ajax.reload(null, false);
            }
        });
    }
</script>
<?= $this->endSection() ?>
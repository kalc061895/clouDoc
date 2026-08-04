<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?>
Gestión de Grupos de Corte
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:users-group-two-rounded-bold" class="me-2 text-primary"
                    style="font-size: 1.8rem;"></iconify-icon>
                Grupos de Corte de Asistencia
            </h4>
            <p class="text-muted mb-0 small">Configuración de rangos de días de corte por jerarquía (DIRESA, Red,
                Microred, Establecimiento)</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <select class="form-select form-select-sm rounded-pill px-3" id="filtroEstado" style="width: 160px;"
                onchange="filtrarTabla()">
                <option value="" selected>Todos los estados</option>
                <option value="ACTIVO">ACTIVO</option>
                <option value="INACTIVO">INACTIVO</option>
            </select>

            <button class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center"
                onclick="abrirModalCrear()">
                <iconify-icon icon="lucide:plus" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
                Nuevo Grupo
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table id="tablaGrupos" class="table table-hover align-middle w-100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Grupo</th>
                    <th>Régimen</th>
                    <th>Día Inicio</th>
                    <th>Día Fin</th>
                    <th>Desfase Mes</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- MODAL: CREAR / EDITAR GRUPO DE CORTE -->
<div class="modal fade" id="modalGrupo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitulo">Nuevo Grupo de Corte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formGrupo">
                <div class="modal-body py-3">
                    <input type="hidden" id="gco_ide">

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Nombre del Grupo *</label>
                            <input type="text" class="form-control" id="gco_nombre" required maxlength="150"
                                placeholder="Ej. Corte General - Nombrados D.L. 276">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Modalidad de Contrato / Régimen</label>
                            <select class="form-select" id="gco_mco_ide">
                                <option value="">-- Cargar modalidades... --</option>
                            </select>
                        </div>

                        <!-- JERARQUÍA DE SELECCIÓN EN CASCADA -->
                        <div class="col-12">
                            <hr class="my-1 text-muted">
                        </div>
                        <div class="col-12">
                            <span class="badge bg-light text-primary border">
                                Alcance Jerárquico (Filtre de Superior a Inferior)
                            </span>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">1. DIRESA *</label>
                            <select class="form-select" id="diresa_id" required>
                                <option value="">-- Seleccione DIRESA --</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">2. Red de Salud</label>
                            <select class="form-select" id="red_id" disabled>
                                <option value="">-- Seleccione DIRESA primero --</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">3. Microred</label>
                            <select class="form-select" id="microred_id" disabled>
                                <option value="">-- Seleccione Red primero --</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">4. Establecimiento</label>
                            <select class="form-select" id="establecimiento_id" disabled>
                                <option value="">-- Seleccione Microred primero --</option>
                            </select>
                        </div>

                        <!-- REGLAS DE CORTE -->
                        <div class="col-12">
                            <hr class="my-1 text-muted">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Día Inicio Corte *</label>
                            <input type="number" class="form-control font-monospace text-center" id="gco_dia_inicio"
                                required min="1" max="31" placeholder="Ej. 26">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Día Fin Corte *</label>
                            <input type="number" class="form-control font-monospace text-center" id="gco_dia_fin"
                                required min="1" max="31" placeholder="Ej. 25">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Desfase de Mes</label>
                            <select class="form-select" id="gco_mes_desfasado">
                                <option value="0">0 (Mismo mes)</option>
                                <option value="1">1 (Mes anterior - Ej: 26 al 25)</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Estado *</label>
                            <select class="form-select" id="gco_estado" required>
                                <option value="ACTIVO">ACTIVO</option>
                                <option value="INACTIVO">INACTIVO</option>
                            </select>
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
    // URLs de la API
    const urlApiGrupos = '<?= base_url('asistencia/gestordb/api/grupos-corte') ?>';
    const urlBaseApi = '<?= base_url('asistencia/gestordb/api') ?>';
    const urlApiModalidades = '<?= base_url('asistencia/personal/select/modalidades-contrato') ?>';
    const urlApiDiresas = '<?= base_url('asistencia/gestordb/api/diresas') ?>';
    const urlApiRedes = '<?= base_url('asistencia/gestordb/api/redes') ?>';
    const urlApiMicroredes = '<?= base_url('asistencia/gestordb/api/microredes') ?>';
    const urlApiEstablecimientos = '<?= base_url('asistencia/gestordb/api/establecimientos') ?>';

    let tabla;
    let modalGrupo = new bootstrap.Modal(document.getElementById('modalGrupo'));
    let mapaModalidades = {};

    $(document).ready(function() {
        // Inicializar selectores base
        cargarDiresas();
        cargarModalidadesContrato();

        // Inicializar DataTable
        tabla = $('#tablaGrupos').DataTable({
            "ajax": {
                "url": urlApiGrupos,
                "type": "GET",
                "data": function(d) {
                    d.estado = $('#filtroEstado').val();
                },
                "dataSrc": "data"
            },
            "columns": [{
                    "data": "gco_ide"
                },
                {
                    "data": "gco_nombre",
                    "className": "fw-bold text-dark",
                    "render": function(data, type, row) {
                        let nivel = 'DIRESA General';
                        if (row.establecimiento_id) nivel = `Establecimiento ID: ${row.establecimiento_id}`;
                        else if (row.microred_id) nivel = `Microred ID: ${row.microred_id}`;
                        else if (row.red_id) nivel = `Red ID: ${row.red_id}`;

                        return `
                            <div>
                                <span>${data}</span>
                                <div class="text-muted small fw-normal">
                                    <iconify-icon icon="solar:structure-bold" class="me-1"></iconify-icon>${nivel}
                                </div>
                            </div>
                        `;
                    }
                },
                {
                    "data": "gco_mco_ide",
                    "render": function(data) {
                        if (!data) {
                            return '<span class="badge bg-secondary-subtle text-secondary border">Todos los Regímenes</span>';
                        }
                        let textoModalidad = mapaModalidades[data] || `Modalidad (${data})`;
                        return `<span class="badge bg-primary-subtle text-primary border border-primary-subtle">${textoModalidad}</span>`;
                    }
                },
                {
                    "data": "gco_dia_inicio",
                    "className": "text-center font-monospace fw-bold",
                    "render": function(data) {
                        return `Día ${data}`;
                    }
                },
                {
                    "data": "gco_dia_fin",
                    "className": "text-center font-monospace fw-bold",
                    "render": function(data) {
                        return `Día ${data}`;
                    }
                },
                {
                    "data": "gco_mes_desfasado",
                    "className": "text-center",
                    "render": function(data) {
                        return data == 1 ?
                            '<span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Sí (-1 Mes)</span>' :
                            '<span class="badge bg-light text-muted border">No</span>';
                    }
                },
                {
                    "data": "gco_estado",
                    "render": function(data) {
                        return data === 'ACTIVO' ?
                            '<span class="badge bg-success-subtle text-success border border-success-subtle">ACTIVO</span>' :
                            '<span class="badge bg-danger-subtle text-danger border border-danger-subtle">INACTIVO</span>';
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
                                    onclick="eliminarGrupo(${row.gco_ide})" title="Eliminar" style="width: 32px; height: 32px;">
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

        // Event Listeners de la UI
        $('#formGrupo').on('submit', function(e) {
            e.preventDefault();
            guardarGrupo();
        });

        $('#diresa_id').on('change', function() {
            cargarRedesPorDiresa($(this).val());
        });

        $('#red_id').on('change', function() {
            cargarMicroredesPorRed($(this).val());
        });

        $('#microred_id').on('change', function() {
            cargarEstablecimientosPorMicrored($(this).val());
        });
    });

    // Cargar Modalidades
    function cargarModalidadesContrato() {
        return $.get(urlApiModalidades, function(res) {
            let options = '<option value="">-- Todos los Regímenes --</option>';
            const items = res.data || res;

            mapaModalidades = {};
            items.forEach(m => {
                const id = m.mco_ide || m.id || m.value;
                const nombre = m.mco_nombre || m.nombre || m.text || m.mco_descripcion;

                mapaModalidades[id] = nombre;
                options += `<option value="${id}">${nombre}</option>`;
            });

            $('#gco_mco_ide').html(options);

            if (tabla) {
                tabla.rows().invalidate().draw(false);
            }
        }).fail(function() {
            $('#gco_mco_ide').html('<option value="">-- Error al cargar modalidades --</option>');
        });
    }

    // Cargar Selects Jerárquicos
    function cargarDiresas() {
        return $.get(urlApiDiresas, function(res) {
            let options = '<option value="">-- Seleccione DIRESA --</option>';
            const items = res.data || res;
            items.forEach(d => {
                options += `<option value="${d.dir_ide}">${d.dir_nombre}</option>`;
            });
            $('#diresa_id').html(options);
        });
    }

    function cargarRedesPorDiresa(diresaId, redSeleccionada = null) {
        let $red = $('#red_id');
        let $mic = $('#microred_id');
        let $est = $('#establecimiento_id');

        $mic.html('<option value="">-- Seleccione Red primero --</option>').prop('disabled', true);
        $est.html('<option value="">-- Seleccione Microred primero --</option>').prop('disabled', true);

        if (!diresaId) {
            $red.html('<option value="">-- Seleccione DIRESA primero --</option>').prop('disabled', true);
            return Promise.resolve();
        }

        $red.html('<option value="">Cargando redes...</option>').prop('disabled', true);

        return $.get(urlApiRedes, function(res) {
            const items = res.data || res;
            const filtradas = items.filter(r => r.red_dir_ide == diresaId);

            let options = '<option value="">-- Todas las Redes (Aplica a DIRESA) --</option>';
            filtradas.forEach(r => {
                options += `<option value="${r.red_ide}">${r.red_nombre}</option>`;
            });

            $red.html(options).prop('disabled', false);
            if (redSeleccionada) $red.val(redSeleccionada);
        });
    }

    function cargarMicroredesPorRed(redId, microredSeleccionada = null) {
        let $mic = $('#microred_id');
        let $est = $('#establecimiento_id');

        $est.html('<option value="">-- Seleccione Microred primero --</option>').prop('disabled', true);

        if (!redId) {
            $mic.html('<option value="">-- Seleccione Red primero --</option>').prop('disabled', true);
            return Promise.resolve();
        }

        $mic.html('<option value="">Cargando microredes...</option>').prop('disabled', true);

        return $.get(urlApiMicroredes, function(res) {
            const items = res.data || res;
            const filtradas = items.filter(m => m.mic_red_ide == redId);

            let options = '<option value="">-- Todas las Microredes (Aplica a Red) --</option>';
            filtradas.forEach(m => {
                options += `<option value="${m.mic_ide}">${m.mic_nombre}</option>`;
            });

            $mic.html(options).prop('disabled', false);
            if (microredSeleccionada) $mic.val(microredSeleccionada);
        });
    }

    function cargarEstablecimientosPorMicrored(microredId, establecimientoSeleccionado = null) {
        let $est = $('#establecimiento_id');

        if (!microredId) {
            $est.html('<option value="">-- Seleccione Microred primero --</option>').prop('disabled', true);
            return Promise.resolve();
        }

        $est.html('<option value="">Cargando establecimientos...</option>').prop('disabled', true);

        return $.get(urlApiEstablecimientos, function(res) {
            const items = res.data || res;
            const filtradas = items.filter(e => e.est_mic_ide == microredId);

            let options = '<option value="">-- Todos los Establecimientos (Aplica a Microred) --</option>';
            filtradas.forEach(e => {
                options += `<option value="${e.est_ide}">${e.est_nombre}</option>`;
            });

            $est.html(options).prop('disabled', false);
            if (establecimientoSeleccionado) $est.val(establecimientoSeleccionado);
        });
    }

    // Acciones de Modal
    function filtrarTabla() {
        tabla.ajax.reload(null, false);
    }

    function abrirModalCrear() {
        $('#formGrupo')[0].reset();
        $('#gco_ide').val('');
        $('#red_id, #microred_id, #establecimiento_id')
            .html('<option value="">-- Seleccione nivel superior --</option>')
            .prop('disabled', true);

        $('#modalTitulo').text('Nuevo Grupo de Corte');
        modalGrupo.show();
    }

    async function abrirModalEditar(data) {
        $('#formGrupo')[0].reset();
        $('#gco_ide').val(data.gco_ide);
        $('#gco_nombre').val(data.gco_nombre);
        $('#gco_mco_ide').val(data.gco_mco_ide || '');
        $('#gco_dia_inicio').val(data.gco_dia_inicio);
        $('#gco_dia_fin').val(data.gco_dia_fin);
        $('#gco_mes_desfasado').val(data.gco_mes_desfasado);
        $('#gco_estado').val(data.gco_estado);

        if (data.diresa_id) {
            $('#diresa_id').val(data.diresa_id);
            await cargarRedesPorDiresa(data.diresa_id, data.red_id);

            if (data.red_id) {
                await cargarMicroredesPorRed(data.red_id, data.microred_id);

                if (data.microred_id) {
                    await cargarEstablecimientosPorMicrored(data.microred_id, data.establecimiento_id);
                }
            }
        }

        $('#modalTitulo').text('Editar Grupo de Corte');
        modalGrupo.show();
    }

    function guardarGrupo() {
        const id = $('#gco_ide').val();
        const esEditar = id !== "";
        const url = esEditar ? `${urlApiGrupos}/${id}` : urlApiGrupos;
        const metodo = esEditar ? 'PUT' : 'POST';

        $('#btnGuardar').prop('disabled', true).text('Guardando...');

        const datos = {
            diresa_id: $('#diresa_id').val(),
            red_id: $('#red_id').val() || null,
            microred_id: $('#microred_id').val() || null,
            establecimiento_id: $('#establecimiento_id').val() || null,
            gco_nombre: $('#gco_nombre').val(),
            gco_mco_ide: $('#gco_mco_ide').val() || null,
            gco_dia_inicio: $('#gco_dia_inicio').val(),
            gco_dia_fin: $('#gco_dia_fin').val(),
            gco_mes_desfasado: $('#gco_mes_desfasado').val(),
            gco_estado: $('#gco_estado').val()
        };

        $.ajax({
            url: url,
            type: metodo,
            data: datos,
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    modalGrupo.hide();
                    tabla.ajax.reload(null, false);
                }
            },
            error: function(xhr) {
                let errorMsg = 'Error en el servidor.';
                if (xhr.responseJSON && xhr.responseJSON.messages) {
                    errorMsg = typeof xhr.responseJSON.messages === 'object' ?
                        Object.values(xhr.responseJSON.messages).join('<br>') :
                        xhr.responseJSON.messages;
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

    function eliminarGrupo(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "El grupo de corte se eliminará del sistema.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sí, eliminar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `${urlApiGrupos}/${id}`,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.status === 'success') {
                            toastr.success(response.message);
                            tabla.ajax.reload(null, false);
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error!',
                            text: 'No se pudo eliminar el grupo.'
                        });
                    }
                });
            }
        });
    }
</script>
<?= $this->endSection() ?>
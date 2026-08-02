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
                            <label class="form-label small fw-bold">Régimen Laboral</label>
                            <select class="form-select" id="gco_regimen_laboral">
                                <option value="">-- Todos los Regímenes --</option>
                                <option value="276">D.L. 276</option>
                                <option value="1057">D.L. 1057 (CAS)</option>
                                <option value="728">D.L. 728</option>
                                <option value="30057">Ley 30057 (Servir)</option>
                            </select>
                        </div>

                        <!-- JERARQUÍA DE SELECCIÓN -->
                        <div class="col-12">
                            <hr class="my-1 text-muted">
                        </div>
                        <div class="col-12">
                            <span class="badge bg-light text-primary border">Alcance Jerárquico (Seleccione
                                Establecimiento para autollenar la cadena)</span>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Establecimiento</label>
                            <select class="form-select" id="establecimiento_id" onchange="alCambiarEstablecimiento()">
                                <option value="">-- Ninguno (Aplica a nivel superior) --</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Microred</label>
                            <select class="form-select" id="microred_id" onchange="alCambiarMicrored()">
                                <option value="">-- Ninguna --</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Red de Salud</label>
                            <select class="form-select" id="red_id" onchange="alCambiarRed()">
                                <option value="">-- Ninguna --</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">DIRESA *</label>
                            <select class="form-select" id="diresa_id" required>
                                <option value="">-- Seleccione DIRESA --</option>
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
    const urlApiGrupos = '<?= base_url('asistencia/gestordb/api/grupos-corte') ?>';
    const urlBaseApi = '<?= base_url('asistencia/gestordb/api') ?>';

    let tabla;
    let modalGrupo = new bootstrap.Modal(document.getElementById('modalGrupo'));

    // Caché local para la relación jerárquica
    let listaEstablecimientos = [];
    let listaMicroredes = [];
    let listaRedes = [];

    $(document).ready(function () {
        cargarLookups();

        tabla = $('#tablaGrupos').DataTable({
            "ajax": {
                "url": urlApiGrupos,
                "type": "GET",
                "data": function (d) {
                    d.estado = $('#filtroEstado').val();
                },
                "dataSrc": "data"
            },
            "columns": [
                { "data": "gco_ide" },
                {
                    "data": "gco_nombre",
                    "className": "fw-bold text-dark",
                    "render": function (data, type, row) {
                        let nivel = 'DIRESA General';
                        if (row.establecimiento_id) nivel = `Establecimiento ID: ${row.establecimiento_id}`;
                        else if (row.microred_id) nivel = `Microred ID: ${row.microred_id}`;
                        else if (row.red_id) nivel = `Red ID: ${row.red_id}`;

                        return `
                            <div>
                                <span>${data}</span>
                                <div class="text-muted small fw-normal"><iconify-icon icon="solar:structure-bold" class="me-1"></iconify-icon>${nivel}</div>
                            </div>
                        `;
                    }
                },
                {
                    "data": "gco_regimen_laboral",
                    "render": function (data) {
                        return data ? `<span class="badge bg-primary-subtle text-primary border border-primary-subtle">${data}</span>`
                            : '<span class="badge bg-secondary-subtle text-secondary border">Todos</span>';
                    }
                },
                {
                    "data": "gco_dia_inicio",
                    "className": "text-center font-monospace fw-bold",
                    "render": function (data) { return `Día ${data}`; }
                },
                {
                    "data": "gco_dia_fin",
                    "className": "text-center font-monospace fw-bold",
                    "render": function (data) { return `Día ${data}`; }
                },
                {
                    "data": "gco_mes_desfasado",
                    "className": "text-center",
                    "render": function (data) {
                        return data == 1
                            ? '<span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Sí (-1 Mes)</span>'
                            : '<span class="badge bg-light text-muted border">No</span>';
                    }
                },
                {
                    "data": "gco_estado",
                    "render": function (data) {
                        return data === 'ACTIVO'
                            ? '<span class="badge bg-success-subtle text-success border border-success-subtle">ACTIVO</span>'
                            : '<span class="badge bg-danger-subtle text-danger border border-danger-subtle">INACTIVO</span>';
                    }
                },
                {
                    "data": null,
                    "orderable": false,
                    "className": "text-end",
                    "render": function (data, type, row) {
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

        $('#formGrupo').on('submit', function (e) {
            e.preventDefault();
            guardarGrupo();
        });
    });

    // Cargar combos de apoyo desde los endpoints Lookups de la API
    function cargarLookups() {
        // Diresas
        $.get(`${urlBaseApi}/diresas`, function (res) {
            let options = '<option value="">-- Seleccione DIRESA --</option>';
            const items = res.data || res;
            items.forEach(d => {
                options += `<option value="${d.dir_ide || d.id}">${d.dir_nombre || d.nombre}</option>`;
            });
            $('#diresa_id').html(options);
        });

        // Redes
        $.get(`${urlBaseApi}/redes-lookup`, function (res) {
            listaRedes = res.data || res;
            let options = '<option value="">-- Ninguna --</option>';
            listaRedes.forEach(r => {
                options += `<option value="${r.red_ide || r.id}">${r.red_nombre || r.nombre}</option>`;
            });
            $('#red_id').html(options);
        });

        // Microredes
        $.get(`${urlBaseApi}/microredes-lookup`, function (res) {
            listaMicroredes = res.data || res;
            let options = '<option value="">-- Ninguna --</option>';
            listaMicroredes.forEach(m => {
                options += `<option value="${m.mre_ide || m.id}">${m.mre_nombre || m.nombre}</option>`;
            });
            $('#microred_id').html(options);
        });

        // Establecimientos
        $.get(`${urlBaseApi}/establecimientos-lookup`, function (res) {
            listaEstablecimientos = res.data || res;
            let options = '<option value="">-- Ninguno (Aplica a nivel superior) --</option>';
            listaEstablecimientos.forEach(e => {
                options += `<option value="${e.est_ide || e.id}">${e.est_nombre || e.nombre}</option>`;
            });
            $('#establecimiento_id').html(options);
        });
    }

    // AUTOSELECCIÓN EN CASCADA
    function alCambiarEstablecimiento() {
        const estId = $('#establecimiento_id').val();
        if (!estId) return;

        const est = listaEstablecimientos.find(item => (item.est_ide || item.id) == estId);
        if (est) {
            if (est.microred_id || est.est_mre_ide) {
                $('#microred_id').val(est.microred_id || est.est_mre_ide);
                alCambiarMicrored();
            }
        }
    }

    function alCambiarMicrored() {
        const mreId = $('#microred_id').val();
        if (!mreId) return;

        const mre = listaMicroredes.find(item => (item.mre_ide || item.id) == mreId);
        if (mre) {
            if (mre.red_id || mre.mre_red_ide) {
                $('#red_id').val(mre.red_id || mre.mre_red_ide);
                alCambiarRed();
            }
        }
    }

    function alCambiarRed() {
        const redId = $('#red_id').val();
        if (!redId) return;

        const red = listaRedes.find(item => (item.red_ide || item.id) == redId);
        if (red) {
            if (red.diresa_id || red.red_dir_ide) {
                $('#diresa_id').val(red.diresa_id || red.red_dir_ide);
            }
        }
    }

    function filtrarTabla() {
        tabla.ajax.reload(null, false);
    }

    function abrirModalCrear() {
        $('#formGrupo')[0].reset();
        $('#gco_ide').val('');
        $('#gco_estado').val('ACTIVO');
        $('#gco_mes_desfasado').val('0');
        $('#modalTitulo').text('Nuevo Grupo de Corte');
        modalGrupo.show();
    }

    function abrirModalEditar(data) {
        $('#formGrupo')[0].reset();
        $('#gco_ide').val(data.gco_ide);
        $('#gco_nombre').val(data.gco_nombre);
        $('#gco_regimen_laboral').val(data.gco_regimen_laboral || '');

        $('#diresa_id').val(data.diresa_id || '');
        $('#red_id').val(data.red_id || '');
        $('#microred_id').val(data.microred_id || '');
        $('#establecimiento_id').val(data.establecimiento_id || '');

        $('#gco_dia_inicio').val(data.gco_dia_inicio);
        $('#gco_dia_fin').val(data.gco_dia_fin);
        $('#gco_mes_desfasado').val(data.gco_mes_desfasado || 0);
        $('#gco_estado').val(data.gco_estado);

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
            gco_regimen_laboral: $('#gco_regimen_laboral').val() || null,
            gco_dia_inicio: $('#gco_dia_inicio').val(),
            gco_dia_fin: $('#gco_dia_fin').val(),
            gco_mes_desfasado: $('#gco_mes_desfasado').val(),
            gco_estado: $('#gco_estado').val()
        };

        $.ajax({
            url: url,
            type: metodo,
            data: datos,
            success: function (response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    modalGrupo.hide();
                    tabla.ajax.reload(null, false);
                }
            },
            error: function (xhr) {
                let errorMsg = 'Error en el servidor.';
                if (xhr.responseJSON && xhr.responseJSON.messages) {
                    errorMsg = typeof xhr.responseJSON.messages === 'object'
                        ? Object.values(xhr.responseJSON.messages).join('<br>')
                        : xhr.responseJSON.messages;
                }
                Swal.fire({ icon: 'error', title: '¡Error!', html: errorMsg });
            },
            complete: function () {
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
                    success: function (response) {
                        if (response.status === 'success') {
                            toastr.success(response.message);
                            tabla.ajax.reload(null, false);
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({ icon: 'error', title: '¡Error!', text: 'No se pudo eliminar el grupo.' });
                    }
                });
            }
        });
    }
</script>
<?= $this->endSection() ?>
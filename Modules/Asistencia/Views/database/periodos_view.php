<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?>
Gestión de Períodos de Asistencia
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:calendar-mark-bold" class="me-2 text-primary" style="font-size: 1.8rem;"></iconify-icon>
                Períodos de Asistencia
            </h4>
            <p class="text-muted mb-0 small">Apertura, edición y cierre de ciclos evaluables de marcaciones</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <select class="form-select form-select-sm rounded-pill px-3" id="filtroEstado" style="width: 170px;" onchange="filtrarTabla()">
                <option value="" selected>Todos los estados</option>
                <option value="PROGRAMADO">PROGRAMADO</option>
                <option value="ABIERTO">ABIERTO</option>
                <option value="EN_EVALUACION">EN EVALUACIÓN</option>
                <option value="CERRADO">CERRADO</option>
                <option value="REABIERTO">REABIERTO</option>
            </select>

            <button class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center" onclick="abrirModalCrear()">
                <iconify-icon icon="lucide:plus" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
                Nuevo Período
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table id="tablaPeriodos" class="table table-hover align-middle w-100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Período</th>
                    <th>Grupo de Corte</th>
                    <th>Rango Evaluable</th>
                    <th>Estado</th>
                    <th>Observación</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- MODAL: CREAR / EDITAR PERÍODO -->
<div class="modal fade" id="modalPeriodo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitulo">Nuevo Período</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formPeriodo">
                <div class="modal-body py-3">
                    <input type="hidden" id="per_ide">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold">Grupo de Corte *</label>
                            <select class="form-select" id="gco_ide" required onchange="sugerirFechas()">
                                <option value="">-- Seleccione Grupo --</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Año *</label>
                            <input type="number" class="form-control" id="per_anio" required value="<?= date('Y') ?>" min="2020" max="2035" onchange="sugerirFechas()">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Mes *</label>
                            <select class="form-select" id="per_mes" required onchange="sugerirFechas()">
                                <option value="1">Enero</option>
                                <option value="2">Febrero</option>
                                <option value="3" selected>Marzo</option>
                                <option value="4">Abril</option>
                                <option value="5">Mayo</option>
                                <option value="6">Junio</option>
                                <option value="7">Julio</option>
                                <option value="8">Agosto</option>
                                <option value="9">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold">Nombre del Período *</label>
                            <input type="text" class="form-control" id="per_nombre" required maxlength="100" placeholder="Ej. MARZO 2026 - ADMINISTRATIVOS">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Fecha Inicio *</label>
                            <input type="date" class="form-control" id="per_fecha_inicio" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Fecha Fin *</label>
                            <input type="date" class="form-control" id="per_fecha_fin" required>
                        </div>

                        <div class="col-12" id="divEstado">
                            <label class="form-label small fw-bold">Estado *</label>
                            <select class="form-select" id="per_estado">
                                <option value="PROGRAMADO">PROGRAMADO</option>
                                <option value="ABIERTO">ABIERTO</option>
                                <option value="EN_EVALUACION">EN EVALUACIÓN</option>
                                <option value="CERRADO">CERRADO</option>
                                <option value="REABIERTO">REABIERTO</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold">Observaciones</label>
                            <textarea class="form-control" id="per_observacion" rows="2" placeholder="Notas opcionales..."></textarea>
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
    const urlApiPeriodos = '<?= base_url('asistencia/gestordb/api/periodos') ?>';
    const urlApiGrupos   = '<?= base_url('asistencia/gestordb/api/grupos-corte') ?>';

    let tabla;
    let modalPeriodo = new bootstrap.Modal(document.getElementById('modalPeriodo'));

    $(document).ready(function() {
        cargarGruposCorte();

        tabla = $('#tablaPeriodos').DataTable({
            "ajax": {
                "url": urlApiPeriodos,
                "type": "GET",
                "data": function(d) {
                    d.per_estado = $('#filtroEstado').val();
                },
                "dataSrc": "data"
            },
            "columns": [
                { "data": "per_ide" },
                { "data": "per_nombre", "className": "fw-bold text-dark" },
                { "data": "gco_nombre", "defaultContent": "General" },
                {
                    "data": null,
                    "className": "small font-monospace",
                    "render": function(data) {
                        return `${data.per_fecha_inicio} <span class="text-muted">al</span> ${data.per_fecha_fin}`;
                    }
                },
                {
                    "data": "per_estado",
                    "render": function(data) {
                        const badges = {
                            'PROGRAMADO': 'bg-secondary-subtle text-secondary border-secondary-subtle',
                            'ABIERTO': 'bg-success-subtle text-success border-success-subtle',
                            'EN_EVALUACION': 'bg-warning-subtle text-warning-emphasis border-warning-subtle',
                            'CERRADO': 'bg-danger-subtle text-danger border-danger-subtle',
                            'REABIERTO': 'bg-info-subtle text-info border-info-subtle'
                        };
                        return `<span class="badge ${badges[data] || 'bg-light text-dark'} border">${data}</span>`;
                    }
                },
                { "data": "per_observacion", "defaultContent": "-" },
                {
                    "data": null,
                    "orderable": false,
                    "className": "text-end",
                    "render": function(data, type, row) {
                        return `
                            <div class="d-inline-flex align-items-center justify-content-end gap-1">
                                <button class="btn btn-outline-warning btn-sm rounded-circle p-1 d-inline-flex align-items-center justify-content-center" 
                                        onclick='abrirModalEditar(${JSON.stringify(row)})' title="Editar" style="width: 30px; height: 30px;">
                                    <iconify-icon icon="lucide:edit" style="font-size: 1rem;"></iconify-icon>
                                </button>
                                <button class="btn btn-outline-danger btn-sm rounded-circle p-1 d-inline-flex align-items-center justify-content-center" 
                                        onclick="eliminarPeriodo(${row.per_ide})" title="Eliminar" style="width: 30px; height: 30px;">
                                    <iconify-icon icon="lucide:trash-2" style="font-size: 1rem;"></iconify-icon>
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            "language": { "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" }
        });

        $('#formPeriodo').on('submit', function(e) {
            e.preventDefault();
            guardarPeriodo();
        });
    });

    function cargarGruposCorte() {
        $.get(urlApiGrupos, function(res) {
            let options = '<option value="">-- Seleccione Grupo --</option>';
            (res.data || res).forEach(g => {
                options += `<option value="${g.gco_ide}">${g.gco_nombre}</option>`;
            });
            $('#gco_ide').html(options);
        });
    }

    function sugerirFechas() {
        const gco_ide = $('#gco_ide').val();
        const anio = $('#per_anio').val();
        const mes = $('#per_mes').val();

        if (gco_ide && anio && mes) {
            const meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            const grupoTexto = $("#gco_ide option:selected").text();
            
            if (!$('#per_ide').val()) { // Solo autogenerar nombre al crear
                $('#per_nombre').val(`${meses[mes - 1].toUpperCase()} ${anio} - ${grupoTexto}`);
            }

            $.get(`${urlApiPeriodos}/calcular-fechas`, { gco_ide, anio, mes }, function(res) {
                if (res.status === 'success') {
                    $('#per_fecha_inicio').val(res.data.fecha_inicio);
                    $('#per_fecha_fin').val(res.data.fecha_fin);
                }
            });
        }
    }

    function filtrarTabla() {
        tabla.ajax.reload(null, false);
    }

    function abrirModalCrear() {
        $('#formPeriodo')[0].reset();
        $('#per_ide').val('');
        $('#per_estado').val('PROGRAMADO');
        $('#modalTitulo').text('Aperturar Nuevo Período');
        $('#btnGuardar').text('Guardar');
        modalPeriodo.show();
    }

    function abrirModalEditar(data) {
        $('#formPeriodo')[0].reset();
        $('#per_ide').val(data.per_ide);
        $('#gco_ide').val(data.gco_ide);
        $('#per_anio').val(data.per_anio);
        $('#per_mes').val(data.per_mes);
        $('#per_nombre').val(data.per_nombre);
        $('#per_fecha_inicio').val(data.per_fecha_inicio);
        $('#per_fecha_fin').val(data.per_fecha_fin);
        $('#per_estado').val(data.per_estado);
        $('#per_observacion').val(data.per_observacion || '');

        $('#modalTitulo').text('Editar Período');
        $('#btnGuardar').text('Guardar Cambios');
        modalPeriodo.show();
    }

    function guardarPeriodo() {
        const id = $('#per_ide').val();
        const esEditar = id !== "";
        const url = esEditar ? `${urlApiPeriodos}/${id}` : urlApiPeriodos;
        const metodo = esEditar ? 'PUT' : 'POST';

        $('#btnGuardar').prop('disabled', true).text('Guardando...');

        const datos = {
            gco_ide: $('#gco_ide').val(),
            per_anio: $('#per_anio').val(),
            per_mes: $('#per_mes').val(),
            per_nombre: $('#per_nombre').val(),
            per_fecha_inicio: $('#per_fecha_inicio').val(),
            per_fecha_fin: $('#per_fecha_fin').val(),
            per_estado: $('#per_estado').val(),
            per_observacion: $('#per_observacion').val()
        };

        $.ajax({
            url: url,
            type: metodo,
            data: datos,
            success: function(res) {
                if (res.status === 'success') {
                    toastr.success(res.message);
                    modalPeriodo.hide();
                    tabla.ajax.reload(null, false);
                }
            },
            error: function(xhr) {
                let msg = 'Error al procesar la solicitud.';
                if (xhr.responseJSON && xhr.responseJSON.messages) {
                    msg = typeof xhr.responseJSON.messages === 'object' 
                        ? Object.values(xhr.responseJSON.messages).join('<br>') 
                        : xhr.responseJSON.messages;
                }
                Swal.fire({ icon: 'error', title: 'Atención', html: msg });
            },
            complete: function() {
                $('#btnGuardar').prop('disabled', false).text('Guardar');
            }
        });
    }

    function eliminarPeriodo(id) {
        Swal.fire({
            title: '¿Eliminar período?',
            text: "El registro pasará a la papelera (Soft Delete).",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sí, eliminar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `${urlApiPeriodos}/${id}`,
                    type: 'DELETE',
                    success: function(res) {
                        toastr.success(res.message);
                        tabla.ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        let msg = 'No se pudo eliminar el período.';
                        if (xhr.responseJSON && xhr.responseJSON.messages) {
                            msg = xhr.responseJSON.messages;
                        }
                        Swal.fire({ icon: 'error', title: 'Error', text: msg });
                    }
                });
            }
        });
    }
</script>
<?= $this->endSection() ?>
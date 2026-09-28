<?= $this->extend('layouts/seleccionLayout') ?>

<?= $this->section('title') ?>
Estados de Convocatoria
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:widget-5-bold" class="me-2 text-primary" style="font-size: 1.5rem;"></iconify-icon>
                Estados de Convocatoria
            </h4>
            <p class="text-muted mb-0 small">Administración de estados de convocatoria</p>
        </div>
        <button class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center" onclick="abrirModalCrear()">
            <iconify-icon icon="lucide:plus" class="me-1"></iconify-icon>
            Nuevo registro
        </button>
    </div>
    <div class="table-responsive">
        <table id="tablaestadosConvocatoria" class="table table-hover align-middle w-100">
            <thead><tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Orden</th>
                    <th class="text-end">Acciones</th>
            </tr></thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalestadosConvocatoria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content">
        <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold" id="modalTitulo">Nuevo Estado de Convocatoria</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <form id="formestadosConvocatoria">
            <div class="modal-body py-3">
                <input type="hidden" id="eco_ide">
                <div class="row g-3">
                        <div class="col-md-6"><label class="form-label small fw-bold">Código <span class="text-danger">*</span></label><input type="text" class="form-control" id="eco_codigo" required></div>
                        <div class="col-md-6"><label class="form-label small fw-bold">Nombre <span class="text-danger">*</span></label><input type="text" class="form-control" id="eco_nombre" required></div>
                        <div class="col-md-6"><label class="form-label small fw-bold">Descripción</label><textarea class="form-control" id="eco_descripcion" rows="3"></textarea></div>
                        <div class="col-md-6"><label class="form-label small fw-bold">Orden</label><input type="number" class="form-control" id="eco_orden"></div>
                        <div class="col-md-6"><label class="form-label small fw-bold">Estado <span class="text-danger">*</span></label><select class="form-select" id="eco_estado" required><option value="ACTIVO">Activo</option><option value="INACTIVO">Inactivo</option></select></div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary btn-sm px-3" id="btnGuardar">Guardar</button>
            </div>
        </form>
    </div></div>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script>
    const urlBaseApi = '<?= base_url('seleccion/api/estados-convocatoria') ?>';
    const tableId = '#tablaestadosConvocatoria';
    const formId = '#formestadosConvocatoria';
    const modal = new bootstrap.Modal(document.getElementById('modalestadosConvocatoria'));
    let tabla;
    const registros = new Map();

    function escapeHtml(value) {
        return String(value).replace(/[&<>"']/g, character => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[character]));
    }
    function mensajeError(xhr) {
        const body = xhr.responseJSON || {};
        const errors = body.errors || body.messages || {};
        return Object.keys(errors).length ? Object.values(errors).map(escapeHtml).join('<br>') : escapeHtml(body.message || 'No se pudo procesar la solicitud.');
    }
    $(document).ready(function () {
        tabla = $(tableId).DataTable({
            ajax: { url: urlBaseApi, type: 'GET', dataSrc: function (response) {
                if (!response.status) { toastr.error(response.message || 'No se pudo cargar la información.'); return []; }
                registros.clear();
                (response.data || []).forEach(row => registros.set(String(row.eco_ide), row));
                return response.data || [];
            }},
            columns: [
                { data: 'eco_codigo', render: function(value) { return escapeHtml(value ?? ''); } },
                { data: 'eco_nombre', render: function(value) { return escapeHtml(value ?? ''); } },
                { data: 'eco_descripcion', render: function(value) { return escapeHtml(value ?? ''); } },
                { data: 'eco_orden', render: function(value) { return escapeHtml(value ?? ''); } },
                { data: null, orderable: false, className: 'text-end', render: function (_, __, row) {
                    return '<button class="btn btn-outline-warning btn-sm me-1 rounded-circle p-1 editar" data-id="' + row.eco_ide + '" title="Editar" style="width:32px;height:32px"><iconify-icon icon="lucide:edit"></iconify-icon></button>' +
                        '<button class="btn btn-outline-danger btn-sm rounded-circle p-1 eliminar" data-id="' + row.eco_ide + '" title="Eliminar" style="width:32px;height:32px"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>';
                }}
            ],
            language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' }
        });
        $(formId).on('submit', function (event) { event.preventDefault(); guardar(); });
        $(tableId + ' tbody').on('click', '.editar', function () { abrirModalEditar(registros.get(String($(this).data('id')))); });
        $(tableId + ' tbody').on('click', '.eliminar', function () { eliminar($(this).data('id')); });
    });
    function abrirModalCrear() {
        $(formId)[0].reset();
        $('#eco_ide').val('');
        $('#eco_estado').val('ACTIVO');
        $('#modalTitulo').text('Nuevo Estado de Convocatoria');
        modal.show();
    }
    function abrirModalEditar(data) {
        if (!data) return;
        $(formId)[0].reset();
        $('#eco_ide').val(data.eco_ide);
        $('#eco_codigo').val(data.eco_codigo ?? '');
        $('#eco_nombre').val(data.eco_nombre ?? '');
        $('#eco_descripcion').val(data.eco_descripcion ?? '');
        $('#eco_orden').val(data.eco_orden ?? '');
        $('#eco_estado').val(data.eco_estado ?? 'ACTIVO');
        $('#modalTitulo').text('Editar Estado de Convocatoria');
        modal.show();
    }
    function guardar() {
        const id = $('#eco_ide').val();
        const editar = id !== '';
        const datos = {
            eco_codigo: $('#eco_codigo').val(),
            eco_nombre: $('#eco_nombre').val(),
            eco_descripcion: $('#eco_descripcion').val(),
            eco_orden: $('#eco_orden').val(),
            eco_estado: $('#eco_estado').val()
        };
        $('#btnGuardar').prop('disabled', true).text('Guardando...');
        $.ajax({
            url: editar ? urlBaseApi + '/' + id : urlBaseApi,
            type: editar ? 'PUT' : 'POST',
            contentType: 'application/json',
            data: JSON.stringify(datos),
            success: function (response) {
                if (response.status) {
                    toastr.success(response.message);
                    modal.hide();
                    tabla.ajax.reload(null, false);
                } else {
                    Swal.fire({ icon: 'error', title: '¡Error!', html: mensajeError({ responseJSON: response }) });
                }
            },
            error: function (xhr) { Swal.fire({ icon: 'error', title: '¡Error!', html: mensajeError(xhr) }); },
            complete: function () { $('#btnGuardar').prop('disabled', false).text('Guardar'); }
        });
    }
    function eliminar(id) {
        Swal.fire({ title: '¿Estás seguro?', text: 'El registro será eliminado.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar' })
            .then(result => {
                if (!result.isConfirmed) return;
                $.ajax({
                    url: urlBaseApi + '/' + id,
                    type: 'DELETE',
                    success: function (response) {
                        if (response.status) { toastr.success(response.message); tabla.ajax.reload(null, false); }
                        else Swal.fire({ icon: 'error', title: '¡Error!', html: mensajeError({ responseJSON: response }) });
                    },
                    error: function (xhr) { Swal.fire({ icon: 'error', title: '¡Error!', html: mensajeError(xhr) }); }
                });
            });
    }
</script>
<?= $this->endSection() ?>


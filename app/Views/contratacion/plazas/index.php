<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Plazas</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPlaza">
        <i class="fa fa-plus"></i> Nueva Plaza
    </button>
</div>

<input type="hidden" id="id_convocatoria" value="<?= esc($id_convocatoria ?? '') ?>">

<div class="card">
    <div class="card-body">
        <table id="tablaPlazas" class="table table-bordered w-100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Establecimiento</th>
                    <th>Vacantes</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="modalPlaza">
    <div class="modal-dialog modal-lg">
        <form id="formPlaza">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Plaza</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="id_plaza" id="id_plaza">
                    <input type="hidden" name="id_convocatoria" id="id_convocatoria_form">

                    <div class="mb-3">
                        <label>Código Plaza</label>
                        <input type="text" name="codigo_plaza" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Establecimiento</label>
                        <input type="text" name="establecimiento" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Detalle</label>
                        <textarea name="detalle" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Vacantes</label>
                        <input type="number" name="vacantes" class="form-control" min="1">
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-success">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    var tablaPlazas = $("#tablaPlazas").DataTable();

    function cargarPlazas() {
        var idConv = $('#id_convocatoria').val();

        $.getJSON('admin/plazas/listar/' + idConv, function(data) {
            tablaPlazas.clear().rows.add(data.map(p => {
                return [
                    p.id_plaza,
                    p.codigo_plaza,
                    p.establecimiento,
                    p.vacantes,
                    `
                <button class="btn btn-sm btn-primary btn-editar" data-id="${p.id_plaza}">
                    <i class="fa fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger btn-eliminar" data-id="${p.id_plaza}">
                    <i class="fa fa-trash"></i>
                </button>
                `
                ];
            })).draw();
        });
    }

    cargarPlazas();

    // Guardar
    $('#formPlaza').submit(function(e) {
        e.preventDefault();

        $.post('admin/plazas/guardar', $(this).serialize(), function(res) {
            $('#modalPlaza').modal('hide');
            $('#formPlaza')[0].reset();
            cargarPlazas();
            Swal.fire('Éxito', 'Plaza guardada', 'success');
        }, 'json');
    });

    // Editar
    $(document).on('click', '.btn-editar', function() {
        $.getJSON('admin/plazas/editar/' + $(this).data('id'), function(p) {
            $('#id_plaza').val(p.id_plaza);
            $('#id_convocatoria_form').val(p.id_convocatoria);
            $('[name="codigo_plaza"]').val(p.codigo_plaza);
            $('[name="establecimiento"]').val(p.establecimiento);
            $('[name="detalle"]').val(p.detalle);
            $('[name="vacantes"]').val(p.vacantes);
            $('#modalPlaza').modal('show');
        });
    });

    // Eliminar
    $(document).on('click', '.btn-eliminar', function() {
        var id = $(this).data('id');

        Swal.fire({
            title: '¿Eliminar?',
            showCancelButton: true
        }).then(r => {
            if (r.isConfirmed) {
                $.post('admin/plazas/eliminar/' + id, function() {
                    cargarPlazas();
                    Swal.fire('Eliminado', '', 'success');
                }, 'json');
            }
        });
    });
</script>
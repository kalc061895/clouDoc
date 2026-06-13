<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Convocatorias</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalConvocatoria">
        <i class="fa fa-plus"></i> Nueva Convocatoria
    </button>
</div>

<div class="card">
    <div class="card-body">

        <div class="table-responsive">
            <table id="tablaConvocatorias" class="table w-100 table-striped table-bordered display ">
                <thead>
                    <!-- start row -->
                    <tr>
                        <th>Id</th>
                        <th>Código</th>
                        <th>Titulo</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Estado</th>
                        <th>Opciones</th>
                    </tr>
                    <!-- end row -->
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="modalConvocatoria" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="formConvocatoria">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Convocatoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="id_convocatoria" id="id_convocatoria">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Código</label>
                            <input type="text" name="codigo" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select">
                                <option value="BORRADOR">BORRADOR</option>
                                <option value="PUBLICADO">PUBLICADO</option>
                                <option value="CERRADO">CERRADO</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Título</label>
                            <input type="text" name="titulo" class="form-control" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha Fin</label>
                            <input type="date" name="fecha_fin" class="form-control" required>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalPlazas" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Plazas de la Convocatoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- ID convocatoria -->
                <input type="hidden" id="plaza_id_convocatoria">

                <div class="d-flex justify-content-between mb-3">
                    <h6 class="mb-0">Listado de plazas</h6>
                    <button class="btn btn-success btn-sm" id="btnNuevaPlaza">
                        <i class="fa fa-plus"></i> Nueva Plaza
                    </button>
                </div>

                <table id="tablaPlazasModal" class="table table-bordered w-100">
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

                <hr>

                <!-- FORMULARIO PLAZA -->
                <form id="formPlaza" class="d-none">
                    <input type="hidden" name="id_plaza" id="id_plaza">
                    <input type="hidden" name="id_convocatoria" id="form_id_convocatoria">

                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label>Código Plaza</label>
                            <input type="text" name="codigo_plaza" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>Establecimiento</label>
                            <input type="text" name="establecimiento" class="form-control">
                        </div>

                        <div class="col-md-2 mb-2">
                            <label>Vacantes</label>
                            <input type="number" name="vacantes" class="form-control" min="1">
                        </div>

                        <div class="col-md-12 mb-2">
                            <label>Detalle</label>
                            <textarea name="detalle" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="button" class="btn btn-secondary btn-sm" id="btnCancelarPlaza">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            Guardar Plaza
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>



<script>
    var convocatoriaTable = $("#tablaConvocatorias").DataTable({
        order: [
            [3, "asc"]
        ],
    });

    $(document).ready(function() {

        // Cargar tabla
        function cargarTabla() {
            $.ajax({
                url: 'admin/convocatorias/listar',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Limpiar y agregar datos
                    convocatoriaTable.clear().rows.add(data.map((conv, index) => {
                        return {
                            0: conv.id_convocatoria,
                            1: conv.codigo,
                            2: conv.titulo,
                            3: conv.fecha_inicio,
                            4: conv.fecha_fin,
                            5: conv.estado,
                            6: `
                            <button class="btn btn-sm btn-secondary btn-plazas"
                                    data-id="${conv.id_convocatoria}">Plazas
                                <i class="fa fa-layer-group"></i>
                            </button>
                            <button class="btn btn-sm btn-primary btn-editar" data-id="${conv.id_convocatoria}">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger btn-eliminar" data-id="${conv.id_convocatoria}">
                                <i class="fa fa-trash"></i>
                            </button>`
                        }
                    })).draw();
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo cargar las convocatorias.', 'error');
                }
            });
        }

        cargarTabla();

        // Guardar convocatoria
        $('#formConvocatoria').submit(function(e) {
            e.preventDefault();
            let formData = $(this).serialize();

            $.ajax({
                url: 'admin/convocatorias/guardar',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        $('#modalConvocatoria').modal('hide');
                        $('#formConvocatoria')[0].reset();
                        cargarTabla();
                        Swal.fire('Éxito', 'Convocatoria guardada correctamente.', 'success');
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo guardar la convocatoria.', 'error');
                }
            });
        });

        // Editar convocatoria
        $(document).off('click', '.btn-editar').on('click', '.btn-editar', function() {
            let id = $(this).data('id');

            $.ajax({
                url: 'admin/convocatorias/editar/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(conv) {
                    $('#id_convocatoria').val(conv.id_convocatoria);
                    $('[name="codigo"]').val(conv.codigo);
                    $('[name="titulo"]').val(conv.titulo);
                    $('[name="descripcion"]').val(conv.descripcion);
                    $('[name="fecha_inicio"]').val(conv.fecha_inicio);
                    $('[name="fecha_fin"]').val(conv.fecha_fin);
                    $('[name="estado"]').val(conv.estado);

                    $('#modalConvocatoria').modal('show');
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo obtener la convocatoria.', 'error');
                }
            });
        });

        // Eliminar convocatoria
        $(document).off('click', '.btn-eliminar').on('click', '.btn-eliminar', function() {
            let id = $(this).data('id');

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'admin/convocatorias/eliminar/' + id,
                        type: 'POST',
                        dataType: 'json',
                        success: function(res) {
                            if (res.status === 'success') {
                                cargarTabla();
                                Swal.fire('Eliminado', 'Convocatoria eliminada.', 'success');
                            } else {
                                Swal.fire('Error', res.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'No se pudo eliminar la convocatoria.', 'error');
                        }
                    });
                }
            });
        });

        $(document).on('click', '.btn-plazas', function() {

            let idConv = $(this).data('id');

            $('#plaza_id_convocatoria').val(idConv);
            $('#form_id_convocatoria').val(idConv);

            cargarPlazasModal(idConv);

            $('#formPlaza').addClass('d-none');
            $('#modalPlazas').modal('show');
        });




    });

    $('#btnNuevaPlaza').on('click', function() {
        $('#formPlaza')[0].reset();
        $('#id_plaza').val('');
        $('#formPlaza').removeClass('d-none');
    });

    function cargarPlazasModal(idConvocatoria) {
        $.getJSON('admin/plazas/listar/' + idConvocatoria, function(data) {

            tablaPlazasModal.clear().rows.add(data.map(p => {
                return [
                    p.id_plaza,
                    p.codigo_plaza,
                    p.establecimiento,
                    p.vacantes,
                    `
                <button class="btn btn-sm btn-primary btn-editar-plaza" data-id="${p.id_plaza}">
                    <i class="fa fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger btn-eliminar-plaza" data-id="${p.id_plaza}">
                    <i class="fa fa-trash"></i>
                </button>
                `
                ];
            })).draw();
        });
    }
    var tablaPlazasModal = $('#tablaPlazasModal').DataTable({
        searching: false,
        paging: false,
        info: false
    });
    $('#formPlaza').submit(function(e) {
        e.preventDefault();

        $.ajax({
            url: 'admin/plazas/guardar',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function() {
                cargarPlazasModal($('#plaza_id_convocatoria').val());
                $('#formPlaza').addClass('d-none');
                Swal.fire('Éxito', 'Plaza guardada', 'success');
            }
        });
    });

    $(document).on('click', '.btn-editar-plaza', function() {
        let id = $(this).data('id');

        $.getJSON('admin/plazas/editar/' + id, function(p) {
            $('#id_plaza').val(p.id_plaza);
            $('[name="codigo_plaza"]').val(p.codigo_plaza);
            $('[name="establecimiento"]').val(p.establecimiento);
            $('[name="vacantes"]').val(p.vacantes);
            $('[name="detalle"]').val(p.detalle);
            $('#formPlaza').removeClass('d-none');
        });
    });

    $(document).on('click', '.btn-eliminar-plaza', function() {
        let id = $(this).data('id');

        Swal.fire({
            title: '¿Eliminar plaza?',
            showCancelButton: true
        }).then(r => {
            if (r.isConfirmed) {
                $.post('admin/plazas/eliminar/' + id, function() {
                    cargarPlazasModal($('#plaza_id_convocatoria').val());
                    Swal.fire('Eliminado', '', 'success');
                }, 'json');
            }
        });
    });

</script>
<!-- start Custom toolbar elements -->

<!-- end Custom toolbar elements -->
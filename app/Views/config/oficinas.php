<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-body h-100">
                <div class="d-flex align-items-center mb-2">
                    <h4 class="card-title mb-0">Gestión de Oficinas</h4>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-success" id="btnNuevaOficina">
                            <i class="ti ti-plus"></i> Nueva Oficina
                        </button>
                    </div>
                </div>
                <div class="table-responsive p-2">
                    <table id="tabla_oficinas" class="table table-sm table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Abreviatura</th>
                                <th>Tipo</th>
                                <th>Padre</th>
                                <th>Estado</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div id="oficinaModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="oficinaForm">
                <div class="modal-header bg-primary text-white">
                    <h4 class="modal-title" id="oficinaModalLabel">Nueva Oficina</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <input type="hidden" name="idoficina" id="idoficina" value="0">

                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Abreviatura</label>
                        <input type="text" name="abreviatura" class="form-control" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Tipo</label>
                        <select name="tipo" class="form-select">
                            <option value="Departamento">Departamento</option>
                            <option value="Oficina">Oficina</option>
                            <option value="Área">Área</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control"></textarea>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Rango</label>
                        <input type="number" name="rango" class="form-control" value="1">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Oficina Padre</label>
                        <select name="oficina_padre_id" class="form-select">
                            <option value="">-- Ninguna --</option>
                            <?php foreach ($oficinas as $item) : ?>
                                <option value="<?= $item['id'] ?>"><?= $item['nombre'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Estado</label>
                        <select name="activo" class="form-select">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">Guardar</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    var tabla_oficinas = $('#tabla_oficinas').DataTable({
        ajax: {
            url: "<?= base_url('/oficinas/listado') ?>",
            type: "GET",
            dataSrc: "data"
        },
        order: [
            [0, "asc"]
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/2.1.0/i18n/es-MX.json"
        },
        columns: [{
                data: "nombre"
            },
            {
                data: "abreviatura"
            },
            {
                data: "tipo"
            },
            {
                data: "oficina_padre"
            },
            {
                data: "activo",
                render: function(data) {
                    return data == 1 ?
                        '<span class="badge bg-success">Activo</span>' :
                        '<span class="badge bg-secondary">Inactivo</span>';
                }
            },
            {
                data: "id",
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                    <div class="btn-group">
                        <button class="btn btn-sm btn-primary btnEditar" data-id="${data}">
                            <i class="ti ti-pencil"></i> Editar
                        </button>
                        <button class="btn btn-sm btn-danger btnEliminar" data-id="${data}">
                            <i class="ti ti-trash"></i> Eliminar
                        </button>
                    </div>`;
                }
            }
        ]
    });


    $('#btnNuevaOficina').on('click', function() {
        $('#oficinaForm')[0].reset();
        $('#idoficina').val(0);
        $('#oficinaModalLabel').html("Nueva Oficina");
        $('#oficinaModal').modal('show');
    });

    $('#oficinaForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.post("<?= base_url('/oficinas/guardar') ?>", formData, function(resp) {
            $('#oficinaModal').modal('hide');
            Swal.fire("Guardado", "", "success");
            tabla_oficinas.ajax.reload(null, false); // 🔄 refresca sin cambiar de página
        }).fail(function() {
            Swal.fire("Error", "No se pudo guardar", "error");
        });
    });

    // Editar
    $('#tabla_oficinas').on('click', '.btnEditar', function() {
        let id = $(this).data('id');

        $.get("<?= base_url('/oficinas/editar') ?>", {
            id: id
        }, function(oficina) {
            $('#idoficina').val(oficina.id);
            $('#oficinaForm [name="nombre"]').val(oficina.nombre);
            $('#oficinaForm [name="abreviatura"]').val(oficina.abreviatura);
            $('#oficinaForm [name="tipo"]').val(oficina.tipo);
            $('#oficinaForm [name="descripcion"]').val(oficina.descripcion);
            $('#oficinaForm [name="rango"]').val(oficina.rango);
            $('#oficinaForm [name="oficina_padre_id"]').val(oficina.oficina_padre_id);
            $('#oficinaForm [name="activo"]').val(oficina.activo);

            $('#oficinaModalLabel').html("Editar Oficina");
            $('#oficinaModal').modal('show');
        }, 'json').fail(function() {
            Swal.fire("Error", "No se pudo cargar la oficina", "error");
        });
    });

    // Eliminar
    $('#tabla_oficinas').on('click', '.btnEliminar', function() {
        let id = $(this).data('id');

        Swal.fire({
            title: "¿Eliminar oficina?",
            text: "No podrás revertir esta acción",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, eliminar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("<?= base_url('/oficinas/eliminar') ?>", {
                    id: id
                }, function(resp) {
                    Swal.fire("Eliminado", "", "success");
                    tabla_oficinas.ajax.reload(null, false);
                }).fail(function() {
                    Swal.fire("Error", "No se pudo eliminar", "error");
                });
            }
        });
    });

    Swal.fire({
        title: "¿Eliminar oficina?",
        text: "No podrás revertir esta acción",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar"
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("<?= base_url('/oficinas/eliminar') ?>", {
                id: id
            }, function() {
                tabla_oficinas.row(function(idx, data, node) {
                    return $(node).data('id') == id;
                }).remove().draw();
                Swal.fire("Eliminado", "", "success");
            }).fail(function() {
                Swal.fire("Error", "No se pudo eliminar", "error");
            });
        }
    });
</script>
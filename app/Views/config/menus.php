<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-body h-100">
                <div class="d-flex align-items-center mb-2">
                    <h4 class="card-title mb-0">Gestión de Menús</h4>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-success" id="btnNuevoMenu">
                            <i class="ti ti-plus"></i> Nuevo Menú
                        </button>
                    </div>
                </div>
                <div class="table-responsive p-2">
                    <table id="tabla_menus" class="table table-sm table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Abbr</th>
                                <th>Tipo</th>
                                <th>URL</th>
                                <th>Ícono</th>
                                <th>Orden</th>
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
<div id="menuModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="menuForm">
                <div class="modal-header bg-primary text-white">
                    <h4 class="modal-title" id="menuModalLabel">Nuevo Menú</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <input type="hidden" name="idmenu" id="idmenu" value="0">

                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Abreviatura</label>
                        <input type="text" name="abbr" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Tipo</label>
                        <select name="type" class="form-select">
                            <option value="primary">Primary</option>
                            <option value="secondary">Secondary</option>
                            <option value="ternary">Ternary</option>
                            <option value="separator">Separator</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">URL</label>
                        <input type="text" name="url" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Ícono</label>
                        <input type="text" name="icon" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Orden</label>
                        <input type="number" name="order" class="form-control" value="1">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Menú Padre</label>
                        <select name="parent_id" class="form-select">
                            <option value="">-- Ninguno --</option>
                            <?php foreach ($menus as $item) : ?>
                                <option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Estado</label>
                        <select name="status" class="form-select">
                            <option value="active">Activo</option>
                            <option value="inactive">Inactivo</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Separador</label>
                        <input type="text" name="separator" class="form-control">
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
    var tabla_menus = $('#tabla_menus').DataTable({
        ajax: {
            url: "<?= base_url('/menus/listado') ?>",
            type: "GET",
            dataSrc: "data"
        },
        order: [[0, "asc"]],
        language: { url: "//cdn.datatables.net/plug-ins/2.1.0/i18n/es-MX.json" },
        columns: [
            { data: "name" },
            { data: "abbr" },
            { data: "type" },
            { data: "url" },
            { data: "icon" },
            { data: "order" },
            { 
                data: "status",
                render: function(data) {
                    return data == "active" ? 
                        '<span class="badge bg-success">Activo</span>' : 
                        '<span class="badge bg-secondary">Inactivo</span>';
                }
            },
            { 
                data: "id",
                orderable: false,
                searchable: false,
                render: function(data) {
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

    // Nuevo
    $('#btnNuevoMenu').on('click', function() {
        $('#menuForm')[0].reset();
        $('#idmenu').val(0);
        $('#menuModalLabel').html("Nuevo Menú");
        $('#menuModal').modal('show');
    });

    // Guardar
    $('#menuForm').submit(function(e) {
        e.preventDefault();
        $.post("<?= base_url('/menus/guardar') ?>", $(this).serialize(), function() {
            $('#menuModal').modal('hide');
            Swal.fire("Guardado", "", "success");
            tabla_menus.ajax.reload(null, false);
        }).fail(function() {
            Swal.fire("Error", "No se pudo guardar", "error");
        });
    });

    // Editar
    $('#tabla_menus').on('click', '.btnEditar', function() {
        let id = $(this).data('id');
        $.get("<?= base_url('/menus/editar') ?>", { id: id }, function(menu) {
            $('#idmenu').val(menu.id);
            $('#menuForm [name="name"]').val(menu.name);
            $('#menuForm [name="abbr"]').val(menu.abbr);
            $('#menuForm [name="type"]').val(menu.type);
            $('#menuForm [name="url"]').val(menu.url);
            $('#menuForm [name="icon"]').val(menu.icon);
            $('#menuForm [name="order"]').val(menu.order);
            $('#menuForm [name="parent_id"]').val(menu.parent_id);
            $('#menuForm [name="status"]').val(menu.status);
            $('#menuForm [name="separator"]').val(menu.separator);

            $('#menuModalLabel').html("Editar Menú");
            $('#menuModal').modal('show');
        }, 'json').fail(function() {
            Swal.fire("Error", "No se pudo cargar el menú", "error");
        });
    });

    // Eliminar
    $('#tabla_menus').on('click', '.btnEliminar', function() {
        let id = $(this).data('id');
        Swal.fire({
            title: "¿Eliminar menú?",
            text: "No podrás revertir esta acción",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, eliminar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("<?= base_url('/menus/eliminar') ?>", { id: id }, function() {
                    Swal.fire("Eliminado", "", "success");
                    tabla_menus.ajax.reload(null, false);
                }).fail(function() {
                    Swal.fire("Error", "No se pudo eliminar", "error");
                });
            }
        });
    });
</script>

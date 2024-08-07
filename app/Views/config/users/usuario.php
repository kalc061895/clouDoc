<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-body h-100">
                <div class="d-flex  align-items-center mb-2">
                    <h4 class="card-title mb-0"><?= lang('Main.titleUsuarioTable') ?></h4>
                    <div class="ms-auto">
                        <div class="btn-group">
                            <button type="button" class="btn btn-rounded btn-success" id="btnNuevoUsuario"> <i class="ti ti-plus"></i> <?= lang('Main.nuevoUsuario') ?></button>

                        </div>

                    </div>
                </div>
                <div class="table-responsive p-2" style="overflow-y: clip;">
                    <table id="zero_config" class="table table-sm table-striped table-bordered text-nowrap align-middle">
                        <thead>
                            <!-- start row -->
                            <tr>
                                <th><?= lang('Main.apellidoNombre') ?></th>
                                <th><?= lang('Main.cargoUsuario') ?></th>
                                <th><?= lang('Main.oficinaUsuario') ?></th>
                                <th><?= lang('Main.estadoUsuario') ?></th>
                                <th><?= lang('Main.opciones') ?></th>

                            </tr>
                            <!-- end row -->
                        </thead>
                        <tbody>
                            <?php foreach ($users as $item) : ?>
                                <!-- start row -->
                                <tr data-id="<?= $item->id ?>">
                                    <td>
                                        <div class="d-flex align-items-center gap-6">
                                            <img src="assets/images/profile/user-4.jpg" width="45" class="rounded-circle" />
                                            <h6 class="mb-0"> <?= $item->paterno . ' ' . $item->materno . ' ' . $item->nombres; ?></h6>
                                        </div>

                                    </td>
                                    <td><?= $item->cargo ?></td>
                                    <td><?= $item->nombre_oficina ?></td>
                                    <td><?= ($item->active) ? 'Activo' : 'Inactivo' ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-rounded btn-primary" onclick="editar_usuario(<?= $item->id ?>)"> <i class="ti ti-pencil"></i> <?= lang('Main.editarUsuario') ?></button>
                                            <button type="button" class="btn btn-sm btn-rounded btn-danger" onclick="eliminar_usuario(<?= $item->id ?>)"> <i class="ti ti-trash"></i> <?= lang('Main.eliminarUsuario') ?></button>
                                        </div>
                                    </td>

                                </tr>
                            <?php endforeach ?>

                        </tbody>

                    </table>
                </div>
            </div>
        </div>

    </div>
</div>


<script>
    var tabla_usuario = $('#zero_config').DataTable({
            order: [
                [0, "asc"]
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/2.1.0/i18n/es-MX.json"
            }
        }

    );
</script>


<!-- Primary Header Modal -->
<div id="userModal" class="modal fade" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary text-white">
                <h4 class="modal-title text-white" id="userModalLabel">
                    Modal Heading
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    <input type="text" hidden name="idusuario" id='idusuario' value="0">
                    <div class="row">
                        <h4 class="card-title mb-4"><?= lang('Main.infoPersonal') ?></h4>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control form-control-sm" placeholder="Username" required name="nombres" />
                                <label>
                                    <i class="ti ti-user me-2 fs-4"></i> <?= lang('Main.nombres') ?>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control form-control-sm" placeholder="Username" required name="paterno" />
                                <label>
                                    <?= lang('Main.paterno') ?>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control form-control-sm" placeholder="Username" required name="materno" />
                                <label>
                                    <?= lang('Main.materno') ?>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control form-control-sm" placeholder="Username" required name="dni" />
                                <label>
                                    <i class="ti ti-credit-card me-2 fs-4"></i> <?= lang('Main.dni') ?>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control form-control-sm" placeholder="Username" required name="email" />
                                <label>
                                    <i class="ti ti-user me-2 fs-4"></i> <?= lang('Main.email') ?>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control form-control-sm" placeholder="Username" required name="telefono" />
                                <label>
                                    <i class="ti ti-user me-2 fs-4"></i> <?= lang('Main.telefono') ?>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control form-control-sm" placeholder="Username" name="cargo" />
                                <label>
                                    <i class="ti ti-user me-2 fs-4"></i> <?= lang('Main.cargoUsuario') ?>
                                </label>
                            </div>
                        </div>

                        <h4 class="card-title mb-4"><?= lang('Main.infoInstitucional') ?></h4>
                        <div class="col-md-6">
                            <div class="mb-3 ">
                                <label class="form-label"><?= lang('Main.oficinaUsuario') ?></label>
                                <select class="form-select" required name="oficina_id">
                                    <?php print_r($oficina); ?>
                                    <?php foreach ($oficina as $item) : ?>

                                        <option value="<?= $item['id'] ?>"><?= $item['nombre'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3 ">
                                <label class="form-label"><?= lang('Main.grupoUsuario') ?></label>
                                <select class="form-select" required name="group_user">
                                    <?php foreach ($grupousuario as $item) : ?>
                                        <option value="<?= $item->name ?>"><?= $item->name ?></option>
                                    <?php endforeach ?>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3 ">
                                <label class="form-label"><?= lang('Main.estado') ?></label>
                                <select class="form-select" required name="estado">
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                        </div>
                        <h4 class="card-title mb-4"><?= lang('Main.infoLogeo') ?></h4>

                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control form-control-sm" placeholder="Username" required name="username" />
                                <label>
                                    <i class="ti ti-user me-2 fs-4"></i> <?= lang('Main.nombreUsuario') ?>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control form-control-sm" placeholder="Password" required name="password" />
                                <label>
                                    <i class="ti ti-lock me-2 fs-4"></i><?= lang('Main.contrasena') ?>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control form-control-sm" placeholder="CPassword" required name="cpassword" />
                                <label>
                                    <i class="ti ti-lock me-2 fs-4"></i><?= lang('Main.ccontrasena') ?>
                                </label>
                            </div>
                        </div>
                        <div class="d-md-flex align-items-center">

                            <div class="mt-3 mt-md-0 ms-auto">
                                <button type="submit" class="btn btn-success  hstack gap-6">
                                    <i class="ti ti-check fs-4"></i>
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <?= lang('Main.cancelar') ?>
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<div id='new_add' hidden>
    <!-- start row -->
    <tr data-id="" id="new_item">
        <td>
            <div class="d-flex align-items-center gap-6">
                <img src="assets/images/profile/user-4.jpg" width="45" class="rounded-circle" />
                <h6 class="mb-0"> </h6>
            </div>

        </td>
        <td></td>
        <td></td>
        <td></td>
        <td>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-rounded btn-primary" onclick="nuevo_usuario()"> <i class="ti ti-pencil"></i> <?= lang('Main.editarUsuario') ?></button>
                <button type="button" class="btn btn-sm btn-rounded btn-danger" onclick="eliminar_usuario()"> <i class="ti ti-trash"></i> <?= lang('Main.eliminarUsuario') ?></button>
            </div>
        </td>

    </tr>
</div>

<script>
    $('#btnNuevoUsuario').on('click', function() {
        $('#userForm')[0].reset();
        $('#userModalLabel').html("<?= lang('Main.nuevoUsuario') ?>");
        $('#userModal').modal('show');

    });

    $('#userForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        if ($('#idusuario').val() == '0') {


            url = '<?= base_url('/configuracion/guardarusuario') ?>';
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    item = JSON.parse(response);

                    $('#userModal').modal('hide');
                    tabla_usuario.row.add(
                        $('<tr>').attr('data-id', item.id).append(
                            $('<td>').html('<div class="d-flex align-items-center gap-6"><img src="assets/images/profile/user-4.jpg" width="45" class="rounded-circle" /><h6 class="mb-0">' + item.nombres + '</h6></div>'),
                            $('<td>').text(item.cargo),
                            $('<td>').text(item.nombre_oficina),
                            $('<td>').text(item.active ? 'Activo' : 'Inactivo'),
                            $('<td>').html('<div class="btn-group"><button type="button" class="btn btn-sm btn-rounded btn-primary" onclick="editar_usuario(' + item.id + ')"> <i class="ti ti-pencil"></i> Editar</button><button type="button" class="btn btn-sm btn-rounded btn-danger" onclick="eliminar_usuario(' + item.id + ')"> <i class="ti ti-trash"></i> Eliminar</button></div>')
                        )
                    ).draw();
                    Swal.fire(
                        "<?= lang('Main.confirmarCreacion') ?>",
                        '',
                        "success"
                    );
                },
                error: function(error) {
                    Swal.fire(
                        "<?= lang('Main.errorCreacion') ?>",
                        '',
                        "error"
                    );
                }
            });
        } else {
            url = '<?= base_url('/configuracion/actualizar') ?>';
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    item = JSON.parse(response);

                    $('#userModal').modal('hide');
                    tabla_usuario.row(function(idx, data, node) {
                            return $(node).data('id') === item.id;
                        }).remove().draw();
                    tabla_usuario.row.add(
                        $('<tr>').attr('data-id', item.id).append(
                            $('<td>').html('<div class="d-flex align-items-center gap-6"><img src="assets/images/profile/user-4.jpg" width="45" class="rounded-circle" /><h6 class="mb-0">' + item.nombres + '</h6></div>'),
                            $('<td>').text(item.cargo),
                            $('<td>').text(item.nombre_oficina),
                            $('<td>').text(item.active ? 'Activo' : 'Inactivo'),
                            $('<td>').html('<div class="btn-group"><button type="button" class="btn btn-sm btn-rounded btn-primary" onclick="editar_usuario(' + item.id + ')"> <i class="ti ti-pencil"></i> Editar</button><button type="button" class="btn btn-sm btn-rounded btn-danger" onclick="eliminar_usuario(' + item.id + ')"> <i class="ti ti-trash"></i> Eliminar</button></div>')
                        )
                    ).draw();
                    Swal.fire(
                        "<?= lang('Main.confirmarActualizacion') ?>",
                        '',
                        "success"
                    );
                },
                error: function(error) {
                    Swal.fire(
                        "<?= lang('Main.errorActualizacion') ?>",
                        '',
                        "error"
                    );
                }
            });
        }
    });

    function editar_usuario(id) {
        var userId = $(this).data('id');
        Swal.fire(
            'Cargando...'
        );
        $.ajax({
            url: "<?= base_url('/configuracion/editar') ?>",
            type: 'GET',
            data: {
                id: id
            },
            success: function(response) {
                var usuario = JSON.parse(response);
                // Rellenar el formulario con los datos del usuario
                $('#userForm [name="idusuario"]').val(usuario.id);
                $('#userForm [name="nombres"]').val(usuario.nombres);
                $('#userForm [name="paterno"]').val(usuario.paterno);
                $('#userForm [name="materno"]').val(usuario.materno);
                $('#userForm [name="dni"]').val(usuario.dni);
                $('#userForm [name="email"]').val(usuario.email);
                $('#userForm [name="telefono"]').val(usuario.telefono);
                $('#userForm [name="cargo"]').val(usuario.cargo);
                $('#userForm [name="oficina_id"]').val(usuario.oficina_id).change();
                $('#userForm [name="group_user"]').val(usuario.group_user).change();
                $('#userForm [name="estado"]').val(usuario.estado).change();
                $('#userForm [name="username"]').val(usuario.username);
                $('#userForm [name="password"]').val(usuario.password);
                $('#userForm [name="cpassword"]').val(usuario.password);
                $('#userModal').modal('show');
                Swal.close();

            },
            error: function(xhr, status, error) {
                Swal.fire(
                    'Error!',
                    'Hubo un problema al acceder el elemento.',
                    'error'
                );
            }
        });
    }

    function eliminar_usuario(id) {
        Swal.fire({
            title: "<?= lang('Main.confirmarEliminar') ?>",
            text: "<?= lang('Main.cuerpoEliminar') ?>",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "<?= lang('Main.si') ?>",
            cancelButtonText: "<?= lang('Main.no') ?>",
        }).then((result) => {
            if (result.value) {
                var userId = id;
                $.ajax({
                    url: "<?= base_url('/configuracion/eliminarusuario') ?>",
                    data: {
                        id: userId
                    },
                    type: 'POST',
                    success: function(response) {
                        tabla_usuario.row(function(idx, data, node) {
                            return $(node).data('id') === id;
                        }).remove().draw();
                        Swal.fire("<?= lang('Main.confirmacionEliminar') ?>");
                    },
                    error: function(error) {
                        Swal.fire(
                            'Error!',
                            'Hubo un problema al eliminar el elemento.',
                            'error'
                        );
                    }
                });
            }
        });

    }
</script>
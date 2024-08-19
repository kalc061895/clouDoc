<?php
$estado = [
    'ESPERA' => 'warning',
    'RECIBIDO' => 'success',
    'DERIVADO' => 'primary',
    'EN PROCESO' => 'primary',
    'FINALIZADO' => 'success',
    'ATENDIDO' => 'primary',
    'OBSERVADO' => 'danger',
    'RECHAZADO' => 'danger',

];

?>
<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <h4 class="card-title mb-0"><?= lang('Main.expedienteTitle') ?></h4>
    </div>
    <div class="card-body ">
        <div class="table-responsive mb-4 ">
            <table class="table table-sm mb-0 table-hover" id="expedientesTable">
                <thead class="text-dark ">
                    <tr>
                        <th>
                            <h6 class=" fw-semibold mb-0"><?= lang('Main.expediente') ?></h6>
                        </th>
                        <th>
                            <h6 class=" fw-semibold mb-0"><?= lang('Main.remitente') ?></h6>
                        </th>
                        <th>
                            <h6 class=" fw-semibold mb-0"><?= lang('Main.asunto') ?></h6>
                        </th>
                        <th>
                            <h6 class=" fw-semibold mb-0"><?= lang('Main.estado') ?></h6>
                        </th>
                        <th>
                            <h6 class=" fw-semibold mb-0"><?= lang('Main.fecha') ?></h6>
                        </th>
                        <th>
                            <h6 class=" fw-semibold mb-0"><?= lang('Main.opciones') ?></h6>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($expediente) : ?>
                        <?php foreach ($expediente as $item) : ?>
                            <tr data-id="<?= $item->id ?>">
                                <td>
                                    <h6 class=" fw-semibold mb-0"><?= $item->numero_expediente; ?></h6>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="assets/images/profile/user-3.jpg" class="rounded-circle" width="40" height="40" />
                                        <div class="ms-3">

                                            <h6 class=" fw-semibold mb-0"><?= $item->nombre ?></h6>
                                            <span class="fw-normal"><?= $item->correo_electronico ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td style="text-wrap: pretty;">
                                    <p class="mb-0 fw-normal "><?= $item->asunto ?></p>
                                </td>
                                <td>

                                    <span class="badge bg-<?= ($item->estado == null) ? 'danger' : $estado[$item->estado] ?> text-white"><?= ($item->estado == null) ? lang('Main.NoLeido') : $item->estado ?> </span>
                                </td>
                                <td>
                                    <h6 class=" fw-semibold mb-0"><?= substr($item->fecha_recepcion, 0, 10); ?></h6>

                                </td>
                                <td>
                                    <div class="button-group">
                                        <button type="button" class="btn btn-sm btn-rounded btn-info" title="<?= lang('Main.revisar') ?>" onclick="RevisarExpediente(<?= $item->id ?>)"><i class="ti ti-eye fs-5"></i> <?= lang('Main.revisar') ?></button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    var tabla_expedientes = $("#expedientesTable").DataTable({
        processing: true, // Show processing indicator
        serverSide: true, // Enable server-side processing
        ajax: {
            url: '<?= base_url('mesa_de_partes/fetch_expedientes') ?>', // URL for the server-side processing script
            type: 'POST',
        },
        columns: [{
                data: 'numero_expediente',
                render: function(data, type, row) {
                    return '<h6 class="fw-semibold mb-0">' + data + '</h6>';
                }
            },
            {
                data: 'nombre',
                render: function(data, type, row) {
                    return `
                    <div class="d-flex align-items-center">
                        <img src="assets/images/profile/user-3.jpg" class="rounded-circle" width="40" height="40" />
                        <div class="ms-3">
                            <h6 class="fw-semibold mb-0">${data}</h6>
                            <span class="fw-normal">${row.correo_electronico}</span>
                        </div>
                    </div>`;
                }
            },
            {
                data: 'asunto',
                render: function(data, type, row) {
                    return '<p class="mb-0 fw-normal">' + data + '</p>';
                }
            },
            {
                data: 'estado',
                render: function(data, type, row) {
                    var estadoClasses = {
                        'ESPERA': 'warning',
                        'RECIBIDO': 'success',
                        'DERIVADO': 'primary',
                        'EN PROCESO': 'primary',
                        'FINALIZADO': 'success',
                        'ATENDIDO': 'primary',
                        'OBSERVADO': 'danger',
                        'RECHAZADO': 'danger'
                    };
                    var estadoClass = data ? estadoClasses[data] : 'danger';
                    return `<span class="badge bg-${estadoClass} text-white">${data ? data : '<?= lang('Main.NoLeido') ?>'}</span>`;
                }
            },
            {
                data: 'fecha_recepcion',
                render: function(data, type, row) {
                    return '<h6 class="fw-semibold mb-0">' + data.substring(0, 10) + '</h6>';
                }
            },
            {
                data: 'opciones',
                render: function(data, type, row) {
                    return `
                    <div class="button-group">
                        <button type="button" class="btn btn-sm btn-rounded btn-info" title="<?= lang('Main.revisar') ?>" onclick="RevisarExpediente(${row.id})">
                            <i class="ti ti-eye fs-5"></i> <?= lang('Main.revisar') ?>
                        </button>
                    </div>`;
                }
            }
        ],
        createdRow: function(row, data, dataIndex) {
            $(row).attr('data-id', data.id);
        },
        order: [
            [1, "desc"]
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/2.1.0/i18n/es-MX.json"
        }
    });

    function RevisarExpediente(expediente_id) {
        Swal.fire({
            type: "info",
            html: "<div class='d-flex justify-content-center'>" +
                "<div class='spinner-border text-primary' role='status'>" +
                "<span class='visually-hidden'>Loading...</span>" +
                "</div>" +
                "</div>",
            showConfirmButton: false,
            showCloseButton: false,
            showCancelButton: false,
        });
        $.ajax({
            url: '<?= base_url('mesa_de_partes/detalle') ?>',
            type: 'GET',
            dataType: 'json',
            data: {
                id: expediente_id
            },
            success: function(response) {
                $('#detalleExpedienteLabel').text(response.title);
                $('#detalleExpedienteBody').html(response.body);
                $('#detalleExpediente').modal('show');
                Swal.close();
            },
            error: function(e) {
                Swal.fire({
                    type: "error",
                    html: "Vuelva a Intentarlo nuevamente",
                    showConfirmButton: true,
                });
            },
        });
    }

    function mensaje_derivar(id) {
        console.log('id del expedietne ' + id);
        tabla_expedientes.row(function(idx, data, node) {
            return $(node).data('id') == id;
        }).remove().draw();
        Swal.fire("<?= lang('Main.confirmacionDerivar') ?>");
    }
</script>

<!-- Primary Header Modal -->
<div id="detalleExpediente" class="modal fade mw-100" tabindex="-1" aria-labelledby="detalleExpedienteLabel vertical-center-modal staticBackdropLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable modal-xl modal-fullscreen-xl-down">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary text-white">
                <h4 class="modal-title text-white" id="detalleExpedienteLabel">
                    Modal Heading
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detalleExpedienteBody">
                <h5 class="mt-0">Primary Background</h5>
                <p>
                    Cras mattis consectetur purus sit amet
                    fermentum. Cras justo odio, dapibus ac facilisis
                    in, egestas eget quam. Morbi leo risus, porta ac
                    consectetur ac, vestibulum at eros.
                </p>
                <p>
                    Praesent commodo cursus magna, vel scelerisque
                    nisl consectetur et. Vivamus sagittis lacus vel
                    augue laoreet rutrum faucibus dolor auctor.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <?= lang('Main.cerrar') ?>
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
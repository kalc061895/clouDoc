

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <h4 class="card-title mb-0"><?= lang('Main.expedienteRecibidoTitle') ?></h4>
    </div>
    <div class="card-body ">
        <div class="table-responsive mb-4 ">
            <table class="table table-sm mb-0 " id="expedientesTable">
                <thead class="text-dark fs-4">
                    <tr>
                        <th>
                            <h6 class="fs-4 fw-semibold mb-0"><?= lang('Main.expediente') ?></h6>
                        </th>
                        <th>
                            <h6 class="fs-4 fw-semibold mb-0"><?= lang('Main.remitente') ?></h6>
                        </th>
                        <th>
                            <h6 class="fs-4 fw-semibold mb-0"><?= lang('Main.asunto') ?></h6>
                        </th>
                        <th>
                            <h6 class="fs-4 fw-semibold mb-0"><?= lang('Main.estado') ?></h6>
                        </th>
                        <th>
                            <h6 class="fs-4 fw-semibold mb-0"><?= lang('Main.fecha') ?></h6>
                        </th>
                        <th>
                            <h6 class="fs-4 fw-semibold mb-0"><?= lang('Main.opciones') ?></h6>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($expediente) : ?>
                        <?php foreach ($expediente as $item) : ?>

                            <tr>
                                <td>
                                    <h6 class="fs-4 fw-semibold mb-0"><?= $item->numero_expediente; ?></h6>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="assets/images/profile/user-3.jpg" class="rounded-circle" width="40" height="40" />
                                        <div class="ms-3">
                                            <h6 class="fs-4 fw-semibold mb-0"><?= $item->nombre ?></h6>
                                            <span class="fw-normal"><?= $item->correo_electronico ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td style="text-wrap: pretty;">
                                    <p class="mb-0 fw-normal fs-4"><?= $item->asunto ?></p>
                                </td>
                                <td>
                                    <span class="badge bg-danger text-white"><?= lang('Main.NoLeido'); ?></span>
                                </td>
                                <td>
                                    <h6 class="fs-4 fw-semibold mb-0"><?= substr($item->fecha_recepcion, 0, 10); ?></h6>

                                </td>
                                <td>
                                    <div class="button-group">
                                        <button type="button" class="btn btn-rounded btn-info" title="<?= lang('Main.revisar') ?>" onclick="RevisarExpediente(<?= $item->id ?>)"><i class="ti ti-eye fs-5"></i> </button>
                                        <button type="button" class="btn btn-rounded btn-success" title="<?= lang('Main.recibir') ?>"><i class="ti ti-check fs-5"></i></button>

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
    $("#expedientesTable").DataTable({
        order: [
            [0, "desc"]
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
            error: function(e){
                Swal.fire({
                    type: "error",
                    html: "Vuelva a Intentarlo nuevamente",
                    showConfirmButton: true,
                });
            },
        });
    }
</script>

<!-- Primary Header Modal -->
<div id="detalleExpediente" class="modal fade mw-100" tabindex="-1" aria-labelledby="detalleExpedienteLabel vertical-center-modal staticBackdropLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
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
                    <?=lang('Main.cerrar')?>
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
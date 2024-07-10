<?= $this->extend('layouts/external'); ?>

<?= $this->section('title'); ?>
<?= lang('External.nuevoTramite') ?>
<?= $this->endSection(); ?>

<?= $this->section('pageStyles'); ?>
<link rel="stylesheet" href="../assets/libs/dropzone/dist/min/dropzone.min.css">
<?= $this->endSection(); ?>



<?= $this->section('content'); ?>
<!-- CODE HERE -->

<div class="row justify-content-center">

    <div class="col-12">
        <div class="card mb-0">
            <div class="text-bg-primary p-4 rounded-3 rounded-bottom-0">
                <div class="text-center text-white display-6">
                    <?= lang('External.titleLargeNew') ?>
                </div>
            </div>

            <div class="card-body">
                <div class="row justify-content-center ">

                    <div class="col-xl-6 col-lg-8 col-md-8 col-sm-12">
                        <div class="card">
                            <div class="card-header text-bg-primary">
                                <h4 class="card-title text-white mb-0">
                                    <?= lang('External.dataTitleNew') ?>
                                </h4>
                            </div>
                            <div class="card-body">
                                <p class="card-subtitle">
                                    <?= lang('External.descriptionNew') ?>
                                </p>
                                <form class="floating-labels mt-4 pt-2" id="entidadForm">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <select class="form-control form-select" id="tipoNew" required>

                                                    <option value="Persona"><?= lang('External.personaNew') ?></option>
                                                    <option value="Empresa"><?= lang('External.juridicaNew') ?></option>
                                                    <option value="Empresa"><?= lang('External.entidadNew') ?></option>
                                                </select>
                                                <span class="bar"></span>
                                                <label for="tipoNew"><?= lang('External.tipoNew') ?>*</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <select class="form-control form-select" name="tipoDocNew" id="tipoDocNew" required>
                                                    <?php foreach ($tipoDocumento as $item) : ?>
                                                        <option value="<?= $item['id'] ?>"><?= $item['nombre'] ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <span class="bar"></span>
                                                <label for="tipoDocNew"><?= lang('External.docTipoNew') ?>*</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <input type="text" class="form-control" id="numDocNew" required name="numDocNew">
                                                <span class="bar"></span>
                                                <label for="numDocNew"><?= lang('External.docNew') ?>*</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group mb-4">
                                                <input type="text" class="form-control" id="nombreNew" required name="nombreNew">
                                                <span class="bar"></span>
                                                <label for="nombreNew"><?= lang('External.nombreNew') ?>*</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <input type="text" class="form-control" id="telefonoNew" name="telefonoNew" required>
                                                <span class="bar"></span>
                                                <label for="telefonoNew"><?= lang('External.telefonoNew') ?>*</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <input type="text" class="form-control" id="correoNew" required name="correoNew">
                                                <span class="bar"></span>
                                                <label for="correoNew"><?= lang('External.correoNew') ?>*</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <input type="text" class="form-control" id="direccionNew" name="direccionNew">
                                                <span class="bar"></span>
                                                <label for="direccionNew"><?= lang('External.direccionNew') ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header text-bg-primary">
                                <h4 class="card-title text-white mb-0">
                                    <?= lang('External.expedienteTitleNew') ?>
                                </h4>
                            </div>
                            <div class="card-body">
                                <form id="externalForm" class="floating-labels mt-4 pt-2" action="<?= site_url('nuevoexpediente') ?>" method="post" enctype="multipart/form-data">

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <select class="form-control form-select" id="tipoDocExp" required name="tipoDocExp">
                                                    <?php foreach ($tipoExpediente as $item) : ?>
                                                        <option value="<?= $item['id'] ?>"><?= $item['nombre'] ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <span class="bar"></span>
                                                <label for="tipoDocExp"><?= lang('External.tipoDocExp') ?>*</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <input type="text" class="form-control" id="numDocExp" required name="numDocExp">
                                                <span class="bar"></span>
                                                <label for="numDocExp"><?= lang('External.numDocExp') ?>*</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-4">
                                                <input type="text" class="form-control" id="folioDocExp" required name="folioDocExp">
                                                <span class="bar"></span>
                                                <label for="folioDocExp"><?= lang('External.folioDocExp') ?>*</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-4">
                                                <textarea class="form-control" rows="3" id="asuntoDocExp" required name="asuntoDocExp"></textarea>
                                                <span class="bar"></span>
                                                <label for="asuntoDocExp"><?= lang('External.asuntoDocExp') ?>*</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <input type="file" class="form-control form-control-file" id="anexoExp" required name="anexoExp" accept=".pdf">
                                                <span class="bar"></span>
                                                <label for="anexoExp"><?= lang('External.anexoExp') ?></label>
                                                <small class="form-control-feedback">
                                                    <?= lang('External.anexoHelpExp') ?>*
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-12 justify-content-center">
                                            <div class="form-group mb-4">
                                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                                    <i class="ti ti-send fs-4"></i>
                                                    <?= lang('External.sendButton') ?>
                                                </button>
                                            </div>

                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('pageScripts'); ?>
<script src="../assets/libs/dropzone/dist/min/dropzone.min.js"></script>
<script>
    $('.floating-labels .form-control').on('focus blur', function(e) {
        $(this).parents('.form-group').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));
    }).trigger('blur');
</script>

<script>
    $(document).ready(function() {



        $('#externalForm').submit(function(event) {
            event.preventDefault(); // Prevenir el envío normal del formulario

            let isValid = true;
            let errorMessage = '';

            // Validación del primer formulario
            const tipoNew = $('#tipoNew').val();
            const tipoDocNew = $('#tipoDocNew').val();
            const numDocNew = $('#numDocNew').val();
            const nombreNew = $('#nombreNew').val();
            const telefonoNew = $('#telefonoNew').val();
            const correoNew = $('#correoNew').val();
            const direccionNew = $('#direccionNew').val();

            if (!tipoNew || !tipoDocNew || !numDocNew || !nombreNew || !telefonoNew || !correoNew || !direccionNew) {
                isValid = false;
                errorMessage += 'Por favor, complete todos los campos del primer formulario.\n';
            }

            // Validación del segundo formulario
            const tipoDocExp = $('#tipoDocExp').val();
            const numDocExp = $('#numDocExp').val();
            const folioDocExp = $('#folioDocExp').val();
            const asuntoDocExp = $('#asuntoDocExp').val();
            const anexoExp = $('#anexoExp').val();

            if (!tipoDocExp || !numDocExp || !folioDocExp || !asuntoDocExp || !anexoExp) {
                isValid = false;
                errorMessage += 'Por favor, complete todos los campos del segundo formulario.\n';
            }

            if (!isValid) {
                alert(errorMessage);
                return;
            }

            // Enviar el formulario mediante AJAX
            var formData = new FormData(this);
            formData.append('tipoNew', tipoNew);
            formData.append('tipoDocNew', tipoDocNew);
            formData.append('numDocNew', numDocNew);
            formData.append('nombreNew', nombreNew);
            formData.append('telefonoNew', telefonoNew);
            formData.append('correoNew', correoNew);
            formData.append('direccionNew', direccionNew);
            formData.append('tipoDocExp', tipoDocExp);
            formData.append('numDocExp', numDocExp);
            formData.append('folioDocExp', folioDocExp);
            formData.append('asuntoDocExp', asuntoDocExp);
            formData.append('anexoExp', $('#anexoExp')[0].files[0]);
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#responseCard').removeClass('d-none');
                    $('#responseTitle').text('Éxito');
                    $('#responseMessage').text('Formulario enviado correctamente.');
                },
                error: function(xhr, status, error) {
                    $('#responseCard').removeClass('d-none');
                    $('#responseTitle').text('Error');
                    $('#responseMessage').text('Ocurrió un error al enviar el formulario: ' + error);
                }
            });
        });
    });
</script>

<?= $this->endSection(); ?>
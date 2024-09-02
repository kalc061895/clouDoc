<div class="card-body" id="formContent">
    <form id="expedienteForm" action="<?= site_url('nuevoexpediente') ?>" method="post" enctype="multipart/form-data">

        <div class="row justify-content-center ">
            <div class="col-xl-6 col-lg-8 col-md-8 col-sm-12">
                <div class="card">
                    <div class="card-header text-bg-primary">
                        <h4 class="card-title text-white mb-0">
                            <?= lang('External.dataTitleNew') ?>
                        </h4>
                    </div>
                    <div class="card-body ">
                        <p class="card-subtitle">
                            <?= lang('External.descriptionNew') ?>
                        </p>
                        <div class="floating-labels mt-4 pt-2">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-4">
                                        <select class="form-control form-select" id="tipoNew" required name="tipoNew">

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
                                        <input type="phone" class="form-control" id="telefonoNew" name="telefonoNew" required>
                                        <span class="bar"></span>
                                        <label for="telefonoNew"><?= lang('External.telefonoNew') ?>*</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-4">
                                        <input type="email" class="form-control" id="correoNew" required name="correoNew">
                                        <span class="bar"></span>
                                        <label for="correoNew"><?= lang('External.correoNew') ?>*</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-4">
                                        <input type="address" class="form-control" id="direccionNew" name="direccionNew" required>
                                        <span class="bar"></span>
                                        <label for="direccionNew"><?= lang('External.direccionNew') ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header text-bg-primary">
                        <h4 class="card-title text-white mb-0">
                            <?= lang('External.expedienteTitleNew') ?>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-primary text-primary role=" alert"="">
                            <strong><?= lang('External.nota') ?>: </strong> <?= lang('External.notaBody') ?> <a href="<?= base_url('tupaexpediente') ?>"><strong class="badge text-bg-info"><?= lang('External.notaLink') ?></strong> </a>
                        </div>
                        <div class="floating-labels mt-4 pt-2">

                            <div class="row">
                                <div class="col-md-6">
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
                                <div class="col-md-2">
                                    <div class="form-group mb-4">
                                        <input type="text" class="form-control" id="numDocExp" name="numDocExp" value="s/n">
                                        <span class="bar"></span>
                                        <label for="numDocExp"><?= lang('External.numDocExp') ?>*</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-4">
                                        <input type="text" min="1" class="form-control" id="folioDocExp" required name="folioDocExp">
                                        <span class="bar"></span>
                                        <label for="folioDocExp"><?= lang('External.folioDocExp') ?>*</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-4">
                                        <textarea class="form-control" rows="2" id="asuntoDocExp" required name="asuntoDocExp" maxlength="250"></textarea>
                                        <span class="bar"></span>
                                        <label for="asuntoDocExp"><?= lang('External.asuntoDocExp') ?>*</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-4">
                                        <input type="file" class="form-control form-control-file" id="anexoExp" required name="anexoExp" accept=".pdf" placeholder=".pdf">
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

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="card-body" id="formResponse" hidden></div>


<script>
    $('.floating-labels .form-control').on('focus blur', function(e) {
        $(this).parents('.form-group').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));
    }).trigger('blur');
</script>
<script>
    $(document).ready(function() {
        $('#expedienteForm').on('submit', function(e) {
            e.preventDefault(); // Previene el envío del formulario de forma convencional
            // Validar el archivo seleccionado
            if (!validateFile($('#anexoExp')[0].files[0])) {
                Swal.fire(
                    "Alerta",
                    "El archivo tiene que estar en formato .PDF y menor a 20MB.",
                    "error"
                );
                return;
            }
            var formData = new FormData($('#expedienteForm')[0]); // Crea un objeto FormData con el formulario

            Swal.fire({
                title: "Enviando",
                html: `<div id='progressWrapper' style='display:none;' class='mb-2'>
                        <progress id='progressBar' value='0' max='100'></progress>
                        <span id='progressText'>0%</span>
                        </div> <div class='d-flex justify-content-center'>
                        <div class='spinner-border' role='status'>
                            <span class='visually-hidden'>Loading...</span>
                        </div>
                    </div>`,
                showConfirmButton: false
            });

            var progressBar = $('#progressBar');
            var progressText = $('#progressText');
            var progressWrapper = $('#progressWrapper');

            progressWrapper.show();

            $.ajax({
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                            progressBar.val(percentComplete);
                            progressText.text(percentComplete + '%');
                        }
                    }, false);
                    return xhr;
                },
                url: "<?= base_url('nuevoexpediente') ?>", // Cambia esto por la URL a la que quieres enviar el formulario
                type: 'POST',
                data: formData,
                contentType: false, // Es necesario establecer en false para evitar que jQuery configure el tipo de contenido
                processData: false, // Es necesar0io establecer en false para que jQuery no procese los datos
                success: function(response) {
                    response = JSON.parse(response);
                    Swal.fire(
                        "Enviado",
                        "Se registro correctamente su Expediente.",
                        "success"
                    );
                    $('#formContent').attr('hidden', '');
                    $('#formResponse').html(response.html);
                    $('#formResponse').removeAttr('hidden');
                    console.log(response.html); // Muestra la respuesta en la consola
                },
                error: function(xhr, status, error) {

                    Swal.fire(
                        "Alerta",
                        "Hubo un Error, Vuelva a intentarlo nuevamente.",
                        "error"
                    ); // Muestra el error en la consola
                }
            });
        });

        function validateFile(file) {
            var validTypes = ['application/pdf'];
            var maxSize = 20 * 1024 * 1024; // 20 MB en bytes

            if (!validTypes.includes(file.type)) {
                return false;
            }

            if (file.size > maxSize) {
                return false;
            }

            return true;
        }
    });
</script>
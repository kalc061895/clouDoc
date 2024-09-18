<div class="card-body" id="formContent">
    <form id="documentoForm" action="<?= site_url('/documentos/guardarDocumento') ?>" method="post" enctype="multipart/form-data">

        <div class="row justify-content-center ">
            <div class="col-xl-6 col-lg-8 col-md-8 col-sm-12">
                <div class="card" hidden>
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
                                            <option value="Interno">Expediente Interno </option>
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
                                            <option value="DNI">DNI</option>
                                        </select>
                                        <span class="bar"></span>
                                        <label for="tipoDocNew"><?= lang('External.docTipoNew') ?>*</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-4">
                                        <input type="text" class="form-control" id="numDocNew" required name="numDocNew" value="<?= $infoUsuario->dni ?>">
                                        <span class="bar"></span>
                                        <label for="numDocNew"><?= lang('External.docNew') ?>*</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mb-4">
                                        <input type="text" class="form-control" id="nombreNew" required name="nombreNew" value="<?= $infoUsuario->paterno . ' ' . $infoUsuario->materno . ' ' . $infoUsuario->nombres ?>">
                                        <span class="bar"></span>
                                        <label for="nombreNew"><?= lang('External.nombreNew') ?>*</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-4">
                                        <input type="phone" class="form-control" id="telefonoNew" name="telefonoNew" required value="<?= $infoUsuario->telefono ?>">
                                        <span class="bar"></span>
                                        <label for="telefonoNew"><?= lang('External.telefonoNew') ?>*</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-4">
                                        <input type="email" class="form-control" id="correoNew" required name="correoNew" value="<?= $infoUsuario->email ?>">
                                        <span class="bar"></span>
                                        <label for="correoNew"><?= lang('External.correoNew') ?>*</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-4">
                                        <input type="address" class="form-control" id="direccionNew" name="direccionNew" required value="-">
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

                        <div class=" mt-4 pt-2">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-4">
                                        <label for="oficinaDestino"><?= lang('External.oficinaDestino') ?>*</label>
                                        <select class="select2 form-control " id="oficinaDestino" required name="oficinaDestino">
                                            <?php foreach ($oficina as $item) : ?>
                                                <?php
                                                // Contar cuántos hijos tiene esta oficina
                                                $children = array_filter($oficina, function ($ofi) use ($item) {
                                                    return $ofi['oficina_padre_id'] == $item['id'];
                                                });
                                                ?>
                                                <?php if (count($children) > 0) : ?>
                                                    <optgroup label="<?= $item['nombre'] ?>">
                                                        <?php foreach ($children as $ofi) : ?>
                                                            <option value="<?= $ofi['id'] ?>"><?= $ofi['nombre'] ?></option>
                                                        <?php endforeach ?>
                                                    </optgroup>
                                                <?php endif ?>
                                            <?php endforeach ?>
                                        </select>

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="tipoDocExp"><?= lang('External.tipoDocExp') ?>*</label>
                                        <select class="form-control form-select" id="tipoDocExp" required name="tipoDocExp">
                                            <?php foreach ($tipoExpediente as $item) : ?>
                                                <option value="<?= $item['id'] ?>"><?= $item['nombre'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-4">
                                        <label for="numDocExp"><?= lang('External.numDocExp') ?>*</label>
                                        <input type="text" class="form-control" id="numDocExp" name="numDocExp" value="s/n" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-3">
                                        <label for="folioDocExp"><?= lang('External.folioDocExp') ?>*</label>
                                        <input type="text" min="1" class="form-control" id="folioDocExp" required name="folioDocExp">
                                        <span class="bar"></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="asuntoDocExp"><?= lang('External.asuntoDocExp') ?>*</label>
                                        <textarea class="form-control" rows="2" id="asuntoDocExp" required name="asuntoDocExp" maxlength="250"></textarea>
                                        <span class="bar"></span>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="documentoReferencia"><?= lang('External.documentoReferencia') ?></label>
                                        <select class="select2-tag form-control" id="documentoReferencia" multiple="multiple" name="documentoReferencia[]">
                                            <?php foreach ($oficina as $item) : ?>

                                                <option value="<?= $item['id'] ?>"><?= $item['nombre'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                        <span class="bar"></span>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="oficinaConCopia"><?= lang('External.conCopia') ?></label>
                                        <select class="select2 form-control" id="oficinaConCopia" multiple="multiple" name="oficinaConCopia[]">
                                            <?php foreach ($oficina as $item) : ?>

                                                <option value="<?= $item['id'] ?>"><?= $item['nombre'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                        <span class="bar"></span>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-4">
                                    <button class="btn btn-sm btn-secondary" id="generarDocBtn"> Generar Plantilla</button>
                                </div>
                                <script>
                                    $('#generarDocBtn').on('click', function(e) {
                                        e.preventDefault();
                                        $.ajax({
                                            url: '<?= base_url('documentos/generar') ?>',
                                            method: 'POST',
                                            success: function(response) {
                                                var newWindow = window.open('<?= base_url('documentos/generar') ?>', '_blank');;

                                            },
                                            error: function(xhr, status, error) {
                                                console.error(error);
                                                alert('Error en la solicitud AJAX.');
                                            }
                                        });
                                    });
                                </script>
                                <div class="col-md-12">
                                    <div class="form-group mb-4">
                                        <label for="anexoExp"><?= lang('External.anexoExp') ?></label>
                                        <input type="file" class="form-control form-control-file" id="anexoExp" required name="anexoExp" accept=".pdf" placeholder=".pdf">
                                        <span class="bar"></span>
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
        $('#documentoForm').on('submit', function(e) {
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
            var formData = new FormData($('#documentoForm')[0]); // Crea un objeto FormData con el formulario

            Swal.fire({
                title: "Enviando",
                html: `<div id='progressWrapper' style='display:none;' class='mb-2'>
                        <progress id='progressBar' value='0' max='100'></progress>
                        <span id='progressText'>0%</span>
                        </div> <div class='d-flex justify-content-center'>
                        <div class='spinner-border' role='status'>
                            <span class='visually-hidden'>Cargando...</span>
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
                url: "<?= base_url('documentos/guardar') ?>", // Cambia esto por la URL a la que quieres enviar el formulario
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

    $('#tipoDocExp').change(function() {
        // Obtiene el valor seleccionado del select
        var tipoDoc = $(this).val();

        // Realiza la llamada AJAX
        $.ajax({
            url: '<?= base_url('/documentos/getNumeracion') ?>',
            // Cambia esta URL por la ruta de tu API
            type: 'POST', // Cambia a 'GET' si es necesario
            data: {
                tipoDocExp: tipoDoc
            }, // Envía el valor seleccionado al servidor
            success: function(respuesta) {
                // Establece el valor de la respuesta en el campo input
                $('#numDocExp').val(respuesta.numDocExp + 1);
            },
            error: function() {
                console.log('Error en la llamada AJAX');
            }
        });
    });
    $(".select2").select2();
    $(".select2-tag").select2({
        tags: true
    });
</script>
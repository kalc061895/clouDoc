<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?>
Registro Integral de Personal
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-0">Registro de Personal (Nuevo Ingreso)</h4>
        <h6 class="card-subtitle mb-3 text-muted">Siga los pasos secuenciales para dar de alta al personal en la Red de
            Salud.</h6>

        <form id="wizardPersonal" class="mt-5">
            <?= csrf_field() ?>

            <div id="example-basic">
                <!-- PASO 1: DATOS PERSONALES (casis_persona) -->
                <h3>Datos Personales</h3>
                <section>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Tipo Documento <span
                                    class="text-danger">*</span></label>
                            <select class="form-select required" name="per_tdi_ide" id="per_tdi_ide">
                                <option value="">Cargando...</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">N° Documento <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control required" name="per_numero_documento"
                                id="per_numero_documento" placeholder="Ej. 70654321" minlength="8" maxlength="20">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">RUC</label>
                            <input type="text" class="form-control" name="per_ruc" placeholder="Ej. 10706543211"
                                maxlength="15" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Apellido Paterno</label>
                            <input type="text" class="form-control text-uppercase" name="per_paterno"
                                placeholder="Ej. MAMANI" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Apellido Materno</label>
                            <input type="text" class="form-control text-uppercase" name="per_materno"
                                placeholder="Ej. QUISPE" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Nombres <span class="text-danger">*</span></label>
                            <input type="text" class="form-control required text-uppercase" name="per_nombre"
                                placeholder="Ej. JUAN CARLOS" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Sexo</label>
                            <select class="form-select" name="per_sexo" required>
                                <option value="">Seleccione...</option>
                                <option value="M">MASCULINO</option>
                                <option value="F">FEMENINO</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" name="per_fecha_nacimiento" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Teléfono / Celular</label>
                            <input type="text" class="form-control" name="per_telefono" placeholder="Ej. 951234567" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Correo Electrónico</label>
                            <input type="email" class="form-control" name="per_email" placeholder="juan@gmail.com" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Lugar de Nacimiento</label>
                            <input type="text" class="form-control" name="per_lugar_nacimiento"
                                placeholder="Ciudad / Distrito" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Residencia Actual</label>
                            <input type="text" class="form-control" name="per_residencia"
                                placeholder="Dirección completa" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Estado Civil</label>
                            <select class="form-select" name="per_estadocivil" required>
                                <option value="">Seleccione...</option>
                                <option value="SOLTERO(A)">SOLTERO(A)</option>
                                <option value="CASADO(A)">CASADO(A)</option>
                                <option value="DIVORCIADO(A)">DIVORCIADO(A)</option>
                                <option value="VIUDO(A)">VIUDO(A)</option>
                            </select>
                        </div>
                    </div>
                </section>

                <!-- PASO 2: ASIGNACIÓN LABORAL (casis_personal) -->
                <h3>Información Laboral</h3>
                <section>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Código Tarjeta/Planilla <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control required" name="perl_codigo"
                                placeholder="Ej. P-10254" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-bold">Establecimiento <span
                                    class="text-danger">*</span></label>
                            <select class="form-select required" name="perl_est_ide" id="perl_est_ide" required>
                                <option value="">Cargando...</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Modalidad Contrato <span
                                    class="text-danger">*</span></label>
                            <select class="form-select required" name="perl_mco_ide" id="perl_mco_ide" required>
                                <option value="">Cargando...</option>
                            </select>
                        </div>

                        
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Oficina / Servicio</label>
                            <select class="form-select" name="perl_ofi_ide" id="perl_ofi_ide" required>
                                <option value="">Seleccione un establecimiento...</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Cargo Estructurado</label>
                            <select class="form-select" name="perl_car_ide" id="perl_car_ide" required>
                                <option value="">Cargando...</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Fecha de Inicio <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control required" name="perl_fecha_inicio" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Fecha de Término</label>
                            <input type="date" class="form-control" name="perl_fecha_termino" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">N° de Plaza / CAP</label>
                            <input type="text" class="form-control" name="perl_plaza" placeholder="Ej. PLAZA-0045" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Nivel Remunerativo</label>
                            <input type="text" class="form-control" name="perl_nivel" placeholder="Ej. MC-1" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Observaciones Laborales</label>
                            <textarea class="form-control" name="perl_observacion" rows="2"
                                placeholder="Detalles de destacos, condiciones especiales..."></textarea>
                        </div>
                    </div>
                </section>

                <!-- PASO 3: HISTORIAL PROFESIONAL (casis_personal_profesion) -->
                <h3>Historial Profesional</h3>
                <section>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Profesión / Carrera <span
                                    class="text-danger">*</span></label>
                            <select class="form-select required" name="pp_pro_ide" id="pp_pro_ide">
                                <option value="">Cargando...</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Colegio Profesional</label>
                            <select class="form-select" name="pp_col_ide" id="pp_col_ide">
                                <option value="">Cargando...</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">N° Colegiatura</label>
                            <input type="text" class="form-control" name="pp_numero_colegiatura"
                                placeholder="Ej. CMP-84521">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Segunda Especialidad</label>
                            <select class="form-select" name="pp_se_ide" id="pp_se_ide">
                                <option value="">Cargando...</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">N° RNE (Especialidad)</label>
                            <input type="text" class="form-control" name="pp_rne" placeholder="Ej. RNE-23145">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Estado Habilitación</label>
                            <select class="form-select" name="pp_habilitado">
                                <option value="1">HABILITADO</option>
                                <option value="0">NO HABILITADO</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Fecha de Habilitación</label>
                            <input type="date" class="form-control" name="pp_fecha_habilitacion">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Fecha de Vencimiento Control</label>
                            <input type="date" class="form-control" name="pp_fecha_vencimiento">
                        </div>
                    </div>
                </section>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>

<script>
    const URL_SELECTS = '<?= base_url('asistencia/personal/select') ?>';
    const URL_API = '<?= base_url('asistencia/personal/api') ?>';


    $(document).ready(function () {
        const form = $("#wizardPersonal");

        // 1. INICIALIZACIÓN DEL WIZARD PRIMERO (Para que prepare el DOM)
        $("#example-basic").steps({
            headerTag: "h3",
            bodyTag: "section",
            transitionEffect: "fade",
            autoFocus: true,
            labels: {
                finish: "Finalizar y Registrar",
                next: "Siguiente",
                previous: "Anterior"
            },
            onStepChanging: function (event, currentIndex, newIndex) {
                if (currentIndex > newIndex) return true;
                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
            },
            onFinishing: function (event, currentIndex) {
                form.validate().settings.ignore = ":disabled";
                return form.valid();
            },
            onFinished: function (event, currentIndex) {
                ejecutarGuardadoWizard(form);
            }
        });

        // 2. CONFIGURACIÓN DE VALIDACIÓN
        form.validate({
            errorClass: 'is-invalid',
            validClass: 'is-valid',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-12').append(error);
            }
        });

        // 3. CARGA DE SELECTORES GENERALES
        cargarSelect(`${URL_SELECTS}/tipos-documento`, '#per_tdi_ide');
        cargarSelect(`${URL_SELECTS}/establecimientos`, '#perl_est_ide');
        cargarSelect(`${URL_SELECTS}/modalidades-contrato`, '#perl_mco_ide');
        cargarSelect(`${URL_SELECTS}/cargos`, '#perl_car_ide');
        cargarSelect(`${URL_SELECTS}/profesiones`, '#pp_pro_ide');

        establecerSelectNinguno('#pp_col_ide');
        establecerSelectNinguno('#pp_se_ide');
        $('#pp_numero_colegiatura, #pp_rne').prop('disabled', true).val('');


        // 4. CORRECCIÓN CRÍTICA: DELEGACIÓN DEL EVENTO CHANGE para Establecimiento
        $(document).on('change', '#perl_est_ide', function () {
            const estId = $(this).val();
            if (estId) {

                cargarSelect(`${URL_SELECTS}/oficinas?est_ide=${estId}`, '#perl_ofi_ide');
            } else {

                $('#perl_ofi_ide').html('<option value="">Seleccione un establecimiento...</option>');
            }
        });

        // 5. DELEGACIÓN DEL EVENTO CHANGE para Profesión (por seguridad, apliquemos lo mismo aquí)
        $(document).on('change', '#pp_pro_ide', function () {
            const proId = $(this).val();

            if (!proId) {
                establecerSelectNinguno('#pp_col_ide');
                establecerSelectNinguno('#pp_se_ide');
                $('#pp_numero_colegiatura, #pp_rne').prop('disabled', true).val('');
                return;
            }

            // Bloque AJAX de Colegios Profesionales
            $.ajax({
                url: `${URL_SELECTS}/colegios?pro_ide=${proId}`,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.data && response.data.length > 0) {
                        let options = '<option value="">Seleccione Colegio...</option>';
                        response.data.forEach(item => {
                            options += `<option value="${item.id}">${item.nombre}</option>`;
                        });
                        $('#pp_col_ide').html(options).prop('disabled', false);
                        $('#pp_numero_colegiatura').prop('disabled', false);
                    } else {
                        establecerSelectNinguno('#pp_col_ide');
                        $('#pp_numero_colegiatura').prop('disabled', true).val('');
                    }
                }
            });

            // Bloque AJAX de Segunda Especialidad
            $.ajax({
                url: `${URL_SELECTS}/especialidades?pro_ide=${proId}`,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.data && response.data.length > 0) {
                        let options = '<option value="">Seleccione Especialidad...</option>';
                        response.data.forEach(item => {
                            options += `<option value="${item.id}">${item.nombre}</option>`;
                        });
                        $('#pp_se_ide').html(options).prop('disabled', false);
                        $('#pp_rne').prop('disabled', false);
                    } else {
                        establecerSelectNinguno('#pp_se_ide');
                        $('#pp_rne').prop('disabled', true).val('');
                    }
                }
            });
        });
    });
    function cargarSelect(url, elementId) {
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                let options = '<option value="">Seleccione...</option>';
                if (response.data && response.data.length > 0) {
                    response.data.forEach(item => {
                        options += `<option value="${item.id}">${item.nombre}</option>`;
                    });
                } else {
                    options = '<option value="">Sin registros disponibles</option>';
                }
                $(elementId).html(options);
            },
            error: function () {
                $(elementId).html('<option value="">Error al cargar catálogo</option>');
            }
        });
    }

    // Función Helper para resetear a "Ninguno"
    function establecerSelectNinguno(elementId) {
        $(elementId).html('<option value="0">NINGUNO</option>').prop('disabled', true);
    }

    function ejecutarGuardadoWizard(formElement) {
        Swal.fire({
            title: '¿Confirmar Registro de Personal?',
            text: "Se guardarán simultáneamente los datos personales, laborales y profesionales.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, registrar',
            cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                const dataPayload = {};
                formElement.serializeArray().forEach(item => {
                    dataPayload[item.name] = item.value;
                });

                return $.ajax({
                    url: `${URL_API}/guardar-wizard`,
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(dataPayload),
                    dataType: 'json'
                }).catch(xhr => {
                    let errorMsg = 'Error en el servidor al guardar.';
                    if (xhr.responseJSON && xhr.responseJSON.messages) {
                        errorMsg = Object.values(xhr.responseJSON.messages).join('<br>');
                    }
                    Swal.showValidationMessage(errorMsg);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed && result.value.status === 'success') {
                toastr.success('Personal dado de alta correctamente en la Red de Salud.');
                setTimeout(() => {
                    window.location.href = '<?= base_url('asistencia/personal') ?>';
                }, 1500);
            }
        });
    }

</script>
<?= $this->endSection() ?>
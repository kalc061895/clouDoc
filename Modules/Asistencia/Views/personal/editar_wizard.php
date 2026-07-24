<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?> Editar Personal <?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Modificar Expediente del Personal</h4>
        <h6 class="card-subtitle text-muted small mb-4">Actualiza la información personal, contractual y académica del trabajador.</h6>

        <form id="wizard-editar" class="wizard-content" autocomplete="off">
            <!-- ID Oculto para la actualización -->
            <input type="hidden" id="perl_ide" name="perl_ide" value="<?= $personal['perl_ide'] ?>">

            <!-- PASO 1: DATOS PERSONALES -->
            <h3>Datos Personales</h3>
            <section>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Tipo Documento <span class="text-danger">*</span></label>
                        <select class="form-select required" id="per_tdi_ide" name="per_tdi_ide"></select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Número Documento <span class="text-danger">*</span></label>
                        <input type="text" class="form-control required" id="per_numero_documento" name="per_numero_documento" value="<?= $personal['per_numero_documento'] ?? '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">RUC</label>
                        <input type="text" class="form-control" id="per_ruc" name="per_ruc" value="<?= $personal['per_ruc'] ?? '' ?>" maxlength="15">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Apellido Paterno <span class="text-danger">*</span></label>
                        <input type="text" class="form-control required" name="per_paterno" value="<?= $personal['per_paterno'] ?? '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Apellido Materno <span class="text-danger">*</span></label>
                        <input type="text" class="form-control required" name="per_materno" value="<?= $personal['per_materno'] ?? '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Nombres <span class="text-danger">*</span></label>
                        <input type="text" class="form-control required" name="per_nombre" value="<?= $personal['per_nombre'] ?? '' ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Sexo <span class="text-danger">*</span></label>
                        <select class="form-select required" id="per_sexo" name="per_sexo">
                            <option value="">Seleccione...</option>
                            <option value="M" <?= (isset($personal['per_sexo']) && $personal['per_sexo'] == 'M') ? 'selected' : '' ?>>MASCULINO</option>
                            <option value="F" <?= (isset($personal['per_sexo']) && $personal['per_sexo'] == 'F') ? 'selected' : '' ?>>FEMENINO</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Fecha de Nacimiento <span class="text-danger">*</span></label>
                        <input type="date" class="form-control required" name="per_fecha_nacimiento" value="<?= $personal['per_fecha_nacimiento'] ?? '' ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Teléfono / Celular</label>
                        <input type="text" class="form-control" id="per_telefono" name="per_telefono" value="<?= $personal['per_telefono'] ?? '' ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Correo Electrónico</label>
                        <input type="email" class="form-control" id="per_email" name="per_email" value="<?= $personal['per_email'] ?? '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Lugar de Nacimiento</label>
                        <input type="text" class="form-control" id="per_lugar_nacimiento" name="per_lugar_nacimiento" value="<?= $personal['per_lugar_nacimiento'] ?? '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Residencia Actual</label>
                        <input type="text" class="form-control" id="per_residencia" name="per_residencia" value="<?= $personal['per_residencia'] ?? '' ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Estado Civil</label>
                        <select class="form-select" id="per_estadocivil" name="per_estadocivil">
                            <option value="">Seleccione...</option>
                            <option value="SOLTERO(A)" <?= (isset($personal['per_estadocivil']) && $personal['per_estadocivil'] == 'SOLTERO(A)') ? 'selected' : '' ?>>SOLTERO(A)</option>
                            <option value="CASADO(A)" <?= (isset($personal['per_estadocivil']) && $personal['per_estadocivil'] == 'CASADO(A)') ? 'selected' : '' ?>>CASADO(A)</option>
                            <option value="DIVORCIADO(A)" <?= (isset($personal['per_estadocivil']) && $personal['per_estadocivil'] == 'DIVORCIADO(A)') ? 'selected' : '' ?>>DIVORCIADO(A)</option>
                            <option value="VIUDO(A)" <?= (isset($personal['per_estadocivil']) && $personal['per_estadocivil'] == 'VIUDO(A)') ? 'selected' : '' ?>>VIUDO(A)</option>
                        </select>
                    </div>
                </div>
            </section>

            <!-- PASO 2: ASIGNACIÓN LABORAL -->
            <h3>Información Laboral</h3>
            <section>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Código Tarjeta/Planilla <span class="text-danger">*</span></label>
                        <input type="text" class="form-control required text-uppercase" name="perl_codigo" value="<?= $personal['perl_codigo'] ?? '' ?>" placeholder="Ej. P-10254">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small fw-bold">Establecimiento (Sede) <span class="text-danger">*</span></label>
                        <select class="form-select required" id="perl_est_ide" name="perl_est_ide">
                            <option value="">Cargando...</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Modalidad Contrato <span class="text-danger">*</span></label>
                        <select class="form-select required" id="perl_mco_ide" name="perl_mco_ide">
                            <option value="">Cargando...</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Oficina / Servicio</label>
                        <select class="form-select" id="perl_ofi_ide" name="perl_ofi_ide">
                            <option value="">Seleccione un establecimiento...</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Cargo Estructurado <span class="text-danger">*</span></label>
                        <select class="form-select required" id="perl_car_ide" name="perl_car_ide">
                            <option value="">Cargando...</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Fecha de Inicio / Ingreso <span class="text-danger">*</span></label>
                        <input type="date" class="form-control required" name="perl_fecha_inicio" value="<?= $personal['perl_fecha_inicio'] ?? '' ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Fecha de Término</label>
                        <input type="date" class="form-control" name="perl_fecha_termino" value="<?= $personal['perl_fecha_termino'] ?? '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">N° de Plaza / CAP</label>
                        <input type="text" class="form-control" name="perl_plaza" value="<?= $personal['perl_plaza'] ?? '' ?>" placeholder="Ej. PLAZA-0045">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Nivel Remunerativo</label>
                        <input type="text" class="form-control" name="perl_nivel" value="<?= $personal['perl_nivel'] ?? '' ?>" placeholder="Ej. MC-1">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label small fw-bold">Observaciones Laborales</label>
                        <textarea class="form-control" name="perl_observacion" rows="2" placeholder="Detalles de destacos, condiciones especiales..."><?= $personal['perl_observacion'] ?? '' ?></textarea>
                    </div>
                </div>
            </section>

            <!-- PASO 3: HISTORIAL PROFESIONAL / COLEGIATURA Y ESPECIALIDAD -->
            <h3>Historial Profesional</h3>
            <section>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Profesión / Carrera Base <span class="text-danger">*</span></label>
                        <select class="form-select required" id="pp_pro_ide" name="pp_pro_ide">
                            <option value="">Cargando...</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Colegio Profesional</label>
                        <select class="form-select" id="pp_col_ide" name="pp_col_ide">
                            <option value="">Cargando...</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">N° de Colegiatura</label>
                        <input type="text" class="form-control" id="pp_numero_colegiatura" name="pp_numero_colegiatura" value="<?= $personal['pp_numero_colegiatura'] ?? '' ?>" placeholder="Ej. CMP-84521">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Segunda Especialidad</label>
                        <select class="form-select" id="pp_se_ide" name="pp_se_ide">
                            <option value="">Cargando...</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Registro Nacional de Especialidad (RNE)</label>
                        <input type="text" class="form-control" id="pp_rne" name="pp_rne" value="<?= $personal['pp_rne'] ?? '' ?>" placeholder="Ej. RNE-23145">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Estado Habilitación</label>
                        <select class="form-select" id="pp_habilitado" name="pp_habilitado">
                            <option value="1" <?= (isset($personal['pp_habilitado']) && $personal['pp_habilitado'] == '1') ? 'selected' : '' ?>>HABILITADO</option>
                            <option value="0" <?= (isset($personal['pp_habilitado']) && $personal['pp_habilitado'] == '0') ? 'selected' : '' ?>>NO HABILITADO</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Fecha de Habilitación</label>
                        <input type="date" class="form-control" id="pp_fecha_habilitacion" name="pp_fecha_habilitacion" value="<?= $personal['pp_fecha_habilitacion'] ?? '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Fecha de Vencimiento Control</label>
                        <input type="date" class="form-control" id="pp_fecha_vencimiento" name="pp_fecha_vencimiento" value="<?= $personal['pp_fecha_vencimiento'] ?? '' ?>">
                    </div>
                </div>
            </section>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>

<script>
    const URL_SELECTS = '<?= base_url('asistencia/personal/select') ?>';
    const URL_UPDATE = '<?= base_url('asistencia/personal/api/actualizar/' . $personal['perl_ide']) ?>';

    // Valores actuales guardados en base de datos para pre-seleccionar
    const VALORES_ACTUALES = {
        tdi_ide: '<?= $personal['per_tdi_ide'] ?>',
        mco_ide: '<?= $personal['perl_mco_ide'] ?>',
        car_ide: '<?= $personal['perl_car_ide'] ?>',
        est_ide: '<?= $personal['perl_est_ide'] ?>',
        ofi_ide: '<?= $personal['perl_ofi_ide'] ?>',
        pro_ide: '<?= $personal['pp_pro_ide'] ?? '' ?>',
        col_ide: '<?= $personal['pp_col_ide'] ?? '' ?>',
        se_ide: '<?= $personal['pp_se_ide'] ?? '' ?>'
    };

    $(document).ready(function() {
        const form = $("#wizard-editar");

        // 1. Inicializar el Plugin jQuery Steps en modo Edición
        form.steps({
            headerTag: "h3",
            bodyTag: "section",
            transitionEffect: "slideLeft",
            autoFocus: true,
            labels: {
                finish: "Guardar Cambios",
                next: "Siguiente",
                previous: "Anterior"
            },
            onStepChanging: function(event, currentIndex, newIndex) {
                if (currentIndex > newIndex) return true; // Permitir retroceso libre
                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
            },
            onFinishing: function(event, currentIndex) {
                form.validate().settings.ignore = ":disabled";
                return form.valid();
            },
            onFinished: function(event, currentIndex) {
                actualizarExpediente();
            }
        });

        // Configuración de alertas de validación
        form.validate({
            errorClass: 'text-danger small mt-1',
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            }
        });

        // 2. Carga y Preselección Asíncrona Secuencial
        inicializarComponentesEdicion();
    });

    // Encapsula las cargas asíncronas respetando los valores actuales
    async function inicializarComponentesEdicion() {
        // Cargas independientes simples
        cargarYSeleccionar(`${URL_SELECTS}/tipos-documento`, '#per_tdi_ide', VALORES_ACTUALES.tdi_ide);
        cargarYSeleccionar(`${URL_SELECTS}/modalidades-contrato`, '#perl_mco_ide', VALORES_ACTUALES.mco_ide);
        cargarYSeleccionar(`${URL_SELECTS}/cargos`, '#perl_car_ide', VALORES_ACTUALES.car_ide);

        // Carga en Cascada: Establecimiento -> Unidades y Oficinas
        await cargarYSeleccionar(`${URL_SELECTS}/establecimientos`, '#perl_est_ide', VALORES_ACTUALES.est_ide);
        if (VALORES_ACTUALES.est_ide) {

            cargarYSeleccionar(`${URL_SELECTS}/oficinas?est_ide=${VALORES_ACTUALES.est_ide}`, '#perl_ofi_ide', VALORES_ACTUALES.ofi_ide);
        }

        // Carga y lógica inteligente de Profesión y Colegiatura
        await cargarYSeleccionar(`${URL_SELECTS}/profesiones`, '#pp_pro_ide', VALORES_ACTUALES.pro_ide);
        if (VALORES_ACTUALES.pro_ide) {
            evaluarDependenciasProfesion(VALORES_ACTUALES.pro_ide, VALORES_ACTUALES.col_ide, VALORES_ACTUALES.se_ide);
        } else {
            establecerSelectNinguno('#pp_col_ide');
            establecerSelectNinguno('#pp_se_ide');
            $('#pp_numero_colegiatura, #pp_rne').prop('disabled', true);
        }

        // Listener para cambios manuales en el establecimiento
        $('#perl_est_ide').on('change', function() {
            const estId = $(this).val();
            if (estId) {

                cargarYSeleccionar(`${URL_SELECTS}/oficinas?est_ide=${estId}`, '#perl_ofi_ide', '');
            }
        });

        // Listener para cambios manuales en la profesión
        $('#pp_pro_ide').on('change', function() {
            evaluarDependenciasProfesion($(this).val(), '', '');
        });
    }

    // Procesa el AJAX inteligente para Colegios y Segundas Especialidades
    function evaluarDependenciasProfesion(proId, colegioSeleccionado = '', especialidadSeleccionada = '') {
        if (!proId) {
            establecerSelectNinguno('#pp_col_ide');
            establecerSelectNinguno('#pp_se_ide');
            $('#pp_numero_colegiatura, #pp_rne').prop('disabled', true).val('');
            return;
        }

        // Cargar Colegio correspondiente
        $.ajax({
            url: `${URL_SELECTS}/colegios?pro_ide=${proId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.data && response.data.length > 0) {
                    let options = '<option value="">Seleccione Colegio...</option>';
                    response.data.forEach(item => {
                        let selected = item.id == colegioSeleccionado ? 'selected' : '';
                        options += `<option value="${item.id}" ${selected}>${item.nombre}</option>`;
                    });
                    $('#pp_col_ide').html(options).prop('disabled', false);
                    $('#pp_numero_colegiatura').prop('disabled', false);
                } else {
                    establecerSelectNinguno('#pp_col_ide');
                    $('#pp_numero_colegiatura').prop('disabled', true).val('');
                }
            }
        });

        // Cargar Segunda Especialidad correspondiente
        $.ajax({
            url: `${URL_SELECTS}/especialidades?pro_ide=${proId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.data && response.data.length > 0) {
                    let options = '<option value="">Seleccione Especialidad...</option>';
                    response.data.forEach(item => {
                        let selected = item.id == especialidadSeleccionada ? 'selected' : '';
                        options += `<option value="${item.id}" ${selected}>${item.nombre}</option>`;
                    });
                    $('#pp_se_ide').html(options).prop('disabled', false);
                    $('#pp_rne').prop('disabled', false);
                } else {
                    establecerSelectNinguno('#pp_se_ide');
                    $('#pp_rne').prop('disabled', true).val('');
                }
            }
        });
    }

    // Helper Asíncrono para poblar selectores y fijar el valor previo
    function cargarYSeleccionar(url, targetId, valueToSelect) {
        return $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                let options = '<option value="">Seleccione una opción...</option>';
                if (response.data) {
                    response.data.forEach(item => {
                        let selected = item.id == valueToSelect ? 'selected' : '';
                        options += `<option value="${item.id}" ${selected}>${item.nombre}</option>`;
                    });
                }
                $(targetId).html(options);
            }
        });
    }

    function establecerSelectNinguno(elementId) {
        $(elementId).html('<option value="0">NINGUNO</option>').prop('disabled', true);
    }

    // Petición Final de Actualización
    function actualizarExpediente() {
        $.ajax({
            url: URL_UPDATE,
            type: 'POST',
            data: $("#wizard-editar").serialize(),
            dataType: 'json',
            beforeSend: function() {
                Swal.fire({
                    title: 'Procesando...',
                    text: 'Actualizando registro en el servidor',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                console.log(response);
                Swal.close();
                if (response.status === 'success') {
                    toastr.success(response.message || 'Datos actualizados correctamente.');
                    setTimeout(() => {
                        //window.location.href = '<?= base_url('asistencia/personal') ?>';
                    }, 1000);
                } else {
                    Swal.fire('Error', response.message || 'Ocurrió un inconveniente.', 'error');
                }
            },
            error: function(xhr) {
                Swal.close();
                let msg = 'Error en el servidor al actualizar.';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                Swal.fire('Error de Operación', msg, 'error');
            }
        });
    }
</script>
<?= $this->endSection() ?>
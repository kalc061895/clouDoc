<!-- Modules/Asistencia/Views/personal/nuevo.php -->
<?= $this->extend('layouts/asistenciaLayout') ?><!-- Reemplaza por tu layout real -->

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <!-- start Step wizard con validación de Personal -->
        <div class="card shadow-sm">
            <div class="card-body wizard-content">
                <h4 class="card-title"><i class="fas fa-user-plus me-2"></i>Registro de Nuevo Personal</h4>
                <p class="card-subtitle mb-4 text-muted">Complete el registro civil antes de proceder con los datos
                    laborales y de asistencia.</p>

                <form action="#" id="wizardAsistencia" class="validation-wizard wizard-circle mt-4" autocomplete="off">

                    <!-- STEP 1: IDENTIDAD CIVIL -->
                    <h6>1. Identidad Civil</h6>
                    <section id="sec-identidad">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="per_tipo_documento">Tipo Documento: <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select required" id="per_tipo_documento"
                                        name="per_tipo_documento">
                                        <option value="DNI">DNI</option>
                                        <option value="CEX">Carnet de Extranjería</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="mb-3">
                                    <label class="form-label" for="per_numero_documento">Número Documento: <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control required" id="per_numero_documento"
                                            name="per_numero_documento" placeholder="Escriba el número..." />
                                        <button class="btn btn-primary" type="button" id="btnVerificarDoc">
                                            <i class="fas fa-search me-1"></i> Verificar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contenedor dinámico de campos civiles. Se desbloquea tras validar que NO existe el documento -->
                        <div id="wrapperDatosCiviles" class="row d-none pt-3 border-top mt-2">
                            <!-- ID Oculto para control en Base de Datos -->
                            <input type="hidden" name="per_ide" id="per_ide">

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="per_paterno">Apellido Paterno: <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control required" id="per_paterno"
                                        name="per_paterno" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="per_materno">Apellido Materno: <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control required" id="per_materno"
                                        name="per_materno" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="per_nombre">Nombres: <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control required" id="per_nombre"
                                        name="per_nombre" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="per_email">Correo Electrónico:</label>
                                    <input type="email" class="form-control" id="per_email" name="per_email"
                                        placeholder="ejemplo@salud.gob.pe" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="per_telefono">Teléfono / Celular:</label>
                                    <input type="tel" class="form-control" id="per_telefono" name="per_telefono" />
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- STEP 2: DATOS LABORALES Y ASISTENCIA -->
                    <h6>2. Asignación Laboral</h6>
                    <section id="sec-laboral">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="perl_est_ide">Establecimiento de Salud: <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select required" id="perl_est_ide" name="perl_est_ide">
                                        <option value="">-- Seleccione --</option>
                                        <?php foreach ($catalogos['establecimientos'] as $est): ?>
                                            <option value="<?= $est['est_ide'] ?>">
                                                <?= $est['est_nombre'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="perl_ofi_ide">Oficina / Servicio: <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select required" id="perl_ofi_ide" name="perl_ofi_ide">
                                        <option value="">-- Seleccione --</option>
                                        <?php foreach ($catalogos['oficinas'] as $ofi): ?>
                                            <option value="<?= $ofi['ofi_ide'] ?>">
                                                <?= $ofi['ofi_nombre'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="perl_car_ide">Cargo Estructural: <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select required" id="perl_car_ide" name="perl_car_ide">
                                        <option value="">-- Seleccione --</option>
                                        <?php foreach ($catalogos['cargos'] as $car): ?>
                                            <option value="<?= $car['car_ide'] ?>">
                                                <?= $car['car_nombre'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="perl_mco_ide">Modalidad Contractual (Régimen): <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select required" id="perl_mco_ide" name="perl_mco_ide">
                                        <option value="">-- Seleccione --</option>
                                        <?php foreach ($catalogos['modalidades'] as $mod): ?>
                                            <option value="<?= $mod['mco_ide'] ?>">
                                                <?= $mod['mco_nombre'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="selectProfesion">Profesión Base:</label>
                                    <select class="form-select" id="selectProfesion" name="pro_ide">
                                        <option value="">-- Seleccione --</option>
                                        <?php foreach ($catalogos['profesiones'] as $pro): ?>
                                            <option value="<?= $pro['pro_ide'] ?>">
                                                <?= $pro['pro_nombre'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="selectEspecialidad">Segunda Especialidad:</label>
                                    <select class="form-select" id="selectEspecialidad" name="perl_se_ide" disabled>
                                        <option value="">-- Seleccione una profesión primero --</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="perl_codigo">Código Marcación / Fotocheck: <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control required" id="perl_codigo" name="perl_codigo"
                                        placeholder="Ej. 70412" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="perl_fecha_inicio">Fecha Ingreso / Inicio: <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control required" id="perl_fecha_inicio"
                                        name="perl_fecha_inicio" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="perl_regimen_laboral">Detalle de Régimen:</label>
                                    <input type="text" class="form-control" id="perl_regimen_laboral"
                                        name="perl_regimen_laboral" placeholder="Ej. D.L. 1057 (CAS)" />
                                </div>
                            </div>
                        </div>
                    </section>

                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>

    // Definimos las URLs relativas basadas en tu estructura actual
    const URL_SELECTS = '<?= base_url('personal/select') ?> ';
    const URL_API = '<?= base_url('personal/api') ?> ';

    $(document).ready(function () {
        // 1. Cargar selectores iniciales de forma limpia
        cargarSelect(`${URL_SELECTS}/tipos-documento`, '#per_tdi_ide');
        cargarSelect(`${URL_SELECTS}/establecimientos`, '#perl_est_ide');
        cargarSelect(`${URL_SELECTS}/modalidades-contrato`, '#perl_mco_ide');
        cargarSelect(`${URL_SELECTS}/cargos`, '#perl_car_ide');
        cargarSelect(`${URL_SELECTS}/profesiones`, '#pp_pro_ide');
        cargarSelect(`${URL_SELECTS}/colegios`, '#pp_col_ide');
        cargarSelect(`${URL_SELECTS}/especialidades`, '#pp_se_ide');

        // 2. Control de cascadas dinámicas (Establecimiento -> Unidades / Oficinas)
        $('#perl_est_ide').on('change', function () {
            const estId = $(this).val();
            if (estId) {
                cargarSelect(`${URL_SELECTS}/unidades?est_ide=${estId}`, '#perl_uo_ide');
                cargarSelect(`${URL_SELECTS}/oficinas?est_ide=${estId}`, '#perl_ofi_ide');
            } else {
                $('#perl_uo_ide').html('<option value="">Seleccione...</option>');
                $('#perl_ofi_ide').html('<option value="">Seleccione...</option>');
            }
        });
    });

    // 3. Cuando el wizard termine con éxito, enviamos los datos aquí:
    // En tu función guardarFormularioWizard() usa:
    // url: `${URL_API}/guardar-wizard`

</script>
<?= $this->endSection() ?>
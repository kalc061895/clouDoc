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

        <p class="small text-muted mb-3">Los campos marcados con * son obligatorios. Puede volver a un paso anterior sin perder los datos.</p>
        <div id="personal-wizard-status" class="alert alert-info d-none" role="status" aria-live="polite" tabindex="-1"></div>
        <form id="wizardPersonal" class="mt-4" novalidate>
            <?= csrf_field() ?>

            <div id="example-basic">
                <!-- PASO 1: DATOS PERSONALES (casis_persona) -->
                <h3>Datos Personales</h3>
                <section>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Tipo Documento <span
                                    class="text-danger">*</span></label>
                            <select class="form-select required" name="per_tdi_ide" id="per_tdi_ide" required disabled>
                                <option value="">Cargando...</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">N° Documento <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control required" name="per_numero_documento"
                                id="per_numero_documento" placeholder="Ej. 70654321" inputmode="numeric" minlength="8" maxlength="15">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">RUC</label>
                            <input type="text" class="form-control" name="per_ruc" placeholder="Ej. 10706543211"
                                inputmode="numeric" minlength="11" maxlength="11">
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
                            <select class="form-select required" name="perl_est_ide" id="perl_est_ide" required disabled>
                                <option value="">Cargando...</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Modalidad Contrato <span
                                    class="text-danger">*</span></label>
                            <select class="form-select required" name="perl_mco_ide" id="perl_mco_ide" required disabled>
                                <option value="">Cargando...</option>
                            </select>
                        </div>

                        
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Oficina / Servicio</label>
                            <select class="form-select" name="perl_ofi_ide" id="perl_ofi_ide" required disabled>
                                <option value="">Seleccione un establecimiento...</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Cargo Estructurado</label>
                            <select class="form-select" name="perl_car_ide" id="perl_car_ide" required disabled>
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
                            <input type="date" class="form-control" name="perl_fecha_termino">
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
                            <select class="form-select required" name="pp_pro_ide" id="pp_pro_ide" required disabled>
                                <option value="">Cargando...</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Colegio Profesional</label>
                            <select class="form-select" name="pp_col_ide" id="pp_col_ide" disabled>
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
                            <select class="form-select" name="pp_se_ide" id="pp_se_ide" disabled>
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
<?= view('Modules\\Asistencia\\Views\\personal\\partials\\registrar_script') ?>
<?= $this->endSection() ?>

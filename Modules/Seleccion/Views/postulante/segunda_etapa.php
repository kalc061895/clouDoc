<?= $this->extend('layouts/seleccionLayout') ?>

<?= $this->section('title') ?>
Ficha Digital de Postulación
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid p-4">

    <!-- ENCABEZADO -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <a href="<?= base_url('seleccion/postulacion') ?>" class="btn btn-outline-secondary btn-sm rounded-pill mb-2">
                <iconify-icon icon="solar:arrow-left-linear" class="align-middle"></iconify-icon>
                Volver al Listado
            </a>
            <h4 class="fw-bold text-dark mb-0">Ficha Digital de Postulación</h4>
            <p class="text-muted small mb-0">
                Complete la información requerida para registrar su postulación.
            </p>
        </div>

        <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-6 px-3 py-2 rounded-pill">
            Proceso CAS
        </span>
    </div>

    <!-- STEPPER -->
    <div class="card bg-white p-3 mb-4">
        <div class="overflow-auto">
            <div class="d-flex justify-content-between text-center" id="stepper-header" style="min-width: 950px;">

                <div class="step-item active flex-fill" id="step-head-1">
                    <span class="badge rounded-circle bg-primary text-white mb-1">1</span>
                    <div class="fw-bold small text-dark">Datos personales</div>
                </div>

                <div class="step-item text-muted flex-fill" id="step-head-2">
                    <span class="badge rounded-circle bg-secondary text-white mb-1">2</span>
                    <div class="fw-bold small">Formación profesional</div>
                </div>

                <div class="step-item text-muted flex-fill" id="step-head-3">
                    <span class="badge rounded-circle bg-secondary text-white mb-1">3</span>
                    <div class="fw-bold small">Formación académica</div>
                </div>

                <div class="step-item text-muted flex-fill" id="step-head-4">
                    <span class="badge rounded-circle bg-secondary text-white mb-1">4</span>
                    <div class="fw-bold small">Experiencia profesional</div>
                </div>

                <div class="step-item text-muted flex-fill" id="step-head-5">
                    <span class="badge rounded-circle bg-secondary text-white mb-1">5</span>
                    <div class="fw-bold small">Capacitaciones</div>
                </div>

                <div class="step-item text-muted flex-fill" id="step-head-6">
                    <span class="badge rounded-circle bg-secondary text-white mb-1">6</span>
                    <div class="fw-bold small">Identificación institucional</div>
                </div>

                <div class="step-item text-muted flex-fill" id="step-head-7">
                    <span class="badge rounded-circle bg-secondary text-white mb-1">7</span>
                    <div class="fw-bold small">Bonificaciones</div>
                </div>

                <div class="step-item text-muted flex-fill" id="step-head-8">
                    <span class="badge rounded-circle bg-secondary text-white mb-1">8</span>
                    <div class="fw-bold small">Declaraciones juradas</div>
                </div>

                <div class="step-item text-muted flex-fill" id="step-head-9">
                    <span class="badge rounded-circle bg-secondary text-white mb-1">9</span>
                    <div class="fw-bold small">Confirmación</div>
                </div>

            </div>
        </div>
    </div>

    <!-- FORMULARIO -->
    <form id="formPostulacion" action="<?= base_url('seleccion/postulacion/guardar') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <input type="hidden" name="pto_con_ide" id="pto_con_ide" value="<?= $convocatoriaId ?>">

        <!-- =====================================================
             PASO 1 - DATOS PERSONALES
        ====================================================== -->
        <div class="card bg-white p-4 mb-4 step-content" id="step-1">

            <div class="mb-4">
                <h5 class="fw-bold text-dark mb-1 d-flex align-items-center">
                    <iconify-icon icon="solar:user-bold" class="me-2 text-primary"></iconify-icon>
                    Datos personales
                </h5>
                <p class="text-muted small mb-0">
                    Registre y verifique sus datos personales.
                </p>
            </div>

            <div class="row g-3">

                <div class="col-md-3">
                    <label class="form-label small fw-bold">Tipo documento</label>
                    <select class="form-select" name="tipo_documento" required>
                        <option value="">Seleccione</option>
                        <option value="DNI">DNI</option>
                        <option value="CE">Carné de Extranjería</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold">N.º documento</label>
                    <input type="text" class="form-control" name="numero_documento" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold">Fecha nacimiento</label>
                    <input type="date" class="form-control" name="fecha_nacimiento" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold">Sexo</label>
                    <select class="form-select" name="sexo" required>
                        <option value="">Seleccione</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold">Apellido paterno</label>
                    <input type="text" class="form-control" name="apellido_paterno" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold">Apellido materno</label>
                    <input type="text" class="form-control" name="apellido_materno" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold">Nombres</label>
                    <input type="text" class="form-control" name="nombres" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold">Celular</label>
                    <input type="text" class="form-control" name="celular" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold">Correo electrónico</label>
                    <input type="email" class="form-control" name="correo" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold">Departamento</label>
                    <select class="form-select" name="departamento" required>
                        <option value="">Seleccione</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold">Provincia</label>
                    <select class="form-select" name="provincia" required>
                        <option value="">Seleccione</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold">Distrito</label>
                    <select class="form-select" name="distrito" required>
                        <option value="">Seleccione</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold">Dirección</label>
                    <input type="text" class="form-control" name="direccion" required>
                </div>

            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="button" class="btn btn-primary rounded-pill btn-next-step" data-current="1" data-next="2">
                    Siguiente
                    <iconify-icon icon="solar:arrow-right-linear" class="ms-1 align-middle"></iconify-icon>
                </button>
            </div>
        </div>

        <!-- =====================================================
             PASO 2 - FORMACIÓN PROFESIONAL
        ====================================================== -->
        <div class="card bg-white p-4 mb-4 step-content d-none" id="step-2">

            <div class="mb-4">
                <h5 class="fw-bold text-dark mb-1 d-flex align-items-center">
                    <iconify-icon icon="solar:diploma-bold" class="me-2 text-primary"></iconify-icon>
                    Formación profesional
                </h5>
                <p class="text-muted small mb-0">
                    Registre la profesión, colegiatura y habilitación profesional.
                </p>
            </div>

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label small fw-bold">Profesión</label>
                    <select class="form-select" name="profesion" required>
                        <option value="">Seleccione su profesión</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold">N.º colegiatura</label>
                    <input type="text" class="form-control" name="numero_colegiatura">
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold">Colegio profesional</label>
                    <input type="text" class="form-control" name="colegio_profesional">
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold">Fecha colegiatura</label>
                    <input type="date" class="form-control" name="fecha_colegiatura">
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold">Estado de colegiatura</label>
                    <select class="form-select" name="estado_colegiatura">
                        <option value="">Seleccione</option>
                        <option value="HABILITADO">Habilitado</option>
                        <option value="NO_HABILITADO">No habilitado</option>
                        <option value="NO_APLICA">No aplica</option>
                    </select>
                </div>

            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-outline-secondary rounded-pill btn-prev-step" data-current="2" data-prev="1">
                    <iconify-icon icon="solar:arrow-left-linear" class="me-1"></iconify-icon>
                    Anterior
                </button>

                <button type="button" class="btn btn-primary rounded-pill btn-next-step" data-current="2" data-next="3">
                    Siguiente
                    <iconify-icon icon="solar:arrow-right-linear" class="ms-1"></iconify-icon>
                </button>
            </div>
        </div>

        <!-- =====================================================
             PASO 3 - FORMACIÓN ACADÉMICA
        ====================================================== -->
        <div class="card bg-white p-4 mb-4 step-content d-none" id="step-3">

            <div class="mb-4">
                <h5 class="fw-bold text-dark mb-1 d-flex align-items-center">
                    <iconify-icon icon="solar:academic-cap-bold" class="me-2 text-primary"></iconify-icon>
                    Formación académica
                </h5>
                <p class="text-muted small mb-0">
                    Registre estudios de posgrado y otros grados académicos.
                </p>
            </div>

            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label small fw-bold">Grado académico</label>
                    <select class="form-select" name="grado_academico">
                        <option value="">Seleccione</option>
                        <option value="BACHILLER">Bachiller</option>
                        <option value="TITULO">Título profesional</option>
                        <option value="SEGUNDA_ESPECIALIDAD">Segunda especialidad</option>
                        <option value="MAESTRIA">Maestría</option>
                        <option value="DOCTORADO">Doctorado</option>
                    </select>
                </div>

                <div class="col-md-8">
                    <label class="form-label small fw-bold">Denominación del grado / especialidad</label>
                    <input type="text" class="form-control" name="grado_denominacion">
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-bold">Universidad / Institución</label>
                    <input type="text" class="form-control" name="institucion_academica">
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold">Fecha obtención</label>
                    <input type="date" class="form-control" name="fecha_grado">
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold">Registro SUNEDU</label>
                    <input type="text" class="form-control" name="registro_sunedu">
                </div>

            </div>

            <div class="alert alert-info border-0 small mt-4 mb-0">
                <iconify-icon icon="solar:info-circle-bold" class="me-1"></iconify-icon>
                En este paso podrá registrar los grados y estudios académicos que correspondan a su perfil.
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-outline-secondary rounded-pill btn-prev-step" data-current="3" data-prev="2">
                    <iconify-icon icon="solar:arrow-left-linear" class="me-1"></iconify-icon>
                    Anterior
                </button>

                <button type="button" class="btn btn-primary rounded-pill btn-next-step" data-current="3" data-next="4">
                    Siguiente
                    <iconify-icon icon="solar:arrow-right-linear" class="ms-1"></iconify-icon>
                </button>
            </div>
        </div>

        <!-- =====================================================
             PASO 4 - EXPERIENCIA PROFESIONAL
        ====================================================== -->
        <div class="card bg-white p-4 mb-4 step-content d-none" id="step-4">

            <div class="mb-4">
                <h5 class="fw-bold text-dark mb-1 d-flex align-items-center">
                    <iconify-icon icon="solar:case-bold" class="me-2 text-primary"></iconify-icon>
                    Experiencia profesional y contratos
                </h5>
                <p class="text-muted small mb-0">
                    Registre su experiencia laboral relacionada con el perfil solicitado.
                </p>
            </div>

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label small fw-bold">Entidad / Empresa</label>
                    <input type="text" class="form-control" name="experiencia_entidad">
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-bold">Cargo desempeñado</label>
                    <input type="text" class="form-control" name="experiencia_cargo">
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold">Modalidad contractual</label>
                    <select class="form-select" name="modalidad_contrato">
                        <option value="">Seleccione</option>
                        <option value="CAS">CAS</option>
                        <option value="276">D. Leg. 276</option>
                        <option value="728">D. Leg. 728</option>
                        <option value="LOCACION">Locación de servicios</option>
                        <option value="PRIVADO">Sector privado</option>
                        <option value="OTRO">Otro</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold">Fecha inicio</label>
                    <input type="date" class="form-control" name="experiencia_inicio">
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold">Fecha término</label>
                    <input type="date" class="form-control" name="experiencia_fin">
                </div>

                <div class="col-md-12">
                    <label class="form-label small fw-bold">Funciones principales</label>
                    <textarea class="form-control" name="experiencia_funciones" rows="3"></textarea>
                </div>

            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-outline-secondary rounded-pill btn-prev-step" data-current="4" data-prev="3">
                    <iconify-icon icon="solar:arrow-left-linear" class="me-1"></iconify-icon>
                    Anterior
                </button>

                <button type="button" class="btn btn-primary rounded-pill btn-next-step" data-current="4" data-next="5">
                    Siguiente
                    <iconify-icon icon="solar:arrow-right-linear" class="ms-1"></iconify-icon>
                </button>
            </div>
        </div>

        <!-- =====================================================
             PASO 5 - CAPACITACIONES
        ====================================================== -->
        <div class="card bg-white p-4 mb-4 step-content d-none" id="step-5">

            <div class="mb-4">
                <h5 class="fw-bold text-dark mb-1 d-flex align-items-center">
                    <iconify-icon icon="solar:notebook-bold" class="me-2 text-primary"></iconify-icon>
                    Capacitaciones
                </h5>
                <p class="text-muted small mb-0">
                    Registre cursos, diplomados, talleres y otras capacitaciones.
                </p>
            </div>

            <div class="row g-3">

                <div class="col-md-8">
                    <label class="form-label small fw-bold">Nombre de la capacitación</label>
                    <input type="text" class="form-control" name="capacitacion_nombre">
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold">Horas académicas</label>
                    <input type="number" class="form-control" name="capacitacion_horas" min="0">
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-bold">Institución que certifica</label>
                    <input type="text" class="form-control" name="capacitacion_institucion">
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold">Fecha inicio</label>
                    <input type="date" class="form-control" name="capacitacion_inicio">
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold">Fecha término</label>
                    <input type="date" class="form-control" name="capacitacion_fin">
                </div>

                <div class="col-md-12">
                    <label class="form-label small fw-bold">Tipo</label>
                    <select class="form-select" name="capacitacion_tipo">
                        <option value="">Seleccione</option>
                        <option value="CURSO">Curso</option>
                        <option value="DIPLOMADO">Diplomado</option>
                        <option value="TALLER">Taller</option>
                        <option value="SEMINARIO">Seminario</option>
                        <option value="CONGRESO">Congreso</option>
                        <option value="OTRO">Otro</option>
                    </select>
                </div>

            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-outline-secondary rounded-pill btn-prev-step" data-current="5" data-prev="4">
                    <iconify-icon icon="solar:arrow-left-linear" class="me-1"></iconify-icon>
                    Anterior
                </button>

                <button type="button" class="btn btn-primary rounded-pill btn-next-step" data-current="5" data-next="6">
                    Siguiente
                    <iconify-icon icon="solar:arrow-right-linear" class="ms-1"></iconify-icon>
                </button>
            </div>
        </div>

        <!-- =====================================================
             PASO 6 - IDENTIFICACIÓN INSTITUCIONAL
        ====================================================== -->
        <div class="card bg-white p-4 mb-4 step-content d-none" id="step-6">

            <div class="mb-4">
                <h5 class="fw-bold text-dark mb-1 d-flex align-items-center">
                    <iconify-icon icon="solar:buildings-bold" class="me-2 text-primary"></iconify-icon>
                    Identificación institucional
                </h5>
                <p class="text-muted small mb-0">
                    Complete la información relacionada con su identificación institucional.
                </p>
            </div>

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label small fw-bold">Código / Registro institucional</label>
                    <input type="text" class="form-control" name="codigo_institucional">
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-bold">Entidad de procedencia</label>
                    <input type="text" class="form-control" name="entidad_procedencia">
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-bold">Condición institucional</label>
                    <select class="form-select" name="condicion_institucional">
                        <option value="">Seleccione</option>
                        <option value="SERVIDOR_PUBLICO">Servidor público</option>
                        <option value="PERSONAL_PRIVADO">Personal privado</option>
                        <option value="INDEPENDIENTE">Independiente</option>
                        <option value="OTRO">Otro</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-bold">Observación</label>
                    <input type="text" class="form-control" name="observacion_institucional">
                </div>

            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-outline-secondary rounded-pill btn-prev-step" data-current="6" data-prev="5">
                    <iconify-icon icon="solar:arrow-left-linear" class="me-1"></iconify-icon>
                    Anterior
                </button>

                <button type="button" class="btn btn-primary rounded-pill btn-next-step" data-current="6" data-next="7">
                    Siguiente
                    <iconify-icon icon="solar:arrow-right-linear" class="ms-1"></iconify-icon>
                </button>
            </div>
        </div>

        <!-- =====================================================
             PASO 7 - BONIFICACIONES
        ====================================================== -->
        <div class="card bg-white p-4 mb-4 step-content d-none" id="step-7">

            <div class="mb-4">
                <h5 class="fw-bold text-dark mb-1 d-flex align-items-center">
                    <iconify-icon icon="solar:medal-ribbons-star-bold" class="me-2 text-primary"></iconify-icon>
                    Bonificaciones
                </h5>
                <p class="text-muted small mb-0">
                    Declare las condiciones que puedan generar una bonificación según las bases del proceso.
                </p>
            </div>

            <div class="d-flex flex-column gap-2">

                <div class="border rounded p-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="bonificacion_discapacidad" value="1" id="bonificacion_discapacidad">
                        <label class="form-check-label fw-semibold" for="bonificacion_discapacidad">
                            Persona con discapacidad
                        </label>
                    </div>
                </div>

                <div class="border rounded p-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="bonificacion_ffaa" value="1" id="bonificacion_ffaa">
                        <label class="form-check-label fw-semibold" for="bonificacion_ffaa">
                            Licenciado de las Fuerzas Armadas
                        </label>
                    </div>
                </div>

                <div class="border rounded p-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="bonificacion_otro" value="1" id="bonificacion_otro">
                        <label class="form-check-label fw-semibold" for="bonificacion_otro">
                            Otra condición susceptible de bonificación
                        </label>
                    </div>
                </div>

            </div>

            <div class="alert alert-warning border-0 small mt-3 mb-0">
                <iconify-icon icon="solar:danger-triangle-bold" class="me-1"></iconify-icon>
                Las bonificaciones declaradas deberán ser acreditadas con la documentación correspondiente.
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-outline-secondary rounded-pill btn-prev-step" data-current="7" data-prev="6">
                    <iconify-icon icon="solar:arrow-left-linear" class="me-1"></iconify-icon>
                    Anterior
                </button>

                <button type="button" class="btn btn-primary rounded-pill btn-next-step" data-current="7" data-next="8">
                    Siguiente
                    <iconify-icon icon="solar:arrow-right-linear" class="ms-1"></iconify-icon>
                </button>
            </div>
        </div>

        <!-- =====================================================
             PASO 8 - DECLARACIONES JURADAS
        ====================================================== -->
        <div class="card bg-white p-4 mb-4 step-content d-none" id="step-8">

            <div class="mb-4">
                <h5 class="fw-bold text-dark mb-1 d-flex align-items-center">
                    <iconify-icon icon="solar:shield-check-bold" class="me-2 text-primary"></iconify-icon>
                    Declaraciones juradas
                </h5>
                <p class="text-muted small mb-0">
                    Revise y acepte las declaraciones correspondientes al proceso.
                </p>
            </div>

            <div class="alert alert-warning border-0 small mb-4">
                <iconify-icon icon="solar:danger-triangle-bold" class="me-1"></iconify-icon>
                La información proporcionada tiene carácter de declaración jurada.
            </div>

            <div class="d-flex flex-column gap-2">

                <div class="border rounded p-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="dj_nepotismo" value="1" id="dj_nepotismo" required>
                        <label class="form-check-label fw-semibold" for="dj_nepotismo">
                            Declaro no encontrarme comprendido en incompatibilidades o nepotismo.
                        </label>
                    </div>
                </div>

                <div class="border rounded p-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="dj_antecedentes" value="1" id="dj_antecedentes" required>
                        <label class="form-check-label fw-semibold" for="dj_antecedentes">
                            Declaro no registrar impedimentos para contratar con el Estado.
                        </label>
                    </div>
                </div>

                <div class="border rounded p-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="dj_veracidad" value="1" id="dj_veracidad" required>
                        <label class="form-check-label fw-semibold" for="dj_veracidad">
                            Declaro que la información y documentación presentada es verdadera.
                        </label>
                    </div>
                </div>

            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-outline-secondary rounded-pill btn-prev-step" data-current="8" data-prev="7">
                    <iconify-icon icon="solar:arrow-left-linear" class="me-1"></iconify-icon>
                    Anterior
                </button>

                <button type="button" class="btn btn-primary rounded-pill btn-next-step" data-current="8" data-next="9">
                    Revisar postulación
                    <iconify-icon icon="solar:arrow-right-linear" class="ms-1"></iconify-icon>
                </button>
            </div>
        </div>

        <!-- =====================================================
             PASO 9 - CONFIRMACIÓN
        ====================================================== -->
        <div class="card bg-white p-4 mb-4 step-content d-none" id="step-9">

            <div class="mb-4">
                <h5 class="fw-bold text-dark mb-1 d-flex align-items-center">
                    <iconify-icon icon="solar:check-circle-bold" class="me-2 text-success"></iconify-icon>
                    Confirmación de postulación
                </h5>
                <p class="text-muted small mb-0">
                    Revise la información antes de presentar definitivamente su postulación.
                </p>
            </div>

            <div class="alert alert-success border-0">
                <div class="d-flex align-items-start">
                    <iconify-icon icon="solar:verified-check-bold" class="fs-4 me-2"></iconify-icon>
                    <div>
                        <strong>Todo listo para presentar su postulación.</strong>
                        <div class="small mt-1">
                            Una vez confirmada, la información registrada quedará asociada a su expediente digital.
                        </div>
                    </div>
                </div>
            </div>

            <div class="border rounded p-3 mb-3">
                <div class="row g-3">

                    <div class="col-md-6">
                        <div class="text-muted small">Postulante</div>
                        <div class="fw-bold" id="resumenPostulante">
                            Información registrada
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="text-muted small">Documento</div>
                        <div class="fw-bold" id="resumenDocumento">
                            Información registrada
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="text-muted small">Plaza / Cargo</div>
                        <div class="fw-bold" id="resumenPlaza">
                            Información registrada
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="text-muted small">Correo</div>
                        <div class="fw-bold" id="resumenCorreo">
                            Información registrada
                        </div>
                    </div>

                </div>
            </div>

            <div class="form-check border rounded p-3">
                <input class="form-check-input" type="checkbox" id="confirmacion_final" required>
                <label class="form-check-label fw-semibold" for="confirmacion_final">
                    Confirmo que he revisado la información registrada y deseo presentar definitivamente mi postulación.
                </label>
            </div>

            <div class="d-flex justify-content-between mt-4">

                <button type="button" class="btn btn-outline-secondary rounded-pill btn-prev-step" data-current="9" data-prev="8">
                    <iconify-icon icon="solar:arrow-left-linear" class="me-1"></iconify-icon>
                    Anterior
                </button>

                <button type="submit" class="btn btn-success rounded-pill px-4" id="btn-submit-postulacion">
                    <iconify-icon icon="solar:check-read-bold" class="me-1"></iconify-icon>
                    Presentar postulación
                </button>

            </div>
        </div>

    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>

<script>
    $(document).ready(function() {

        const convocatoriaId = $('#pto_con_ide').val();

        let currentStep = 1;

        const totalSteps = 9;

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        function validarPaso(step) {
            let valido = true;

            $(`#step-${step} [required]`).each(function() {
                if (!this.checkValidity()) {
                    this.reportValidity();
                    valido = false;
                    return false;
                }
            });

            return valido;
        }

        function mostrarPaso(step) {
            $('.step-content').addClass('d-none');
            $(`#step-${step}`).removeClass('d-none');

            $('.step-item')
                .removeClass('active')
                .addClass('text-muted');

            $('.step-item .badge')
                .removeClass('bg-primary')
                .removeClass('bg-success')
                .addClass('bg-secondary');

            for (let i = 1; i < step; i++) {
                $(`#step-head-${i}`)
                    .removeClass('text-muted')
                    .find('.badge')
                    .removeClass('bg-secondary')
                    .addClass('bg-success');
            }

            $(`#step-head-${step}`)
                .removeClass('text-muted')
                .addClass('active');

            $(`#step-head-${step} .badge`)
                .removeClass('bg-secondary')
                .addClass('bg-primary');

            currentStep = step;

            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        $('.btn-next-step').on('click', function() {

            const current = parseInt($(this).data('current'));
            const next = parseInt($(this).data('next'));

            if (!validarPaso(current)) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Complete los campos requeridos'
                });
                return;
            }

            mostrarPaso(next);
        });

        $('.btn-prev-step').on('click', function() {

            const prev = parseInt($(this).data('prev'));

            mostrarPaso(prev);
        });

        $('#formPostulacion').on('submit', function(e) {

            e.preventDefault();

            if (!validarPaso(9)) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Debe confirmar su postulación'
                });
                return;
            }

            Swal.fire({
                title: '¿Confirmar postulación?',
                text: 'Una vez presentada, la información registrada quedará asociada a su expediente digital.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, presentar postulación',
                cancelButtonText: 'Revisar nuevamente',
                reverseButtons: true
            }).then(function(result) {

                if (!result.isConfirmed) {
                    return;
                }

                const formData = new FormData($('#formPostulacion')[0]);

                $.ajax({
                    url: $('#formPostulacion').attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,

                    beforeSend: function() {
                        Swal.fire({
                            title: 'Procesando postulación',
                            text: 'Registrando su expediente digital...',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: function() {
                                Swal.showLoading();
                            }
                        });
                    },

                    success: function(response) {

                        if (response.status === 'success') {

                            Swal.fire({
                                title: '¡Postulación registrada!',
                                text: response.message || 'Su postulación fue registrada correctamente.',
                                icon: 'success',
                                confirmButtonText: 'Continuar'
                            }).then(function() {

                                if (response.redirect) {
                                    window.location.href = response.redirect;
                                }

                            });

                        } else {

                            Swal.fire({
                                title: 'No se pudo completar',
                                text: response.message || 'Ocurrió un error al registrar la postulación.',
                                icon: 'error'
                            });

                        }
                    },

                    error: function() {

                        Swal.fire({
                            title: 'Error de servidor',
                            text: 'No se pudo registrar la postulación. Intente nuevamente.',
                            icon: 'error'
                        });

                    }
                });
            });
        });

        mostrarPaso(1);
    });
</script>

<?= $this->endSection() ?>
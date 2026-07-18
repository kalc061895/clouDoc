<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?> Alta de Nuevo Personal <?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="mb-4">
        <h4 class="text-dark fw-bold mb-1">
            <iconify-icon icon="solar:user-plus-bold" class="me-2 text-primary" style="font-size: 1.5rem;"></iconify-icon>
            Incorporación de Nuevo Personal
        </h4>
        <p class="text-muted small mb-0">Registro secuencial de datos de identidad, asignación laboral, condiciones de contrato y control de asistencia.</p>
    </div>

    <!-- Navegación de Tabs -->
    <ul class="nav nav-pills nav-justified backend-tabs mb-4" id="personalTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold" id="identidad-tab" data-bs-toggle="tab" data-bs-target="#tabIdentidad" type="button" role="tab">
                1. Identidad Base
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold disabled" id="laboral-tab" data-bs-toggle="tab" data-bs-target="#tabLaboral" type="button" role="tab">
                2. Asignación Laboral
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold disabled" id="contrato-tab" data-bs-toggle="tab" data-bs-target="#tabContrato" type="button" role="tab">
                3. Modalidad y Contrato
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold disabled" id="control-tab" data-bs-toggle="tab" data-bs-target="#tabControl" type="button" role="tab">
                4. Control de Asistencia
            </button>
        </li>
    </ul>

    <!-- Cuerpo de los Formularios -->
    <form id="formWizardPersonal">
        <!-- Input oculto clave: Almacena el ID obtenido del paso 1 -->
        <input type="hidden" id="hdn_persona_id" name="persona_id">

        <div class="tab-content border p-4 rounded-3 bg-light-subtle" id="personalTabsContent">
            
            <!-- TAB 1: IDENTIDAD BASE -->
            <div class="tab-pane fade show active" id="tabIdentidad" role="tabpanel">
                <h5 class="fw-bold mb-3 text-secondary">Datos de Identidad Civil</h5>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Tipo Documento</label>
                        <select class="form-select" id="per_tipo_documento" name="per_tipo_documento" required>
                            <option value="1">DNI</option>
                            <option value="2">Carnet de Extranjería</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Número de Documento</label>
                        <div class="input-group">
                            <input type="text" class="form-control font-monospace" id="per_numero_documento" name="per_numero_documento" required maxlength="12">
                            <button class="btn btn-primary" type="button" id="btnVerificarIdentidad">
                                <iconify-icon icon="lucide:search"></iconify-icon> Verificar
                            </button>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small fw-bold">Correo Electrónico Personal</label>
                        <input type="email" class="form-control" id="per_correo" name="per_correo">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Apellido Paterno</label>
                        <input type="text" class="form-control" id="per_apellido_paterno" name="per_apellido_paterno" required disabled>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Apellido Materno</label>
                        <input type="text" class="form-control" id="per_apellido_materno" name="per_apellido_materno" required disabled>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Nombres</label>
                        <input type="text" class="form-control" id="per_nombres" name="per_nombres" required disabled>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <button type="button" class="btn btn-primary btn-sm px-4 rounded-pill" id="btnPaso1Siguiente" disabled>
                        Siguiente Asignación Laboral <iconify-icon icon="lucide:arrow-right" class="ms-1"></iconify-icon>
                    </button>
                </div>
            </div>

            <!-- TAB 2: ASIGNACIÓN LABORAL -->
            <div class="tab-pane fade" id="tabLaboral" role="tabpanel">
                <h5 class="fw-bold mb-3 text-secondary">Ubicación Estructural e Institucional</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Oficina / Departamento / Servicio</label>
                        <select class="form-select" name="oficina_id" required>
                            <option value="">-- Seleccione Oficina Destino --</option>
                            <?php foreach($oficinas as $o): ?>
                                <option value="<?= $o['oficina_id'] ?>"><?= $o['oficina_nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Cargo Estructural / Funcional</label>
                        <select class="form-select" name="cargo_id" required>
                            <option value="">-- Seleccione Cargo --</option>
                            <?php foreach($cargos as $c): ?>
                                <option value="<?= $c['cargo_id'] ?>"><?= $c['cargo_nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Profesión Base</label>
                        <select class="form-select" name="profesion_id" required>
                            <option value="">-- Seleccione Profesión --</option>
                            <?php foreach($profesiones as $p): ?>
                                <option value="<?= $p['pro_ide'] ?>"><?= $p['pro_nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Segunda Especialidad (Opcional)</label>
                        <select class="form-select" name="se_ide">
                            <option value="">-- Ninguna --</option>
                            <?php foreach($especialidades as $e): ?>
                                <option value="<?= $e['pes_se_ide'] ?>"><?= $e['pes_pro_ide'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-light btn-sm px-3 rounded-pill" onclick="irATab('identidad-tab')">Atrás</button>
                    <button type="button" class="btn btn-primary btn-sm px-4 rounded-pill" onclick="irATab('contrato-tab')">Siguiente Contrato</button>
                </div>
            </div>

            <!-- TAB 3: MODALIDAD Y CONTRATO -->
            <div class="tab-pane fade" id="tabContrato" role="tabpanel">
                <h5 class="fw-bold mb-3 text-secondary">Régimen y Vínculo Laboral</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Régimen Laboral (D.L.)</label>
                        <select class="form-select" name="regimen_id" required>
                            <option value="276">D.L. 276</option>
                            <option value="1057">D.L. 1057 (CAS)</option>
                            <option value="728">D.L. 728</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Fecha de Ingreso / Inicio de Vínculo</label>
                        <input type="date" class="form-control" name="psn_fecha_ingreso" required value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Número de Resolución / Contrato</label>
                        <input type="text" class="form-control text-uppercase" name="psn_documento_vinculo" placeholder="Ej. R.D. Nº 142-2026-RED-SR">
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-light btn-sm px-3 rounded-pill" onclick="irATab('laboral-tab')">Atrás</button>
                    <button type="button" class="btn btn-primary btn-sm px-4 rounded-pill" onclick="irATab('control-tab')">Siguiente Control</button>
                </div>
            </div>

            <!-- TAB 4: CONTROL DE ASISTENCIA -->
            <div class="tab-pane fade" id="tabControl" role="tabpanel">
                <h5 class="fw-bold mb-3 text-secondary">Parámetros de Marcación y Biométrico</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Código de Marcación (ID Biométrico)</label>
                        <input type="text" class="form-control font-monospace text-center fw-bold text-primary fs-5" name="psn_codigo_biometrico" required placeholder="0000">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Condición de Exoneración</label>
                        <select class="form-select" name="psn_exonerado_asistencia">
                            <option value="0" selected>Sujeto a Control Biométrico Ordinario</option>
                            <option value="1">Exonerado (Cargos de Confianza/Funcionarios)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Situación Actual</label>
                        <input type="text" class="form-control" value="ACTIVO / EN SERVICIO" disabled>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-light btn-sm px-3 rounded-pill" onclick="irATab('contrato-tab')">Atrás</button>
                    <button type="submit" class="btn btn-success btn-sm px-4 rounded-pill fw-bold" id="btnFinalizarRegistro">
                        <iconify-icon icon="lucide:check-circle" class="me-1"></iconify-icon> Finalizar Incorporación
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script>
    const urlBaseApi = '<?= base_url('asistencia/personal/api') ?>';

    $(document).ready(function() {
        
        // Manejador del Botón de Verificación (Paso 1)
        $('#btnVerificarIdentidad').on('click', function() {
            const dni = $('#per_numero_documento').val().trim();
            if (dni.length < 8) {
                toastr.warning('Por favor, ingrese un número de documento válido.');
                return;
            }

            $('#btnVerificarIdentidad').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Buscando...');

            $.ajax({
                url: `${urlBaseApi}/verificar-identidad`,
                type: 'POST',
                data: {
                    per_numero_documento: dni,
                    per_tipo_documento: $('#per_tipo_documento').val()
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        const persona = response.data.persona;
                        
                        // Seteamos la ID oculta de la persona civil encontrada/creada
                        $('#hdn_persona_id').val(persona.persona_id);

                        // Mapeamos los datos civiles
                        $('#per_apellido_paterno').val(persona.per_apellido_paterno).prop('disabled', !!persona.persona_id);
                        $('#per_apellido_materno').val(persona.per_apellido_materno).prop('disabled', !!persona.persona_id);
                        $('#per_nombres').val(persona.per_nombres).prop('disabled', !!persona.persona_id);
                        $('#per_correo').val(persona.per_correo || '');

                        if (persona.persona_id && !response.data.es_personal_activo) {
                            toastr.info('Persona encontrada en el padrón base. Puede proceder a asignarle sus parámetros laborales.');
                        } else if (response.data.es_personal_activo) {
                            Swal.fire('Atención', 'Esta persona ya se encuentra registrada como Personal Activo en la Red de Salud.', 'warning');
                            $('#btnPaso1Siguiente').prop('disabled', true);
                            return;
                        } else {
                            toastr.success('Documento habilitado para nuevo registro civil.');
                            // Si es totalmente nueva, habilitamos los campos para escritura manual
                            $('#per_apellido_paterno, #per_apellido_materno, #per_nombres').prop('disabled', false);
                        }

                        // Habilitar avance
                        $('#btnPaso1Siguiente').prop('disabled', false);
                        desbloquearPestañas();
                    }
                },
                error: function() {
                    toastr.error('Error al conectarse al servicio de verificación.');
                },
                complete: function() {
                    $('#btnVerificarIdentidad').prop('disabled', false).html('<iconify-icon icon="lucide:search"></iconify-icon> Verificar');
                }
            });
        });

        // Trigger del botón Siguiente del Paso 1
        $('#btnPaso1Siguiente').on('click', function() {
            irATab('laboral-tab');
        });

        // Envío final del Formulario unificado (Wizard)
        $('#formWizardPersonal').on('submit', function(e) {
            e.preventDefault();
            
            // Forzar habilitación temporal para capturar campos si el backend los requiere en el post global
            $('#tabIdentidad input').prop('disabled', false);
            const datosSerializados = $(this).serialize();
            
            $('#btnFinalizarRegistro').prop('disabled', true).text('Procesando Alta...');

            $.ajax({
                url: urlBaseApi,
                type: 'POST',
                data: datosSerializados,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Operación Exitosa!',
                            text: response.message,
                            confirmButtonText: 'Ir al Listado'
                        }).then(() => {
                            window.location.href = '<?= base_url('personal') ?>';
                        });
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Error interno en el servidor.';
                    if (xhr.responseJSON && xhr.responseJSON.messages) {
                        errorMsg = Object.values(xhr.responseJSON.messages).join('<br>');
                    }
                    Swal.fire({ icon: 'error', title: 'Error de validación', html: errorMsg });
                    // Volver a deshabilitar el bloque 1 si ya estaba verificado para protección de flujo
                    if ($('#hdn_persona_id').val() !== '') {
                        $('#per_apellido_paterno, #per_apellido_materno, #per_nombres').prop('disabled', true);
                    }
                },
                complete: function() {
                    $('#btnFinalizarRegistro').prop('disabled', false).html('<iconify-icon icon="lucide:check-circle" class="me-1"></iconify-icon> Finalizar Incorporación');
                }
            });
        });
    });

    function desbloquearPestañas() {
        $('#laboral-tab, #contrato-tab, #control-tab').removeClass('disabled');
    }

    function irATab(tabId) {
        const triggerEl = document.getElementById(tabId);
        if(triggerEl) {
            const tabInstance = bootstrap.Tab.getOrCreateInstance(triggerEl);
            tabInstance.show();
        }
    }
</script>

<?= $this->endSection() ?>
<script src="assets/libs/jquery-steps/build/jquery.steps.min.js"></script>
<!--script src="assets/js/forms/form-wizard.js"></script-->
<!-- start Custom Design Example -->
<div class="card">
    <div class="card-body wizard-content">
        <h4 class="card-title mb-0">Custom Design Example</h4>
        <h6 class="card-subtitle mb-3"></h6>
        <form action="#" class="tab-wizard wizard-circle">
            <!-- STEP 1: DATOS PERSONALES -->
            <h6>Datos personales</h6>
            <section>

                <input type="hidden" id="postulante_id" value="">

                <div class="row">

                    <div class="col-md-3 mb-3">
                        <label class="form-label">DNI</label>
                        <input type="text" class="form-control" id="dni" maxlength="15" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Nombres</label>
                        <input type="text" class="form-control" id="nombres" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Apellido Paterno</label>
                        <input type="text" class="form-control" id="paterno" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Apellido Materno</label>
                        <input type="text" class="form-control" id="materno" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Fecha de nacimiento</label>
                        <input type="date" class="form-control" id="fecha_nacimiento" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccion">

                    </div>

                </div>

                <div class="text-end">
                    <button type="button" class="btn btn-primary" id="btnGuardarPostulante">
                        <i class="fa fa-save"></i> Guardar datos personales
                    </button>
                </div>

            </section>
            <!-- Step 2: FORMACION PROFESIONAL -->
            <h6>Formación profesional</h6>
            <section>

                <input type="hidden" id="id_formacion">

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nivel</label>
                        <select class="form-select" id="nivel">
                            <option value="">Seleccione</option>
                            <option value="TECNICO">Técnico</option>
                            <option value="UNIVERSITARIO">Universitario</option>
                            <option value="MAESTRIA">Maestría</option>
                            <option value="DOCTORADO">Doctorado</option>
                        </select>
                    </div>

                    <div class="col-md-8 mb-3">
                        <label class="form-label">Carrera / Especialidad</label>
                        <input type="text" class="form-control" id="carrera">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Institución</label>
                        <input type="text" class="form-control" id="institucion">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fecha inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fecha fin</label>
                        <input type="date" class="form-control" id="fecha_fin">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Documento (título / constancia)</label>
                        <input type="file" accept="application/pdf" class="form-control" id="archivo_formacion">
                    </div>

                </div>

                <div class="text-end mb-3">
                    <button type="button" class="btn btn-success" id="btnGuardarFormacion">
                        <i class="fa fa-plus"></i> Agregar formación
                    </button>
                </div>

                <hr>

                <table class="table table-bordered table-sm" id="tablaFormacion">
                    <thead>
                        <tr>
                            <th>Nivel</th>
                            <th>Carrera</th>
                            <th>Institución</th>
                            <th>Documento</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </section>
            <!-- Step 3: Experiencia Laboral -->
            <h6>Experiencia Laboral</h6>
            <section>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Entidad</label>
                        <input type="text" id="entidad" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Cargo</label>
                        <input type="text" id="cargo" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Fecha Inicio</label>
                        <input type="date" id="fecha_inicio_experiencia" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Fecha Fin</label>
                        <input type="date" id="fecha_fin_experiencia" class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Anexo (contrato, resolución, constancia)</label>
                        <input type="file" accept="application/pdf" id="archivo_experiencia" class="form-control">
                    </div>

                    <div class="col-md-12">
                        <button id="btnGuardarExperiencia" type="button" class="btn btn-success">
                            Guardar Experiencia
                        </button>
                    </div>
                </div>

                <hr>

                <table class="table" id="tablaExperiencia">
                    <thead>
                        <tr>
                            <th>Entidad</th>
                            <th>Cargo</th>
                            <th>Periodo</th>
                            <th>Anexo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </section>
            <!-- Step 4: Capacitaciones -->
            <h6>Capacitaciones</h6>
            <section>

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nombre del curso / capacitación</label>
                        <input type="text" id="cap_nombre" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Institución</label>
                        <input type="text" id="cap_institucion" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Horas</label>
                        <input type="number" id="cap_horas" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Fecha inicio</label>
                        <input type="date" id="cap_fecha_inicio" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Fecha fin</label>
                        <input type="date" id="cap_fecha_fin" class="form-control">
                    </div>

                    <div class="col-md-8 mb-3">
                        <label class="form-label">Certificado / constancia (PDF)</label>
                        <input type="file" accept="application/pdf" id="cap_archivo" class="form-control" accept="application/pdf">
                    </div>

                    <div class="col-md-4 d-flex align-items-end mb-3">
                        <button type="button" id="btnGuardarCapacitacion" class="btn btn-success w-100">
                            <i class="fa fa-plus"></i> Agregar
                        </button>
                    </div>

                </div>


                <table class="table " id="tablaCapacitaciones">
                    <thead>
                        <tr>
                            <th>Capacitación</th>
                            <th>Institución</th>
                            <th>Horas</th>
                            <th>Certificado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </section>
            <!-- Step 5: Otros Anexos -->
            <!-- Step 6: Informacion Extra -->

            <!-- Step X: INFORMACIÓN EXTRA -->
            <h6>Información adicional</h6>
            <section>

                <input type="hidden" id="id_extra">

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tipo de documento</label>
                        <select class="form-select" id="tipo_extra">
                            <option value="">Seleccione</option>
                            <option value="DNI">DNI</option>
                            <option value="SOLICITUD">Solicitud de Postulación</option>
                            <option value="FICHA DE AUTOEVALUACION">Ficha Unica de Datos</option>
                            <option value="DJ. IMPEDIMENTOS">Declaracion Jurada de impedimentos e incompatibilidades</option>
                            <option value="FICHA DE AUTOEVALUACION">Ficha de AutoEvaluación</option>
                            <option value="SERUMS">SERUMS</option>
                            <option value="CONADIS">CONADIS</option>
                            <option value="FFAA">Fuerzas Armadas</option>
                            <option value="FELICITACION">Resolución de Felicitación</option>
                            <option value="ENCARGATURA">Resolución de Encargatura</option>
                            <option value="OTRO">Otro</option>
                        </select>
                    </div>

                    <div class="col-md-8 mb-3">
                        <label class="form-label">Descripción (opcional)</label>
                        <input type="text" class="form-control" id="descripcion_extra">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Fecha Emisión</label>
                        <input type="date" class="form-control" id="fecha_expedicion_extra">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Documento</label>
                        <input type="file" class="form-control" id="archivo_extra">
                    </div>

                </div>

                <div class="text-end mb-3">
                    <button type="button" class="btn btn-success" id="btnGuardarExtra">
                        <i class="fa fa-plus"></i> Agregar documento
                    </button>
                </div>

                <hr>

                <table class="table table-bordered table-sm" id="tablaExtra">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Descripción</th>
                            <th>Documento</th>
                            <th width="90">Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </section>

            <!-- Step 2 -->
            <!-- STEP: SELECCIÓN DE PLAZA -->
            <h6>Selección de plaza</h6>
            <section>

                <div class="row">

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Plaza a la que postula</label>


                        <select class="form-select" id="id_plaza">
                            <option value="">Seleccione una plaza</option>
                            <!-- se llena por ajax -->
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="alert alert-warning">
                            <strong>Importante:</strong>
                            Al confirmar su postulación, no podrá modificar la información registrada.
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="confirmacion">
                            <label class="form-check-label" for="confirmacion">
                                Declaro que la información proporcionada es verdadera y acepto las bases de la convocatoria
                            </label>
                        </div>
                    </div>

                </div>

                <div class="text-end">
                    <button type="button" class="btn btn-primary" id="btnConfirmarPostulacion">
                        <i class="fa fa-check"></i> Confirmar postulación
                    </button>
                </div>

            </section>

        </form>
    </div>
</div>
<!-- end Custom Design Example -->

<script>
    function validarCampos(selector) {
        let valido = true;

        $(selector).each(function() {
            if (!$(this).val()) {
                valido = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        return valido;
    }
</script>
<!-- CARGAR PLAZAS Y CONFIRMAR POSTULACION -->
<script>
    $(document).ready(function() {

        function cargarPlazas() {


            $.getJSON('postulante/listarplazas', function(res) {
                let html = '<option value="">Seleccione una plaza</option>';
                res.forEach(p => {
                    html += `
                    <option value="${p.id_plaza}">
                        ${p.codigo_plaza} - ${p.cargo} (${p.establecimiento})
                    </option>`;
                });
                $('#id_plaza').html(html);
            });
        }
        setTimeout(() => {
                cargarPlazas();

        }, 10000);


        $('#btnConfirmarPostulacion').click(function() {

            let idPlaza = $('#id_plaza').val();
            let confirmado = $('#confirmacion').is(':checked');

            if (!idPlaza) {
                Swal.fire('Atención', 'Debe seleccionar una plaza', 'warning');
                return;
            }

            if (!confirmado) {
                Swal.fire('Atención', 'Debe confirmar la veracidad de la información', 'warning');
                return;
            }

            Swal.fire({
                title: '¿Confirmar postulación?',
                text: 'No podrá modificar su información luego de confirmar',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: 'postulante/postulacion/confirmar',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id_plaza: idPlaza
                        },
                        success: function(res) {

                            if (res.status === 'success') {
                                Swal.fire({
                                    title: 'Postulación registrada',
                                    text: 'Se ha registrado Correctamente tu postulación, en breve recibirás un correo para tu confirmacion',
                                    icon: 'success'
                                }).then(() => {
                                    window.location.href = '';
                                });
                            } else {
                                Swal.fire('Error', res.message, 'error');
                            }

                        },
                        error: function() {
                            Swal.fire('Error', 'No se pudo confirmar la postulación', 'error');
                        }
                    });

                }

            });

        });

    });
</script>



<!-- AGREGAR INFORMACIÓN EXTRA -->
<script>
    $(document).ready(function() {

        function cargarInformacionExtra() {
            $.getJSON('postulante/extra/listar', function(res) {
                let html = '';
                res.forEach(e => {
                    html += `
                    <tr>
                        <td>${e.tipo}</td>
                        <td>${e.descripcion ?? '-'}</td>
                        <td>
                            ${e.archivo ? `<a href="${e.archivo}" target="_blank">
                            <i class="fa fa-file-pdf text-danger"></i>Ver</a>` : ''}
                        </td>
                        <td>
                            <button class="btn btn-sm btn-danger btn-eliminar-extra" data-id="${e.id_extra}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;
                });
                $('#tablaExtra tbody').html(html);
            });
        }

        $('#btnGuardarExtra').click(function() {

            let formDataExtra = new FormData();
            formDataExtra.append('tipo', $('#tipo_extra').val());
            formDataExtra.append('descripcion', $('#descripcion_extra').val());
            formDataExtra.append('fecha_expedicion', $('#fecha_expedicion_extra').val());
            formDataExtra.append('archivo', $('#archivo_extra')[0].files[0]);

            if (!validarCampos('#tipo_extra, #descripcion_extra,#fecha_expedicion_extra')) {
                Swal.fire('Atención', 'Seleccione el tipo de documento', 'warning');
                return;
            }

            // Validar archivo
            if (!$('#archivo_extra')[0].files.length) {
                Swal.fire('Atención', 'Debe adjuntar un archivo', 'warning');
                return;
            }

            $.ajax({
                url: 'postulante/extra/guardar',
                type: 'POST',
                data: formDataExtra,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        cargarInformacionExtra();
                        Swal.fire('Correcto', 'Documento agregado', 'success');
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                }
            });
        });

        $(document).on('click', '.btn-eliminar-extra', function() {

            let id = $(this).data('id');

            Swal.fire({
                title: '¿Eliminar documento?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: 'postulante/extra/eliminar/' + id,
                        type: 'POST',
                        dataType: 'json',
                        success: function(res) {

                            if (res.status === 'success') {
                                cargarInformacionExtra();
                                Swal.fire('Eliminado', 'Documento eliminado correctamente', 'success');
                            } else {
                                Swal.fire('Error', res.message, 'error');
                            }

                        },
                        error: function() {
                            Swal.fire('Error', 'No se pudo eliminar el registro', 'error');
                        }
                    });

                }

            });

        });

        cargarInformacionExtra();
    });
</script>


<!-- AGREGAR CAPACITACIONES -->
<script>
    $(document).ready(function() {

        function cargarCapacitaciones() {
            $.getJSON('postulante/capacitaciones/listar', function(res) {

                let html = '';

                res.forEach(c => {
                    html += `
                    <tr>
                        <td>${c.nombre}</td>
                        <td>${c.institucion}</td>
                        <td>${c.horas ?? '-'}</td>
                        <td>
                            <a href="/uploads/${c.ruta}" target="_blank">
                            <i class="fa fa-file-pdf text-danger"></i> Ver
                            </a>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-danger btn-eliminar-cap"
                                data-id="${c.id_capacitacion}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;
                });

                $('#tablaCapacitaciones tbody').html(html);
            });
        }

        $('#btnGuardarCapacitacion').on('click', function() {

            let formData = new FormData();

            const campos = {
                nombre: $('#cap_nombre').val(),
                institucion: $('#cap_institucion').val(),
                fecha_inicio: $('#cap_fecha_inicio').val(),
                fecha_fin: $('#cap_fecha_fin').val()
            };

            if (!validarCampos('#cap_nombre, #cap_institucion, #cap_fecha_inicio, #cap_fecha_fin')) {
                Swal.fire('Atención', 'Complete los campos obligatorios', 'warning');
                return;
            }

            if (!$('#cap_archivo')[0].files.length) {
                Swal.fire('Atención', 'Debe adjuntar el certificado', 'warning');
                return;
            }

            formData.append('nombre', campos.nombre);
            formData.append('institucion', campos.institucion);
            formData.append('horas', $('#cap_horas').val());
            formData.append('fecha_inicio', campos.fecha_inicio);
            formData.append('fecha_fin', campos.fecha_fin);
            formData.append('archivo', $('#cap_archivo')[0].files[0]);

            $.ajax({
                url: 'postulante/capacitaciones/guardar',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        Swal.fire('Correcto', 'Capacitación registrada', 'success');

                        cargarCapacitaciones();
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                }
            });
        });

        $(document).on('click', '.btn-eliminar-cap', function() {

            let id = $(this).data('id');

            Swal.fire({
                title: '¿Eliminar Capacitación?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: 'postulante/capacitaciones/eliminar/' + id,
                        type: 'POST',
                        dataType: 'json',
                        success: function(res) {

                            if (res.status === 'success') {
                                cargarCapacitaciones();
                                Swal.fire('Eliminado', 'Registro eliminado correctamente', 'success');
                            } else {
                                Swal.fire('Error', res.message, 'error');
                            }

                        },
                        error: function() {
                            Swal.fire('Error', 'No se pudo eliminar el registro', 'error');
                        }
                    });

                }

            });
        });

        cargarCapacitaciones();
    });
</script>



<!-- AGREGAR EXPERIENCIA LABORAL -->
<script>
    $(document).ready(function() {

        function cargarExperiencia() {
            $.getJSON('postulante/experiencia/listar', function(res) {
                let html = '';
                res.forEach(e => {
                    html += `
                    <tr>
                        <td>${e.entidad}</td>
                        <td>${e.cargo}</td>
                        <td>${e.fecha_inicio} - ${e.fecha_fin ?? 'Actual'}</td>
                        <td>
                            ${e.archivo ? `<a href="${e.archivo}" target="_blank">
                            <i class="fa fa-file-pdf text-danger"></i>Ver</a>` : ''}
                        </td>
                        <td>
                            <button class="btn btn-sm btn-danger btn-eliminar-experiencia" data-id="${e.id_experiencia}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;
                });
                $('#tablaExperiencia tbody').html(html);
            });
        }

        $('#btnGuardarExperiencia').click(function() {

            let formDataExperiencia = new FormData();
            formDataExperiencia.append('entidad', $('#entidad').val());
            formDataExperiencia.append('cargo', $('#cargo').val());
            formDataExperiencia.append('fecha_inicio', $('#fecha_inicio_experiencia').val());
            formDataExperiencia.append('fecha_fin', $('#fecha_fin_experiencia').val());
            formDataExperiencia.append('archivo', $('#archivo_experiencia')[0].files[0]);

            if (!validarCampos('#entidad, #cargo, #fecha_inicio_experiencia, #fecha_fin_experiencia')) {
                Swal.fire('Atención', 'Complete los campos obligatorios', 'warning');
                return;
            }
            // Validar archivo
            if (!$('#archivo_experiencia')[0].files.length) {
                Swal.fire('Atención', 'Debe adjuntar un archivo', 'warning');
                return;
            }

            $.ajax({
                url: 'postulante/experiencia/guardar',
                type: 'POST',
                data: formDataExperiencia,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        cargarExperiencia();
                        Swal.fire('Correcto', 'Experiencia agregada', 'success');
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                }
            });
        });

        $(document).on('click', '.btn-eliminar-experiencia', function() {

            let id = $(this).data('id');

            Swal.fire({
                title: '¿Eliminar Experiencia?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: 'postulante/experiencia/eliminar/' + id,
                        type: 'POST',
                        dataType: 'json',
                        success: function(res) {

                            if (res.status === 'success') {
                                cargarExperiencia();
                                Swal.fire('Eliminado', 'Registro eliminado correctamente', 'success');
                            } else {
                                Swal.fire('Error', res.message, 'error');
                            }

                        },
                        error: function() {
                            Swal.fire('Error', 'No se pudo eliminar el registro', 'error');
                        }
                    });

                }

            });


        });

        cargarExperiencia();
    });
</script>







<!-- AGREGAR FORMACION PROFESIONAL -->
<script>
    $(document).ready(function() {

        function cargarFormacion() {
            $.getJSON('postulante/formacion/listar', function(res) {
                let html = '';
                res.forEach(f => {
                    html += `
                    <tr>
                        <td>${f.nivel}</td>
                        <td>${f.carrera}</td>
                        <td>${f.institucion}</td>
                        <td>
                            <a href="${f.archivo}" target="_blank">
                            <i class="fa fa-file-pdf text-danger"></i>Ver</a>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-danger btn-eliminar" data-id="${f.id_formacion}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;
                });
                $('#tablaFormacion tbody').html(html);
            });
        }

        $('#btnGuardarFormacion').on('click', function() {

            let formDataFormacion = new FormData();
            formDataFormacion.append('nivel', $('#nivel').val());
            formDataFormacion.append('carrera', $('#carrera').val());
            formDataFormacion.append('institucion', $('#institucion').val());
            formDataFormacion.append('fecha_inicio', $('#fecha_inicio').val());
            formDataFormacion.append('fecha_fin', $('#fecha_fin').val());
            formDataFormacion.append('archivo', $('#archivo_formacion')[0].files[0]);

            if (!validarCampos('#nivel, #carrera, #institucion, #fecha_inicio, #fecha_fin')) {
                Swal.fire('Atención', 'Complete los campos obligatorios', 'warning');
                return;
            }
            // Validar archivo
            if (!$('#archivo_formacion')[0].files.length) {
                Swal.fire('Atención', 'Debe adjuntar un archivo', 'warning');
                return;
            }


            $.ajax({
                url: 'postulante/formacion/guardar',
                type: 'POST',
                data: formDataFormacion,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        cargarFormacion();
                        Swal.fire('Correcto', 'Formación agregada', 'success');
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                }
            });
        });
        $(document).on('click', '.btn-eliminar', function() {

            let id = $(this).data('id');

            Swal.fire({
                title: '¿Eliminar formación?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: 'postulante/formacion/eliminar/' + id,
                        type: 'POST',
                        dataType: 'json',
                        success: function(res) {

                            if (res.status === 'success') {
                                cargarFormacion();
                                Swal.fire('Eliminado', 'Registro eliminado correctamente', 'success');
                            } else {
                                Swal.fire('Error', res.message, 'error');
                            }

                        },
                        error: function() {
                            Swal.fire('Error', 'No se pudo eliminar el registro', 'error');
                        }
                    });

                }

            });

        });
        cargarFormacion();
    });
</script>


<!-- GUARDAR DATOS PERSONALES-->
<script>
    function cargarDataPersonal() {
        $.getJSON('postulante/ver-datos', function(res) {

            $('#postulante_id').val(res.postulante_id),
                $('#dni').val(res.dni),
                $('#nombres').val(res.nombres),
                $('#paterno').val(res.paterno),
                $('#materno').val(res.materno),
                $('#fecha_nacimiento').val(res.fecha_nacimiento),
                $('#direccion').val(res.direccion),
                $('#telefono').val(res.telefono)

        });
    }
    cargarDataPersonal();

    $(document).ready(function() {

        $('#btnGuardarPostulante').on('click', function() {

            let data = {
                postulante_id: $('#postulante_id').val(),
                dni: $('#dni').val(),
                nombres: $('#nombres').val(),
                paterno: $('#paterno').val(),
                materno: $('#materno').val(),
                fecha_nacimiento: $('#fecha_nacimiento').val(),
                direccion: $('#direccion').val(),
                telefono: $('#telefono').val()
            };

            if (!data.dni || !data.nombres || !data.paterno || !data.materno) {
                Swal.fire('Atención', 'Complete los campos obligatorios', 'warning');
                return;
            }

            $.ajax({
                url: 'postulante/guardar-datos',
                type: 'POST',
                dataType: 'json',
                data: data,
                success: function(res) {

                    if (res.status === 'success') {
                        $('#postulante_id').val(res.postulante_id);

                        Swal.fire({
                            icon: 'success',
                            title: 'Datos guardados',
                            timer: 1200,
                            showConfirmButton: false
                        });

                        // 👉 aquí puedes permitir avanzar al siguiente paso del wizard
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }

                },
                error: function() {
                    Swal.fire('Error', 'No se pudo guardar la información', 'error');
                }
            });
        });

    });
</script>



<script>
    //Custom design form example
    $(".tab-wizard").steps({
        headerTag: "h6",
        bodyTag: "section",
        transitionEffect: "fade",
        titleTemplate: '<span class="step">#index#</span> #title#',
        labels: {
            finish: "",
            next: "Siguiente",
            previous: "Anterior"
        },
        onFinished: function(event, currentIndex) {
            
        },
    });

    $('#formDatosPersonales').submit(function(e) {
        e.preventDefault();

        let formData = new FormData(this);
        formData.append('id_postulacion', $('#id_postulacion').val());

        $.ajax({
            url: 'postulante/postulacion/datos-personales',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function() {
                toastr.success('Datos guardados');
            }
        });
    });
</script>
<div class="row">
    <div class="col-md-8">
        <!-- DATOS GENERALES -->
        <h6 class="border-bottom">Datos del Postulante</h6>
        <h2><strong class="badge text-bg-success">Plaza:</strong> <span id="ev_plaza"> <?= $postulacion['codigo_plaza'] . ' ' . $postulacion['cargo'] ?> </span></h2>
        <p>
            <strong>Nombre:</strong>
            <span id="ev_nombre">
                <?= $postulante['paterno'] . ' ' . $postulante['materno'] . ' ' . $postulante['nombres'] ?>
            </span>
        </p>

        <!-- FORMACION -->
        <h6 class="mt-3">Formación Profesional</h6>
        <table class="table table-sm table-bordered table-sm">
            <thead>
                <tr>
                    <th>Nivel</th>
                    <th>Carrera</th>
                    <th>Institución</th>
                    <th>Documento</th>
                </tr>
            </thead>
            <tbody id="tablaFormacionEval">
                <?php if ($formacion) : ?>
                    <!-- TRUE -->
                    <?php foreach ($formacion as $item) : ?>
                        <tr>
                            <td><?= $item['nivel'] ?></td>
                            <td><?= $item['carrera'] ?></td>
                            <td><?= $item['institucion'] ?></td>
                            <td>
                                <a href="<?= base_url($item['ruta']) ?>" target="_blank" rel="noopener noreferrer"class="badge text-bg-primary"> Ver </a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>

        <!-- EXPERIENCIA -->
        <h6 class="mt-3">Experiencia Laboral</h6>
        <table class="table table-sm table-bordered table-sm">
            <thead>
                <tr>
                    <th>Entidad</th>
                    <th>Cargo</th>
                    <th>Periodo</th>
                    <th>Documento</th>
                    <th>Ptos</th>
                </tr>
            </thead>
            <tbody id="tablaExperienciaEval">
                <?php if ($experiencia) :
                    $granTotal = 0; ?>
                    <!-- TRUE -->
                    <?php foreach ($experiencia as $item) :
                    ?>

                        <tr>

                            <td><?= $item['entidad'] ?></td>
                            <td><?= $item['cargo'] ?></td>

                            <td>
                                <?php
                                $fechaInicio = new DateTime($item['fecha_inicio']);
                                $fechaFin    = new DateTime($item['fecha_fin']);
                                $fechaFin->modify('+1 day');
                                // Calculamos la diferencia
                                $diferencia = $fechaInicio->diff($fechaFin);

                                // Extraemos los años y los meses
                                $totalMeses = ($diferencia->y * 12) + $diferencia->m;
                                $granTotal += $totalMeses;
                                echo $totalMeses ?> meses</td>
                            <td>
                                <a href="<?= base_url($item['ruta']) ?>" target="_blank" rel="noopener noreferrer"class="badge text-bg-primary"> Ver </a>
                            </td>
                            <td>
                                <input type="number" class="form-control form-control-sm input_experiencia" min="0" max="100" value="<?= $totalMeses ?>" id="exp_<?= $item['id_experiencia'] ?>" />
                            </td>
                        </tr>
                    <?php endforeach ?>
                    <tr class="table font-weight-bold">
                        <td colspan="4" class="text-right"><strong>TOTAL MESES:</strong></td>
                        <td>
                            <span id="sumaTotalMeses"><?= $granTotal ?></span> meses
                        </td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>
        <script>
            $(document).ready(function() {
                // Función para recalcular
                function actualizarSumaTotal() {
                    let suma = 0;

                    // Recorremos todos los inputs con la clase
                    $('.input_experiencia').each(function() {
                        let valor = parseInt($(this).val());

                        // Validamos que sea un número para evitar el NaN
                        if (!isNaN(valor)) {
                            suma += valor;
                        }
                    });

                    // Mostramos el resultado con una pequeña animación opcional
                    $('#sumaTotalMeses').text(suma);
                }

                // Escuchamos el evento 'input' en los campos
                // Usamos delegación por si acaso agregas filas dinámicamente
                $(document).on('input', '.input_experiencia', function() {
                    actualizarSumaTotal();
                });
            });
        </script>
        <!-- CAPACITACION -->
        <h6 class="mt-3">Capacitaciones</h6>
        <table class="table table-sm table-bordered table-sm">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Institución</th>
                    <th>Horas</th>
                    <th>Documento</th>
                </tr>
            </thead>
            <tbody id="tablaFormacionEval">
                <?php if ($capacitacion) : ?>
                    <!-- TRUE -->
                    <?php foreach ($capacitacion as $item) : ?>
                        <tr>
                            <td><?= $item['nombre'] ?></td>
                            <td><?= $item['institucion'] ?></td>
                            <td><?= $item['horas'] ?></td>
                            <td>
                                <a href="<?= base_url($item['ruta']) ?>" target="_blank" rel="noopener noreferrer"class="badge text-bg-primary"> Ver </a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>

        <!-- EXTRAS -->
        <h6 class="mt-3">Información Extra</h6>
        <table class="table table-sm table-bordered table-sm">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Nombre</th>
                    <th>Documento</th>
                    <th>Obligatorio</th>
                </tr>
            </thead>
            <tbody id="tablaExtrasEval">
                <?php if ($extra) : ?>
                    <!-- TRUE -->
                    <?php foreach ($extra as $item) : ?>
                        <tr>

                            <td><?= $item['tipo'] ?></td>
                            <td><?= $item['descripcion'] ?></td>
                            <td>
                                <a href="<?= base_url($item['ruta']) ?>" target="_blank" rel="noopener noreferrer" class="badge text-bg-primary"> Ver </a>
                            </td>
                            <td>

                                <input type="text" class="form-control form-control-sm" min="0" max="100" id="ext_<?= $item['id_extra'] ?>" />
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>

            </tbody>
        </table>

        <!-- EVALUACION EXTRAS -->
        <table class="table table-bordered table-sm">
            <tbody id="evalExtras"></tbody>
        </table>

    </div>
    <div class="col-md-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-bg-primary">
                    <h5 class="mb-0 text-white">Pre Evaluación</h5>
                </div>
                <div class="card card-body">

                    <div>
                        <h4 class="card-title mb-3">Formacion Profesional</h4>

                        <div class="form-check py-2 form-switch">
                            <input class="form-check-input" type="checkbox" id="titulo" name="titulo" <?php  if($calificacionPrevia){
                                if($calificacionPrevia['titulo'] == 1){ echo 'checked'; }
                            } ?> >
                            <label class="form-check-label" for="titulo">Titulo</label>
                        </div>
                        <div class="form-check py-2 form-switch">
                            <input class="form-check-input" type="checkbox" id="titulo_especialidad" name="titulo_especialidad"
                            <?php  if($calificacionPrevia){
                                if($calificacionPrevia['titulo_especialidad'] == 1){ echo 'checked'; }
                            } ?>>
                            <label class="form-check-label" for="titulo_especialidad">Titulo Especialidad</label>
                        </div>
                        <div class="form-check py-2 form-switch">
                            <input class="form-check-input" type="checkbox" id="doctorado" name="doctorado"
                            <?php  if($calificacionPrevia){
                                if($calificacionPrevia['doctorado'] == 1){ echo 'checked'; }
                            } ?> >
                            <label class="form-check-label" for="doctorado">Doctorado</label>
                        </div>
                        <div class="form-check py-2 form-switch">
                            <input class="form-check-input" type="checkbox" id="maestria" name="maestria"
                            <?php if($calificacionPrevia){
                                if($calificacionPrevia['maestria'] == 1){ echo 'checked'; }
                            } ?>>
                            <label class="form-check-label" for="maestria">Maestría</label>
                        </div>

                    </div>
                    <div>
                        <h4 class="card-title mb-3">Experiencia</h4>

                    <div class="form-check py-2 form-switch">
                        <input class="form-check-input" type="checkbox" id="experiencia" name="experiencia" 
                        <?php  if($calificacionPrevia){
                            if($calificacionPrevia['experiencia'] == 1){ echo 'checked'; }
                        } ?>>
                        <label class="form-check-label" for="experiencia">Experiencia</label>
                    </div>

                    </div>
                    <div>
                        <h4 class="card-title mb-3">Capacitacion</h4>

                        <div class="form-check py-2 form-switch">
                            <input class="form-check-input" type="checkbox" id="capacitacion" name="capacitacion" 
                            <?php  if($calificacionPrevia){
                                if($calificacionPrevia['capacitacion'] == 1){ echo 'checked'; }
                            } ?>>
                            <label class="form-check-label" for="capacitacion">Capacitacion</label>
                        </div>

                    </div>
                    <div>
                        <h4 class="card-title mb-3">Calificación</h4>
                        <div class="mb-3">
                            <label class="form-label">Estado</label>
                            <select class="form-select" name="estado_evaluacion" id="estado_evaluacion">
                                <option value="NO APTO" 
                                <?php  if($calificacionPrevia){
                                    if($calificacionPrevia['estado_evaluacion'] == 'NO APTO'){ echo 'selected'; }
                                }
                                  ?> 
                                >NO APTO</option>
                                <option value="APTO" 
                                <?php  if($calificacionPrevia){
                                    if($calificacionPrevia['estado_evaluacion'] == 'APTO'){ echo 'selected'; }
                                }
                                  ?> 
                                >APTO</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Observación</label>
                            <textarea class="form-control" name="observacion" id="observacion" rows="3"><?php  if($calificacionPrevia){
                                     echo $calificacionPrevia['observacion']; }
                                  ?></textarea>
                        </div>
                    </div>
                    <div class="guardar_datos">
                        <input type="hidden" id="id_postulacion" value="<?= $postulacion['id_postulacion'] ?>" />
                        <input type="hidden" id="id_postulante" value="<?= $postulacion['id_postulante'] ?>" />
                        <input type="hidden" id="id_convocatoria" value="<?= $postulacion['id_convocatoria'] ?>" />
                    </div>
                    <button class="btn btn-success w-100 mb-2" id="btnGuardarEvaluacion">
                        <i class="bi bi-save me-2"></i> Guardar Evaluación
                    </button>

                    <button class="btn btn-secondary w-100 mb-2" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i> Cerrar
                    </button>
                </div>
            </div>

        </div>



    </div>
</div>
<script>
    $('#btnGuardarEvaluacion').click(function() {

        let data = {
            id_postulacion: $('#id_postulacion').val(),
            id_postulante: $('#id_postulante').val(),
            id_convocatoria: $('#id_convocatoria').val(),

            titulo: $('#titulo').is(':checked') ? 1 : 0,
            titulo_especialidad: $('#titulo_especialidad').is(':checked') ? 1 : 0,
            doctorado: $('#doctorado').is(':checked') ? 1 : 0,
            maestria: $('#maestria').is(':checked') ? 1 : 0,
            experiencia: $('#experiencia').is(':checked') ? 1 : 0,
            experiencia_meses: $('#sumaTotalMeses').text(),
            capacitacion: $('#capacitacion').is(':checked') ? 1 : 0,

            estado_evaluacion: $('#estado_evaluacion').val(),
            observacion: $('#observacion').val()
        };

        Swal.fire({
            title: '¿Guardar evaluación?',
            text: 'Esta acción no se puede modificar',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {

            if (result.isConfirmed) {

                $.post('comision/calificacionprevia/guardar', data, function(res) {

                    if (res.status === 'success') {
                        Swal.fire(
                            'Guardado',
                            'Evaluación registrada correctamente',
                            'success'
                        );

                        $('#modalEvaluar').modal('hide');

                    } else {
                        Swal.fire(
                            'Error',
                            res.message,
                            'error'
                        );
                    }

                }, 'json');

            }

        });

    });
</script>
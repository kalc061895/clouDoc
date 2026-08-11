<div class="container-fluid">

    <h4 class="mb-3">Resultados de Pre Evaluación</h4>

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Convocatoria</label>
            <select class="form-select" id="selectConvocatoria">
                <option value="">Seleccione convocatoria</option>
            </select>
        </div>
    </div>

    <div id="contenedorResultados"></div>

</div>




<script>
    $(document).ready(function() {

        setTimeout(() => {
            // 🔹 Cargar convocatorias
            $.getJSON('admin/postulacion/convocatorias', function(data) {
                console.log(data);
                var html = '<option value="">Seleccione convocatoria</option>';
                data.forEach(c => {
                    html += `<option value="${c.id_convocatoria}">
                ${c.codigo} - ${c.titulo}
            </option>`;
                });
                $('#selectConvocatoria').html(html);
            });
        }, 2000);

        // 🔹 Al seleccionar convocatoria
        $('#selectConvocatoria').on('change', function() {

            var id = $(this).val();
            $('#contenedorResultados').html('');

            if (!id) return;

            $.post('comision/reporte/preevaluacion/' + id, function(data) {

                var html = '';

                Object.keys(data).forEach(plaza => {

                    html += `
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        ${plaza}
                    </div>
                    <div class="card-body">

                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>DNI</th>
                                    <th>Postulante</th>
                                    <th>Título</th>
                                    <th>Esp.</th>
                                    <th>Doc.</th>
                                    <th>Maes.</th>
                                    <th>Exp.</th>
                                    <th>Meses</th>
                                    <th>Cap.</th>
                                    <th>Estado</th>
                                    <th>Observación</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                    var i = 1;
                    data[plaza].forEach(p => {
                        html += `
                        <tr>
                            <td>${i++}</td>
                            <td>${p.dni}</td>
                            <td>${p.postulante}</td>
                            <td class="text-center">${p.titulo == 1 ? '✔' : '-'}</td>
                            <td class="text-center">${p.titulo_especialidad == 1 ? '✔' : '-'}</td>
                            <td class="text-center">${p.doctorado == 1 ? '✔' : '-'}</td>
                            <td class="text-center">${p.maestria == 1 ? '✔' : '-'}</td>
                            <td class="text-center">${p.experiencia == 1 ? '✔' : '-'}</td>
                            <td class="text-center">${p.experiencia_meses}</td>
                            <td class="text-center">${p.capacitacion == 1 ? '✔' : '-'}</td>
                            <td class="fw-bold">${p.estado_evaluacion}</td>
                            <td class="">${p.observacion}</td>
                        </tr>
                    `;
                    });

                    html += `
                            </tbody>
                        </table>
                    </div>
                </div>
                `;
                });

                $('#contenedorResultados').html(html);
            }, 'json');
        });

    });
</script>
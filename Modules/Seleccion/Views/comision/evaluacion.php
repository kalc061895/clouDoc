<div class="card mb-3">
    <div class="card-body">
        <label class="form-label">Seleccione convocatoria</label>
        <select id="selectConvocatoria" class="form-select">
            <option value="">-- Seleccione --</option>
        </select>
    </div>
</div>

<div class="card">
    <div class="card-body datatable-responsive">
        <table class="table table-hovered w-100" id="tablaPostulantes">
            <thead>
                <tr>

                    <th>Apellidos y Nombres</th>
                    <th>DNI</th>
                    <th>Plaza</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalEvaluacion" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-fullscreen-xl-down">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Evaluación de Postulante (Solo lectura)
                </h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="contenedorWizard">
                <!-- aquí se carga el wizard por AJAX -->
            </div>

        </div>
    </div>
</div>


<script>
    var tabla;

    $(document).ready(function() {

        // 1. Cargar convocatorias
        setTimeout(() => {

            $.getJSON('comision/evaluacion/convocatorias', function(data) {
                data.forEach(c => {
                    $('#selectConvocatoria').append(
                        `<option value="${c.id_convocatoria}">
                    ${c.codigo} - ${c.titulo}
                    </option>`
                    );
                });
            });
        }, 1000);

        // 2. Inicializar tabla
        tabla = $('#tablaPostulantes').DataTable({
            data: [],
            columns: [{
                    data: null,
                    render: d => `${d.nombres} ${d.paterno} ${d.materno}`
                },
                {
                    data: 'dni'
                },
                {
                    data: null,
                    render: d => `<strong>${d.codigo_plaza}</strong><br>${d.cargo}`
                },


                {
                    data: 'estado_evaluacion',
                    render: d => {
                        if (!d) return 'SIN EVALUAR';
                        const color = (d.toUpperCase() === 'APTO') ? 'success' : 'danger';
                        return `<span class="badge text-bg-${color}">${d}</span>`;
                    }
                },
                {
                    data: 'id_postulacion',
                    render: id => `
                    <button class="btn btn-sm btn-primary btn-evaluar"
                            data-id="${id}">
                        <i class="fa fa-eye"></i> Evaluar
                    </button> `
                },
            ]
        });

        // 3. Al cambiar convocatoria
        $('#selectConvocatoria').change(function() {

            let id = $(this).val();
            if (!id) return;

            $.getJSON(`comision/evaluacion/postulantes/${id}`, function(res) {
               
                tabla.clear().rows.add(res).draw();
            });

        });

        $(document).on('click', '.btn-evaluar', function() {

            let idPostulacion = $(this).data('id');

            $('#contenedorWizard').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary"></div>
                </div>
            `);

            $('#modalEvaluacion').modal('show');

            $.get(
                'comision/evaluar/ver/' + idPostulacion,
                function(html) {
                    $('#contenedorWizard').html(html);
                }
            );

        });


    });
</script>
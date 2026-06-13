<div class="container-fluid">

    <h4 class="mb-3">Resumen de Postulantes por Convocatoria</h4>

    <!-- SELECT CONVOCATORIA -->
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Convocatoria</label>
            <select class="form-select" id="selectConvocatoria">
                <option value="">Seleccione convocatoria</option>
            </select>
        </div>
    </div>

    <!-- TABLA PLAZAS -->
    <div class="card">
        <div class="card-body">

            <table class="table table-bordered table-striped w-100" id="tablaPlazas">
                <thead>
                    <tr>
                        <th>Código Plaza</th>
                        <th>Cargo</th>
                        <th>Total Postulantes</th>
                    </tr>
                </thead>

                <tbody></tbody>

                <tfoot>
                    <tr>
                        <th colspan="2" class="text-end">TOTAL POSTULANTES</th>
                        <th id="totalPostulantes">0</th>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>

</div>



<script>
    $(document).ready(function() {

        // 🔹 Cargar convocatorias
        setTimeout(() => {
            $.getJSON('admin/postulacion/convocatorias', function(data) {

                var html = '<option value="">Seleccione convocatoria</option>';

                data.forEach(c => {
                    html += `<option value="${c.id_convocatoria}">
                        ${c.codigo} - ${c.titulo}
                     </option>`;
                });

                $('#selectConvocatoria').html(html);
            });
        }, 2000);


        // 🔹 Al seleccionar convocatoria → cargar plazas
        $('#selectConvocatoria').on('change', function() {

            let id = $(this).val();

            if (!id) {
                $('#tablaPlazas tbody').html(`
                <tr>
                    <td colspan="3" class="text-center text-muted">
                        Seleccione una convocatoria
                    </td>
                </tr>
            `);
                return;
            }

            $.getJSON('admin/postulacion/plazas/' + id, function(data) {

                let html = '';
                let total = 0;
                if (data.length === 0) {
                    html = `
                    <tr>
                        <td colspan="3" class="text-center text-muted">
                            No hay plazas registradas
                        </td>
                    </tr>
                `;
                } else {
                    data.forEach(p => {
                        total += parseInt(p.total_postulantes);
                        html += `
                        <tr>
                            <td>${p.codigo_plaza}</td>
                            <td>${p.cargo}</td>
                            <td class="text-center fw-bold">${p.total_postulantes}</td>
                        </tr>
                    `;
                    });
                }
                $('#totalPostulantes').text(total);
                $('#tablaPlazas tbody').html(html);
            });

        });

    });
</script>
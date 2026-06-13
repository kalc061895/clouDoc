<div class="container-fluid">

    <h4 class="mb-3">📢 Convocatorias Vigentes</h4>

    <div class="row" id="contenedorConvocatorias"></div>

</div>


<div class="modal fade" id="modalPostulacion" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Registro de Postulación</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="id_postulacion">

                <!-- AQUÍ VA TU WIZARD -->
                <?= view('contratacion/postulante/convocatorias/wizard') ?>
            </div>

        </div>
    </div>
</div>


<script>
    $(document).on('click', '.btn-postular', function() {

        let idConv = $(this).data('convocatoria');

        $.post('postulante/postulacion/iniciar', {
            id_convocatoria: idConv
        }, function(res) {

            if (res.status === 'login') {
                window.location.href = '/login';
                return;
            }

            if (res.status === 'ok') {
                $('#modalPostulacion').modal('show');
                $('#id_postulacion').val(res.id_postulacion);
            }

        }, 'json');

    });


    $(document).ready(function() {

        $.getJSON('postulante/convocatorias/listar', function(data) {

            if (data.length === 0) {
                $('#contenedorConvocatorias').html(
                    '<div class="alert alert-info">No hay convocatorias vigentes.</div>'
                );
                return;
            }

            let html = '';

            data.forEach(c => {

                let boton = '';

                if (c.estado === 'POSTULADO') {
                    // YA POSTULADO
                    boton = `
                    <a href="postulante/postulacion/constancia/${c.id_postulacion}"
                       class="btn btn-success w-100" target="_blank">
                        <i class="fa fa-file-pdf"></i> Constancia
                    </a>`;
                } else {
                    // NO POSTULADO
                    boton = `
                    <button class="btn btn-primary btn-postular w-100"
                            data-convocatoria="${c.id_convocatoria}">
                        Postular
                    </button>`;
                }

                html += `
                <div class="col-md-4">
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body">
                            <h5>${c.titulo}</h5>
                            <p class="text-muted mb-1">
                                <strong>Código:</strong> ${c.codigo}
                            </p>
                            <p class="mb-1">
                                <strong>Vigente hasta:</strong> ${c.fecha_fin}
                            </p>
                            ${boton}
                        </div>
                    </div>
                </div>
            `;
            });

            $('#contenedorConvocatorias').html(html);
        });

    });
</script>
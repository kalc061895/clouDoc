<div id="moduloRequisitos" data-convocatoria-id="<?= esc($convocatoriaId) ?>">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold">Requisitos por plaza</h4>
            <p class="text-muted small mb-0">Seleccione una plaza para definir los requisitos que evaluaran los postulantes.</p>
        </div>
        <a class="btn btn-outline-primary btn-sm" href="#" data-seccion="cargos">Gestionar plazas</a>
    </div>

    <div class="card border-0 bg-white shadow-sm mb-3">
        <div class="card-body">
            <label for="selectorPlazaRequisito" class="form-label fw-semibold">Plaza de la convocatoria</label>
            <select id="selectorPlazaRequisito" class="form-select">
                <option value="">Cargando plazas...</option>
            </select>
        </div>
    </div>

    <div id="contenidoRequisitos" class="bg-white rounded border p-3">
        <div class="text-center text-muted py-4 small">Seleccione una plaza para ver y configurar sus requisitos.</div>
    </div>
</div>

<script>
    (function () {
        const $modulo = $('#moduloRequisitos');
        const convocatoriaId = $modulo.data('convocatoria-id');
        const urlCargos = '<?= base_url('seleccion/admin/cargos-convocatoria/listar') ?>/' + convocatoriaId;
        const urlRequisitos = '<?= base_url('seleccion/admin/requisitos/partial') ?>';

        function escapeHtml(valor) {
            return $('<div>').text(valor || '').html();
        }

        function cargarRequisitos(cargoId) {
            if (!cargoId) {
                $('#contenidoRequisitos').html('<div class="text-center text-muted py-4 small">Seleccione una plaza para ver y configurar sus requisitos.</div>');
                return;
            }

            $('#contenidoRequisitos').html('<div class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary" role="status"></div></div>');
            $('#contenidoRequisitos').load(urlRequisitos + '/' + cargoId, function (_, estado) {
                if (estado === 'error') {
                    $(this).html('<div class="alert alert-danger mb-0">No se pudieron cargar los requisitos de la plaza.</div>');
                }
            });
        }

        $.get(urlCargos).done(function (respuesta) {
            const cargos = respuesta.data || respuesta;
            let opciones = '<option value="">Seleccione una plaza...</option>';

            if (Array.isArray(cargos) && cargos.length) {
                cargos.forEach(function (cargo) {
                    const nombre = [cargo.car_codigo, cargo.car_denominacion].filter(Boolean).join(' - ');
                    const ubicacion = [cargo.cco_area, cargo.cco_establecimiento].filter(Boolean).join(' / ');
                    opciones += '<option value="' + cargo.cco_ide + '">' + escapeHtml(nombre) + (ubicacion ? ' (' + escapeHtml(ubicacion) + ')' : '') + '</option>';
                });
            } else {
                opciones = '<option value="">No hay plazas registradas</option>';
                $('#contenidoRequisitos').html('<div class="alert alert-info mb-0">Primero registre al menos una plaza en la pestaña Cargos y plazas.</div>');
            }

            $('#selectorPlazaRequisito').html(opciones);
        }).fail(function () {
            $('#selectorPlazaRequisito').html('<option value="">No se pudieron cargar las plazas</option>');
            $('#contenidoRequisitos').html('<div class="alert alert-danger mb-0">No se pudo obtener la lista de plazas de la convocatoria.</div>');
        });

        $('#selectorPlazaRequisito').on('change', function () {
            cargarRequisitos(this.value);
        });

        $modulo.on('click', '[data-seccion="cargos"]', function (event) {
            event.preventDefault();
            $('#navTabsConvocatoria .nav-link[data-seccion="cargos"]').trigger('click');
        });
    })();
</script>

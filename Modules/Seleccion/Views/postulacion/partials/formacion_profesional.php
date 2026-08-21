<form id="formProfesional" autocomplete="off">
    <?= csrf_field() ?>

    <div class="row g-3">
        <!-- Profesión (Catálogo Dinámico) -->
        <div class="col-md-6">
            <label class="form-label fw-bold text-dark">
                Profesión / Carrera Principal <span class="text-danger">*</span>
            </label>
            <select class="form-select" name="ppr_pro_ide" id="ppr_pro_ide" required>
                <option value="">Cargando profesiones...</option>
            </select>
            <div class="invalid-feedback" id="err_ppr_pro_ide"></div>
        </div>

        <!-- Institución -->
        <div class="col-md-6">
            <label class="form-label fw-bold text-dark">
                Universidad / Institución Educativa <span class="text-danger">*</span>
            </label>
            <input type="text"
                class="form-control"
                name="ppr_institucion"
                placeholder="Ej: Universidad Nacional del Altiplano"
                value="<?= esc($profesion['ppr_institucion'] ?? '') ?>"
                required>
            <div class="invalid-feedback" id="err_ppr_institucion"></div>
        </div>

        <!-- Grado Académico -->
        <div class="col-md-4">
            <label class="form-label fw-bold text-dark">
                Grado Académico <span class="text-danger">*</span>
            </label>
            <select class="form-select" name="ppr_grado" required>
                <option value="">-- Seleccione --</option>
                <?php
                $grados = ['Bachiller', 'Titulado', 'Maestría', 'Doctorado'];
                foreach ($grados as $g):
                ?>
                    <option value="<?= $g ?>" <?= (isset($profesion['ppr_grado']) && $profesion['ppr_grado'] == $g) ? 'selected' : '' ?>>
                        <?= $g ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback" id="err_ppr_grado"></div>
        </div>

        <!-- Denominación del Título -->
        <div class="col-md-5">
            <label class="form-label fw-bold text-dark">
                Título / Denominación Profesional <span class="text-danger">*</span>
            </label>
            <input type="text"
                class="form-control"
                name="ppr_titulo"
                placeholder="Ej: Ingeniero de Sistemas"
                value="<?= esc($profesion['ppr_titulo'] ?? '') ?>"
                required>
            <div class="invalid-feedback" id="err_ppr_titulo"></div>
        </div>

        <!-- Fecha del Título / Grado -->
        <div class="col-md-3">
            <label class="form-label fw-bold text-dark">
                Fecha de Expedición <span class="text-danger">*</span>
            </label>
            <input type="date"
                class="form-control"
                name="ppr_fecha"
                value="<?= esc($profesion['ppr_fecha'] ?? '') ?>"
                required>
            <div class="invalid-feedback" id="err_ppr_fecha"></div>
        </div>

        <!-- N° Colegiatura -->
        <div class="col-md-6">
            <label class="form-label fw-bold text-dark">
                N° Colegiatura (Opcional)
            </label>
            <input type="text"
                class="form-control"
                name="ppr_colegiatura"
                placeholder="Ej: CIP 123456"
                value="<?= esc($profesion['ppr_colegiatura'] ?? '') ?>">
            <div class="invalid-feedback" id="err_ppr_colegiatura"></div>
        </div>

        <!-- N° Habilitación -->
        <div class="col-md-6">
            <label class="form-label fw-bold text-dark">
                N° Constancia Habilitación (Opcional)
            </label>
            <input type="text"
                class="form-control"
                name="ppr_habilitacion"
                placeholder="Ej: HAB-2026-987"
                value="<?= esc($profesion['ppr_habilitacion'] ?? '') ?>">
            <div class="invalid-feedback" id="err_ppr_habilitacion"></div>
        </div>
    </div>

    <!-- BOTONES DE ACCIÓN -->
    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
        <button type="submit" class="btn btn-primary px-4 rounded-pill" id="btnGuardarProfesional">
            <iconify-icon icon="solar:diskette-bold" class="align-middle me-1"></iconify-icon>
            Guardar y Continuar
        </button>
    </div>
</form>

<script>
    $(document).ready(function() {

        const idProfesionGuardada = '<?= $profesion['ppr_pro_ide'] ?? '' ?>';
        const selectProfesion = $('#ppr_pro_ide');

        // 1. Carga dinámica del catálogo de profesiones
        $.ajax({
            url: '<?= base_url('seleccion/api/profesiones') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                selectProfesion.empty().append('<option value="">-- Seleccione Profesión --</option>');

                // Manejo según si la API retorna el array directamente o dentro de res.data
                const items = Array.isArray(res) ? res : (res.data || []);

                $.each(items, function(index, item) {
                    const selected = (idProfesionGuardada && idProfesionGuardada == item.pro_ide) ? 'selected' : '';
                    selectProfesion.append(`<option value="${item.pro_ide}" ${selected}>${item.pro_nombre}</option>`);
                });
            },
            error: function() {
                selectProfesion.empty().append('<option value="">Error al cargar profesiones</option>');
            }

        });

        // 2. Evento submit del formulario
        $('#formProfesional').off('submit').on('submit', function(e) {
            e.preventDefault();

            const form = $(this);
            const btn = $('#btnGuardarProfesional');

            form.find('.is-invalid').removeClass('is-invalid');
            form.find('.invalid-feedback').text('');

            btn.prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                Guardando...
            `);

            $.ajax({
                url: '<?= base_url('seleccion/postulacion/profesion/guardar-profesion') ?>',
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(res) {
                    btn.prop('disabled', false).html(`
                        <iconify-icon icon="solar:diskette-bold" class="align-middle me-1"></iconify-icon>
                        Guardar y Continuar
                    `);

                    if (res.success) {
                        if (typeof window.irATabPostulacion === 'function') {
                            window.irATabPostulacion('academica');
                        }
                    } else if (res.errors) {
                        $.each(res.errors, function(field, msg) {
                            const input = form.find(`[name="${field}"]`);
                            input.addClass('is-invalid');
                            $(`#err_${field}`).text(msg);
                        });
                    }
                },
                error: function() {
                    btn.prop('disabled', false).html(`
                        <iconify-icon icon="solar:diskette-bold" class="align-middle me-1"></iconify-icon>
                        Guardar y Continuar
                    `);
                    alert('Ocurrió un problema de comunicación con el servidor.');
                }
            });
        });
    });
</script>
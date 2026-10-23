<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Remuneraciones por cargo y nivel<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="mb-1 text-dark fw-bold">Remuneraciones por cargo y nivel</h4>
            <p class="text-muted mb-0 small">Administre la remuneración mensual, guardias y retén de cada escala.</p>
        </div>
        <button id="nuevaRemuneracion" class="btn btn-primary btn-sm rounded-pill px-3">Nueva remuneración</button>
    </div>
    <div class="table-responsive">
        <table id="tablaRemuneraciones" class="table table-striped table-hover align-middle w-100">
            <thead class="table-light"><tr><th>ID</th><th>Cargo</th><th>Nivel</th><th>Moneda</th><th>Remuneración</th><th>G. diurna</th><th>G. nocturna</th><th>Estado</th><th>Acciones</th></tr></thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="modalRemuneracion" tabindex="-1" aria-labelledby="tituloRemuneracion" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title fw-bold" id="tituloRemuneracion">Nueva remuneración</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button></div>
        <div class="modal-body">
            <form id="formRemuneracion">
                <input type="hidden" id="rem_ide">
                <div class="row g-3">
                    <div class="col-md-8"><label for="rem_car_ide" class="form-label">Cargo</label>
                        <select id="rem_car_ide" class="form-select" required>
                            <option value="">Seleccione un cargo</option>
                            <?php foreach ($cargos as $cargo): ?>
                            <option value="<?= (int)$cargo['car_ide'] ?>"><?= esc($cargo['car_nombre']) ?><?= $cargo['car_estado'] ? '' : ' (inactivo)' ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col-md-4"><label for="rem_nivel" class="form-label">Nivel</label><input id="rem_nivel" class="form-control" required maxlength="50" placeholder="Ej. I, II, III"></div>
                    <div class="col-12"><div class="form-check form-switch"><input id="rem_estado" type="checkbox" class="form-check-input" checked><label for="rem_estado" class="form-check-label">Escala activa</label></div></div>
                    <div class="col-12"><p class="small text-muted mb-0">Importes en <span id="monedaRemuneracion">PEN</span>. Deje los importes opcionales vacíos si no están definidos; registre 0 cuando corresponda un importe cero.</p></div>
                    <?php foreach ($campos as $ruta => $etiqueta): $clave = str_replace('.', '_', $ruta); ?>
                        <?php if ($ruta === 'guardias.diurna'): ?><div class="col-12"><h6 class="mb-0">Guardias</h6></div><?php endif ?>
                        <?php if ($ruta === 'reten.diurno'): ?><div class="col-12"><h6 class="mb-0">Retén</h6></div><?php endif ?>
                        <?php if ($ruta === 'descuentos.base_calculo'): ?><div class="col-12"><h6>Parámetros de descuentos</h6><p class="small text-muted mb-0">Opcionales. Estos datos se almacenan para el cálculo posterior de faltas y tardanzas.</p></div><?php endif ?>
                        <div class="col-md-6"><label for="<?= esc($clave) ?>" class="form-label small"><?= esc($etiqueta) ?></label>
                            <input id="<?= esc($clave) ?>" data-ruta="<?= esc($ruta) ?>" class="form-control importe-remuneracion" type="number" step="0.01" min="<?= in_array($ruta, ['descuentos.dias_divisor', 'descuentos.horas_jornada'], true) ? '0.01' : '0' ?>" max="9999999999.99" <?= $ruta === 'remuneracion' ? 'required' : '' ?>>
                        </div>
                    <?php endforeach ?>
                </div>
            </form>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancelar</button><button form="formRemuneracion" type="submit" id="guardarRemuneracion" class="btn btn-primary btn-sm">Guardar</button></div>
    </div></div>
</div>
<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
<script>
(() => {
    const api = <?= json_encode(base_url('asistencia/gestordb/api/remuneraciones')) ?>;
    const csrfHeader = <?= json_encode(csrf_header()) ?>;
    let csrf = <?= json_encode(csrf_hash()) ?>;
    let ocupado = false;
    const modalElement = document.getElementById('modalRemuneracion');
    const modal = new bootstrap.Modal(modalElement);
    const texto = $.fn.dataTable.render.text();
    const importe = ruta => (data, type, row) => {
        const valor = ruta.split('.').reduce((obj, key) => obj?.[key], row.rem_configuracion);
        if (type !== 'display') return valor == null ? '' : Number(valor);
        return valor == null ? 'Sin definir' : texto.display(Number(valor).toLocaleString('es-PE', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
    };
    function error(xhr) {
        const mensajes = xhr.responseJSON?.messages;
        Swal.fire({icon: 'error', title: 'No se pudo completar la operación', text: mensajes ? Object.values(mensajes).join('\n') : 'Revise su conexión o recargue la página e inténtelo nuevamente.'});
    }
    const tabla = $('#tablaRemuneraciones').DataTable({
        ajax: {url: api, dataSrc: 'data', error},
        columns: [
            {data: 'rem_ide'}, {data: 'car_nombre', render: texto}, {data: 'rem_nivel', render: texto},
            {data: 'rem_configuracion.moneda', defaultContent: 'PEN', render: texto},
            {data: null, render: importe('remuneracion')}, {data: null, render: importe('guardias.diurna')}, {data: null, render: importe('guardias.nocturna')},
            {data: 'rem_estado', render: value => Number(value) === 1 ? '<span class="badge bg-success-subtle text-success">Activo</span>' : '<span class="badge bg-secondary-subtle text-secondary">Inactivo</span>'},
            {data: null, orderable: false, searchable: false, render: () => '<button type="button" class="btn btn-outline-warning btn-sm editar-rem me-1" title="Editar">Editar</button><button type="button" class="btn btn-outline-danger btn-sm eliminar-rem" title="Eliminar">Eliminar</button>'}
        ],
        language: {url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'}
    });
    function abrir(row = null) {
        $('#formRemuneracion')[0].reset();
        $('#rem_car_ide option[data-antiguo]').remove();
        $('#rem_ide').val(row?.rem_ide ?? '');
        if (row && !$('#rem_car_ide option').toArray().some(option => option.value === String(row.rem_car_ide))) {
            const option = new Option(row.car_nombre + ' (eliminado; seleccione otro)', row.rem_car_ide);
            option.dataset.antiguo = '1';
            $('#rem_car_ide').append(option);
        }
        $('#rem_car_ide').val(row?.rem_car_ide ?? '');
        $('#rem_nivel').val(row?.rem_nivel ?? '');
        $('#rem_estado').prop('checked', row ? Number(row.rem_estado) === 1 : true);
        $('#monedaRemuneracion').text(row?.rem_configuracion?.moneda ?? 'PEN');
        $('.importe-remuneracion').each(function () {
            const value = this.dataset.ruta.split('.').reduce((obj, key) => obj?.[key], row?.rem_configuracion);
            this.value = value ?? '';
        });
        $('#tituloRemuneracion').text(row ? 'Editar remuneración' : 'Nueva remuneración');
        modal.show();
    }
    function mutar(url, method, data, success) {
        if (ocupado) return;
        ocupado = true;
        $('#guardarRemuneracion, #nuevaRemuneracion, .editar-rem, .eliminar-rem').prop('disabled', true);
        $.ajax({url, type: method, contentType: 'application/json', data: JSON.stringify(data), headers: {[csrfHeader]: csrf},
            success: response => { success(); toastr.success(response.message); tabla.ajax.reload(null, false); }, error,
            complete: xhr => {
                csrf = xhr.getResponseHeader(csrfHeader) || csrf;
                ocupado = false;
                $('#guardarRemuneracion, #nuevaRemuneracion, .editar-rem, .eliminar-rem').prop('disabled', false);
            }
        });
    }
    $(modalElement).on('hide.bs.modal', event => { if (ocupado) event.preventDefault(); });
    $('#nuevaRemuneracion').on('click', () => { if (!ocupado) abrir(); });
    $('#tablaRemuneraciones').on('click', '.editar-rem', function () { if (!ocupado) abrir(tabla.row($(this).closest('tr')).data()); });
    $('#formRemuneracion').on('submit', function (event) {
        event.preventDefault();
        const id = $('#rem_ide').val();
        const data = {rem_car_ide: $('#rem_car_ide').val(), rem_nivel: $('#rem_nivel').val().trim(), rem_estado: $('#rem_estado').is(':checked') ? 1 : 0};
        $('.importe-remuneracion').each(function () { data[this.id] = this.value; });
        mutar(id ? `${api}/${id}` : api, id ? 'PUT' : 'POST', data, () => { ocupado = false; modal.hide(); });
    });
    $('#tablaRemuneraciones').on('click', '.eliminar-rem', async function () {
        if (ocupado) return;
        const row = tabla.row($(this).closest('tr')).data();
        const result = await Swal.fire({title: '¿Eliminar remuneración?', text: `${row.car_nombre} / ${row.rem_nivel}. Esta eliminación es permanente.`, icon: 'warning', showCancelButton: true, cancelButtonText: 'Cancelar', confirmButtonText: 'Sí, eliminar', confirmButtonColor: '#d33'});
        if (result.isConfirmed) mutar(`${api}/${row.rem_ide}`, 'DELETE', {}, () => {});
    });
})();
</script>
<?= $this->endSection() ?>

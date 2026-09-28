<div id="moduloEvaluacion" data-convocatoria="<?= esc($convocatoriaId) ?>">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1 fw-bold">Fichas de evaluación</h4>
            <p class="small text-muted mb-0">Configure fichas, criterios y reglas de puntaje.</p>
        </div>
        <button class="btn btn-primary btn-sm" id="nuevaFicha">Nueva ficha</button>
    </div>
    <div class="table-responsive bg-white border rounded">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Puntaje máximo</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="tablaFichas"></tbody>
        </table>
    </div>

    <div id="panelCriterios" class="mt-4 d-none"></div>

    <!-- Modal Ficha -->
    <div class="modal fade" id="modalFicha" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formFicha">
                    <input type="hidden" name="fie_ide">
                    <input type="hidden" name="fie_con_ide" value="<?= esc($convocatoriaId) ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Ficha de evaluación</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input class="form-control mb-2" name="fie_nombre" placeholder="Nombre" required>
                        <select class="form-select mb-2" name="fie_tipo">
                            <option value="CURRICULAR">Curricular</option>
                            <option value="ENTREVISTA">Entrevista</option>
                            <option value="TECNICA">Técnica</option>
                            <option value="OTRO">Otro</option>
                        </select>
                        <input class="form-control mb-2" name="fie_version" type="number" min="1" value="1"
                            placeholder="Versión">
                        <input class="form-control mb-2" name="fie_puntaje_maximo" type="number" min="0" step="0.01"
                            required placeholder="Puntaje máximo">
                        <select class="form-select" name="fie_estado">
                            <option value="ACTIVA">Activa</option>
                            <option value="INACTIVA">Inactiva</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Criterio (Estático en el DOM para evitar superposición de backdrops) -->
    <div class="modal fade" id="modalCriterio" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formCriterio">
                    <input type="hidden" name="cri_ide">
                    <input type="hidden" name="cri_fie_ide" id="cri_fie_ide">
                    <div class="modal-header">
                        <h5 class="modal-title">Criterio</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input class="form-control mb-2" name="cri_codigo" placeholder="Código">
                        <input class="form-control mb-2" name="cri_nombre" placeholder="Nombre" required>
                        <textarea class="form-control mb-2" name="cri_descripcion" placeholder="Descripción"></textarea>
                        <input class="form-control mb-2" name="cri_tipo" placeholder="Tipo">
                        <input class="form-control mb-2" type="number" min="0" step="0.01" name="cri_puntaje_maximo"
                            placeholder="Puntaje máximo" required>
                        <input class="form-control mb-2" type="number" min="1" name="cri_orden" value="1" required>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="cri_obligatorio" value="1" checked
                                id="obligatorio">
                            <label class="form-check-label" for="obligatorio">Obligatorio</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Regla (Estático en el DOM) -->
    <div class="modal fade" id="modalRegla" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formRegla">
                    <input type="hidden" name="rpu_ide">
                    <input type="hidden" name="rpu_cri_ide" id="rpu_cri_ide">
                    <div class="modal-header">
                        <h5 class="modal-title">Regla de puntaje</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input class="form-control mb-2" name="rpu_tipo" placeholder="Tipo">
                        <input class="form-control mb-2" name="rpu_condicion" placeholder="Condición">
                        <input class="form-control mb-2" type="number" step="0.01" name="rpu_valor_min"
                            placeholder="Valor mínimo">
                        <input class="form-control mb-2" type="number" step="0.01" name="rpu_valor_max"
                            placeholder="Valor máximo">
                        <input class="form-control mb-2" type="number" min="0" step="0.01" name="rpu_puntaje"
                            placeholder="Puntaje" required>
                        <input class="form-control mb-2" type="number" min="1" name="rpu_orden" value="1" required>
                        <textarea class="form-control" name="rpu_formula" placeholder="Fórmula opcional"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const $m = $('#moduloEvaluacion'),
            con = $m.data('convocatoria'),
            base = '<?= base_url('seleccion/admin/fichas-evaluacion') ?>',
            modalFicha = new bootstrap.Modal('#modalFicha'),
            modalCriterio = new bootstrap.Modal('#modalCriterio'),
            modalRegla = new bootstrap.Modal('#modalRegla'),
            esc = v => $('<div>').text(v || '').html(),
            err = x => Swal.fire('Atención', (x.responseJSON || {}).message || 'Error en la operación', 'warning');

        // Configuración global para Toasts de SweetAlert2
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        // Cargar Fichas
        function fichas() {
            $.get(base + '/listar/' + con, r => {
                const html = (r.data || []).map(x => `
                <tr>
                    <td class="fw-semibold">${esc(x.fie_nombre)}</td>
                    <td>${esc(x.fie_tipo)}</td>
                    <td>${esc(x.fie_puntaje_maximo)}</td>
                    <td>${esc(x.fie_estado)}</td>
                    <td class="text-end">
                        <button class="btn btn-outline-primary btn-sm criterios" data-id="${x.fie_ide}" data-n="${esc(x.fie_nombre)}">Criterios</button>
                        <button class="btn btn-outline-danger btn-sm eliminar-ficha" data-id="${x.fie_ide}">Eliminar</button>
                    </td>
                </tr>
            `).join('');
                $('#tablaFichas').html(html || '<tr><td colspan="5" class="text-center text-muted py-4">No hay fichas configuradas.</td></tr>');
            }).fail(err);
        }

        // Cargar Criterios
        function criterios(id, nombre) {
            $('#panelCriterios').removeClass('d-none').data('fie_ide', id).data('fie_nombre', nombre).html(`
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <strong>Criterios - ${esc(nombre)}</strong>
                    <button class="btn btn-primary btn-sm nuevo-criterio" data-fie="${id}">Nuevo criterio</button>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Puntaje</th>
                                <th>Orden</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="tablaCriterios"></tbody>
                    </table>
                </div>
            </div>
        `);

            cargarTablaCriterios(id);
        }

        function cargarTablaCriterios(id) {
            $.get(base + '/criterios/' + id, r => {
                const html = (r.data || []).map(c => `
                <tr>
                    <td>${esc(c.cri_codigo)}</td>
                    <td>${esc(c.cri_nombre)}</td>
                    <td>${esc(c.cri_puntaje_maximo)}</td>
                    <td>${esc(c.cri_orden)}</td>
                    <td class="text-end">
                        <button class="btn btn-outline-primary btn-sm reglas" data-id="${c.cri_ide}" data-n="${esc(c.cri_nombre)}">Reglas</button>
                        <button class="btn btn-outline-danger btn-sm eliminar-criterio" data-id="${c.cri_ide}" data-fie="${id}">Eliminar</button>
                    </td>
                </tr>
            `).join('');
                $('#tablaCriterios').html(html || '<tr><td colspan="5" class="text-center text-muted py-3">Sin criterios.</td></tr>');
            }).fail(err);
        }

        // Cargar Reglas
        function reglas(id, nombre) {
            $('#panelReglas').remove();
            $('#panelCriterios').append(`
            <div class="card mt-3" id="panelReglas" data-cri_ide="${id}">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <strong>Reglas - ${esc(nombre)}</strong>
                    <button class="btn btn-primary btn-sm nueva-regla" data-cri="${id}">Nueva regla</button>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Condición</th>
                                <th>Desde</th>
                                <th>Hasta</th>
                                <th>Puntaje</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="tablaReglas"></tbody>
                    </table>
                </div>
            </div>
        `);

            cargarTablaReglas(id);
        }

        function cargarTablaReglas(id) {
            $.get(base + '/reglas/' + id, r => {
                const html = (r.data || []).map(q => `
                <tr>
                    <td>${esc(q.rpu_condicion)}</td>
                    <td>${esc(q.rpu_valor_min)}</td>
                    <td>${esc(q.rpu_valor_max) || '-'}</td>
                    <td>${esc(q.rpu_puntaje)}</td>
                    <td class="text-end">
                        <button class="btn btn-outline-danger btn-sm eliminar-regla" data-id="${q.rpu_ide}">Eliminar</button>
                    </td>
                </tr>
            `).join('');
                $('#tablaReglas').html(html || '<tr><td colspan="5" class="text-center text-muted py-3">Sin reglas.</td></tr>');
            }).fail(err);
        }

        // Inicializar
        fichas();

        // Eventos - Ficha
        $('#nuevaFicha').click(() => {
            $('#formFicha')[0].reset();
            $('#formFicha [name=fie_ide]').val('');
            modalFicha.show();
        });

        $('#formFicha').submit(function (e) {
            e.preventDefault();
            $.post(base + '/guardar', $(this).serialize()).done(() => {
                modalFicha.hide();
                fichas();
                Toast.fire({ icon: 'success', title: 'Ficha guardada con éxito' });
            }).fail(err);
        });

        $m.on('click', '.eliminar-ficha', function () {
            const id = $(this).data('id');
            Swal.fire({
                title: '¿Eliminar ficha?',
                text: 'Se eliminarán sus criterios y reglas asociadas.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(res => {
                if (res.isConfirmed) {
                    $.post(base + '/eliminar/' + id).done(() => {
                        fichas();
                        $('#panelCriterios').addClass('d-none').html('');
                        Toast.fire({ icon: 'success', title: 'Ficha eliminada' });
                    }).fail(err);
                }
            });
        });

        // Eventos - Criterio
        $m.on('click', '.criterios', function () {
            criterios($(this).data('id'), $(this).data('n'));
        });

        $m.on('click', '.nuevo-criterio', function () {
            $('#formCriterio')[0].reset();
            $('#formCriterio [name=cri_ide]').val('');
            $('#cri_fie_ide').val($(this).data('fie'));
            modalCriterio.show();
        });

        $('#formCriterio').submit(function (e) {
            e.preventDefault();
            const fieId = $('#cri_fie_ide').val();
            $.post(base + '/criterios/guardar', $(this).serialize()).done(() => {
                modalCriterio.hide();
                cargarTablaCriterios(fieId);
                Toast.fire({ icon: 'success', title: 'Criterio guardado' });
            }).fail(err);
        });

        $m.on('click', '.eliminar-criterio', function () {
            const id = $(this).data('id');
            const fieId = $(this).data('fie');
            Swal.fire({
                title: '¿Eliminar criterio?',
                text: 'Se eliminarán sus reglas asociadas.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(res => {
                if (res.isConfirmed) {
                    $.post(base + '/criterios/eliminar/' + id).done(() => {
                        cargarTablaCriterios(fieId);
                        $('#panelReglas').remove();
                        Toast.fire({ icon: 'success', title: 'Criterio eliminado' });
                    }).fail(err);
                }
            });
        });

        // Eventos - Reglas
        $m.on('click', '.reglas', function () {
            reglas($(this).data('id'), $(this).data('n'));
        });

        $m.on('click', '.nueva-regla', function () {
            $('#formRegla')[0].reset();
            $('#formRegla [name=rpu_ide]').val('');
            $('#rpu_cri_ide').val($(this).data('cri'));
            modalRegla.show();
        });

        $('#formRegla').submit(function (e) {
            e.preventDefault();
            const criId = $('#rpu_cri_ide').val();
            $.post(base + '/reglas/guardar', $(this).serialize()).done(() => {
                modalRegla.hide();
                cargarTablaReglas(criId);
                Toast.fire({ icon: 'success', title: 'Regla guardada' });
            }).fail(err);
        });

        $m.on('click', '.eliminar-regla', function () {
            const id = $(this).data('id');
            const $row = $(this).closest('tr');
            Swal.fire({
                title: '¿Eliminar regla?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar'
            }).then(res => {
                if (res.isConfirmed) {
                    $.post(base + '/reglas/eliminar/' + id).done(() => {
                        $row.remove();
                        Toast.fire({ icon: 'success', title: 'Regla eliminada' });
                    }).fail(err);
                }
            });
        });

    })();
</script>
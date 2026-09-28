<div id="moduloComision" data-convocatoria="<?= esc($convocatoriaId) ?>">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1 fw-bold">Comisiones evaluadoras</h4>
            <p class="small text-muted mb-0">Designa las comisiones y administra sus integrantes.</p>
        </div>
        <button class="btn btn-primary btn-sm" id="nuevaComision">Nueva comisión</button>
    </div>

    <div class="table-responsive bg-white border rounded">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Número</th>
                    <th>Fecha de designación</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody id="tablaComisiones"></tbody>
        </table>
    </div>

    <div id="panelMiembros" class="mt-4 d-none"></div>

    <!-- Modal Comisión -->
    <div class="modal fade" id="modalComision" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formComision">
                    <input type="hidden" name="com_ide" id="com_ide">
                    <input type="hidden" name="com_con_ide" value="<?= esc($convocatoriaId) ?>">

                    <div class="modal-header">
                        <h5 class="modal-title">Comisión evaluadora</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Número *</label>
                            <input class="form-control" name="com_numero" required maxlength="50">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha de designación *</label>
                            <input class="form-control" type="date" name="com_fecha_designacion" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Documento de designación</label>
                            <select class="form-select" name="com_documento_ide" id="com_documento_ide">
                                <option value="">Sin documento</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Estado</label>
                            <select class="form-select" name="com_estado">
                                <option value="ACTIVA">Activa</option>
                                <option value="INACTIVA">Inactiva</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- Modal Miembro (Declarado estáticamente en el DOM) -->
    <div class="modal fade" id="modalMiembro" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formMiembro">
                    <input type="hidden" name="cmi_com_ide" id="cmi_com_ide">
                    <input type="hidden" name="cmi_ide" id="cmi_ide">
                    <div class="modal-header">
                        <h5 class="modal-title">Miembro de comisión</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Usuario *</label>
                            <select class="form-select" name="cmi_usu_ide" id="cmi_usu_ide" required></select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tipo *</label>
                            <select class="form-select" name="cmi_tipo">
                                <option value="PRESIDENTE">PRESIDENTE</option>
                                <option value="SECRETARIO">SECRETARIO</option>
                                <option value="MIEMBRO">MIEMBRO</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label class="form-label">Inicio</label>
                                <input class="form-control" type="datetime-local" name="cmi_fecha_inicio">
                            </div>
                            <div class="col">
                                <label class="form-label">Fin</label>
                                <input class="form-control" type="datetime-local" name="cmi_fecha_fin">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const $m = $('#moduloComision');
    const con = $m.data('convocatoria');
    const base = '<?= base_url("seleccion/admin/comisiones") ?>';
    const modalComision = new bootstrap.Modal('#modalComision');
    const modalMiembro = new bootstrap.Modal('#modalMiembro');
    let documentos = [];

    const esc = v => $('<div>').text(v || '').html();
    const error = x => Swal.fire('Atención', (x.responseJSON || {}).message || 'No se pudo procesar la solicitud.', 'warning');

    // Instancia reutilizable de Toast con SweetAlert2
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });

    function cargar() {
        $.get(base + '/listar/' + con, r => {
            const filas = (r.data || []).map(x => `
                <tr>
                    <td class="fw-semibold">${esc(x.com_numero)}</td>
                    <td>${esc(x.com_fecha_designacion)}</td>
                    <td><span class="badge bg-${x.com_estado === 'ACTIVA' ? 'success' : 'secondary'}">${esc(x.com_estado)}</span></td>
                    <td class="text-end">
                        <button class="btn btn-outline-primary btn-sm ver-com" data-id="${x.com_ide}" data-n="${esc(x.com_numero)}">Miembros</button> 
                        <button class="btn btn-outline-warning btn-sm editar-com" data-item='${esc(JSON.stringify(x))}'>Editar</button> 
                        <button class="btn btn-outline-danger btn-sm eliminar-com" data-id="${x.com_ide}">Eliminar</button>
                    </td>
                </tr>
            `).join('') || '<tr><td colspan="4" class="text-center text-muted py-4">No hay comisiones registradas.</td></tr>';

            $('#tablaComisiones').html(filas);
        }).fail(error);
    }

    function opciones() {
        $.get('<?= base_url("seleccion/admin/documentos-convocatoria/listar") ?>/' + con, r => {
            documentos = r.data || [];
            $('#com_documento_ide').html('<option value="">Sin documento</option>' + documentos.map(d => `<option value="${d.cod_ide}">${esc(d.cod_nombre)}</option>`).join(''));
        });
    }

    function miembros(id, n) {
        $('#panelMiembros').removeClass('d-none').data('com_ide', id).data('com_nombre', n).html(`
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <strong>Miembros - ${esc(n)}</strong>
                    <button class="btn btn-primary btn-sm nuevo-miembro" data-id="${id}">Agregar miembro</button>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Tipo</th>
                                <th>Inicio</th>
                                <th>Fin</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaMiembros"></tbody>
                    </table>
                </div>
            </div>
        `);

        cargarTablaMiembros(id);

        $.get(base + '/usuarios', r => {
            $('#cmi_usu_ide').html('<option value="">Seleccione...</option>' + (r.data || []).map(u => `<option value="${u.id}">${esc(u.username || u.email)}</option>`).join(''));
        });
    }

    function cargarTablaMiembros(id) {
        $.get(base + '/miembros/' + id, r => {
            const filasMiembros = (r.data || []).map(x => `
                <tr>
                    <td>${esc(x.username || x.email)}</td>
                    <td>${esc(x.cmi_tipo)}</td>
                    <td>${esc(x.cmi_fecha_inicio)}</td>
                    <td>${esc(x.cmi_fecha_fin) || '-'}</td>
                    <td class="text-end">
                        <button class="btn btn-outline-danger btn-sm eliminar-miembro" data-id="${x.cmi_ide}" data-com="${id}">Retirar</button>
                    </td>
                </tr>
            `).join('') || '<tr><td colspan="5" class="text-center text-muted py-3">Sin miembros registrados.</td></tr>';

            $('#tablaMiembros').html(filasMiembros);
        }).fail(error);
    }

    opciones();
    cargar();

    // --- EVENTOS COMISIÓN ---

    $('#nuevaComision').click(() => {
        $('#formComision')[0].reset();
        $('#com_ide').val('');
        modalComision.show();
    });

    $m.on('click', '.editar-com', function () {
        const item = JSON.parse($(this).attr('data-item'));
        $('#com_ide').val(item.com_ide);
        $('[name="com_numero"]').val(item.com_numero);
        $('[name="com_fecha_designacion"]').val(item.com_fecha_designacion);
        $('[name="com_documento_ide"]').val(item.com_documento_ide);
        $('[name="com_estado"]').val(item.com_estado);
        modalComision.show();
    });

    $('#formComision').submit(function (e) {
        e.preventDefault();
        $.post(base + '/guardar', $(this).serialize()).done(() => {
            modalComision.hide();
            cargar();
            Toast.fire({ icon: 'success', title: 'Comisión guardada con éxito' });
        }).fail(error);
    });

    $m.on('click', '.eliminar-com', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: '¿Eliminar comisión?',
            text: 'Se eliminarán sus miembros asociados.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(x => {
            if (x.isConfirmed) {
                $.post(base + '/eliminar/' + id).done(() => {
                    cargar();
                    $('#panelMiembros').addClass('d-none').html('');
                    Toast.fire({ icon: 'success', title: 'Comisión eliminada' });
                }).fail(error);
            }
        });
    });

    // --- EVENTOS MIEMBROS ---

    $m.on('click', '.ver-com', function () {
        miembros($(this).data('id'), $(this).data('n'));
    });

    $m.on('click', '.nuevo-miembro', function () {
        $('#formMiembro')[0].reset();
        $('#cmi_ide').val('');
        $('#cmi_com_ide').val($(this).data('id'));
        modalMiembro.show();
    });

    $('#formMiembro').submit(function (e) {
        e.preventDefault();
        const comId = $('#cmi_com_ide').val();

        $.post(base + '/miembros/guardar', $(this).serialize())
            .done(() => {
                modalMiembro.hide();
                cargarTablaMiembros(comId);
                Toast.fire({ icon: 'success', title: 'Miembro guardado' });
            })
            .fail(error);
    });

    $m.on('click', '.eliminar-miembro', function () {
        const miembroId = $(this).data('id');
        const comId = $(this).data('com');

        Swal.fire({
            title: '¿Retirar miembro?',
            text: 'El usuario ya no formará parte de esta comisión.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, retirar',
            cancelButtonText: 'Cancelar'
        }).then(x => {
            if (x.isConfirmed) {
                $.post(base + '/miembros/eliminar/' + miembroId)
                    .done(() => {
                        cargarTablaMiembros(comId);
                        Toast.fire({ icon: 'success', title: 'Miembro retirado' });
                    })
                    .fail(error);
            }
        });
    });

})();
</script>
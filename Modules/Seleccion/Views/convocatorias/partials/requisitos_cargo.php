<div id="requisitosCargo" data-cargo-id="<?= esc($cargo_id) ?>">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-1 fw-bold">Requisitos de la plaza</h5>
            <p class="mb-0 small text-muted">Los requisitos obligatorios se consideran en la validacion de la postulacion.</p>
        </div>
        <button type="button" class="btn btn-primary btn-sm" id="btnNuevoRequisito">
            <iconify-icon icon="lucide:plus" class="me-1"></iconify-icon> Nuevo requisito
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>Ord.</th><th>Tipo</th><th>Requisito</th><th>Detalle</th><th class="text-center">Oblig.</th><th class="text-end">Acciones</th></tr>
            </thead>
            <tbody>
                <?php if (empty($requisitos)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Aun no se han configurado requisitos para esta plaza.</td></tr>
                <?php else: ?>
                    <?php foreach ($requisitos as $req): ?>
                        <tr>
                            <td><?= esc($req['req_orden']) ?></td>
                            <td><span class="badge bg-<?= $req['req_tipo'] === 'FORMACION' ? 'info' : ($req['req_tipo'] === 'EXPERIENCIA' ? 'warning text-dark' : 'secondary') ?>"><?= esc($req['req_tipo']) ?></span></td>
                            <td><div class="fw-semibold"><?= esc($req['req_nombre']) ?></div><?php if (!empty($req['req_descripcion'])): ?><small class="text-muted"><?= esc($req['req_descripcion']) ?></small><?php endif; ?></td>
                            <td><small><?php if ($req['req_tipo'] === 'FORMACION'): ?><?= esc($req['detalle']['rfo_grado'] ?? 'Sin grado definido') ?><?= !empty($req['detalle']['nfo_nombre']) ? '<br>' . esc($req['detalle']['nfo_nombre']) : '' ?><?= !empty($req['detalle']['pro_nombre']) ? '<br>' . esc($req['detalle']['pro_nombre']) : '' ?><?php elseif ($req['req_tipo'] === 'EXPERIENCIA'): ?><?= esc($req['detalle']['rex_anios'] ?? 0) ?> anos, <?= esc($req['detalle']['rex_meses'] ?? 0) ?> meses<?= !empty($req['detalle']['rex_especifica']) ? '<br>Experiencia especifica' : '' ?><?php else: ?>-<?php endif; ?></small></td>
                            <td class="text-center"><?= !empty($req['req_obligatorio']) ? '<span class="badge bg-success">Si</span>' : '<span class="badge bg-light text-dark border">No</span>' ?></td>
                            <td class="text-end">
                                <button type="button" class="btn btn-outline-warning btn-sm btn-editar-requisito" data-requisito='<?= esc(json_encode($req), 'attr') ?>' title="Editar"><iconify-icon icon="lucide:edit-3"></iconify-icon></button>
                                <button type="button" class="btn btn-outline-danger btn-sm btn-eliminar-requisito" data-id="<?= esc($req['req_ide']) ?>" title="Eliminar"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalRequisito" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content">
            <form id="formRequisito"><input type="hidden" name="req_cco_ide" value="<?= esc($cargo_id) ?>"><input type="hidden" name="req_ide" id="req_ide">
                <div class="modal-header"><h5 class="modal-title fw-bold" id="tituloModalRequisito">Nuevo requisito</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body"><div class="row g-3">
                    <div class="col-md-3"><label class="form-label">Orden</label><input class="form-control" type="number" min="1" name="req_orden" value="1"></div>
                    <div class="col-md-4"><label class="form-label">Tipo *</label><select class="form-select" name="req_tipo" id="req_tipo" required><option value="GENERAL">General</option><option value="FORMACION">Formacion</option><option value="EXPERIENCIA">Experiencia</option></select></div>
                    <div class="col-md-5"><label class="form-label">Nombre *</label><input class="form-control" name="req_nombre" required maxlength="255"></div>
                    <div class="col-12"><label class="form-label">Descripcion</label><textarea class="form-control" rows="2" name="req_descripcion"></textarea></div>
                    <div class="col-12 d-none border rounded p-3 mx-1" id="bloqueFormacion"><div class="row g-3"><div class="col-md-4"><label class="form-label">Grado requerido</label><input class="form-control" name="rfo_grado" placeholder="Ej. Titulado"></div><div class="col-md-4"><label class="form-label">Nivel de formacion</label><select class="form-select" name="rfo_nfo_ide" id="rfo_nfo_ide"><option value="">Cualquiera</option></select></div><div class="col-md-4"><label class="form-label">Profesion</label><select class="form-select" name="rfo_pro_ide" id="rfo_pro_ide"><option value="">Cualquiera</option></select></div></div></div>
                    <div class="col-12 d-none border rounded p-3 mx-1" id="bloqueExperiencia"><div class="row g-3"><div class="col-md-3"><label class="form-label">Anos</label><input class="form-control" type="number" min="0" name="rex_anios" value="0"></div><div class="col-md-3"><label class="form-label">Meses</label><input class="form-control" type="number" min="0" max="11" name="rex_meses" value="0"></div><div class="col-md-3"><label class="form-label">Dias</label><input class="form-control" type="number" min="0" max="30" name="rex_dias" value="0"></div><div class="col-md-3 d-flex align-items-end"><div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="rex_especifica" value="1" id="rex_especifica"><label class="form-check-label" for="rex_especifica">Especifica</label></div></div></div></div>
                    <div class="col-md-4"><div class="form-check"><input class="form-check-input" type="checkbox" name="req_obligatorio" value="1" id="req_obligatorio" checked><label class="form-check-label" for="req_obligatorio">Obligatorio</label></div></div>
                    <div class="col-md-4"><div class="form-check"><input class="form-check-input" type="checkbox" name="req_puntaje" value="1" id="req_puntaje"><label class="form-check-label" for="req_puntaje">Asigna puntaje</label></div></div>
                </div></div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary" id="btnGuardarRequisito">Guardar</button></div>
            </form>
        </div></div>
    </div>
</div>

<script>
    (function () {
        const $contenedor = $('#requisitosCargo');
        const cargoId = $contenedor.data('cargo-id');
        const modal = new bootstrap.Modal(document.getElementById('modalRequisito'));
        const urls = { store: '<?= base_url('seleccion/admin/requisitos/store') ?>', update: '<?= base_url('seleccion/admin/requisitos/update') ?>', remove: '<?= base_url('seleccion/admin/requisitos/delete') ?>', partial: '<?= base_url('seleccion/admin/requisitos/partial') ?>' };

        function toggleDetalles() { const tipo = $('#req_tipo').val(); $('#bloqueFormacion').toggleClass('d-none', tipo !== 'FORMACION'); $('#bloqueExperiencia').toggleClass('d-none', tipo !== 'EXPERIENCIA'); }
        function cargarOpciones(url, selector, id, texto) { $.get(url).done(function (r) { const datos = r.data || r; if (!Array.isArray(datos)) return; let opciones = '<option value="">Cualquiera</option>'; datos.forEach(function (item) { opciones += '<option value="' + item[id] + '">' + $('<div>').text(item[texto] || '').html() + '</option>'; }); $(selector).html(opciones); }); }
        function recargar() { $('#contenidoRequisitos').load(urls.partial + '/' + cargoId); }
        function mostrarError(xhr) { const respuesta = xhr.responseJSON || {}; const errores = respuesta.messages || respuesta.errors || {}; const mensaje = respuesta.message || Object.values(errores).join(' ') || 'No se pudo procesar la solicitud.'; Swal.fire('Atencion', mensaje, 'warning'); }

        cargarOpciones('<?= base_url('seleccion/api/niveles-formacion') ?>', '#rfo_nfo_ide', 'nfo_ide', 'nfo_nombre');
        cargarOpciones('<?= base_url('seleccion/api/profesiones') ?>', '#rfo_pro_ide', 'pro_ide', 'pro_nombre');
        $('#req_tipo').on('change', toggleDetalles);
        $('#btnNuevoRequisito').on('click', function () { $('#formRequisito')[0].reset(); $('#req_ide').val(''); $('#tituloModalRequisito').text('Nuevo requisito'); toggleDetalles(); modal.show(); });
        $contenedor.on('click', '.btn-editar-requisito', function () { const req = $(this).data('requisito'); const d = req.detalle || {}; $('#formRequisito')[0].reset(); $('#req_ide').val(req.req_ide); $('[name=req_orden]').val(req.req_orden || 1); $('#req_tipo').val(req.req_tipo); $('[name=req_nombre]').val(req.req_nombre); $('[name=req_descripcion]').val(req.req_descripcion || ''); $('[name=req_obligatorio]').prop('checked', !!Number(req.req_obligatorio)); $('[name=req_puntaje]').prop('checked', !!Number(req.req_puntaje)); $('[name=rfo_grado]').val(d.rfo_grado || ''); $('[name=rfo_nfo_ide]').val(d.rfo_nfo_ide || ''); $('[name=rfo_pro_ide]').val(d.rfo_pro_ide || ''); $('[name=rex_anios]').val(d.rex_anios || 0); $('[name=rex_meses]').val(d.rex_meses || 0); $('[name=rex_dias]').val(d.rex_dias || 0); $('[name=rex_especifica]').prop('checked', !!Number(d.rex_especifica)); $('#tituloModalRequisito').text('Editar requisito'); toggleDetalles(); modal.show(); });
        $('#formRequisito').on('submit', function (event) { event.preventDefault(); const id = $('#req_ide').val(); const $boton = $('#btnGuardarRequisito').prop('disabled', true); $.post(id ? urls.update + '/' + id : urls.store, $(this).serialize()).done(function (r) { modal.hide(); recargar(); Swal.fire({ toast: true, position: 'top-end', timer: 2500, showConfirmButton: false, icon: 'success', title: r.message || 'Guardado correctamente' }); }).fail(mostrarError).always(function () { $boton.prop('disabled', false); }); });
        $contenedor.on('click', '.btn-eliminar-requisito', function () { const id = $(this).data('id'); Swal.fire({ title: 'Eliminar requisito?', text: 'Esta accion no se puede deshacer.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Eliminar', cancelButtonText: 'Cancelar' }).then(function (resultado) { if (!resultado.isConfirmed) return; $.ajax({ url: urls.remove + '/' + id, type: 'DELETE' }).done(recargar).fail(mostrarError); }); });
    })();
</script>

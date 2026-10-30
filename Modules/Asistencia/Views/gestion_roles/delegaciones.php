<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Delegaciones temporales | Gestión de roles<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div><h1 class="h3 mb-1">Delegaciones temporales</h1><p class="text-muted mb-0">Administra quién programa o revisa roles por oficina y etapa, con vigencia definida. El acceso se extiende a las oficinas descendientes.</p></div>
        <button class="btn btn-primary" type="button" onclick="abrirDelegacion()"><iconify-icon icon="lucide:plus" class="me-1"></iconify-icon>Nueva delegación</button>
    </div>
    <?= view('Modules\Asistencia\Views\gestion_roles\navegacion', ['admin' => true]) ?>
    <?php foreach (['error' => 'error', 'success' => 'success'] as $key => $toast): if ($flash = session()->getFlashdata($key)): ?><script>document.addEventListener('DOMContentLoaded',()=>toastr.<?= $toast ?>(<?= json_encode($flash, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>));</script><?php endif; endforeach ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="get" class="row g-2 align-items-end mb-3">
                <div class="col-md-6"><label for="buscar" class="form-label">Buscar usuario u oficina</label><input id="buscar" name="buscar" value="<?= esc($filtros['buscar']) ?>" class="form-control" placeholder="Nombre, usuario u oficina"></div>
                <div class="col-md-3"><label for="etapaFiltro" class="form-label">Etapa</label><select id="etapaFiltro" name="etapa" class="form-select"><option value="">Todas</option><?php foreach (['PROGRAMACION' => 'Programación', 'RRHH' => 'Recursos Humanos', 'DIRECCION' => 'Dirección'] as $value => $label): ?><option value="<?= $value ?>" <?= $filtros['etapa'] === $value ? 'selected' : '' ?>><?= $label ?></option><?php endforeach ?></select></div>
                <div class="col-md-3 d-flex gap-2"><button class="btn btn-outline-primary">Filtrar</button><a class="btn btn-outline-secondary" href="<?= site_url('asistencia/gestion-roles/delegaciones') ?>">Limpiar</a></div>
            </form>
            <div class="table-responsive"><table id="tablaDelegaciones" class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light"><tr><th>Usuario</th><th>Oficina</th><th>Etapa / permiso</th><th>Vigencia</th><th class="text-end">Acciones</th></tr></thead><tbody>
                <?php foreach ($delegaciones as $d): $nombre = trim(($d['nombres'] ?? '') . ' ' . ($d['paterno'] ?? '') . ' ' . ($d['materno'] ?? '')); $etapa = $d['etapa'] ?? (!empty($d['aprobar']) ? 'DIRECCION' : 'PROGRAMACION'); ?>
                    <tr><td><div class="fw-semibold"><?= esc($nombre ?: $d['username']) ?></div><small class="text-muted">@<?= esc($d['username']) ?></small></td><td><?= esc($d['ofi_nombre']) ?></td><td><span class="badge <?= $etapa === 'PROGRAMACION' ? 'text-bg-secondary' : ($etapa === 'RRHH' ? 'text-bg-info' : 'text-bg-primary') ?>"><?= esc(['PROGRAMACION' => 'Programación', 'RRHH' => 'Recursos Humanos', 'DIRECCION' => 'Dirección'][$etapa] ?? $etapa) ?></span></td><td><?= esc($d['desde']) ?> <span class="text-muted">al</span> <?= esc($d['hasta']) ?></td>
                    <td class="text-end text-nowrap"><button type="button" class="btn btn-sm btn-outline-primary" aria-label="Editar delegación" onclick='editarDelegacion(<?= json_encode(['id' => (int)$d['id'], 'usuario_id' => (int)$d['usuario_id'], 'oficina_id' => (int)$d['oficina_id'], 'etapa' => $etapa, 'desde' => $d['desde'], 'hasta' => $d['hasta']], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'><iconify-icon icon="lucide:pencil"></iconify-icon></button> <form method="post" action="<?= site_url('asistencia/gestion-roles/delegaciones/' . (int)$d['id'] . '/eliminar') ?>" class="d-inline js-eliminar-delegacion"><?= csrf_field() ?><button class="btn btn-sm btn-outline-danger" aria-label="Eliminar delegación"><iconify-icon icon="lucide:trash-2"></iconify-icon></button></form></td></tr>
                <?php endforeach ?>
                <?php if (!$delegaciones): ?><tr><td colspan="5" class="text-center text-muted py-4">No hay delegaciones que coincidan con estos filtros.</td></tr><?php endif ?>
                </tbody></table></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDelegacion" tabindex="-1" aria-labelledby="tituloDelegacion" aria-hidden="true"><div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content">
    <form method="post" action="<?= site_url('asistencia/gestion-roles/delegaciones/guardar') ?>">
        <?= csrf_field() ?><div class="modal-header"><h2 class="modal-title fs-5" id="tituloDelegacion">Nueva delegación</h2><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button></div>
        <div class="modal-body"><input type="hidden" name="id" id="delegacion_id"><div class="row g-3">
            <div class="col-md-6"><label for="usuario_id" class="form-label">Usuario responsable</label><select class="form-select" id="usuario_id" name="usuario_id" required><option value="">Seleccione...</option><?php foreach ($usuarios as $u): $nombreUsuario = trim(($u['nombres'] ?? '') . ' ' . ($u['paterno'] ?? '') . ' ' . ($u['materno'] ?? '')); ?><option value="<?= (int)$u['id'] ?>"><?= esc($nombreUsuario ?: $u['username']) ?> — @<?= esc($u['username']) ?></option><?php endforeach ?></select></div>
            <div class="col-md-6"><label for="oficina_id" class="form-label">Oficina</label><select class="form-select" id="oficina_id" name="oficina_id" required><option value="">Seleccione...</option><?php foreach ($oficinas as $o): ?><option value="<?= (int)$o['ofi_ide'] ?>"><?= esc($o['ofi_nombre']) ?></option><?php endforeach ?></select></div>
            <div class="col-md-4"><label for="etapa" class="form-label">Etapa</label><select class="form-select" id="etapa" name="etapa" required><option value="PROGRAMACION">Programación</option><option value="RRHH">Recursos Humanos</option><option value="DIRECCION">Dirección</option></select><div class="form-text">La delegación de programación permite editar; RR. HH. y Dirección habilitan su revisión.</div></div>
            <div class="col-md-4"><label for="desde" class="form-label">Vigente desde</label><input class="form-control" type="date" id="desde" name="desde" required></div><div class="col-md-4"><label for="hasta" class="form-label">Vigente hasta</label><input class="form-control" type="date" id="hasta" name="hasta" required></div>
        </div></div><div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary">Guardar delegación</button></div>
    </form>
</div></div></div>
<?= $this->section('pageScripts') ?>
<script>
function abrirDelegacion() { document.getElementById('tituloDelegacion').textContent = 'Nueva delegación'; document.getElementById('delegacion_id').value = ''; document.getElementById('usuario_id').value = ''; document.getElementById('oficina_id').value = ''; document.getElementById('etapa').value = 'PROGRAMACION'; document.getElementById('desde').value = ''; document.getElementById('hasta').value = ''; bootstrap.Modal.getOrCreateInstance(document.getElementById('modalDelegacion')).show(); }
function editarDelegacion(d) { document.getElementById('tituloDelegacion').textContent = 'Editar delegación'; for (const key of ['id', 'usuario_id', 'oficina_id', 'etapa', 'desde', 'hasta']) document.getElementById(key === 'id' ? 'delegacion_id' : key).value = d[key]; bootstrap.Modal.getOrCreateInstance(document.getElementById('modalDelegacion')).show(); }
$(function(){
    if ($.fn.DataTable) $('#tablaDelegaciones').DataTable({language:{emptyTable:'No hay delegaciones',info:'Mostrando _START_ a _END_ de _TOTAL_ delegaciones',infoEmpty:'Mostrando 0 delegaciones',lengthMenu:'Mostrar _MENU_ registros',search:'Buscar:',zeroRecords:'No se encontraron delegaciones',paginate:{first:'Primero',last:'Último',next:'Siguiente',previous:'Anterior'}},pageLength:25,order:[[3,'desc']],columnDefs:[{targets:4,orderable:false,searchable:false}]});
    document.querySelectorAll('.js-eliminar-delegacion').forEach(form=>form.addEventListener('submit',async event=>{event.preventDefault();const result=await Swal.fire({title:'¿Eliminar delegación?',text:'La operación quedará registrada en auditoría.',icon:'warning',showCancelButton:true,confirmButtonText:'Sí, eliminar',cancelButtonText:'Cancelar',confirmButtonColor:'#d33'});if(result.isConfirmed)form.submit();}));
    <?php if (old('usuario_id') || old('oficina_id')): ?>
    document.getElementById('usuario_id').value=<?= json_encode((string)old('usuario_id'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    document.getElementById('oficina_id').value=<?= json_encode((string)old('oficina_id'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    document.getElementById('etapa').value=<?= json_encode((string)old('etapa', 'PROGRAMACION'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    document.getElementById('desde').value=<?= json_encode((string)old('desde'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    document.getElementById('hasta').value=<?= json_encode((string)old('hasta'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalDelegacion')).show();
    <?php endif ?>
});
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>

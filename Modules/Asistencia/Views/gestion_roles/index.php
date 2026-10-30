<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Roles por oficina<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container-fluid py-3">
    <div class="card border-0 shadow-sm"><div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-1"><iconify-icon icon="solar:calendar-mark-bold-duotone" class="text-primary" style="font-size:1.8rem"></iconify-icon><h1 class="h3 mb-0">Roles por oficina</h1></div>
        <p class="text-muted">Seguimiento de los roles de turnos por oficina. Los plazos y reglas se administran en Períodos; las revisiones se atienden en Envíos y aprobaciones.</p>
        <?= view('Modules\Asistencia\Views\gestion_roles\navegacion', ['admin' => $admin]) ?>
        <?php foreach (['error'=>'error','success'=>'success'] as $key=>$toast): if ($flash = session()->getFlashdata($key)): ?><script>document.addEventListener('DOMContentLoaded',()=>toastr.<?= $toast ?>(<?= json_encode($flash, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>));</script><?php endif; endforeach ?>
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3"><div><h2 class="h5 mb-1">Programación por oficina</h2><p class="text-muted small mb-0">Envíe los roles listos a RR. HH. Cada envío conserva su snapshot.</p></div><a href="<?= site_url('asistencia/gestion-roles/envios') ?>" class="btn btn-outline-primary"><iconify-icon icon="lucide:inbox" class="me-1"></iconify-icon>Ver todos los envíos</a></div>
        <div class="table-responsive"><table id="tablaRolesOficina" class="table table-striped table-hover align-middle"><thead class="table-light"><tr><th>Mes</th><th>Oficina</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead><tbody>
            <?php foreach ($roles as $r): $badge = ['BORRADOR'=>'secondary','OBSERVADO'=>'warning','PENDIENTE_RRHH'=>'info','VISTO_BUENO_RRHH'=>'primary','APROBADO_DIRECCION'=>'success','RECHAZADO'=>'danger'][$r['estado']] ?? 'light'; ?>
                <tr><td><?= esc($r['mes']) ?></td><td class="fw-semibold"><?= esc($r['ofi_nombre']) ?></td><td><span class="badge text-bg-<?= $badge ?>"><?= esc(str_replace('_',' ',$r['estado'])) ?></span></td><td class="text-end text-nowrap">
                    <?php if (in_array($r['estado'], ['BORRADOR', 'OBSERVADO'], true)): ?><form method="post" action="<?= site_url('asistencia/gestion-roles/' . (int) $r['id'] . '/enviar') ?>" class="d-inline js-enviar-rol"><?= csrf_field() ?><button class="btn btn-sm btn-primary"><iconify-icon icon="lucide:send" class="me-1"></iconify-icon>Enviar a RR. HH.</button></form><?php endif ?>
                    <a class="btn btn-sm btn-outline-secondary" href="<?= site_url('asistencia/gestion-roles/envios') ?>"><iconify-icon icon="lucide:history" class="me-1"></iconify-icon>Envíos</a>
                </td></tr>
            <?php endforeach ?>
        </tbody></table></div>
    </div></div>
</div>
<?= $this->section('pageScripts') ?><script>
$(function(){
    if ($.fn.DataTable) $('#tablaRolesOficina').DataTable({language:{emptyTable:'No hay roles',info:'Mostrando _START_ a _END_ de _TOTAL_ roles',infoEmpty:'Mostrando 0 roles',lengthMenu:'Mostrar _MENU_ registros',search:'Buscar:',zeroRecords:'No se encontraron roles',paginate:{first:'Primero',last:'Último',next:'Siguiente',previous:'Anterior'}},pageLength:25,order:[[0,'desc']],columnDefs:[{targets:3,orderable:false,searchable:false}]});
    document.querySelectorAll('.js-enviar-rol').forEach(form=>form.addEventListener('submit',async event=>{event.preventDefault();const result=await Swal.fire({title:'¿Enviar el rol a RR. HH.?',text:'Se guardará un snapshot y el rol quedará bloqueado para edición mientras se revisa.',icon:'question',showCancelButton:true,confirmButtonText:'Enviar rol',cancelButtonText:'Cancelar'});if(result.isConfirmed)form.submit();}));
});
</script><?= $this->endSection() ?>
<?= $this->endSection() ?>

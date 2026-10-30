<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Envíos y aprobaciones de roles<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div><h1 class="h3 mb-1"><iconify-icon icon="solar:inbox-in-bold-duotone" class="text-primary me-2"></iconify-icon>Envíos y aprobaciones</h1><p class="text-muted mb-0">Historial versionado de los roles remitidos a Recursos Humanos y Dirección.</p></div>
        <a class="btn btn-outline-primary" href="<?= site_url('asistencia/gestion-roles') ?>"><iconify-icon icon="lucide:arrow-left" class="me-1"></iconify-icon>Roles por oficina</a>
    </div>
    <?= view('Modules\Asistencia\Views\gestion_roles\navegacion', ['admin' => $admin ?? false]) ?>
    <?php foreach (['error' => 'error', 'success' => 'success'] as $key => $toast): if ($mensaje = session()->getFlashdata($key)): ?><script>document.addEventListener('DOMContentLoaded',()=>toastr.<?= $toast ?>(<?= json_encode($mensaje, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>));</script><?php endif; endforeach ?>
    <div class="card border-0 shadow-sm"><div class="card-body"><div class="table-responsive">
        <table id="tablaEnviosRoles" class="table table-striped table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Período</th><th>Oficina</th><th>Establecimiento</th><th>Versión</th><th>Estado</th><th>Enviado</th><th>Acción</th></tr></thead>
            <tbody>
                <?php foreach ($envios as $envio): ?>
                    <tr>
                        <td><?= sprintf('%02d/%04d', (int) $envio['mes'], (int) $envio['anio']) ?></td>
                        <td><?= esc($envio['ofi_nombre']) ?></td><td><?= esc($envio['est_nombre']) ?></td>
                        <?php $badge = ['PENDIENTE_RRHH'=>'info','OBSERVADO'=>'warning','VISTO_BUENO_RRHH'=>'primary','APROBADO_DIRECCION'=>'success','RECHAZADO'=>'danger'][$envio['estado']] ?? 'secondary'; ?>
                        <td><?= (int) $envio['version'] ?></td><td><span class="badge text-bg-<?= $badge ?>"><?= esc(str_replace('_',' ',$envio['estado'])) ?></span></td>
                        <td><?= esc($envio['enviado_at']) ?></td>
                        <td><a class="btn btn-sm btn-outline-primary" href="<?= site_url('asistencia/gestion-roles/envios/' . (int) $envio['id']) ?>"><iconify-icon icon="lucide:eye" class="me-1"></iconify-icon>Revisar snapshot</a></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div></div></div>
    <p class="small text-muted mt-3 mb-0">Cada versión conserva sus datos y huella digital. La revisión registra por separado a cada actor y su fecha.</p>
</div>
<?= $this->section('pageScripts') ?><script>$(function(){if($.fn.DataTable)$('#tablaEnviosRoles').DataTable({language:{emptyTable:'No hay envíos',info:'Mostrando _START_ a _END_ de _TOTAL_ envíos',infoEmpty:'Mostrando 0 envíos',lengthMenu:'Mostrar _MENU_ registros',search:'Buscar:',zeroRecords:'No se encontraron envíos',paginate:{first:'Primero',last:'Último',next:'Siguiente',previous:'Anterior'}},pageLength:25,order:[[5,'desc']],columnDefs:[{targets:6,orderable:false,searchable:false}]});});</script><?= $this->endSection() ?>
<?= $this->endSection() ?>

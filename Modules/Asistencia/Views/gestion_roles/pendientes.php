<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Programacion pendiente<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container-fluid py-3">
    <div class="card border-0 shadow-sm"><div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-1"><iconify-icon icon="solar:list-check-bold-duotone" class="text-primary" style="font-size:1.8rem"></iconify-icon><h1 class="h3 mb-0">Programacion pendiente</h1></div>
        <p class="text-muted">Filtra al personal activo que aun no tiene turnos cargados para el mes del periodo. El alcance respeta DIRESA, red, microred, establecimiento y oficina.</p>
        <form method="get" class="row g-2 align-items-end mb-4">
            <div class="col-md-7"><label class="form-label" for="periodo">Periodo de roles</label><select class="form-select" id="periodo" name="periodo" required>
                <?php foreach ($periodos as $item): ?><option value="<?= (int)$item['id'] ?>" <?= $periodo && (int)$periodo['id']===(int)$item['id']?'selected':'' ?>><?= esc($item['mes']) ?> - <?= esc($item['alcance']) ?> (<?= esc($item['nivel'] ?? 'ESTABLECIMIENTO') ?>)</option><?php endforeach ?>
            </select></div>
            <div class="col-auto"><button class="btn btn-primary"><iconify-icon icon="lucide:search" class="me-1"></iconify-icon>Consultar</button></div>
            <?php if ($periodo): ?><div class="col-auto ms-md-auto"><button type="button" class="btn btn-outline-success" id="exportarExcelPendientes"><iconify-icon icon="solar:file-excel-bold" class="me-1"></iconify-icon>Exportar Excel legible / importable</button></div><?php endif ?>
        </form>
        <?php if (!$periodo): ?>
            <div class="alert alert-info mb-0">No hay periodos asignados dentro de su ambito.</div>
        <?php else: ?>
            <div class="row g-3 mb-4">
                <?php foreach ([['Oficinas con pendientes',$resumen['oficinas'],'warning'],['Personal del periodo',$resumen['personal'],'primary'],['Con programacion',$resumen['programados'],'success'],['Sin programacion',$resumen['pendientes'],'danger']] as [$label,$value,$color]): ?>
                <div class="col-6 col-xl-3"><div class="border rounded p-3 h-100"><div class="small text-muted"><?= esc($label) ?></div><div class="fs-3 fw-semibold text-<?= esc($color) ?>"><?= (int)$value ?></div></div></div>
                <?php endforeach ?>
            </div>
            <div class="alert alert-light border"><strong>Periodo <?= esc($periodo['mes']) ?>:</strong> <?= esc($periodo['alcance']) ?> (<?= esc($periodo['nivel'] ?? 'ESTABLECIMIENTO') ?>). Un periodo superior solo aplica cuando el establecimiento descendiente no tiene uno mas especifico.</div>
            <?php if (!$oficinas): ?><div class="alert alert-success mb-0">Todo el personal activo de este periodo tiene al menos un turno cargado.</div><?php else: ?>
                <div class="table-responsive"><table class="table table-striped table-hover align-middle w-100" id="tablaPendientes"><thead class="table-light"><tr>
                    <th>DIRESA</th><th>Red</th><th>Microred</th><th>Establecimiento</th><th>Oficina</th><th>Codigo</th><th>DNI</th><th>Personal sin turnos</th><th>Cargo</th>
                </tr></thead><tbody>
                    <?php foreach ($oficinas as $oficina): foreach ($oficina['pendientes'] as $persona): ?>
                    <tr><td><?= esc($oficina['diresa']) ?></td><td><?= esc($oficina['red']) ?></td><td><?= esc($oficina['microred']) ?></td><td><?= esc($oficina['establecimiento']) ?></td><td><?= esc($oficina['oficina']) ?></td><td><?= esc($persona['codigo']) ?></td><td><?= esc($persona['documento']) ?></td><td><?= esc($persona['nombre']) ?></td><td><?= esc($persona['cargo']) ?></td></tr>
                    <?php endforeach; endforeach ?>
                </tbody></table></div>
            <?php endif ?>
        <?php endif ?>
    </div></div>
</div>
<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
<script>
$(function(){
    const tabla = $('#tablaPendientes');
    const dt = tabla.length && $.fn.DataTable ? tabla.DataTable({
        language:{emptyTable:'No hay personal pendiente',info:'Mostrando _START_ a _END_ de _TOTAL_ personas',infoEmpty:'Mostrando 0 personas',lengthMenu:'Mostrar _MENU_ registros',search:'Buscar:',zeroRecords:'No se encontraron resultados',paginate:{first:'Primero',last:'Ultimo',next:'Siguiente',previous:'Anterior'}},
        pageLength:25,
        order:[[0,'asc'],[1,'asc'],[2,'asc'],[3,'asc'],[4,'asc'],[7,'asc']],
        scrollX:true
    }) : null;
    $('#exportarExcelPendientes').on('click',function(){
        const params=new URLSearchParams({periodo:'<?= (int)($periodo['id'] ?? 0) ?>'});
        if(dt && dt.search()) params.set('busqueda',dt.search());
        window.location.href='<?= site_url('asistencia/gestion-roles/pendientes/excel') ?>?'+params.toString();
    });
});
</script>
<?= $this->endSection() ?>

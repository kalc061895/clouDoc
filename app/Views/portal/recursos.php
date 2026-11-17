<?php
$recursos = array_values(array_filter($portal->recursos, static function($r) use ($portal, $moduloActual) {
    if (!isset($portal->modulos[$r['modulo'] ?? '']) || !in_array($r['tipo'] ?? '', ['pdf','video'], true)) return false;
    if ($moduloActual !== null && $r['modulo'] !== $moduloActual) return false;
    $url = $r['url'] ?? '';
    return is_string($url) && (preg_match('~^https://[^\s]+$~i', $url) || preg_match('~^[a-zA-Z0-9][a-zA-Z0-9_./-]*$~D', $url));
}));
?>
<section class="space-sm bg-info-subtle" id="recursos"><div class="container">
<p class="text-primary fw-semibold">CENTRO DE APRENDIZAJE</p><h2 class="section-title">La orientaci&oacute;n que necesita.</h2><p class="mb-4">Manuales y videos<?= $moduloActual ? ' de '.esc($portal->modulos[$moduloActual]['nombre']) : ' para cada m&oacute;dulo' ?>.</p>
<ul class="nav nav-pills gap-2 mb-4" role="tablist" aria-label="Tipo de material">
<?php foreach (['todos'=>'Todos','video'=>'Videos','pdf'=>'Manuales PDF'] as $tipo=>$label): ?>
<li class="nav-item" role="presentation"><button class="nav-link <?= $tipo==='todos'?'active':'' ?>" id="tab-<?= $tipo ?>" type="button" data-bs-toggle="pill" data-bs-target="#material-<?= $tipo ?>" role="tab" aria-controls="material-<?= $tipo ?>" aria-selected="<?= $tipo==='todos'?'true':'false' ?>"><?= $label ?></button></li>
<?php endforeach ?></ul>
<div class="tab-content">
<?php foreach (['todos','video','pdf'] as $tipo): $visibles=array_filter($recursos,static fn($r)=>$tipo==='todos'||$r['tipo']===$tipo); ?>
<div class="tab-pane fade <?= $tipo==='todos'?'show active':'' ?>" id="material-<?= $tipo ?>" role="tabpanel" aria-labelledby="tab-<?= $tipo ?>" tabindex="0"><div class="row g-4">
<?php foreach ($visibles as $r): $url=str_starts_with(strtolower($r['url']),'https://')?$r['url']:base_url($r['url']); ?>
<div class="col-md-6"><article class="card border shadow-none h-100 mb-0"><div class="card-body"><p class="text-primary fw-semibold"><?= $r['tipo']==='video'?'VIDEO':'MANUAL PDF' ?> &middot; <?= esc($portal->modulos[$r['modulo']]['nombre']) ?></p><h3 class="fs-6"><?= esc($r['titulo'] ?? 'Material de capacitacion') ?></h3><p><?= esc($r['descripcion'] ?? '') ?></p><a class="btn btn-outline-primary" href="<?= esc($url,'attr') ?>" target="_blank" rel="noopener noreferrer"><?= $r['tipo']==='video'?'Ver video':'Abrir PDF' ?><span class="visually-hidden"> (abre en otra pesta&ntilde;a)</span></a></div></article></div>
<?php endforeach ?>
<?php foreach (['video'=>'Videos de capacitaci&oacute;n','pdf'=>'Manuales en PDF'] as $pendiente=>$titulo): if (($tipo!=='todos'&&$tipo!==$pendiente)||array_filter($visibles,static fn($r)=>$r['tipo']===$pendiente)) continue; ?>
<div class="col-md-6"><article class="card border shadow-none h-100 mb-0"><div class="card-body"><i class="ti ti-<?= $pendiente==='video'?'video':'book' ?> text-primary fs-8" aria-hidden="true"></i><h3 class="fs-6 mt-3"><?= $titulo ?></h3><p>Aqu&iacute; encontrar&aacute; los materiales de orientaci&oacute;n para los procesos del sistema.</p><span class="badge bg-primary-subtle text-primary">Materiales por publicar</span></div></article></div>
<?php endforeach ?>
</div></div><?php endforeach ?>
</div></div></section>

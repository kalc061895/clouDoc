<?php
$recursos = array_values(array_filter($portal->recursos, static function($r) use ($portal, $moduloActual) {
    if (!isset($portal->modulos[$r['modulo'] ?? '']) || !in_array($r['tipo'] ?? '', ['pdf','video'], true)) return false;
    if ($moduloActual !== null && $r['modulo'] !== $moduloActual) return false;
    $url = $r['url'] ?? '';
    return is_string($url) && (preg_match('~^https://[^\s]+$~i', $url) || preg_match('~^[a-zA-Z0-9][a-zA-Z0-9_./-]*$~D', $url));
}));
?>
<section class="learning-section" id="recursos"><div class="shell section"><div class="section-heading"><div><p class="eyebrow">CENTRO DE APRENDIZAJE</p><h2>La orientación que necesita.</h2></div><p>Manuales y videos<?= $moduloActual ? ' de '.esc($portal->modulos[$moduloActual]['nombre']) : ' para cada módulo' ?>.<br>Consulte los materiales a su ritmo.</p></div>
<div class="resource-controls" data-resource-controls hidden><div class="resource-tabs" aria-label="Filtrar materiales"><button type="button" class="selected" data-resource-type="todos" aria-pressed="true">Todos</button><button type="button" data-resource-type="video" aria-pressed="false">Videos</button><button type="button" data-resource-type="pdf" aria-pressed="false">Manuales PDF</button></div><div class="resource-search"><label for="buscar-recurso">Buscar material</label><input id="buscar-recurso" type="search" placeholder="Por título o módulo…"></div></div>
<div class="resource-grid">
<?php foreach ($recursos as $r): $url=str_starts_with(strtolower($r['url']),'https://')?$r['url']:base_url($r['url']); ?>
    <article class="resource-card" data-resource-card data-type="<?= esc($r['tipo']) ?>" data-search="<?= esc(($r['titulo'] ?? '').' '.$portal->modulos[$r['modulo']]['nombre'].' '.($r['descripcion'] ?? ''), 'attr') ?>"><span class="resource-kind"><?= view('portal/icono', ['icono'=>$r['tipo']==='video'?'video':'libro']) ?> <?= $r['tipo']==='video'?'VIDEO':'MANUAL PDF' ?></span><p class="eyebrow"><?= esc($portal->modulos[$r['modulo']]['nombre']) ?></p><h3><?= esc($r['titulo'] ?? 'Material de capacitación') ?></h3><p><?= esc($r['descripcion'] ?? '') ?></p><a class="card-link" href="<?= esc($url, 'attr') ?>" target="_blank" rel="noopener noreferrer"><?= $r['tipo']==='video'?'Ver video':'Abrir PDF' ?> <span aria-hidden="true">↗</span><span class="sr-only"> (abre en otra pestaña)</span></a></article>
<?php endforeach ?>
<?php foreach (['video'=>'Videos de capacitación','pdf'=>'Manuales en PDF'] as $tipo=>$titulo): if (array_filter($recursos,static fn($r)=>$r['tipo']===$tipo)) continue; ?>
    <article class="resource-card resource-pending" data-resource-card data-type="<?= $tipo ?>" data-search="<?= esc($titulo) ?>"><span class="resource-kind"><?= view('portal/icono', ['icono'=>$tipo==='video'?'video':'libro']) ?> <?= $tipo==='video'?'APRENDA PASO A PASO':'CONSULTE CUANDO LO NECESITE' ?></span><h3><?= $titulo ?></h3><p><?= $tipo==='video'?'Aquí encontrará recorridos por las pantallas y explicaciones de los procesos.':'Aquí podrá consultar las guías de uso y los procedimientos de cada módulo.' ?></p><span class="pending-label">Materiales por publicar</span></article>
<?php endforeach ?>
</div><p id="sin-recursos" class="empty-result" role="status" hidden>No hay materiales que coincidan con su búsqueda.</p>
</div></section>

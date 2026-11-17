<?php
/** items: [['label'=>'Inicio','url'=>site_url('inicio')], ['label'=>'Personal']]. */
helper('ui');
$items = array_values($items ?? []);
?>
<?php if ($items !== []): ?>
    <nav aria-label="Ruta de navegación">
        <ol class="breadcrumb">
            <?php foreach ($items as $index => $item): $current = $index === count($items) - 1; ?>
                <li class="breadcrumb-item<?= $current ? ' active' : '' ?>"<?= $current ? ' aria-current="page"' : '' ?>>
                    <?php if (! $current && isset($item['url'])): ?><a href="<?= ui_escape(ui_url($item['url'])) ?>"><?= ui_escape($item['label']) ?></a><?php else: ?><?= ui_escape($item['label']) ?><?php endif ?>
                </li>
            <?php endforeach ?>
        </ol>
    </nav>
<?php endif ?>

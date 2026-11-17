<?php

/** title is text; bodyHtml, actionsHtml and footerHtml are trusted view output. */
helper('ui');
$attributes = $attributes ?? [];
$attributes['class'] = trim('card w-100 ' . ($class ?? '') . ' ' . ($attributes['class'] ?? ''));
?>
<div<?= ui_attributes($attributes) ?>>
    <?php if (! empty($title) || ! empty($actionsHtml)): ?>
        <div class="card-header bg-transparent d-flex align-items-center justify-content-between flex-wrap gap-3">
            <?php if (! empty($title)): ?><h2 class="card-title mb-0"><?= ui_escape($title) ?></h2><?php endif ?>
            <?php if (! empty($actionsHtml)): ?><div class="d-flex flex-wrap gap-2"><?= $actionsHtml ?></div><?php endif ?>
        </div>
    <?php endif ?>
    <div class="card-body"><?= $bodyHtml ?? '' ?></div>
    <?php if (isset($footerHtml)): ?><div class="card-footer bg-transparent"><?= $footerHtml ?></div><?php endif ?>
    </div>
<?php
/** Empty content/result state; actionsHtml must be produced by trusted helpers/views. */
helper('ui');
?>
<div class="text-center py-5 px-3">
    <i class="<?= ui_escape($icon ?? 'ti ti-inbox') ?> fs-10 text-body-secondary" aria-hidden="true"></i>
    <h2 class="fs-5 mt-3 mb-2"><?= ui_escape($title ?? 'No hay registros disponibles.') ?></h2>
    <?php if (! empty($description)): ?><p class="text-body-secondary mb-3"><?= ui_escape($description) ?></p><?php endif ?>
    <?php if (! empty($actionsHtml)): ?><div class="d-flex justify-content-center flex-wrap gap-2"><?= $actionsHtml ?></div><?php endif ?>
</div>

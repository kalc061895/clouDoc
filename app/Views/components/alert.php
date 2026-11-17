<?php
/** Inline notification. Text is escaped; bodyHtml is optional trusted view output. */
helper('ui');
$variant = ui_variant($variant ?? 'info');
$dismissible = $dismissible ?? false;
?>
<div class="alert alert-<?= $variant ?><?= $dismissible ? ' alert-dismissible fade show' : '' ?>" role="<?= in_array($variant, ['danger', 'warning'], true) ? 'alert' : 'status' ?>">
    <?php if (! empty($title)): ?><h2 class="alert-heading fs-4"><?= ui_escape($title) ?></h2><?php endif ?>
    <?php if (isset($bodyHtml)): ?><?= $bodyHtml ?><?php else: ?><?= ui_escape($message ?? '') ?><?php endif ?>
    <?php if ($dismissible): ?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar aviso"></button><?php endif ?>
</div>

<?php

/** bodyHtml and footerHtml must come from trusted application views/helpers. */
helper('ui');
if (! isset($id) || ! preg_match('/^[a-zA-Z][a-zA-Z0-9_-]*$/D', $id)) {
    throw new InvalidArgumentException('A modal needs a unique, CSS-safe id.');
}
$size = $size ?? '';
if (! in_array($size, ['', 'sm', 'lg', 'xl'], true)) {
    throw new InvalidArgumentException('Modal size must be sm, lg or xl.');
}
$dialogClass = 'modal-dialog' . ($size !== '' ? ' modal-' . $size : '');
$dialogClass .= ($centered ?? true) ? ' modal-dialog-centered' : '';
$dialogClass .= ($scrollable ?? true) ? ' modal-dialog-scrollable' : '';
?>
<div class="modal fade" id="<?= ui_escape($id) ?>" tabindex="-1" aria-labelledby="<?= ui_escape($id) ?>-title" aria-hidden="true" <?= ! empty($staticBackdrop) ? ' data-bs-backdrop="static" data-bs-keyboard="false"' : '' ?>>
    <div class="<?= $dialogClass ?>">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="<?= ui_escape($id) ?>-title"><?= ui_escape($title ?? '') ?></h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body"><?= $bodyHtml ?? '' ?></div>
            <div class="modal-footer"><?= $footerHtml ?? ui_button('Cerrar', ['variant' => 'secondary', 'attrs' => ['data-bs-dismiss' => 'modal']]) ?></div>
        </div>
    </div>
</div>
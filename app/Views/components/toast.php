<?php

/**
 * Pass messages explicitly, or read flashdata once at layout level.
 * session()->setFlashdata('toasts', [['variant'=>'success', 'title'=>'Guardado', 'message'=>'...']]);
 * Legacy scalar success/error/warning/info/message flashdata are supported too.
 */
helper('ui');
if (! isset($messages)) {
    $flash = session()->getFlashdata();
    $messages = is_array($flash['toasts'] ?? null) ? $flash['toasts'] : [];
    foreach (['success' => 'success', 'error' => 'danger', 'warning' => 'warning', 'info' => 'info', 'message' => 'info'] as $key => $variant) {
        if (isset($flash[$key]) && is_scalar($flash[$key])) {
            $messages[] = ['variant' => $variant, 'message' => (string) $flash[$key]];
        }
    }
}
$messages = array_filter(is_array($messages) ? $messages : [], 'is_array');
?>
<div class="toast-container position-fixed top-0 end-0 p-3" aria-live="polite" aria-atomic="true">
    <?php foreach ($messages as $message):
        $variant = in_array($message['variant'] ?? '', ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'], true) ? $message['variant'] : 'info';
        $delay = max(1000, min(60000, (int) ($message['delay'] ?? 5000)));
        $urgent = $variant === 'danger';
    ?>
        <div class="toast align-items-center text-bg-<?= $variant ?> border-0" role="<?= $urgent ? 'alert' : 'status' ?>" aria-live="<?= $urgent ? 'assertive' : 'polite' ?>" aria-atomic="true" data-ui-toast data-bs-delay="<?= $delay ?>" data-bs-autohide="<?= $urgent ? 'false' : 'true' ?>">
            <div class="toast-body hstack align-items-start gap-3">
                <i class="ti ti-alert-circle fs-6" aria-hidden="true"></i>
                <div><?php if (! empty($message['title'])): ?><strong class="d-block mb-1"><?= ui_escape($message['title']) ?></strong><?php endif ?><span><?= ui_escape($message['message'] ?? '') ?></span></div>
                <button type="button" class="btn-close <?= in_array($variant, ['warning', 'info', 'light'], true) ? '' : 'btn-close-white' ?> fs-2 m-0 ms-auto shadow-none" data-bs-dismiss="toast" aria-label="Cerrar notificación"></button>
            </div>
        </div>
    <?php endforeach ?>
</div>
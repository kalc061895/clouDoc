<?php
/** Summary accepts validation->getErrors() or an explicit list; no session reads. */
helper('ui');
$errors = array_values(array_filter((array) ($errors ?? []), static fn ($error) => is_scalar($error) && (string) $error !== ''));
?>
<?php if ($errors !== []): ?>
    <div class="alert alert-danger" role="alert">
        <h2 class="alert-heading fs-4"><?= ui_escape($title ?? 'Revise los datos del formulario.') ?></h2>
        <ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= ui_escape($error) ?></li><?php endforeach ?></ul>
    </div>
<?php endif ?>

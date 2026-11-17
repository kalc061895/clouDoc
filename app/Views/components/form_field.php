<?php
/**
 * Native Bootstrap field. Pass value/old() and validation errors explicitly.
 * Types: text, email, password, number, date, datetime-local, time, tel, url,
 * search, file, textarea, select. File/password values are never repopulated.
 */
helper('ui');
if (! isset($name) || ! is_string($name) || $name === '') {
    throw new InvalidArgumentException('A form field needs a name.');
}
$type = $type ?? 'text';
if (! in_array($type, ['text', 'email', 'password', 'number', 'date', 'datetime-local', 'time', 'tel', 'url', 'search', 'file', 'textarea', 'select'], true)) {
    throw new InvalidArgumentException('Unsupported form field type.');
}
$id = $id ?? 'field-' . substr(hash('sha256', $name), 0, 12);
$errors = array_values(array_filter((array) ($errors ?? []), static fn ($error) => is_scalar($error) && (string) $error !== ''));
$attributes = $attributes ?? [];
$attributes['id'] = $id;
$attributes['name'] = $name;
$attributes['class'] = trim(($type === 'select' ? 'form-select' : 'form-control')
    . ($errors !== [] ? ' is-invalid' : '') . ' ' . ($attributes['class'] ?? ''));
$attributes['required'] = (bool) ($required ?? ($attributes['required'] ?? false));
$attributes['disabled'] = (bool) ($disabled ?? ($attributes['disabled'] ?? false));
if (isset($placeholder) && $type !== 'select') {
    $attributes['placeholder'] = $placeholder;
}
$describedBy = trim((string) ($attributes['aria-describedby'] ?? ''));
if (! empty($helpText)) {
    $describedBy .= ' ' . $id . '-help';
}
if ($errors !== []) {
    $attributes['aria-invalid'] = 'true';
    $describedBy .= ' ' . $id . '-error';
}
if (trim($describedBy) !== '') {
    $attributes['aria-describedby'] = trim($describedBy);
}
unset($attributes['type'], $attributes['value']);
?>
<div class="mb-3">
    <label class="form-label" for="<?= ui_escape($id) ?>"><?= ui_escape($label ?? $name) ?><?php if ($attributes['required']): ?> <span class="text-danger" aria-hidden="true">*</span><?php endif ?></label>
    <?php if ($type === 'textarea'): ?>
        <textarea<?= ui_attributes($attributes + ['rows' => 3]) ?>><?= ui_escape($value ?? '') ?></textarea>
    <?php elseif ($type === 'select'):
        $selectedValues = array_map('strval', (array) ($value ?? ''));
    ?>
        <select<?= ui_attributes($attributes) ?>>
            <?php if (isset($placeholder)): ?><option value=""<?= in_array('', $selectedValues, true) || $selectedValues === [] ? ' selected' : '' ?>><?= ui_escape($placeholder) ?></option><?php endif ?>
            <?php foreach ($options ?? [] as $optionValue => $optionLabel): ?>
                <option value="<?= ui_escape($optionValue) ?>"<?= in_array((string) $optionValue, $selectedValues, true) ? ' selected' : '' ?>><?= ui_escape($optionLabel) ?></option>
            <?php endforeach ?>
        </select>
    <?php else:
        $attributes['type'] = $type;
        if (! in_array($type, ['password', 'file'], true)) {
            $attributes['value'] = $value ?? '';
        }
    ?>
        <input<?= ui_attributes($attributes) ?>>
    <?php endif ?>
    <?php if (! empty($helpText)): ?><div id="<?= ui_escape($id) ?>-help" class="form-text"><?= ui_escape($helpText) ?></div><?php endif ?>
    <?php if ($errors !== []): ?><div id="<?= ui_escape($id) ?>-error" class="invalid-feedback"><?php foreach ($errors as $error): ?><div><?= ui_escape($error) ?></div><?php endforeach ?></div><?php endif ?>
</div>

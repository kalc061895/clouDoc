<?php
/**
 * Action menu using Bootstrap's installed dropdown plugin. Each item has label,
 * optional href, disabled, icon and attrs (e.g. data-bs-toggle/target for a modal).
 * An item with divider=true is a separator. Mutations belong in CSRF forms.
 */
helper('ui');
if (! isset($id) || ! preg_match('/^[a-zA-Z][a-zA-Z0-9_-]*$/D', $id)) {
    throw new InvalidArgumentException('A dropdown needs a unique, CSS-safe id.');
}
?>
<div class="dropdown">
    <?= ui_button($label ?? 'Acciones', ['variant' => $variant ?? 'secondary', 'size' => $size ?? 'sm', 'class' => 'dropdown-toggle',
        'attrs' => ['id' => $id, 'data-bs-toggle' => 'dropdown', 'aria-expanded' => 'false']]) ?>
    <ul class="dropdown-menu<?= ($alignEnd ?? true) ? ' dropdown-menu-end' : '' ?>" aria-labelledby="<?= ui_escape($id) ?>">
        <?php foreach ($items ?? [] as $item): ?>
            <?php if (! empty($item['divider'])): ?><li><hr class="dropdown-divider"></li><?php continue; endif ?>
            <?php
            $disabled = (bool) ($item['disabled'] ?? false);
            $link = isset($item['href']);
            $attrs = $item['attrs'] ?? [];
            $attrs['class'] = trim('dropdown-item' . ($disabled ? ' disabled' : '') . ' ' . ($attrs['class'] ?? ''));
            if ($link) {
                $attrs['href'] = $disabled ? '#' : ui_url($item['href']);
                if ($disabled) {
                    $attrs['aria-disabled'] = 'true';
                    $attrs['tabindex'] = '-1';
                }
                if (($attrs['target'] ?? '') === '_blank') {
                    $attrs['rel'] = trim(($attrs['rel'] ?? '') . ' noopener noreferrer');
                }
            } else {
                unset($attrs['href']);
                $attrs['type'] = 'button';
                $attrs['disabled'] = $disabled;
            }
            $tag = $link ? 'a' : 'button';
            ?>
            <li><<?= $tag ?><?= ui_attributes($attrs) ?>><?php if (! empty($item['icon'])): ?><i class="<?= ui_escape($item['icon']) ?> me-2" aria-hidden="true"></i><?php endif ?><?= ui_escape($item['label'] ?? '') ?></<?= $tag ?>></li>
        <?php endforeach ?>
    </ul>
</div>

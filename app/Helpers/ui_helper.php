<?php

declare(strict_types=1);

/**
 * Stateless HTML helpers for the Monster / Bootstrap 5 template in public/main.
 * Load with helper('ui'). Labels and attributes are always escaped.
 */

if (! function_exists('ui_escape')) {
    function ui_escape(mixed $value): string
    {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (! function_exists('ui_variant')) {
    function ui_variant(string $variant): string
    {
        if (! in_array($variant, ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'], true)) {
            throw new InvalidArgumentException('Unknown UI variant: ' . $variant);
        }

        return $variant;
    }
}

if (! function_exists('ui_url')) {
    /** Accept application URLs, fragments and HTTP(S); reject executable schemes. */
    function ui_url(string $url): string
    {
        if (
            preg_match('/[\x00-\x20\x7f\\\\]/', $url)
            || (preg_match('/^[a-z][a-z0-9+.-]*:/i', $url) && ! preg_match('~^https?://~i', $url))
        ) {
            throw new InvalidArgumentException('UI links must be relative or HTTP(S) URLs.');
        }

        return $url;
    }
}

if (! function_exists('ui_attributes')) {
    /** Serialize attributes without allowing inline JavaScript or markup injection. */
    function ui_attributes(array $attributes): string
    {
        $html = '';
        foreach ($attributes as $name => $value) {
            if (
                ! is_string($name) || ! preg_match('/^[a-zA-Z][a-zA-Z0-9_:.-]*$/D', $name)
                || preg_match('/^on/i', $name) || in_array(strtolower($name), ['style', 'srcdoc'], true)
            ) {
                throw new InvalidArgumentException('Unsupported UI attribute.');
            }
            if ($value === null) {
                continue;
            }
            if (str_starts_with($name, 'aria-') || str_starts_with($name, 'data-')) {
                $value = is_bool($value) ? ($value ? 'true' : 'false') : $value;
            }
            if ($value === false) {
                continue;
            }
            if (! is_scalar($value)) {
                throw new InvalidArgumentException('UI attributes must have scalar values.');
            }
            if (in_array(strtolower($name), ['href', 'src', 'action', 'formaction'], true)) {
                $value = ui_url((string) $value);
            }
            $html .= ' ' . $name . ($value === true ? '' : '="' . ui_escape($value) . '"');
        }

        return $html;
    }
}

if (! function_exists('ui_button')) {
    /**
     * ui_button('Guardar', ['type'=>'submit', 'variant'=>'primary', 'icon'=>'ti ti-check']).
     * Provide href to render a link. attrs supports data-bs-* for modal triggers.
     */
    function ui_button(string $label, array $options = []): string
    {
        $variant = ui_variant($options['variant'] ?? 'primary');
        $size = $options['size'] ?? '';
        if (! in_array($size, ['', 'sm', 'lg'], true)) {
            throw new InvalidArgumentException('Button size must be sm or lg.');
        }
        $link = isset($options['href']);
        $disabled = (bool) ($options['disabled'] ?? false);
        $attributes = $options['attrs'] ?? [];
        $classes = 'btn btn-' . (! empty($options['outline']) ? 'outline-' : '') . $variant;
        $classes .= $size !== '' ? ' btn-' . $size : '';
        $classes .= ! empty($options['rounded']) ? ' rounded-pill' : '';
        $classes .= $disabled && $link ? ' disabled' : '';
        $classes .= ' ' . ($options['class'] ?? '') . ' ' . ($attributes['class'] ?? '');
        $attributes['class'] = trim($classes);
        if ($link) {
            unset($attributes['type'], $attributes['disabled']);
            $attributes['href'] = $disabled ? '#' : ui_url($options['href']);
            if ($disabled) {
                $attributes['aria-disabled'] = 'true';
                $attributes['tabindex'] = '-1';
            }
        } else {
            $type = $options['type'] ?? 'button';
            if (! in_array($type, ['button', 'submit', 'reset'], true)) {
                throw new InvalidArgumentException('Invalid button type.');
            }
            unset($attributes['href']);
            $attributes['type'] = $type;
            $attributes['disabled'] = $disabled;
        }
        if (($attributes['target'] ?? '') === '_blank') {
            $attributes['rel'] = trim(($attributes['rel'] ?? '') . ' noopener noreferrer');
        }
        $icon = empty($options['icon']) ? '' : '<i class="' . ui_escape($options['icon']) . ' me-2" aria-hidden="true"></i>';
        $tag = $link ? 'a' : 'button';

        return '<' . $tag . ui_attributes($attributes) . '>' . $icon . ui_escape($label) . '</' . $tag . '>';
    }
}

if (! function_exists('ui_badge')) {
    /** The template uses badge bg-*-subtle text-* and optional rounded-pill. */
    function ui_badge(string $label, string $variant = 'primary', array $options = []): string
    {
        $variant = ui_variant($variant);
        $class = ($options['subtle'] ?? true) ? 'bg-' . $variant . '-subtle text-' . $variant : 'text-bg-' . $variant;
        $class = 'badge ' . $class . (! empty($options['pill']) ? ' rounded-pill' : '');
        $attributes = $options['attrs'] ?? [];
        $attributes['class'] = trim($class . ' ' . ($options['class'] ?? '') . ' ' . ($attributes['class'] ?? ''));

        return '<span' . ui_attributes($attributes) . '>' . ui_escape($label) . '</span>';
    }
}

if (! function_exists('ui_switch')) {
    /**
     * Native Bootstrap switch, no plugin required. Unchecked submits 0 by default.
     * Give an explicit id when repeating a name on the same page.
     */
    function ui_switch(string $name, bool $checked = false, array $options = []): string
    {
        $id = $options['id'] ?? 'switch-' . substr(hash('sha256', $name), 0, 12);
        $disabled = (bool) ($options['disabled'] ?? false);
        $attributes = $options['attrs'] ?? [];
        $attributes = array_merge($attributes, [
            'class' => trim('form-check-input ' . ($attributes['class'] ?? '')),
            'type' => 'checkbox',
            'role' => 'switch',
            'name' => $name,
            'id' => $id,
            'value' => $options['value'] ?? '1',
            'checked' => $checked,
            'disabled' => $disabled,
        ]);
        $label = $options['label'] ?? $name;
        $hidden = '';
        if (($options['uncheckedValue'] ?? '0') !== false) {
            $hidden = '<input' . ui_attributes([
                'type' => 'hidden',
                'name' => $name,
                'value' => $options['uncheckedValue'] ?? '0',
                'disabled' => $disabled
            ]) . '>';
        }

        return '<div class="form-check form-switch py-2">' . $hidden . '<input' . ui_attributes($attributes) . '>'
            . '<label class="form-check-label" for="' . ui_escape($id) . '">' . ui_escape($label) . '</label></div>';
    }
}

if (! function_exists('ui_columns')) {
    /** Shared column contract for the static table and DataTableCell. */
    function ui_columns(array $columns): array
    {
        if ($columns === []) {
            throw new InvalidArgumentException('A table needs at least one column.');
        }
        foreach ($columns as $column) {
            if (
                ! is_array($column) || ! isset($column['key'], $column['label'])
                || ! (is_string($column['key']) || is_int($column['key'])) || ! is_string($column['label'])
            ) {
                throw new InvalidArgumentException('Each column needs key and label.');
            }
            if (isset($column['format']) && ! is_callable($column['format'])) {
                throw new InvalidArgumentException('Column format must be callable.');
            }
            if (! empty($column['html']) && ! isset($column['format'])) {
                throw new InvalidArgumentException('HTML columns require a trusted formatter.');
            }
        }

        return array_values($columns);
    }
}

if (! function_exists('ui_table_value')) {
    /** Formatters receive ($value, $row); HTML is opt-in for developer-owned markup. */
    function ui_table_value(array|object $row, array $column): string
    {
        $key = $column['key'];
        $value = is_array($row) ? ($row[$key] ?? '') : ($row->{$key} ?? '');
        $formatted = isset($column['format']) ? $column['format']($value, $row) : $value;

        return ! empty($column['html']) ? (string) $formatted : ui_escape($formatted);
    }
}

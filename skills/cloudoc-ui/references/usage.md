# API de componentes

Las rutas indicadas son relativas a la raíz de ClouDoc. Los componentes cargan el helper cuando lo necesitan y no importan CSS ni JS. Todos los recursos provienen del directorio `public/assets` al que apunta la plantilla `public/main`.

## Helpers puros

```php
helper('ui');

echo ui_button('Guardar', [
    'type' => 'submit', 'variant' => 'primary', 'icon' => 'ti ti-check',
    'attrs' => ['form' => 'form-persona'],
]);
echo ui_button('Volver', ['href' => site_url('inicio'), 'outline' => true]);
echo ui_badge('Aprobado', 'success', ['pill' => true]);
echo ui_switch('activo', true, ['id' => 'persona-activo', 'label' => 'Activo']);
```

`ui_button(string $label, array $options = []): string`:

- `variant`: primary, secondary, success, danger, warning, info, light o dark.
- `href`: convierte el botón en enlace. Recibe una URL creada por el consumidor, no resuelve rutas.
- `type`: button por defecto, submit o reset; `size`: vacío, sm o lg.
- `outline`, `rounded`, `disabled`: booleanos; `icon`: clase Tabler existente.
- `class`: clases adicionales de la plantilla; `attrs`: atributos escapados, incluidos `data-bs-*`, `aria-*`, `id` y `form`.

`ui_badge(string $label, string $variant = 'primary', array $options = []): string`: opciones `subtle` (true), `pill` (false), `class` y `attrs`.

`ui_switch(string $name, bool $checked = false, array $options = []): string`: opciones `id`, `label`, `value` (1), `uncheckedValue` (0), `disabled` y `attrs` para el checkbox. Un campo oculto envía el valor de apagado; `uncheckedValue => false` lo suprime. Al deshabilitar el switch también se deshabilita el campo oculto. Para nombres repetidos, dar un ID único en cada llamada.

Labels y atributos siempre se escapan. No se admiten manejadores inline `onclick`, `style`, `srcdoc` ni esquemas ejecutables en URLs. Conectar eventos mediante el JS existente o atributos Bootstrap. Botones que mutan datos deben enviar formularios protegidos con `csrf_field()`; un enlace deshabilitado apunta a `#` y usa `aria-disabled`.

## Layouts

```php
<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Personal<?= $this->endSection() ?>
<?= $this->section('content') ?>
    <!-- contenido con componentes -->
<?= $this->endSection() ?>
<?= $this->section('modals') ?>
    <!-- modal fuera de contenedores con overflow -->
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
    <!-- solo lógica de esta vista, sin reimportar librerías -->
<?= $this->endSection() ?>
```

`layouts/main` tiene sidebar, navbar, contenedor `content-main`, toasts y dependencias compartidas. El usuario viene de Shield; los menús vienen de `sidebar`, `menuVertical` o, como fallback, del `MenuCell` existente. `navbar` añade acciones de cabecera. Se conservan las secciones heredadas `username`, `pageStyles` y `pageScripts`. El layout principal es vertical; no migra ni modifica layouts de módulos.

`layouts/auth` ofrece un contenedor central limpio para login y recuperación. Acepta `content` para nuevas vistas o `main` para las vistas Shield existentes; elegir una sola sección. También admite `styles`, `scripts`, `modals`, `pageStyles` y `pageScripts`. No carga dependencias administrativas de tablas/gráficos. `app/Views/Shield/layout.php` es el adaptador hacia este layout.

`layouts/partials/ui_styles.php` y `ui_scripts.php` centralizan recursos con el parámetro `authenticated`. El layout principal conserva SweetAlert, Select2, ApexCharts y Toastr para consumidores anteriores, junto a jQuery/DataTables y Bootstrap. No carga scripts demo de dashboard ni versiones duplicadas de jQuery. El adaptador `ui_init.php` conecta tablas/toasts de forma idempotente. El menú `.open-here` se conserva en `ui_legacy_menu.php`, exclusivo del layout principal.

Para usar los componentes en Asistencia u otro layout existente:

1. Reutilizar el CSS y las librerías ya importadas; no añadir `ui_scripts.php` si las dependencias están presentes.
2. Renderizar `components/toast` una vez si se necesitan flash toasts.
3. Incluir `view('layouts/partials/ui_init')` una vez, después de jQuery, DataTables (para tablas) y Bootstrap (para toasts). Es un bloque de inicialización, no otro archivo JS.
4. No incluir `ui_legacy_menu.php`: el módulo conserva su navegación.

## Tarjetas y modales

```php
echo view('components/card', [
    'title' => 'Datos personales',
    'actionsHtml' => ui_button('Editar', ['attrs' => [
        'data-bs-toggle' => 'modal', 'data-bs-target' => '#editar-persona',
    ]]),
    'bodyHtml' => view('personal/formulario', ['persona' => $persona], ['saveData' => false]),
    'footerHtml' => '<small>Revise los datos antes de guardar.</small>',
], ['saveData' => false]);

echo view('components/modal', [
    'id' => 'editar-persona', 'title' => 'Editar persona', 'size' => 'lg',
    'bodyHtml' => view('personal/formulario', ['persona' => $persona], ['saveData' => false]),
    'footerHtml' => ui_button('Guardar', ['type' => 'submit', 'attrs' => ['form' => 'form-persona']]),
], ['saveData' => false]);
```

Las vistas `personal/formulario` son ejemplos del consumidor, no archivos de la base UI. No repetir el mismo formulario/ID a la vez en tarjeta y modal.

`card`: título opcional, `actionsHtml`, `bodyHtml`, `footerHtml`, `class`, `attributes`. Sin título ni acciones omite cabecera. Sin footer omite pie.

`modal`: `id` obligatorio; `title`, `bodyHtml`, `footerHtml`; `size`: vacío, sm, lg, xl; `centered` y `scrollable`: true; `staticBackdrop`: false. Sin footer personalizado incluye un botón Cerrar. Los formularios dentro del modal se renderizan desde vistas que incluyen CSRF. Para un submit en el pie, usar el atributo `form` con el ID del formulario.

## Tablas

Columnas compartidas por `components/table` y `DataTableCell`:

```php
$columns = [
    ['key' => 'nombre', 'label' => 'Nombre'],
    ['key' => 'activo', 'label' => 'Estado', 'html' => true,
        'format' => static fn ($value, $row) => ui_badge($value ? 'Activo' : 'Inactivo', $value ? 'success' : 'secondary')],
    ['key' => 'id', 'label' => 'Acciones', 'html' => true,
        'orderable' => false, 'searchable' => false,
        'format' => static fn ($value, $row) => ui_button('Ver', ['href' => site_url('personal/' . (int) $value), 'size' => 'sm'])],
];

echo view('components/table', [
    'columns' => $columns, 'rows' => $rows, 'caption' => 'Personal',
], ['saveData' => false]);

// Con closures: instanciar la celda evita la serialización del caché de CI4 4.5.
echo (new \App\Cells\DataTableCell())->fill([
    'id' => 'tabla-personal', 'columns' => $columns, 'rows' => $rows,
    'pageLength' => 25, 'columnFilters' => true, 'order' => [[0, 'asc']],
])->render();

// Sin closures en los parámetros: también se admite view_cell().
echo view_cell('App\\Cells\\DataTableCell', [
    'id' => 'tabla-nombres',
    'columns' => [['key' => 'nombre', 'label' => 'Nombre']],
    'rows' => $rows,
]);
```

Cada fila es un array u objeto. `key` accede a una propiedad/clave directa. `format($value, $row)` devuelve texto por defecto; `html => true` requiere un formateador confiable. `class` en la columna aplica clases existentes a cabecera y celdas. `orderable` y `searchable` se usan en la celda dinámica.

La tabla estática acepta `id`, `caption`, `class`, `attributes` y `emptyMessage`. La celda acepta `id`, `caption`, `columns`, `rows`, `pageLength` (10/25/50/100), `searching` (true), `ordering` (true), `columnFilters` (false) y `order` (pares de índice de columna y asc/desc). No hace consultas ni procesa peticiones AJAX; toda la interacción es local al dataset recibido. No activar filtros ni paginación de servidor sin implementar su protocolo en un endpoint autorizado.

La tabla vacía dinámica no genera una fila con colspan porque DataTables no la admite en tbody. La librería muestra el aviso de tabla vacía. En modales se ajustan los anchos al abrirlos.

## Toasts de sesión

En el controlador, antes de redirigir:

```php
session()->setFlashdata('toasts', [
    ['variant' => 'success', 'title' => 'Guardado', 'message' => 'La persona fue actualizada.', 'delay' => 5000],
]);
return redirect()->to(site_url('personal'));
```

También se aceptan flashdata escalares `success`, `error`, `warning`, `info` y `message`. Los textos se escapan: no enviar HTML. Variantes Bootstrap como en badges. Errores `danger` permanecen visibles hasta cerrarlos; el resto usa `delay` (1000–60000 ms). Para renderizar sin sesión, pasar `messages` explícitamente a `components/toast`.

## Formularios, navegación y estados

```php
echo view('components/form_errors', [
    'errors' => $errors ?? [], // validation->getErrors() desde el controlador
], ['saveData' => false]);

echo view('components/form_field', [
    'name' => 'correo', 'id' => 'persona-correo', 'type' => 'email', 'label' => 'Correo',
    'value' => old('correo', $persona['correo'] ?? ''),
    'required' => true, 'helpText' => 'Use su correo institucional.',
    'errors' => $errors['correo'] ?? [],
], ['saveData' => false]);

echo view('components/form_field', [
    'name' => 'estado', 'type' => 'select', 'label' => 'Estado',
    'options' => [1 => 'Activo', 0 => 'Inactivo'], 'value' => old('estado', 1),
    'placeholder' => 'Seleccione',
], ['saveData' => false]);

echo view('components/alert', [
    'variant' => 'warning', 'title' => 'Revise los plazos',
    'message' => 'El registro cierra esta semana.', 'dismissible' => true,
], ['saveData' => false]);

echo view('components/empty_state', [
    'title' => 'Sin resultados', 'description' => 'Cambie los filtros para continuar.',
    'actionsHtml' => ui_button('Crear', ['href' => site_url('personal/nuevo')]),
], ['saveData' => false]);

echo view('components/breadcrumbs', ['items' => [
    ['label' => 'Inicio', 'url' => site_url('inicio')], ['label' => 'Personal'],
]], ['saveData' => false]);

echo view('components/dropdown', [
    'id' => 'persona-acciones-7', 'label' => 'Acciones', 'items' => [
        ['label' => 'Ver', 'icon' => 'ti ti-eye', 'href' => site_url('personal/7')],
        ['label' => 'Editar', 'attrs' => ['data-bs-toggle' => 'modal', 'data-bs-target' => '#editar-persona']],
        ['divider' => true], ['label' => 'No disponible', 'disabled' => true],
    ],
], ['saveData' => false]);
```

`form_field` acepta `name` obligatorio, `id`, `label`, `type`, `value`, `required`, `disabled`, `placeholder`, `helpText`, `errors` (string o array) y `attributes`. Tipos: text, email, password, number, date, datetime-local, time, tel, url, search, file, textarea y select. Un textarea admite `attributes => ['rows' => 5]`. Un select recibe `options` como mapa valor/texto; para selección múltiple, usar nombre `roles[]`, `attributes => ['multiple' => true]` y un array en `value`. Se preservan valores cero. Los campos password y file nunca repueblan su valor. Los atributos permiten min, max, step, autocomplete, accept y readonly, respetando las restricciones de `ui_attributes`.

Los campos conectan label, ayuda y errores mediante IDs y `aria-describedby`; los inválidos usan `is-invalid`, `aria-invalid` e `invalid-feedback`. Para campos repetidos con el mismo nombre, suministrar IDs únicos. Los componentes reciben valores/errores, no ejecutan validaciones, consultas ni lecturas de sesión. El formulario consumidor incorpora `csrf_field()` y `enctype="multipart/form-data"` si sube archivos. El escapar valores en la vista no sustituye la validación en el servidor.

`form_errors` recibe `errors` y un `title` opcional; no genera aviso si no hay errores. `alert` recibe `variant`, `title`, `message` y `dismissible` (false). `bodyHtml` sustituye el texto únicamente para contenido generado por una vista confiable. `empty_state` acepta `title`, `description`, `icon` (clase Tabler) y `actionsHtml` confiable; no tiene efectos de sesión ni recarga.

`breadcrumbs` acepta `items` con `label` y `url` opcional. El último es siempre texto con `aria-current="page"`, sin enlace. `dropdown` necesita un `id` único y acepta `label`, `variant` (secondary), `size` (sm), `alignEnd` (true) e `items`. Los elementos sin href son botones type=button; los atributos Bootstrap abren modales sin JS adicional. Los enlaces deshabilitados no navegan. Las operaciones que cambian datos usan formularios POST con CSRF, no enlaces GET.

## Verificación

Ejecutar desde la raíz:

```text
php vendor/phpunit/phpunit/phpunit --no-coverage app/Verification/UiComponentsTest.php
```

Las pruebas verifican escape HTML, URLs/atributos peligrosos, contratos de tablas, filtros, modales, flashdata, herencia de layouts y que las dependencias existan y se importen una vez. No necesitan base de datos.

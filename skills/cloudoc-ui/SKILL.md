---
name: cloudoc-ui
description: Construir o refactorizar vistas PHP de ClouDoc con sus helpers UI, View Cell DataTableCell y componentes Bootstrap de la plantilla public/main. Usar para formularios, tablas, tarjetas, modales, notificaciones y layouts de este proyecto.
---

# ClouDoc UI

Aplicar esta skill en el repositorio que contiene `app/Helpers/ui_helper.php` y `app/Cells/DataTableCell.php`. Antes de cambiar una vista, identificar su layout: `layouts/main`, `layouts/auth`, `layouts/portal` o un layout de módulo como `asistenciaLayout`. Mantener el layout del módulo salvo que la tarea solicite migrarlo.

Consultar [la API y los ejemplos](references/usage.md) para los parámetros y la integración con layouts existentes. La galería ejecutable está en `app/Views/examples/ui.php`; no tiene ruta pública propia.

- Cargar `helper('ui')` donde se usen `ui_button`, `ui_badge` o `ui_switch`. Reutilizar `components/card`, `components/modal`, `components/toast` y `components/table` en vez de copiar su HTML.
- Para formularios usar `components/form_field` y `components/form_errors`; para avisos y navegación usar `components/alert`, `components/empty_state`, `components/breadcrumbs` y `components/dropdown`. Pasar valores recuperados con `old()` y errores de validación explícitamente; los componentes no consultan la sesión. No repoblar contraseñas ni archivos. Los enlaces de dropdown no deben ejecutar operaciones de escritura.
- `public/main` es la referencia visual. Sus páginas usan `../assets`, que corresponde a `public/assets` en este proyecto. Construir las URLs con `base_url('assets/...')`; usar `site_url()` para rutas de la aplicación. Algunos HTML de ejemplo están vacíos: revisar `public/main/index.html`, `form-bootstrap-switch.html` y el CSS compartido cuando sea necesario.
- Conservar clases Monster/Bootstrap 5: `card`, `card-body`, `btn`, `badge`, `form-check form-switch`, `table-responsive`, `modal` y `toast`. No agregar otro framework ni archivos CSS/JS para variaciones que las utilidades existentes resuelven.
- Los layouts importan las dependencias una vez. Los componentes nunca importan librerías ni inicializadores de demostración. El adaptador mínimo está en `layouts/partials/ui_init.php`; reutilizarlo para tablas y toasts.
- Usar `content`, `modals`, `styles` y `scripts` en los nuevos layouts. Se conservan `pageStyles`, `pageScripts`, `menuVertical` y `username` para vistas existentes. `Shield/layout` adapta las vistas de autenticación a `layouts/auth` y conserva la sección `main`.
- Textos y atributos se escapan automáticamente. `bodyHtml`, `actionsHtml`, `footerHtml` y columnas `html => true` son contratos explícitos de HTML confiable: producirlos mediante vistas/helpers, nunca con datos crudos de usuarios. Las columnas HTML requieren un `format` callable.
- `DataTableCell` pagina, busca, ordena y filtra en el navegador el dataset completo recibido. Consultar y autorizar los datos en el controlador/servicio; no enviar datos fuera del ámbito del usuario ni un dataset previamente paginado. Para volúmenes que requieren paginación de servidor, integrar un endpoint existente de DataTables por separado.
- En CI4 4.5, `view_cell()` serializa los parámetros incluso con TTL cero: con closures en `format`, usar `(new DataTableCell())->fill($config)->render()`. No activar caché compartida para tablas personalizadas por usuario.
- Los IDs de tablas y modales deben ser únicos por documento. Tras insertar componentes por AJAX, ejecutar `ClouDocUI.init(contenedor)` si el layout ya cargó el adaptador. El menú AJAX del layout principal ya lo hace.

Para validar cambios de esta base, ejecutar `php vendor/phpunit/phpunit/phpunit --no-coverage app/Verification/UiComponentsTest.php`, revisar sintaxis PHP y verificar en navegador cuando el entorno de la aplicación esté disponible. Los filtros de autenticación/permisos y CSRF siguen siendo responsabilidad de rutas y formularios; elegir un layout no autoriza un endpoint.

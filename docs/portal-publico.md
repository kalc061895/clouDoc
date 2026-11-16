# Portal público institucional

La ruta `/` muestra la portada general de ClouDoc. El inicio de sesión continúa en `/login`, mediante CodeIgniter Shield.

## Bienvenidas por módulo

- `/portal/modulos/tramite-documentario`
- `/portal/modulos/legajos`
- `/portal/modulos/seleccion`
- `/portal/modulos/asistencia`
- `/portal/modulos/planillas`

Estas páginas son públicas e informativas. No sustituyen los paneles privados ni conceden permisos. Planillas se presenta como «En preparación» porque aún no tiene un módulo operativo en este proyecto. Las entradas administrativas existentes mantienen sus filtros de sesión.

La mesa de partes, consulta de expedientes y TUPA mantienen sus rutas anteriores, enlazadas desde Trámite Documentario.

## Contenido y manuales

Editar `app/Config/Portal.php` para cambiar la entidad, descripciones, funciones, primeros pasos y la lista `$recursos`. Cada material necesita módulo, tipo (`pdf` o `video`), título, descripción y URL. Los archivos públicos pueden colocarse en `public/manuales/`; los videos también pueden enlazarse mediante HTTPS.

Cada recurso aparece tanto en el centro de aprendizaje general como en la página de su módulo. Hay filtros por tipo y búsqueda por título o módulo. Mientras no se publiquen materiales se muestran espacios informativos, sin enlaces vacíos. El ejemplo completo está en `public/manuales/README.md`.

La portada utiliza un diseño adaptable y estilos propios en `public/assets/css/portal.css`, sin depender de recursos externos. Incluye navegación por teclado, etiquetas para lectores de pantalla, preguntas frecuentes y acceso al login en todas las páginas.

## Verificación

Se verificaron respuestas HTTP 200 para la portada y las cinco bienvenidas, además de CSS y JavaScript. Un módulo inexistente devuelve 404. Sin sesión, Asistencia, Legajos y la administración de convocatorias redirigen al login. Se comprobó la sintaxis PHP y JavaScript. La inspección visual en navegador quedó pendiente porque el acceso a la aplicación agotó su tiempo de espera.

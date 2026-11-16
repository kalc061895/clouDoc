# Materiales públicos del portal

Coloque los PDF o videos públicos en subcarpetas por módulo, por ejemplo:
`asistencia/programacion.pdf` o `legajos/introduccion.mp4`.

Registre cada material en `app/Config/Portal.php`, propiedad `$recursos`:

```php
[
    'modulo' => 'asistencia',
    'tipo' => 'pdf', // pdf o video
    'titulo' => 'Guía de programación',
    'descripcion' => 'Pasos para preparar y enviar un rol.',
    'url' => 'manuales/asistencia/programacion.pdf',
],
```

Para videos alojados externamente, puede usar una URL HTTPS completa.
Los recursos aparecen en la portada principal y en la portada del módulo.
Sin materiales registrados, se muestran espacios «Materiales por publicar».

Este directorio es público: no coloque legajos, boletas, expedientes ni datos personales.

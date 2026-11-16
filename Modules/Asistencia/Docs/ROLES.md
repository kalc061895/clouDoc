# PDF de roles: flujo vigente

La generación y el historial anteriores fueron retirados el 16/11/2026. Sus menús seguían activos porque continuaban definidos en el catálogo y asignados a perfiles.

## Uso actual

1. Cargar programación y enviar el rol a Recursos Humanos.
2. Obtener el visto bueno de Recursos Humanos y la aprobación de Dirección.
3. En `asistencia/gestion-roles/envios`, abrir el envío aprobado y pulsar «Generar / abrir PDF oficial».
4. Continuar las firmas en el módulo Firma desde ese mismo detalle.

El documento se genera desde el snapshot aprobado y se reutiliza al abrirlo nuevamente. No se recalcula un documento ya emitido.

## Elementos conservados

- `RolDocumentoService::generarDesdeSnapshot` y `archivo`.
- `RolPdfRenderer`, `RolDocumentoData` y las plantillas `roles/pdf.php` y `roles/tabla.php`.
- Tabla `casis_rol_documento`, enlace `envio_id`, PDF privados y sus hashes.
- Integración con Firma y versiones firmadas.
- Descarga `asistencia/roles/{id}/pdf`, con comprobación de autorización.

Respaldar conjuntamente la base de datos y `writable/asistencia/roles/`.

## Compatibilidad

`asistencia/roles`, `asistencia/roles/generacion` y `asistencia/roles/historial` redirigen al historial de envíos. Los endpoints antiguos `consultar`, `listar`, `generar` y `{id}/anular` responden HTTP 410 y no ejecutan operaciones. `asistencia/roles/firmar` conserva su redirección a Firma.

Se eliminaron las vistas de generación e historial, su formulario de filtros y el JavaScript exclusivo de esas pantallas. Los métodos internos antiguos de `RolDocumentoService` se conservan por compatibilidad y pruebas, pero el controlador no los expone.

La migración `2026-11-16-140000_RetireLegacyRoleScreens` desactiva los menús sin borrar documentos, firmas ni asignaciones históricas. El catálogo de navegación también los mantiene inactivos.
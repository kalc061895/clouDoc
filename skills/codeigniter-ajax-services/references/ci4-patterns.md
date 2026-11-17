# Aplicación del patrón en CI4

## Ubicación y composición

Un caso de uso puede componerse de ruta y filtro, controlador, servicio, modelo/repositorio y vista/JS. No necesita por defecto DTO, interfaz, repositorio nuevo, bus de comandos o clase base. Añadirlos cuando una frontera o varios consumidores lo justifiquen.

En un proyecto modular, mantener los namespaces del módulo. Las operaciones transversales pueden vivir en `app/Services/`; los servicios específicos del módulo permanecen junto al módulo. Un servicio puede invocar otro servicio para una capacidad compartida, pero una sola capa debe ser dueña de la transacción del caso de uso.

CI4 dispone de factorías en `Config\Services`; no confundir ese archivo con las clases de negocio. Mantener `Config\Services` como lugar de composición/factorías, no como contenedor de métodos de negocio. Usar constructores con dependencias claras o el mecanismo ya establecido por el proyecto. Evitar inicializar dependencias HTTP dentro de los servicios.

En controladores existentes, preservar `initController()` y su llamada al padre cuando se use para inicializar dependencias. No introducir un constructor de controlador que impida la inicialización del framework. Las pruebas deben poder sustituir el servicio sin conectar por accidente a una base de datos real.

## Validación y datos permitidos

Inspeccionar primero las APIs de la versión instalada. En versiones que lo soportan, obtener exclusivamente los datos validados mediante las APIs de validación disponibles; en otras versiones, construir explícitamente una whitelist a partir de los campos con reglas. No asumir que validar transforma tipos o elimina campos adicionales por sí solo.

La secuencia de un endpoint de escritura es:

1. Sus filtros establecen sesión, permisos generales y CSRF.
2. El controlador lee la fuente prevista y comprueba formato/estructura.
3. Valida y normaliza únicamente los campos del contrato.
4. Obtiene el actor y el contexto autorizado del servidor.
5. Llama al servicio con esos datos; el servicio comprueba ámbito e invariantes y persiste.
6. El adaptador HTTP devuelve el resultado público con el estado adecuado.

Los datos válidos no son necesariamente datos autorizados. Un ID de oficina existente puede quedar fuera del ámbito del actor. Una regla `is_unique` de validación tampoco sustituye una restricción única de base de datos frente a dos solicitudes concurrentes.

## Límites del servicio

Los métodos expresan intención, por ejemplo `aprobarSolicitud($solicitudId, $actorId, $datos)` o `listarPersonal($contexto, $filtros)`. El servicio obtiene y comprueba los recursos relevantes. No recibir un flag `esAdministrador` del payload como decisión de confianza.

Las excepciones de dominio deben permitir distinguir, cuando el caso lo necesite, recurso no visible, regla incumplida y conflicto. Usar los tipos ya existentes si son suficientes. No lanzar `PageNotFoundException`, devolver `ResponseInterface` ni usar `session()`, `request()` o `view()` dentro de la lógica del caso de uso.

Para datos de entrada usar arrays tipados/documentados o DTO según complejidad. Para salida devolver un resultado pequeño y explícito; evitar serializar directamente modelos/entidades que podrían exponer campos nuevos al cambiar el esquema. Un servicio de consulta entrega los datos y metadatos del listado, no la plantilla HTML que los presenta.

## Transacciones y efectos externos

CI4 puede marcar una transacción como fallida sin lanzar una excepción de consulta, según la versión y configuración. Al usar `transStart()`/`transComplete()`, comprobar `transStatus()` y propagar el fallo. Al usar transacciones manuales, garantizar rollback en todas las rutas fallidas y comprobar los resultados relevantes. No mezclar ambos patrones en un mismo caso de uso por rutina.

Todas las escrituras atómicas deben usar la misma conexión. Un modelo construido con otra conexión puede quedar fuera de la transacción aunque se llame desde el mismo método. No dar por hecho que transacciones anidadas crean unidades independientes o savepoints.

Validar reglas que puedan cambiar concurrentemente junto a la escritura, usando restricciones, bloqueo o actualización condicionada según el caso. Un flujo que cambia de pendiente a aprobado puede requerir verificar el estado en la propia actualización y distinguir una operación ya aplicada de un conflicto.

No añadir retries de escrituras ciegos, ni enviar correos/llamadas externas dentro de un bloque transaccional largo. Cuando un efecto externo deba seguir a una confirmación, aprovechar los mecanismos de colas, post-commit u outbox existentes; introducir uno nuevo únicamente si la garantía requerida lo necesita.

## Refactorización gradual

Al extraer lógica de un controlador existente, primero registrar su comportamiento observable: rutas, métodos, filtros, parámetros, mensajes y formato de respuesta. Mover reglas y persistencia al servicio conservando ese comportamiento. Mejorar el contrato de forma coordinada si el cambio lo requiere, actualizando consumidores y pruebas.

No convertir automáticamente todos los formularios a AJAX ni todos los fragmentos a JSON. Para una funcionalidad nueva, la interfaz y el tipo de endpoint se eligen según el flujo, conservando la independencia del caso de uso. El destino Vue/React orienta las fronteras, no obliga a instalar un framework ahora.

En ClouDoc, un componente UI puede recibir datos ya preparados o esperar un fragmento; los servicios no llaman a `ui_button()`, `ui_badge()` ni a las vistas. La presentación de acciones y estados corresponde a la vista, a su formateador de tabla o al futuro componente frontend.

## Pruebas que aportan evidencia

- Servicio: invariantes, transiciones, límites, ámbito y resultados con dependencias controladas.
- Integración de persistencia: unicidad, actualización condicionada, conteos autorizados y rollback de todos los cambios relacionados ante un fallo.
- Endpoint: método y formato, input inválido, falta de autenticación/autorización, validación, códigos y estructura del éxito/error.
- Consumidor: estados de carga y error, datos con caracteres especiales, errores de campo, token CSRF actualizado y sesión expirada.

Para probar filtros y CSRF, usar pruebas de integración con los filtros realmente habilitados o comprobar la configuración efectiva; una prueba que los omite no demuestra protección del endpoint. Un mock de base de datos tampoco demuestra que una transacción real revierta escrituras.

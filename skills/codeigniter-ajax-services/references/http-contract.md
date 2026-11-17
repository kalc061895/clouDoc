# Contratos HTTP y consumo asíncrono

Esta convención es el valor por defecto para endpoints nuevos. No reemplaza automáticamente contratos existentes, JSON:API, DataTables ni integraciones externas.

## Envelope

Una creación correcta puede responder HTTP 201:

```json
{
  "success": true,
  "message": "Registro creado.",
  "data": {"id": 17, "nombre": "Ejemplo"},
  "errors": {},
  "meta": {}
}
```

Una validación fallida responde HTTP 422:

```json
{
  "success": false,
  "message": "Revise los datos enviados.",
  "data": null,
  "errors": {"nombre": "El nombre es obligatorio."},
  "meta": {}
}
```

`errors` es siempre un objeto campo/mensaje; `meta` es un objeto. En PHP, usar `(object) []` para serializar un mapa vacío como `{}`. `data` contiene la representación pública del resultado, un listado o null. No exponer entidades completas, columnas privadas, mensajes SQL, rutas internas ni trazas. Los mensajes inesperados que recibe el cliente son genéricos; registrar los detalles con el mecanismo del proyecto.

En CI4, el adaptador HTTP aplica `setStatusCode($status)->setJSON($payload)`. Si hay una ubicación canónica de un recurso creado, puede añadir `Location`. No crear un helper estático global ni una clase base nueva si un método privado o un responder HTTP existente basta.

| Estado | Significado habitual |
| --- | --- |
| 200 | Lectura o modificación completada con cuerpo JSON |
| 201 | Recurso creado |
| 204 | Éxito sin cuerpo; no enviar un envelope y no llamar response.json() |
| 400 | JSON malformado o estructura de solicitud incorrecta |
| 401 | No autenticado |
| 403 | Operación no permitida; fallo CSRF cuando esa sea la respuesta configurada |
| 404 | Recurso inexistente o no visible, según la política del proyecto |
| 409 | Conflicto con el estado actual, duplicado o concurrencia |
| 422 | Campos bien formados pero inválidos o incumplimiento de reglas de entrada |
| 429 | Límite de solicitudes, si existe |
| 500 | Fallo inesperado |
| 503 | Dependencia temporalmente indisponible cuando se ha identificado ese caso |

El servicio no lanza errores HTTP. El controlador/responder traduce excepciones específicas del caso de uso a la política anterior. No convertir toda excepción a 422 ni toda excepción a 503. No devolver HTTP 200 para ocultar errores de una mutación.

## Sesión y CSRF

Las rutas protegidas conservan sus filtros de autenticación y autorización. Para endpoints JSON, verificar que los filtros respeten el formato también en errores; una redirección a login no equivale a una respuesta API 401. Al cambiar un filtro compartido, comprobar las pantallas HTML y los demás consumidores.

Con autenticación por cookies, mantener el filtro CSRF existente en operaciones de escritura. Enviar el token mediante el header o campo que CI4 esté configurado para aceptar; no inventar un header fijo distinto para cada pantalla. Si el proyecto regenera tokens, puede incluir:

```json
{
  "success": true,
  "message": "Registro actualizado.",
  "data": {"id": 17},
  "errors": {},
  "meta": {
    "csrf": {
      "header": "X-CSRF-TOKEN",
      "name": "csrf_test_name",
      "hash": "token-actual-generado-por-el-servidor"
    }
  }
}
```

Los nombres del ejemplo son ilustrativos: generar los reales con `csrf_header()`, `csrf_token()` y `csrf_hash()` donde corresponda. Si el contrato existente ya usa `csrf` en la raíz, mantenerlo en sus consumidores. Actualizar el token después de respuestas de éxito y de validación que lo incluyan; no asumir que un fallo en un filtro alcanzó al controlador. Coordinar solicitudes concurrentes si la regeneración de tokens lo requiere. No deshabilitar CSRF para resolver una carrera entre peticiones.

## Cliente Fetch: comportamiento esperado

Reutilizar el wrapper AJAX existente si proporciona estas garantías. Un adaptador debe:

- Usar URLs generadas por el servidor para la instalación actual, no rutas absolutas inventadas ni localhost embebido.
- Enviar `Accept: application/json`; para un cuerpo JSON usar `Content-Type: application/json` y `JSON.stringify`. Con `FormData`, dejar que el navegador añada su Content-Type y boundary.
- Conservar las cookies en solicitudes del mismo origen. No añadir credenciales a otro origen ni configurar CORS sin que el proyecto lo necesite.
- Comprobar `response.ok`, el tipo de contenido y si hubo una redirección inesperada antes de intentar interpretar JSON. Tratar una respuesta HTML de login o un error del servidor como tal, sin ejecutar ese HTML.
- Manejar 204 sin parsear cuerpo; manejar errores de JSON como errores de protocolo, no como éxito vacío.
- Distinguir cancelación, falta de red, errores HTTP y validación por campo. Volver a habilitar el formulario y retirar el estado de carga en `finally`.
- Actualizar el estado CSRF del cliente y los formularios que lo necesiten, usando el contrato existente. No reemplazar indiscriminadamente todos los inputs del documento.
- Usar `AbortController` o un identificador de petición para búsquedas que compiten; no interpretar la cancelación de un POST como garantía de que el servidor canceló la escritura.
- Ofrecer mensajes de error y conservar las entradas del usuario. La política de recuperación de una sesión expirada pertenece al flujo de autenticación existente.

La validación del cliente mejora la experiencia, pero los mismos controles siguen siendo obligatorios en el servidor.

## Fragmentos y listados

Una ruta de fragmento responde `text/html` y tiene un consumidor que espera un parcial concreto. Las rutas de datos responden JSON; no decidir el formato a partir de un supuesto de que Fetch siempre envía `X-Requested-With`.

Un listado propio puede usar `data` como array y `meta.pagination` con página, tamaño, total y último número de página. Validar filtros, limitar tamaño de página y permitir solo columnas/direcciones de ordenación conocidas. Si se usa DataTables server-side, respetar `draw`, `recordsTotal`, `recordsFiltered` y `data`, validar sus parámetros y mantener ambos conteos dentro del ámbito autorizado. No envolver ese protocolo en el envelope general sin adaptar el cliente.

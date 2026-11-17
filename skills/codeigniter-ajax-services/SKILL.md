---
name: codeigniter-ajax-services
description: Diseñar o modificar controladores, servicios, endpoints AJAX/JSON y vistas modulares en CodeIgniter 4 con separación de responsabilidades y contratos preparados para un futuro frontend Vue o React. Usar para lógica backend y flujos asíncronos; no para cambios exclusivamente visuales.
---

# CodeIgniter 4: servicios y contratos AJAX

Construir casos de uso que puedan consumirse desde vistas PHP, peticiones asíncronas o un futuro frontend independiente. La sustitución del frontend debe conservar las reglas de negocio y los servicios, y requerir pocos cambios en los adaptadores HTTP.

## Antes de implementar

Revisar el flujo completo afectado: rutas, filtros, controlador, servicio, modelos, consumidores JavaScript y vistas. Comprobar la versión instalada de CI4, el mecanismo de autenticación y CSRF y los contratos de respuesta existentes. No imponer una migración general ni cambiar formatos consumidos por otras pantallas al corregir un caso concreto.

Ubicar servicios compartidos en `app/Services/` y servicios de un módulo en su namespace/directorio `Modules/<Modulo>/Services/` si esa es la estructura existente. Respetar las factorías y convenciones de inyección ya utilizadas. En ClouDoc, complementar esta arquitectura con los componentes de `cloudoc-ui` cuando estén disponibles; la skill también debe funcionar en otros proyectos CI4.

## Distribución de responsabilidades

| Capa | Responsabilidad | Evitar |
| --- | --- | --- |
| Rutas y filtros | Verbo HTTP, autenticación, permisos generales, CSRF y restricciones del endpoint | Depender de un menú oculto para autorizar |
| Controlador | Leer el formato de entrada previsto, validar su estructura, obtener contexto de identidad, llamar al servicio y convertir resultados/errores a HTTP | Consultas, transacciones, cálculos de negocio, reglas de aprobación y procesamiento de múltiples pasos |
| Servicio | Ejecutar el caso de uso, comprobar reglas e invariantes, aplicar ámbito sobre recursos y coordinar transacciones | Request/Response, renderizar HTML, redirecciones, flashdata o leer directamente la sesión |
| Modelos o repositorios | Persistencia, consultas parametrizadas y acceso a datos | Formatear respuestas HTTP o depender del navegador |
| Vistas y componentes | Presentar datos ya preparados, formularios y contenedores para resultados/errores | Consultas, autorización efectiva, cálculos de negocio o construir reglas duplicadas en JS |
| JavaScript | Recoger entradas, enviar peticiones y actualizar la interfaz | Decidir permisos o sustituir validaciones y reglas del servidor |

La sesión y los flashdata pertenecen al límite HTTP. Pasar al servicio un ID de actor o contexto explícito obtenido del servidor. No confiar en `user_id`, establecimiento, oficina, rol o tenant enviados por el cliente para determinar sus permisos. Validar tanto la operación como el acceso al recurso concreto en el servidor; el servicio conserva las restricciones de ámbito del caso de uso.

## Entrada y reglas de negocio

- El controlador define las reglas de formato, tipo, presencia y límites del payload. Leer solo JSON, formulario o query según el endpoint; evitar `getVar()` para mezclar fuentes sin un contrato explícito.
- Tratar JSON malformado como error de entrada y exigir el contenedor previsto. Una creación recibe un objeto, no un array arbitrario o un escalar. Solo pasar campos validados y permitidos al servicio, nunca el payload completo por conveniencia.
- Normalizar de forma explícita las fechas, booleanos e IDs que el caso de uso requiera. Validar no equivale a escapar HTML ni justifica aplicar un limpiador genérico a todos los datos. Escapar al presentar y usar bindings/Query Builder al persistir.
- El servicio vuelve a garantizar invariantes del dominio, incluso si se invoca desde CLI u otro controlador: transiciones de estado, ámbito, límites, integridad y requisitos de la operación. La validación HTTP no sustituye estas reglas.
- Mantener consultas y cálculos fuera del controlador, también para listados y opciones de selects. Usar modelos/repositorios existentes; introducir abstracciones adicionales solo cuando faciliten una separación real o pruebas útiles.

## Servicios y consistencia

Preferir métodos nombrados por caso de uso, con entradas y retornos tipados. Inyectar dependencias sustituibles cuando hagan falta; no agregar un contenedor DI externo. Los servicios retornan datos/resultados de dominio o lanzan excepciones específicas, sin elegir códigos HTTP.

Coordinar en el servicio una transacción para cambios que deban ser atómicos. Comprobar el resultado efectivo según la versión/configuración de CI4; un `try/catch` no garantiza que una consulta fallida haya lanzado una excepción. Mantener restricciones de base de datos para unicidad e integridad y contemplar concurrencia cuando el flujo lo requiera. No mantener una transacción abierta esperando llamadas lentas a servicios externos; separar esos efectos o usar el mecanismo existente de jobs/outbox cuando el caso lo justifique.

Consultar [patrones CI4](references/ci4-patterns.md) al implementar validación, dependencias, transacciones o sus pruebas.

## Contratos HTTP y AJAX

Preferir endpoints explícitos para datos y para renderizado. Un endpoint de datos responde JSON tanto si lo llama Fetch como si lo llama otro cliente. `isAJAX()` o `X-Requested-With` no son requisitos de autorización ni una forma fiable de identificar Fetch. Los GET no cambian estado.

Para endpoints nuevos, usar el contrato de [respuestas y cliente](references/http-contract.md): estado HTTP significativo y envelope consistente `success`, `message`, `data`, `errors`, `meta`. Centralizar el formateo solo si se reutiliza; mantenerlo en la capa HTTP. En endpoints existentes, conservar su contrato o adaptar/versionar coordinadamente todos sus consumidores. Los protocolos externos como DataTables server-side mantienen su propio esquema.

Una pantalla completa puede renderizar `view(...)`; una ruta de fragmento puede devolver HTML parcial. Elegir y documentar el formato de cada endpoint. No hacer que un servicio produzca HTML, ni mezclar una página completa de login con una respuesta JSON. Verificar también qué devuelven los filtros cuando una sesión expira, falla CSRF o no hay permisos.

El cliente gestiona carga, doble envío, errores HTTP, validación por campo y fallos de red. Usar el CSRF existente en operaciones con sesión; si se regenera el token, actualizarlo a partir del contrato previsto. Descartar o cancelar respuestas antiguas en búsquedas/listados que puedan competir. No reintentar automáticamente mutaciones sin una garantía de idempotencia.

## Presentación y migración progresiva

Separar layouts, componentes y fragmentos. Mantener en PHP el renderizado que el proyecto ya necesita, pero dar a los servicios retornos independientes de HTML. Para nuevos flujos de negocio, preferir JSON como contrato de datos; reservar fragmentos HTML para integración progresiva con pantallas PHP.

El JS actualiza nodos de texto y propiedades de controles con APIs seguras; no insertar valores de JSON mediante `innerHTML`. La inserción de un fragmento HTML es un contrato explícito de presentación generado y escapado por el servidor. Reutilizar los recursos y componentes existentes; no añadir Vue, React, nuevas librerías ni archivos de infraestructura solo para preparar una futura migración.

Los listados grandes se paginan y filtran en el servidor con límites y columnas de ordenación permitidas. No enviar un dataset ilimitado al navegador por tratarse de AJAX. Mantener el alcance autorizado en consultas, totales y exportaciones. Una tabla pequeña puede usar paginación local si ya es la opción apropiada del proyecto.

## Comprobación del cambio

Probar los invariantes modificados en servicios y la traducción HTTP en pruebas de endpoints. Cubrir, según el flujo, entrada inválida, falta de sesión/permisos, recurso fuera de ámbito, resultado correcto y rollback de escrituras relacionadas. Sustituir dependencias externas al probar reglas; usar integración de base de datos para verificar atomicidad y restricciones reales.

Verificar los consumidores existentes y la forma de mostrar errores, actualizar CSRF y recuperarse de una sesión expirada. Ejecutar las comprobaciones pertinentes al cambio y reportar lo validado y cualquier límite real del entorno. No introducir pruebas triviales que solo reproduzcan el código ni refactorizaciones ajenas a la tarea.

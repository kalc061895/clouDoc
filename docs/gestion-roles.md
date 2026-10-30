# Gestión de roles

Acceso: `/asistencia/gestion-roles`, también desde Programación.

## Puesta en marcha

Aplicar las migraciones pendientes del módulo en el entorno con acceso a la base de datos:

```powershell
docker compose exec app php spark migrate -n 'Modules\Asistencia'
```

Esta migración crea `casis_rol_envio`, separa permisos de programación y revisión por etapa, y vincula el PDF oficial al envío aprobado. No ejecuté la migración sobre la base de datos.

Para añadir el enlace al menú lateral, sincronice la navegación:

```powershell
docker compose exec app php spark db:seed 'Modules\Asistencia\Database\Seeds\NavigationSeeder'
```

1. Ingresar como `superadmin` o `asi_sua`.
2. Clasificar los cargos en Gestor DB → Cargos y los turnos en Gestión de roles. Cada trabajador hereda `car_modalidad` de su cargo; `perl_modalidad` fue eliminado. La migración conserva la clasificación previa cuando existe y usa ASISTENCIAL para los cargos sin personal.
3. Asignar usuarios responsables de programación a oficinas con fecha de inicio y término. Para aprobación, asignar revisores distintos por oficina y etapa (RR. HH. o Dirección); esas delegaciones no conceden permiso para editar la programación. La gestión completa está en Gestión de roles → Delegaciones temporales (crear, consultar, editar y eliminar); requiere perfil `superadmin` o `asi_sua`. La clasificación asistencial/administrativa de turnos está separada en Gestión de roles → Modalidad de turnos; el catálogo completo sigue en GestorDB → Turnos.
4. Crear el periodo del establecimiento, mes trabajado, apertura, inicio de aprobación y cierre. Las reglas se fijan al crearlo y no se editan retroactivamente.
5. Programar los turnos y enviar cada rol a RR. HH. Cada envío crea una versión inmutable con SHA-256. RR. HH. puede observar y devolver para corregir, o dar visto bueno; Dirección puede aprobar, devolver con comentario o rechazar definitivamente luego del visto bueno.

No se permite escribir fuera del periodo abierto. Una observación permite corregir y reenviar hasta el cierre. Las horas se interpretan en America/Lima. El cierre se calcula con el reloj, sin depender de una tarea programada. No aprueba automáticamente roles pendientes.

Los roles se crean con la primera asignación por oficina exacta. Una jefatura superior opera los mismos registros de sus dependencias. Los estados PENDIENTE_RRHH, VISTO_BUENO_RRHH, APROBADO_DIRECCION y RECHAZADO bloquean la edición; OBSERVADO permite corregir mientras el periodo no esté cerrado. PRESENTADO y APROBADO se conservan para compatibilidad con registros previos.

## Reglas implementadas

- TURNOS_PERMITIDOS: lista de códigos; combina restricciones por oficina, régimen contractual y modalidad.
- MAX_TURNOS: cantidad máxima de los códigos indicados por mes.
- MAX_HORAS_MES: duración máxima mensual de turnos cuya fecha de inicio pertenece al mes.
- MAX_CONTINUAS: duración de bloques contiguos de trabajo, incluyendo turnos de meses vecinos.
- Cruces de horarios, compatibilidad de modalidad y jornada administrativa de ocho horas de lunes a viernes.

Todas las reglas configuradas bloquean; el ámbito de oficina de una regla es exacto, a diferencia del permiso del responsable que incluye descendientes. Las reglas CAS/nombrados y máximos GD/GN se configuran, no se presuponen.

Las plantillas son reutilizables, pero no se aplican automáticamente: cada período conserva una copia de las reglas que se cargaron al crearlo. Un período marcado «Sin reglas configuradas» no ejecuta límites de plantilla hasta que se cree o se cargue una regla en ese período. Los períodos abiertos no se pueden modificar retroactivamente.

Edición, eliminación, importación definitiva y cambios/reversiones de turno verifican permisos y plazos. La importación vuelve a consultar registros existentes y valida su resultado completo antes de confirmar. Una violación revierte el lote. La previsualización Excel conserva sus controles básicos; las reglas institucionales se comprueban al confirmar.

Cada envío conserva su JSON y hash SHA-256; observaciones y reenvíos crean una nueva versión y no modifican la anterior. Los actores y fechas de RR. HH. y Dirección quedan registrados por separado. Como `casis_programacion` sigue siendo la tabla operativa actual, los turnos enviados ya son visibles para los procesos de asistencia mientras esperan aprobación. La aprobación final verifica que la programación y el personal de la oficina sigan coincidiendo con el snapshot; si cambiaron, no habilita el PDF hasta corregir y enviar una nueva versión.

El PDF oficial queda habilitado después de APROBADO_DIRECCION y se vincula al envío para reutilizar el mismo documento. FirmaPerú permite firmar secuencialmente el PDF por el elaborador, RR. HH. y Dirección, en ese orden; cada actor inicia su propia operación y el sistema registra la fecha al recibir cada nueva versión firmada.

## Alcance pendiente

- Descanso de 72 horas: requiere definir si es mínimo o máximo, tratamiento de licencias y extremos del periodo.
- Feriados administrativos, cobertura mínima, advertencias no bloqueantes y excepciones autorizadas.
- Validación criptográfica institucional de los certificados y de las versiones firmadas; el cliente FirmaPerú y la entidad certificadora realizan esa validación.
- Asignaciones laborales con vigencia histórica y flujo de revocación/edición de responsables.

## Verificación

La migración y la sincronización del menú están pendientes de ejecutar en el entorno. Se comprobó la sintaxis PHP de los archivos modificados; no se ejecutaron pruebas automatizadas. No se han configurado periodos o responsables reales automáticamente.

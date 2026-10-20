# Gestión de roles

Acceso: `/asistencia/gestion-roles`, también desde Programación.

## Puesta en marcha

1. Ingresar como `superadmin` o `asi_sua`.
2. Clasificar los cargos en Gestor DB → Cargos y los turnos en Gestión de roles. Cada trabajador hereda `car_modalidad` de su cargo; `perl_modalidad` fue eliminado. La migración conserva la clasificación previa cuando existe y usa ASISTENCIAL para los cargos sin personal.
3. Asignar usuarios responsables a oficinas con fecha de inicio y término. Incluye todos los descendientes; la facultad de aprobar es opcional.
4. Crear el periodo del establecimiento, mes trabajado, apertura, inicio de aprobación y cierre. Las reglas se fijan al crearlo y no se editan retroactivamente.
5. Programar los turnos y presentar cada rol antes del inicio de aprobación. Aprobar dentro del plazo siguiente.

No se permite escribir sin periodo abierto. Las horas se interpretan en America/Lima. El inicio de aprobación es exclusivo para editar. El cierre se calcula con el reloj, sin depender de una tarea programada. No aprueba automáticamente roles pendientes.

Los roles se crean con la primera asignación por oficina exacta. Una jefatura superior opera los mismos registros de sus dependencias. PRESENTADO y APROBADO bloquean la edición; OBSERVADO permite corregir solo durante PROGRAMACION. No hay reapertura de periodos ni modificación de aprobados.

## Reglas implementadas

- TURNOS_PERMITIDOS: lista de códigos; combina restricciones por oficina, régimen contractual y modalidad.
- MAX_TURNOS: cantidad máxima de los códigos indicados por mes.
- MAX_HORAS_MES: duración máxima mensual de turnos cuya fecha de inicio pertenece al mes.
- MAX_CONTINUAS: duración de bloques contiguos de trabajo, incluyendo turnos de meses vecinos.
- Cruces de horarios, compatibilidad de modalidad y jornada administrativa de ocho horas de lunes a viernes.

Todas las reglas configuradas bloquean; el ámbito de oficina de una regla es exacto, a diferencia del permiso del responsable que incluye descendientes. Las reglas CAS/nombrados y máximos GD/GN se configuran, no se presuponen.

Edición, eliminación, importación definitiva y cambios/reversiones de turno verifican permisos y plazos. La importación vuelve a consultar registros existentes y valida su resultado completo antes de confirmar. Una violación revierte el lote. La previsualización Excel conserva sus controles básicos; las reglas institucionales se comprueban al confirmar.

Se conservan eventos de auditoría y una instantánea del personal/horarios al presentar y aprobar. Los PDF del módulo documental existente todavía no están asociados automáticamente a esta instantánea.

## Alcance pendiente

- Descanso de 72 horas: requiere definir si es mínimo o máximo, tratamiento de licencias y extremos del periodo.
- Feriados administrativos, cobertura mínima, advertencias no bloqueantes y excepciones autorizadas.
- Vinculación de documentos PDF a la versión aprobada y consulta visual de la instantánea histórica.
- Asignaciones laborales con vigencia histórica y flujo de revocación/edición de responsables.

## Verificación

Migración aplicada en Docker: `php spark migrate -n 'Modules\Asistencia'`.
Pruebas: `php vendor/bin/phpunit --no-coverage tests/unit/ProgramacionFiltrosTest.php tests/unit/CambioTurnoServiceTest.php tests/unit/GestionRolTest.php`.
No se han configurado periodos o responsables reales automáticamente.

# Calificación mensual

Ruta: `asistencia/calificacion`. Menú: Calificar y revisar, Historial y PDF, Criterios.

## Instalación

Ejecutar en el entorno con conexión a la base de datos:

```sh
php spark migrate -n 'Modules\Asistencia'
php spark db:seed 'Modules\Asistencia\Database\Seeds\CalificacionMenuSeeder'
```

La migración crea cuatro tablas `casis_cal_*`; conserva las tablas antiguas `casis_calificacion` y `casis_calificacion_detalle`. El seeder es aditivo e idempotente. NavigationSeeder también incluye las nuevas opciones.

## Operación

1. Cerrar el periodo de papeletas en Periodos. Solo se admite CERRADO.
2. Seleccionar periodo, establecimiento/microred/red y, opcionalmente, ID de trabajador. Se incluyen vínculos que se superponen al periodo. Todos deben pertenecer al grupo de corte elegido; si hay grupos distintos, se requiere selección individual o un ámbito compatible. No se omiten trabajadores silenciosamente.
3. Generar: guarda fuentes, criterios y resultado como una versión independiente, LISTA u OBSERVADA.
4. Revisar todas las observaciones y registrar sustento: REVISADA. La revisión acepta las cifras; no las modifica. Para corregirlas, modificar las fuentes mediante sus flujos y generar otra versión. Se puede ANULAR una versión aún no cerrada.
5. Cerrar: exige LISTA o REVISADA, periodo cerrado, fuentes sin cambios y ausencia de trabajadores cerrados en otro lote del mismo periodo. CERRADA es definitiva.
6. Generar PDF: cada archivo se conserva en base de datos con fecha, autor y SHA-256. Su descarga verifica integridad y autorización. El historial de eventos registra generación, revisión, cierre, anulación y documentos.

Los superadministradores y `asi_sua` tienen alcance global. Los demás necesitan autorización de aprobación sobre **todas** las oficinas incluidas, según las delegaciones existentes de Gestión de roles. El menú no concede permisos. Solo los administradores cambian criterios globales.

## Criterios iniciales

- Tolerancia 0 minutos; umbral de observación 3 tardanzas; ventana de asociación 120 minutos. Son valores iniciales operativos configurables, no reglas legales.
- Guardias: códigos GD y GN configurables, contadas como **programadas**. Domingo y feriado se determinan por fecha de inicio. Se consideran feriados repetitivos del catálogo; no se calculan pagos.
- Horas asistidas: pares ENTRADA/SALIDA válidos, limitados al turno. Permite múltiples pares y turnos que cruzan medianoche/fin de mes. Marca ambigua entre turnos, horario inválido o secuencia incompleta: observación, sin inferir horas.
- Ausencia sin marcaciones ni licencia/vacación/permiso aprobado de jornada completa: falta por turno. Sin rol no se infiere una falta por cada día calendario.
- Licencias y vacaciones activas: días calendario únicos dentro del corte, por categoría, sin convertirlos en presencia física.
- Papeletas con estado exacto APROBADO: intervalos únicos justificados, separados de presencia física y descontados de tardanza cuando cubren la demora. Otros estados no anulados/rechazados/cancelados generan observación. No se equipara PENDIENTE a aprobación.
- Observaciones por falta sin licencia, sin registros, sin rol, marcación sin turno, salida anticipada, turnos superpuestos, marcas ambiguas y tardanzas excesivas.

La ubicación institucional es la actual, como en el reporte mensual existente. No reconstruye traslados históricos ni calcula descuentos/remuneración. La revisión registra una aceptación global con sustento; las observaciones originales se conservan. Un periodo reabierto exige cerrarlo nuevamente antes de revisar/cerrar. Una calificación cerrada no se recalcula ni se reabre en esta versión.

## Verificación

```sh
php -d extension=sqlite3 vendor/phpunit/phpunit/phpunit --no-coverage Modules/Asistencia/Verification
```

Las pruebas aisladas cubren el motor, estados, cambios de fuentes, duplicidad de cierre, permisos y conservación de PDF. La prueba de PDF genera `writable/calificacion-qa/muestra.pdf` para inspección visual. En XAMPP se habilitó SQLite solo para el proceso de pruebas, sin modificar php.ini. En este proyecto el host `db` se resuelve dentro de Docker; la migración y el seeder se aplicaron con `docker compose exec -T app php spark ...`.

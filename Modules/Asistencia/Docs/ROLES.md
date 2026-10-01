# Consulta y documentos de roles

## Activación

Ejecutar en el entorno que tiene acceso a la base de datos. En este proyecto el servicio Docker de PHP se llama `app`:

```powershell
docker compose exec app php spark migrate -n 'Modules\Asistencia'
docker compose exec app php spark db:seed 'Modules\Asistencia\Database\Seeds\NavigationSeeder'
```

Sin Docker, usar los mismos comandos desde `php spark` con una conexión válida. La migración agrega `casis_rol_documento`; el seeder incorpora ROLES (1040), Generación (1041), Historial (1042) y Firmar (1043), vinculados al grupo 1000. No asigna usuarios al grupo. `migrate -n` también ejecuta las demás migraciones pendientes del módulo.

Rutas de entrada:

- `asistencia/roles/generacion`
- `asistencia/roles/historial`
- `asistencia/roles/firmar` (acceso al módulo independiente `firma`; ver `Modules/Firma/README.md`)

## Flujo

1. Elegir año, mes y establecimiento. Opcionalmente filtrar por tipo de oficina, oficina/departamento/área, UPSS y servicio UPSS.
2. Activar «Incluir dependencias» para abarcar las oficinas descendientes de la selección. Al elegir solamente un tipo, incluye todas las oficinas de ese tipo y, si se marca la opción, sus descendientes.
3. Consultar y revisar la matriz. Se reutiliza `ProgramacionTurnoService`: personal actualmente activo y turnos existentes no eliminados. Se omite personal sin programación para ese ámbito.
4. Generar y guardar. Se recalcula y compara la huella de los datos revisados; si cambiaron filtros, turnos o cabeceras, se requiere otra consulta.
5. Abrir el PDF almacenado desde el resultado o el historial. Generar nuevamente crea otro documento; no reemplaza el anterior.

La oficina debe pertenecer al establecimiento y tipo seleccionados. UPSS y servicio se verifican contra sus asociaciones al establecimiento. Departamento se obtiene del tipo DEPARTAMENTO en la oficina o sus antecesores. DIRESA, red y microred se obtienen de la jerarquía del establecimiento. Los datos ausentes se muestran como no registrados.

## Documento y conservación

- Dompdf, ya instalado en Composer; no requiere mPDF ni nuevas dependencias.
- A4 horizontal, días reales del mes y día de semana, cabeceras repetidas, numeración continua, leyenda y espacios para responsables.
- DNI/documento como texto, apellidos y nombres, cargo, turnos con el color del catálogo.
- GD, GN, M, T, N y cualquier otro código presente tienen **conteos de turnos**. MT se conserva como código independiente; no se descompone ni se presupone su duración. Total horas suma las duraciones calculadas por el servicio existente, incluidos cruces de medianoche.
- Varias asignaciones en un día se imprimen apiladas. La paginación reserva espacio según longitud de nombres, cargos y cantidad de turnos.
- Cada registro guarda filtros, cabeceras, personal, colores, horarios, totales y fecha como snapshot JSON, además del PDF y su SHA-256.
- Archivos privados en `writable/asistencia/roles/`. Se sirven por controlador con sesión y verificación de integridad. Respaldar juntos esta carpeta y `casis_rol_documento`.
- Si falla el registro en base de datos, se elimina solamente el archivo recién generado. No se regenera un archivo histórico al descargarlo.

## Estados

| Estado | Comportamiento |
| --- | --- |
| GENERADO | PDF almacenado, sin firma digital. |
| ANULADO | Conserva PDF original; registra usuario, fecha y motivo. No permite una segunda anulación. |

Anular no altera el PDF original. El estado vigente se consulta en el historial. Aún no existen firma digital, aprobaciones ni asignación de responsables a usuarios. Se conserva la protección de sesión del módulo; generar y anular requieren CSRF. La restricción de acceso por jefatura/establecimiento sigue pendiente junto con el modelo de responsables solicitado para una etapa posterior.

## Verificación

```powershell
php -d extension=sqlite3 vendor/bin/phpunit --no-configuration --bootstrap vendor/codeigniter4/framework/system/Test/bootstrap.php tests/unit/RolDocumentoTest.php tests/unit/ProgramacionFiltrosTest.php
```

En instalaciones con SQLite3 ya habilitado, omitir `-d extension=sqlite3`. Las pruebas usan SQLite en memoria y archivos temporales, sin tocar la base real. Cubren migración, filtros y dependencias, relaciones de UPSS, calendarios de 28–31 días, varios turnos al día, totales, persistencia, anulación, integridad, inserción fallida y PDF real.

`output/pdf/rol_turnos_demo.pdf` es una muestra con datos ficticios, revisada visualmente en sus tres páginas. La conexión real al host Docker `db` y las pantallas con sesión deben verificarse al activar la migración en el entorno de ejecución.

# Reporte mensual

Ruta: `asistencia/reportes/mensual`, usando `layouts/asistenciaLayout`. Consultas y exportaciones requieren la sesión existente de Asistencia.

Filtros: año, mes, DIRESA, red, microred, establecimiento, UPSS, servicio, tipo de oficina, oficina, dependencias y documento de identidad exacto. Los selectores se relacionan entre sí; el servidor vuelve a validar las selecciones. Máximo 500 trabajadores por consulta: si se supera, solicita reducir el ámbito, sin truncar resultados.

El resumen tiene una fila por registro de personal e incluye desglose por código de turno, cantidad y horas programadas, número de marcaciones y días con marcación, días de licencia, registros y horas de permiso y días de vacaciones. El detalle muestra cada día del mes y los registros que originan el resumen.

## Criterios

- Se usa la ubicación institucional actual. Se incluye personal no eliminado cuyo inicio/cese permita el mes; no se exige que continúe activo hoy para consultar meses pasados. Un trabajador sin actividad también puede aparecer.
- Programación no eliminada ni anulada/cancelada. La duración se calcula por horarios; una salida menor que la entrada termina al día siguiente. Horarios iguales representan cero horas, no se presume un turno de 24 horas.
- Marcaciones no eliminadas, por fecha calendario, mostrando reloj, tipo y origen (incluidas manuales). No se emparejan entradas/salidas ni se deducen horas efectivas, faltas o tardanzas. La salida de una guardia nocturna figura en su fecha real.
- UPSS y servicio pertenecen a la programación: seleccionan turnos y trabajadores con turnos en ese ámbito. Marcaciones e incidencias permanecen a nivel del trabajador durante el mes completo; no se atribuyen a una UPSS.
- Licencias y vacaciones activas (`estado = 1`) y no eliminadas. Los intervalos se recortan al mes; los días superpuestos dentro de una categoría se cuentan una sola vez. Categorías distintas no se restan entre sí.
- Permisos no eliminados ni anulados/rechazados/cancelados. Incluye pendientes y muestra el estado en el detalle. Las horas suman intervalos registrados, incluso si se superponen; no acreditan ausencia autorizada.
- Cambios posteriores en las fuentes se reflejan al volver a consultar/exportar. No es una instantánea cerrada ni una liquidación de planilla.

## Exportación

Excel: hojas `Resumen mensual` y `Detalle diario`, con cabeceras, filtros y paneles inmovilizados; documentos de identidad y otros textos se escriben explícitamente como texto para preservar ceros y evitar fórmulas inyectadas. Incluye todos los días del mes.

PDF e impresión: A4 horizontal, resumen y detalle diario opcional por trabajador. El PDF se genera con Dompdf, sin recursos remotos. Las exportaciones usan el mismo servicio y filtros que la consulta.

Para incorporar la opción al menú existente:

El seeder solo modifica las dos opciones de Reportes. La asignación de esas opciones a grupos se realiza por separado desde la administración de menús, con autorización del responsable. Como en las demás rutas de Asistencia, las rutas requieren sesión; la visibilidad del menú no constituye un filtro adicional de autorización del controlador.

```sh
php spark db:seed 'Modules\Asistencia\Database\Seeds\ReportesMenuSeeder'
```

Pruebas: `php -d extension=sqlite3 vendor/phpunit/phpunit/phpunit --no-coverage tests/unit/ReporteMensualTest.php` en XAMPP (si SQLite ya está habilitado, omitir `-d extension=sqlite3`).

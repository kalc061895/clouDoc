# Dispositivos y asistencia desde archivo

- Configuración: `/asistencia/dispositivos`.
- Carga única e historial: `/asistencia/dispositivos/archivos`.
- Los accesos antiguos `administrador/load_file` y `administrador/load_file_hv` redirigen a la carga única.

## Operación

1. Registrar el reloj con código, nombre, marca, establecimiento y, opcionalmente, modelo, serie, IP y puerto. Se puede editar o desactivar; no se eliminan los equipos que respaldan cargas históricas.
2. Seleccionar equipo y archivo. El servidor identifica y lee la estructura, archiva el original bajo `writable/asistencia/dispositivos` y registra un código `IMP-fecha-hora-identificador`, usuario, equipo y fecha de Lima.
3. Revisar el resultado. No se insertan marcaciones durante esta etapa. Los errores rechazan el lote completo, conservando el archivo y el detalle de hasta 100 incidencias.
4. Confirmar. Se vuelve a leer y validar el archivo, sus documentos, asignaciones laborales y periodos de asistencia. Todas las marcaciones nuevas y el estado de la carga se confirman en una transacción.

La modalidad de trabajo y los periodos de programación de turnos no sustituyen los periodos de corte de asistencia. Para importar fechas nuevas debe existir un periodo de asistencia ABIERTO o REABIERTO aplicable al trabajador.

## Formatos

- ZKTeco antiguo: `.txt`, `.dat`, `.csv` o `.log` con documento en la primera columna y `AAAA-MM-DD HH:MM[:SS]` en la segunda, separadas por tabulación. Las demás columnas se ignoran. Se completa a ocho dígitos el documento numérico corto, siguiendo el sistema anterior.
- Hikvision: `.xls` o `.xlsx`, hoja `Attendance Record`, rango mensual en A4 con fechas `AAAA/MM/DD`, trabajadores desde la fila 7, documento en A y día 1 en D. Se procesan todas las filas de continuación, los saltos de línea y horas Excel numéricas. No se ejecutan fórmulas. Fechas inexistentes, rangos de más de un mes o contenido no reconocido se informan como errores.
- Límite: 10 MB por original, 100 000 marcas; Excel hasta 20 000 filas/100 columnas y XLSX hasta 64 MB descomprimidos.

## Trazabilidad y duplicados

Se utiliza `casis_asistencia_importacion` y cada marcación queda relacionada mediante `asi_imp_ide` en `casis_asistencia`. Se conservan el nombre original, hash SHA-256, ruta privada, equipo y establecimiento originales, usuario de carga y usuario de confirmación. Las descargas requieren autorización y no exponen una ruta pública.

Mismo hash y establecimiento: se abre la carga anterior aunque se haya renombrado el archivo o elegido otro reloj. Las marcaciones repetidas dentro del archivo o ya existentes se omiten; no se restauran registros eliminados. La clave única de personal/fecha protege frente a cargas concurrentes. Los archivos corregidos se cargan como nuevos; si solo faltaba configurar personal o periodos, puede usarse Volver a revisar.

Configuración: superadmin, asi_sua y asistencia. Carga, revisión, confirmación e historial: los anteriores y asi_adm. Los seeders de navegación incluyen DISPOSITIVOS y ambas opciones, manteniendo esa distinción.

Instalación aislada del menú, preservando las personalizaciones existentes: `php spark db:seed 'Modules\Asistencia\Database\Seeds\DispositivosMenuSeeder'`.

## Alcance

IP y puerto son configuración de inventario. La descarga directa por protocolo de red, autenticación de equipos y sincronización automática quedan pendientes de integrar con los modelos físicos utilizados. No se requiere conectar al reloj para cargar sus reportes.

Pruebas: `php -d extension=sqlite3 vendor/bin/phpunit --no-coverage tests/unit/MarcacionArchivoParserTest.php tests/unit/DispositivoImportacionTest.php Modules/Asistencia/Verification/NavigationSeederTest.php`.

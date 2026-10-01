# Firma de documentos

Módulo independiente en `/firma`, basado en el protocolo de la carpeta `firma/`:
`startSignature(48596, base64)` → POST de `param_token` → parámetros PAdES en base64 → descarga del PDF → POST multipart del PDF firmado.

## Instalación

Ejecutar desde la raíz (en XAMPP puede usar `C:\xampp\php\php.exe`):

```sh
php spark migrate -n 'Modules\Firma'
```

Agregar a la configuración `.env` principal, sin publicar secretos:

```ini
firma.publicBase = 'https://su-dominio/cloudoc/public'
firma.clientId = 'credencial-institucional'
firma.clientSecret = 'secreto-institucional'
firma.stampUrl = 'https://su-dominio/logo-firma.png'
```

También se aceptan `FIRMAPERU_CLIENT_ID` y `FIRMAPERU_CLIENT_SECRET` como variables de entorno. No se lee ni se copia el `.env` de la aplicación de ejemplo. `publicBase` debe apuntar a esta aplicación, ser accesible desde el cliente de escritorio y usar HTTPS en producción. Configure una imagen de estampa accesible. El usuario necesita el cliente Firma Perú instalado y su certificado disponible.

Máximo predeterminado: 20 MiB por PDF (`firma.maxBytes`). Ajustar también `upload_max_filesize` y `post_max_size` de PHP. Guardado privado en `writable/firma/`; el servidor debe permitir escritura allí.

## Uso e integración

- Cargar un PDF externo en `/firma`.
- En Asistencia → Historial de roles → Firmar, incorporar el rol y pulsar Firmar. Solo se pueden incorporar roles propios y no anulados.
- Elegir motivo, cargo y estampa. La ubicación se confirma en el cliente Firma Perú.
- El servidor conserva original, versiones, SHA-256, usuario, fechas y operación. El navegador confirma éxito únicamente cuando el servidor recibió el archivo.
- Cada usuario consulta sus propios documentos. El módulo no implementa todavía bandejas compartidas ni circuitos de aprobación.
- Se firma un PDF por operación. La firma masiva en archivos 7z del ejemplo queda fuera de esta implementación.

Otros módulos pueden registrar un PDF mediante el servicio, **después de comprobar sus permisos y el estado del documento de origen**:

```php
$id = (new \Modules\Firma\Services\FirmaService())->registrar(
    $rutaLocalAutorizada,
    'documento.pdf',
    (int) auth()->id(),
    'modulo.tipo_documento',
    (string) $idOrigen
);
```

No se admiten rutas locales ni URLs arbitrarias enviadas por el navegador. La copia original del módulo de origen no se sustituye. Para nuevos orígenes que admitan anulación, extender `validarOrigen()` con su comprobación de vigencia.

## Interacción y datos obligatorios

Asunto, motivo, cargo y estampa son obligatorios. Se validan en el navegador y en el servidor; no se aceptan campos vacíos o solo espacios. El asunto se conserva como metadato de la operación y de la versión, sin modificar el contenido del PDF ni el protocolo de Firma Perú.

SweetAlert solicita confirmación antes de iniciar o cancelar. Los toast informan el inicio, las cargas y los errores. El aviso de éxito depende de la recepción confirmada por el servidor. El historial se consulta en un modal con asunto, motivo, cargo, fecha y acceso a cada PDF. Las versiones anteriores a esta actualización conservan el asunto vacío.

Aplicar también la migración `AddAsuntoFirma` con el mismo comando de instalación.

## Recepción y límites

Las tres rutas `firma/cliente/...` están exceptuadas de sesión porque el cliente de escritorio no comparte cookies. Se autorizan mediante un secreto aleatorio de 256 bits, almacenado únicamente como SHA-256 y válido 30 minutos. No agregar esas rutas a un filtro global CSRF sin exceptuarlas; las mutaciones del navegador sí exigen CSRF y sesión. Evitar registrar las URLs completas del cliente en el proxy, pues contienen el token temporal.

La recepción comprueba formato, tamaño, integridad del original y presencia estructural de firma; **no valida criptográficamente el certificado, su vigencia, revocación ni identidad**. Por eso la versión se registra como `FIRMA_RECIBIDA`, no como firma certificada o validada. La validación criptográfica debe realizarse con el validador institucional. No se confía en `signatureOk()` para guardar o acreditar una firma.

Una operación completada, cancelada, vencida o sobre una versión antigua no puede volver a escribir. Las actualizaciones usan transacción y comparación de versión para impedir sobrescrituras simultáneas.

## Comprobación manual con certificado

1. Incorporar PDF, lanzar Firma Perú y confirmar la estampa.
2. Confirmar una versión adicional, descargarla y verificarla con el validador institucional.
3. Firmar nuevamente esa versión y comprobar que se preservan ambas firmas.
4. Cancelar otra operación y comprobar que no cambia la versión.
5. Probar el retorno desde el equipo real y la URL HTTPS de despliegue.

# Cambio de contraseña desde Asistencia

Abrir el menú de usuario y elegir **Cambiar contraseña**. El mismo acceso está disponible en las orientaciones vertical y horizontal. El formulario pide contraseña actual, nueva contraseña y confirmación; muestra los errores por campo y el resultado en el modal.

## Flujo

- `GET asistencia/mi-cuenta/password/token` renueva el token del formulario; no modifica credenciales y responde con `Cache-Control: private, no-store`.
- `POST asistencia/mi-cuenta/password` acepta los campos de formulario `current_password`, `password` y `password_confirm`. Tiene filtros `session` y `csrf`; el actor siempre proviene de Shield, nunca del payload. Limita a cinco intentos por minuto por usuario.
- `Modules/Asistencia/Controllers/PasswordController.php` valida la entrada y devuelve el contrato JSON `success`, `message`, `data`, `errors`, `meta.csrf`. Estados: 200, 401, 422, 429 y 500, además de 403 del filtro CSRF.
- `app/Services/PasswordChangeService.php` verifica la contraseña actual, rechaza reutilización, comprueba confirmación y política Shield y guarda mediante `User::saveEmailIdentity()`. Shield genera el hash. No se escribe en otra cuenta ni se actualizan otras identidades.

Los parciales `passwordForm`, `passwordModal` y `passwordScript` reutilizan `components/form_field`, `components/modal`, helpers UI y Bootstrap ya cargado. No hay nuevas dependencias CSS/JS. Los datos de contraseña no se repueblan, se limpian al cerrar/completar el modal y no se incluyen en respuestas ni logs del controlador. La limpieza conserva el token CSRF vigente. Un fallo CSRF renueva el formulario y pide un nuevo envío manual; nunca reintenta automáticamente el cambio.

## Verificación

```text
php vendor/phpunit/phpunit/phpunit --no-coverage app/Verification/PasswordChangeTest.php app/Verification/UiComponentsTest.php
php spark filter:check POST asistencia/mi-cuenta/password
```

Las pruebas cubren reglas del servicio, contrato HTTP, selección del actor, límite de intentos, hashing nativo de Shield y rechazo del filtro CSRF sin token. La persistencia se sustituye por mocks: no se cambia ninguna contraseña real al ejecutar estas pruebas.

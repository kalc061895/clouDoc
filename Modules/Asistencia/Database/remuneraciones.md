# Remuneraciones por cargo y nivel

La tabla `casis_remuneraciones` contiene una escala por cada combinación de
`rem_car_ide` (referencia a `casis_cargo.car_ide`) y `rem_nivel` (hasta 50 caracteres,
como `casis_personal.perl_nivel`). La combinación es única incluso si se desactiva:
para reutilizarla se actualiza o reactiva el registro existente.

`rem_configuracion` es JSON obligatorio. Estructura propuesta (los valores `null`
representan importes o reglas pendientes de definir; no equivalen a cero):

```json
{
  "moneda": "PEN",
  "remuneracion": null,
  "guardias": {
    "diurna": null,
    "nocturna": null,
    "diurna_domingo": null,
    "nocturna_domingo": null,
    "diurna_feriado": null,
    "nocturna_feriado": null
  },
  "reten": {
    "diurno": null,
    "nocturno": null,
    "diurno_feriado": null,
    "nocturno_feriado": null
  },
  "descuentos": {
    "base_calculo": null,
    "dias_divisor": null,
    "horas_jornada": null
  }
}
```

Registrar los importes definidos como cadenas decimales de dos posiciones
(por ejemplo, `"3500.00"`), para conservar su representación exacta en JSON.
La capa que administre estos valores deberá validar importes no negativos y
divisores positivos antes de habilitar el cálculo. La columna JSON valida la
sintaxis, pero no impone este esquema ni las reglas de negocio.

El modelo recibe `rem_configuracion` como array de PHP y lo devuelve como array.
`obtenerPorCargoNivel($cargoId, $nivel)` devuelve la escala activa o `null` cuando
no existe. Se consulta usando `perl_car_ide` y `perl_nivel` del trabajador.

El CRUD está disponible en `asistencia/gestordb/remuneraciones`, con listado,
creación, edición y eliminación permanente con confirmación. Permite el acceso
a los perfiles `superadmin`, `asistencia` y `asi_sua`. Las escrituras requieren
sesión y token CSRF. El formulario valida montos no negativos de hasta dos
decimales, requiere remuneración mensual y conserva configuraciones adicionales.
Los valores vacíos opcionales se guardan como `null`. Los divisores definidos
deben ser positivos. No calcula descuentos. Las fórmulas de tardanzas y faltas,
los conceptos afectos y los redondeos deben definirse antes de integrarlos a la
calificación. Al cerrar una calificación se deberán conservar los importes y
reglas utilizados para que cambios posteriores de escala no alteren el historial.

Aplicar con el mecanismo habitual de migraciones del módulo:

```powershell
C:\xampp\php\php.exe spark migrate -n 'Modules\Asistencia'
```

El comando aplica todas las migraciones pendientes de ese namespace.

Para agregar únicamente la opción de menú y sus asignaciones:

```powershell
C:\xampp\php\php.exe spark db:seed 'Modules\Asistencia\Database\Seeds\RemuneracionesMenuSeeder'
```

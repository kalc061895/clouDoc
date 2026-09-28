<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Solicitud de Postulación</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            margin: 40px;
            color: #000;
        }

        .text-bold {
            font-weight: bold;
        }

        .justify {
            text-align: justify;
        }

        .mt-20 {
            margin-top: 20px;
        }

        .mt-30 {
            margin-top: 30px;
        }

        .mt-40 {
            margin-top: 40px;
        }

        .text-right {
            text-align: right;
        }

        /* Contenedor principal que agrupa las 3 columnas */
        .firmas-row {
            display: flex;
            justify-content: space-between;
            /* Distribuye el espacio entre ellas */
            align-items: flex-end;
            /* Alinea los elementos hacia la parte inferior */
            margin-top: 50px;
            width: 100%;
            max-width: 900px;
            /* Ajusta según el ancho de tu documento/página */
            margin-left: auto;
            margin-right: auto;
        }

        /* Estilos compartidos o individuales para las columnas */
        .firma-container-left,
        .firma-container,
        .firma-container-right {
            width: 260px;
            margin-top: 0;
            /* El margen superior lo maneja el contenedor padre */
            margin-left: 0;
            /* Anulamos los "auto" individuales */
            margin-right: 0;
        }

        .firma-container-left {
            text-align: left;
        }

        .firma-container {
            text-align: center;
        }

        .firma-container-right {
            text-align: right;
        }

        .linea-firma {
            border-top: 1px solid #000;
            margin-bottom: 5px;
        }

        .huella-box {
            border: 1px solid #000;
            width: 75px;
            height: 75px;
            margin: 10px auto 0 auto;
            font-size: 8pt;
            padding-top: 25px;
            text-align: center;
        }
    </style>
</head>

<body>


    <div class="text-bold" style="font-size: 10pt;">
        SOLICITA: Postular a <?= esc($num_convocatoria ?? '') ?>
    </div>

    <div class="mt-30 text-bold">
        <span class="text-bold">SEÑOR:</span><br>
        PRESIDENTE DE LA COMISION DE CONCURSO PARA CONTRATO TEMPORAL<br>
        POR REMPLAZO DE LA UNIDAD EJECUTORA 403 SALUD SAN ROMAN<br>
        <u>S. P.</u>
    </div>

    <div class="mt-30 justify">
        El que suscribe, <span class="text-bold"><?= esc($nombres) ?> <?= esc($apellidos) ?></span>, con DNI N° <span
            class="text-bold"><?= esc($documento) ?></span>, domiciliado legalmente en <span
            class="text-bold"><?= esc($direccion) ?></span>, del Distrito de Juliaca, Provincia de San Román, Región de
        Puno, ante usted con el debido respeto me presento y expongo:
    </div>

    <div class="mt-30 justify">
        Que, teniendo conocimiento del concurso para contrato por reemplazo que se llevará a cabo en la Unidad Ejecutora
        403 Salud San Román de la Dirección Regional de Salud Puno y reuniendo los requisitos necesarios para postular a
        la plaza de: <span class="text-bold"><?= esc($cargo) ?></span>, solicito a Usted tenga a bien acepte mi
        participación en el presente concurso, para lo cual me someto a lo dispuesto por las normas vigentes sobre el
        ingreso a la Administración Pública, por lo que <b>DECLARO BAJO JURAMENTO</b> conocer las bases del presente
        concurso y los perfiles de las Plazas publicadas, además manifiesto que la información proporcionada en la FICHA
        DE INSCRIPCION y AUTO EVALUACION es auténtica, veraz y conforme.
    </div>

    <div class="mt-30 text-right">
        Juliaca, <?= esc($fecha_actual) ?>
    </div>

    <div class="mt-30">
        <b>POR LO EXPUESTO:</b><br>
        A usted, Señor presidente solicito acceder a mi solicitud por ser de justicia.
    </div>

    <table style="width: 100%; max-width: 900px; margin: 50px auto 0 auto; border-collapse: collapse;">
        <tr>
            <!-- Columna Izquierda: Datos (Alineado a la izquierda) -->
            <td style="width: 50%; text-align: left; vertical-align: bottom;">
                <b>DNI: <?= esc($documento) ?></b><br>
                <b>Teléfono: <?= esc($telefono) ?></b><br>
                <b>Email: <?= esc($email) ?></b><br>
                <b>ID: <?= esc($codigo_inscripcion ?? '') ?></b>
            </td>

            <!-- Columna Central: Firma (Alineado al centro) -->
            <td style="width: 30%; text-align: center; vertical-align: bottom;">
                <!-- Asegúrate de tener tu clase .linea-firma en el CSS o puedes usar estilos inline como el ejemplo -->
                <div class="linea-firma" style="border-top: 1px solid #000; width: 80%; margin: 0 auto 5px auto;"></div>
                <b>Firma del postulante</b>
            </td>

            <!-- Columna Derecha: Huella (Alineado a la derecha) -->
            <td style="width: 20%; text-align: right; vertical-align: bottom;">
                <div class="huella-box"
                    style="border: 1px solid #000; width: 80px; height: 100px; display: inline-block; text-align: center; line-height: 100px; font-size: 11px;">
                    Huella digital
                </div>
            </td>
        </tr>
    </table>

</body>

</html>
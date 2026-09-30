<?php

use Modules\Asistencia\Services\RolDocumentoData;

$cab = $documento['cabecera'];
$paginas = RolDocumentoData::paginas($documento);
$numero = static fn($n) => rtrim(rtrim(number_format((float) $n, 2, '.', ''), '0'), '.');
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title><?= esc($documento['codigo']) ?></title>
    <style>
        @page {
            margin: 26px 28px 38px;
        }

        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 9px;
            color: #17212b;
        }

        .pagina {
            page-break-after: always;
        }

        .pagina:last-child {
            page-break-after: auto;
        }

        .institucion {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            margin: 0 0 6px;
        }

        h1 {
            text-align: center;
            font-size: 10px;
            margin: 0 0 10px;
        }

        .banda {
            background: #e9eef3;
            padding: 5px 8px;
            font-weight: bold;
            border: 1px solid #82909d;
        }

        .datos {
            width: 100%;
            border-collapse: collapse;
            margin: 0 0 9px;
        }

        .datos td {
            padding: 5px 7px;
            border: 1px solid #aab3bb;
        }

        .ambito {
            margin: 7px 0;
        }

        .rol-tabla {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 8px;
        }

        .rol-tabla th,
        .rol-tabla td {
            border: 0.5px solid #687786;
            padding: 4px 1px;
            text-align: center;
            vertical-align: middle;
            overflow-wrap: break-word;
        }

        .rol-tabla thead {
            display: table-header-group;
        }

        .rol-tabla th {
            background: #e2eaf2;
            font-weight: bold;
            font-size: 7px;
        }

        .rol-tabla .nombre {
            text-align: left;
            padding: 4px;
            font-size: 8px;
        }

        .rol-tabla .cargo {
            font-size: 7px;
        }

        .rol-tabla .dia {
            padding: 1px;
        }

        .rol-tabla .fin-semana {
            background: #f2f4f6;
        }

        .rol-tabla .horas {
            background: #eaf0f5;
            font-weight: bold;
        }

        .turno {
            padding: 4px 0;
            margin: 1px 0;
            font-size: 7px;
            font-weight: bold;
        }

        .resumen {
            text-align: right;
            font-weight: bold;
            margin: 8px 0;
        }

        .leyenda {
            margin-top: 10px;
            font-size: 8px;
            line-height: 1.7;
        }

        .leyenda span {
            display: inline-block;
            margin-right: 12px;
        }

        .leyenda b {
            padding: 2px 5px;
        }

        .nota {
            color: #53606c;
            font-size: 8px;
            margin-top: 7px;
        }

        .firmas {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }

        .firmas td {
            width: 33.33%;
            text-align: center;
            padding: 0 36px;
        }

        .linea {
            border-top: 1px solid #7a8792;
            padding-top: 5px;
        }

        .identidad {
            font-size: 8px;
            color: #53606c;
            margin: 8px 0 0;
        }

        tr {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    <?php foreach ($paginas as $pagina): $filas = $pagina['filas']; ?>
        <div class="pagina">
            <div class="institucion">MINISTERIO DE SALUD | <?= esc($cab['dir_nombre'] ?: 'DIRESA no registrada') ?> | <?= esc($cab['red_nombre'] ?: 'Red no registrada') ?></div>
            <h1>PROGRAMACIÓN DE TURNOS DE TRABAJO DE PERSONAL PROFESIONAL, TÉCNICO Y AUXILIAR DE LA SALUD</h1>
            <div class="banda">Datos de establecimiento</div>
            <table class="datos">
                <tr>
                    <td colspan="2"><b>ESTABLECIMIENTO:</b> <?= esc($cab['est_nombre']) ?></td>
                    <td><b>IPRESS:</b> <?= esc($cab['est_ipress'] ?: 'No registrado') ?></td>
                    <td><b>NIVEL:</b> <?= esc($cab['est_categoria'] ?: 'No registrado') ?></td>
                </tr>
                <tr>
                    <td><b>DIRESA:</b> <?= esc($cab['dir_nombre'] ?: 'No registrada') ?></td>
                    <td><b>RED:</b> <?= esc($cab['red_nombre'] ?: 'No registrada') ?></td>
                    <td colspan="2"><b>MICRORED:</b> <?= esc($cab['mic_nombre'] ?: 'No registrada') ?></td>
                </tr>
                <tr>
                    <td><b>DEPARTAMENTO:</b> <?= esc($cab['departamento']) ?></td>
                    <td colspan="2"><b>SERVICIO O ÁREA:</b> <?= esc($cab['servicio_area']) ?></td>
                    <td><b>MES/AÑO:</b> <?= esc($documento['mes_nombre']) ?>-<?= (int) $documento['filtros']['anio'] ?></td>
                </tr>
            </table>
            <div class="ambito"><b>Ámbito:</b> <?= esc($cab['ambito']) ?></div>
            <?= view('Modules\Asistencia\Views\roles\tabla', ['documento' => $documento, 'filas' => $filas, 'offset' => $pagina['offset']]) ?>
            <div class="resumen">Personal en esta hoja: <?= count($filas) ?> · Total personal del rol: <?= count($documento['personal']) ?> · Total horas del rol: <?= $numero($documento['total_horas']) ?></div>
            <div class="leyenda"><b>LEYENDA DE TURNOS:</b><br>
                <?php foreach ($documento['leyenda'] as $turno): ?>
                    <span><b style="background-color:<?= RolDocumentoData::color($turno['tur_color']) ?>;color:<?= RolDocumentoData::tinta($turno['tur_color']) ?>"><?= esc($turno['tur_codigo']) ?></b> <?= esc($turno['tur_nombre']) ?> (<?= esc($turno['th_hora_ingreso']) ?>–<?= esc($turno['th_hora_salida']) ?>; <?= $numero($turno['duracion_horas']) ?> h)</span>
                <?php endforeach ?>
            </div>
            <div class="nota">Los conteos GD, GN, M, T, N y demás códigos representan turnos asignados. Total horas suma las duraciones de los horarios, incluidos los nocturnos. Una casilla vacía indica que no hay turno programado.</div>
            <table class="firmas">
                <tr>
                    <td>
                        <div class="linea">Elaborado por</div>
                    </td>
                    <td>
                        <div class="linea">Revisado por</div>
                    </td>
                    <td>
                        <div class="linea">Aprobado por</div>
                    </td>
                </tr>
            </table>
            <div class="identidad"><?= esc($documento['codigo']) ?> · Generado: <?= esc($documento['generado_at']) ?> · Documento sin firma digital</div>
        </div>
    <?php endforeach ?>
</body>

</html>
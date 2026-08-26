<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ficha Única de Datos del Postulante</title>
    <style>
        @page {
            margin: 12mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #000;
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 3px 5px;
            vertical-align: middle;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #000;
            padding: 3px 5px;
            font-size: 8.5pt;
            text-align: left;
            vertical-align: middle;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        /* Evita que una fila se divida entre páginas */
        table.data-table tr {
            page-break-inside: avoid;
        }

        /* Evita que tablas o bloques pequeños se corten */
        .avoid-break {
            page-break-inside: avoid;
        }

        .bg-light {
            background-color: #f2f2f2;
        }

        .bg-label {
            background-color: #f2f2f2;
            font-weight: bold;
            width: 20%;
        }

        .text-center {
            text-align: center !important;
        }

        .text-end {
            text-align: right;
        }

        .text-bold,
        .fw-bold {
            font-weight: bold;
        }

        .header-title {
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 8px;
        }

        .section-title {
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 4px;
            margin-top: 8px;
        }

        .notes {
            font-size: 8.5px;
            margin-bottom: 6px;
        }

        .notes p {
            margin: 0 0 4px 0;
        }

        .firma-section {
            margin-top: 25px;
            width: 100%;
        }

        .firma-box {
            width: 230px;
            float: right;
            text-align: center;
        }

        .huella-box {
            width: 80px;
            height: 90px;
            border: 1px dashed #444;
            float: left;
            margin-left: 50px;
            text-align: center;
            font-size: 7.5pt;
            color: #777;
            padding-top: 35px;
            box-sizing: border-box;
        }

        .linea-firma {
            border-top: 1px solid #000;
            margin-bottom: 4px;
        }

        .clearfix {
            clear: both;
        }

        .declaracion {
            text-align: justify;
            font-size: 8.5px;
            margin: 0;
        }

        .empty-row {
            text-align: center;
            padding: 6px;
        }
    </style>
</head>

<body>
    <!-- ENCABEZADO -->
    <div class="header-title">
        <?= esc($convocatoria['ejecutora'] ?? 'GOBIERNO REGIONAL PUNO - DIRECCIÓN REGIONAL DE SALUD') ?><br>
        PROCESO DE SELECCIÓN DE PERSONAL - <?= esc($convocatoria['numero'] ?? '') ?><br>
        FICHA ÚNICA DE DATOS DEL POSTULANTE
    </div>
    <!-- I. DATOS PERSONALES -->
    <div class="section-title">I. DATOS PERSONALES DEL POSTULANTE</div>
    <table class="data-table">
        <tr>
            <td class="bg-label">Nombres y Apellidos:</td>
            <td colspan="3" class="text-bold">
                <?= esc($postulante['nombres_completos'] ?? '') ?>
            </td>
        </tr>
        <tr>
            <td class="bg-label">N° DNI / Documento:</td>
            <td><?= esc($postulante['documento'] ?? '') ?></td>
            <td class="bg-label">Fecha Nacimiento:</td>
            <td><?= esc($postulante['fecha_nacimiento'] ?? '') ?></td>
        </tr>
        <tr>
            <td class="bg-label">Sexo:</td>
            <td><?= esc($postulante['sexo'] ?? '') ?></td>
            <td class="bg-label">Teléfono / Celular:</td>
            <td><?= esc($postulante['telefono'] ?? '') ?></td>
        </tr>
        <tr>
            <td class="bg-label">Dirección Domiciliaria:</td>
            <td colspan="3"><?= esc($postulante['direccion'] ?? '') ?></td>
        </tr>
        <tr>
            <td class="bg-label">Correo Electrónico:</td>
            <td colspan="3"><?= esc($postulante['email'] ?? '') ?></td>
        </tr>
    </table>
    <!-- II. COLEGIATURA -->
    <?php if (!empty($colegiatura)): ?>
        <div class="section-title">II. COLEGIATURA PROFESIONAL</div>
        <table class="data-table">
            <tr>
                <td class="bg-label">Colegio Profesional:</td>
                <td><?= esc($colegiatura['colegio'] ?? '') ?></td>
                <td class="bg-label">N° Colegiatura:</td>
                <td><?= esc($colegiatura['numero'] ?? '') ?></td>
            </tr>
            <tr>
                <td class="bg-label">Condición Actual:</td>
                <td class="text-bold"><?= esc($colegiatura['estado'] ?? '') ?></td>
                <td class="bg-label">Fecha Colegiado:</td>
                <td><?= esc($colegiatura['fecha_colegiado'] ?? '') ?></td>
            </tr>
        </table>
    <?php endif; ?>
    <!-- III. FORMACIÓN ACADÉMICA -->
    <div class="section-title">III. FORMACIÓN ACADÉMICA (GRADOS Y TÍTULOS)</div>
    <table class="data-table">
        <thead>
            <tr class="bg-light text-center fw-bold">
                <th width="5%">#</th>
                <th width="18%">Nivel Académico</th>
                <th width="27%">Especialidad / Carrera / Denominación</th>
                <th width="35%">Institución</th>
                <th width="15%">Nivel Alcanzado</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($profesion)): ?>
                <?php $i = 1; ?>
                <?php foreach ($profesion as $item): ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>
                        <td><?= esc($item['ppr_grado'] ?? '') ?></td>
                        <td><?= esc($item['ppr_titulo'] ?? '') ?></td>
                        <td><?= esc($item['ppr_institucion'] ?? '') ?></td>
                        <td><?= esc($item['ppr_grado'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="empty-row">
                        No registra formación académica.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <!-- IV. CAPACITACIONES -->
    <div class="section-title">IV. CAPACITACIONES Y ACTUALIZACIÓN PROFESIONAL</div>
    <table class="data-table">
        <thead>
            <tr class="bg-light text-center fw-bold">
                <th width="5%">#</th>
                <th width="15%">Tipo</th>
                <th width="40%">Nombre del Curso / Evento / Diplomado</th>
                <th width="30%">Institución Certificadora</th>
                <th width="10%">Horas</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($capacitaciones)): ?>
                <?php $i = 1; ?>
                <?php foreach ($capacitaciones as $cap): ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>
                        <td><?= esc($cap['pca_tipo'] ?? '') ?></td>
                        <td><?= esc($cap['pca_nombre'] ?? '') ?></td>
                        <td><?= esc($cap['pca_institucion'] ?? '') ?></td>
                        <td class="text-center">
                            <?= esc($cap['pca_horas'] ?? '') ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="empty-row">
                        No registra capacitaciones.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <!-- V. EXPERIENCIA LABORAL -->
    <div class="section-title">V. EXPERIENCIA LABORAL DECLARADA</div>
    <table class="data-table">
        <thead>
            <tr class="bg-light text-center fw-bold">
                <th width="5%">#</th>
                <th width="25%">Entidad / Empresa</th>
                <th width="25%">Cargo / Función Desempeñada</th>
                <th width="15%">Inicio</th>
                <th width="15%">Fin</th>
                <th width="15%">Días</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($experiencia)): ?>
                <?php $i = 1; ?>
                <?php foreach ($experiencia as $exp): ?>
                    <?php
                    $dias = '';
                    if (
                        !empty($exp['pex_fecha_inicio']) &&
                        !empty($exp['pex_fecha_termino'])
                    ) {
                        try {
                            $inicio = new DateTime($exp['pex_fecha_inicio']);
                            $fin = new DateTime($exp['pex_fecha_termino']);
                            $dias = $inicio->diff($fin)->days + 1;
                        } catch (Exception $e) {
                            $dias = '';
                        }
                    }
                    ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>
                        <td><?= esc($exp['pex_institucion'] ?? '') ?></td>
                        <td><?= esc($exp['pex_cargo'] ?? '') ?></td>
                        <td class="text-center">
                            <?= esc($exp['pex_fecha_inicio'] ?? '') ?>
                        </td>
                        <td class="text-center">
                            <?= esc($exp['pex_fecha_termino'] ?? '') ?>
                        </td>
                        <td class="text-center"><?= $dias ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="empty-row">
                        No registra experiencia laboral.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <!-- VI. IDENTIFICACIÓN INSTITUCIONAL -->
    <div class="section-title">VI. IDENTIFICACIÓN INSTITUCIONAL Y OTROS ANEXOS</div>
    <table class="data-table">
        <thead>
            <tr class="bg-light text-center fw-bold">
                <th width="8%">#</th>
                <th width="25%">Tipo</th>
                <th width="67%">Descripción</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($identificacion)): ?>
                <?php $i = 1; ?>
                <?php foreach ($identificacion as $item): ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>
                        <td><?= esc($item['otr_tipo'] ?? '') ?></td>
                        <td><?= esc($item['otr_descripcion'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="empty-row">
                        No registra identificación institucional ni otros anexos.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <!-- DECLARACIÓN Y FIRMA -->
    <div class="avoid-break" style="margin-top: 12px;">
        <p class="declaracion">
            Declaro bajo juramento que la información proporcionada en la presente
            Ficha Única de Datos es verídica y coincide de manera exacta con el
            soporte documental original adjunto en mi expediente digital.
        </p>
        <div class="firma-section">
            <div class="firma-box">
                <div style="height: 45px;"></div>
                <div class="linea-firma"></div>
                <span class="text-bold">
                    <?= esc($postulante['nombres_completos'] ?? '') ?>
                </span>
                <br>
                DNI N° <?= esc($postulante['documento'] ?? '') ?>
                <br>
                <b>POSTULANTE</b>
            </div>
            <div class="huella-box">
                Huella Dactilar
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</body>

</html>
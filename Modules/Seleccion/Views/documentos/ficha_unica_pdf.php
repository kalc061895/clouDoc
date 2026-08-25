<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ficha Única de Datos del Postulante</title>
    <style>
        /* Estilos de Página y Margen */
        @page {
            margin: 110px 40px 70px 40px;
            /* Arriba espacio para Header, Abajo para Footer */
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
            color: #333;
            line-height: 1.3;
        }

        /* Header Fijo en todas las páginas */
        header {
            position: fixed;
            top: -90px;
            left: 0px;
            right: 0px;
            height: 75px;
            border-bottom: 2px solid #003366;
            text-align: center;
        }

        .header-title {
            font-weight: bold;
            font-size: 11pt;
            color: #003366;
            text-transform: uppercase;
        }

        .header-subtitle {
            font-size: 8pt;
            color: #555;
        }

        /* Footer Fijo con Numeración de Páginas */
        footer {
            position: fixed;
            bottom: -50px;
            left: 0px;
            right: 0px;
            height: 35px;
            border-top: 1px solid #ccc;
            font-size: 8pt;
            color: #666;
            line-height: 35px;
        }

        .footer-left {
            float: left;
        }

        .footer-right {
            float: right;
        }

        /* Magia CSS de Dompdf para la Numeración "Página X de Y" */
        .page-number:before {
            content: "Página " counter(page);
        }

        /* Estilos de Secciones y Tablas */
        .section-header {
            background-color: #003366;
            color: #ffffff;
            font-weight: bold;
            font-size: 9.5pt;
            padding: 5px 8px;
            margin-top: 15px;
            margin-bottom: 5px;
            text-transform: uppercase;
            border-radius: 2px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #b0bec5;
            padding: 5px 6px;
            font-size: 8.5pt;
            text-align: left;
            vertical-align: middle;
        }

        /* Evita que una fila individual de la tabla se rompa a la mitad entre páginas */
        table.data-table tr {
            page-break-inside: avoid;
        }

        /* Opcional: Asegura que las cabeceras de las tablas se repitan si una tabla pasa a la siguiente página */
        table.data-table thead {
            display: table-header-group;
        }

        table.data-table th {
            background-color: #eceff1;
            color: #263238;
            font-weight: bold;
        }

        .bg-label {
            background-color: #f5f5f5;
            font-weight: bold;
            width: 20%;
        }

        .text-center {
            text-align: center !important;
        }

        .text-bold {
            font-weight: bold;
        }

        /* Evitar que bloques de firma o tablas se rompan feo entre páginas */
        .avoid-break {
            page-break-inside: avoid;
        }

        /* Bloque de Firma y Huella */
        .firma-section {
            margin-top: 40px;
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
    </style>
</head>

<body>

    <!-- Encabezado Fijo -->
    <header>
        <div class="header-title">GOBIERNO REGIONAL PUNO - DIRECCIÓN REGIONAL DE SALUD</div>
        <div class="header-title"><?= esc($convocatoria['ejecutora']) ?></div>
        <div class="header-subtitle">PROCESO DE SELECCIÓN DE PERSONAL - <?= esc($convocatoria['numero']) ?></div>
        <div style="font-weight: bold; font-size: 10pt; margin-top: 4px; color: #000;">FICHA ÚNICA DE DATOS DEL POSTULANTE</div>
    </header>

    <!-- Pie de Página Fijo -->
    <footer>
        <div class="footer-left">Impreso el: <?= esc($convocatoria['fecha_imp']) ?> | DNI: <?= esc($postulante['documento']) ?></div>
        <div class="footer-right page-number"></div>
    </footer>

    <!-- CONTENIDO PRINCIPAL DEL DOCUMENTO -->

    <!-- I. DATOS PERSONALES -->
    <div class="section-header">I. Datos Personales del Postulante</div>
    <table class="data-table">
        <tr>
            <td class="bg-label">Nombres y Apellidos:</td>
            <td colspan="3" class="text-bold"><?= esc($postulante['nombres_completos']) ?></td>
        </tr>
        <tr>
            <td class="bg-label">N° DNI / Documento:</td>
            <td><?= esc($postulante['documento']) ?></td>
            <td class="bg-label">Fecha Nacimiento:</td>
            <td><?= esc($postulante['fecha_nacimiento']) ?></td>
        </tr>
        <tr>
            <td class="bg-label">Sexo:</td>
            <td><?= esc($postulante['sexo']) ?></td>
            <td class="bg-label">Teléfono / Celular:</td>
            <td><?= esc($postulante['telefono']) ?></td>
        </tr>
        <tr>
            <td class="bg-label">Dirección Domiciliaria:</td>
            <td colspan="3"><?= esc($postulante['direccion']) ?></td>
        </tr>
        <tr>
            <td class="bg-label">Correo Electrónico:</td>
            <td colspan="3"><?= esc($postulante['email']) ?></td>
        </tr>
    </table>

    <!-- II. COLEGIATURA PROFESIONAL -->
    <?php if (!empty($colegiatura)): ?>
        <div class="section-header">II. Colegiatura Profesional</div>
        <table class="data-table">
            <tr>
                <td class="bg-label">Colegio Profesional:</td>
                <td><?= esc($colegiatura['colegio']) ?></td>
                <td class="bg-label">N° Colegiatura:</td>
                <td><?= esc($colegiatura['numero']) ?></td>
            </tr>
            <tr>
                <td class="bg-label">Condición Actual:</td>
                <td class="text-bold"><?= esc($colegiatura['estado']) ?></td>
                <td class="bg-label">Fecha Colegiado:</td>
                <td><?= esc($colegiatura['fecha_colegiado']) ?></td>
            </tr>
        </table>
    <?php endif; ?>
    <!-- III. FORMACIÓN ACADÉMICA -->
    <div class="section-header">III. Formación Académica (Grados y Títulos)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nivel Académico</th>
                <th>Especialidad / Carrera / Denominación</th>
                <th>Institución Universitaria / Pedagógica</th>
                <th>Nivel Alcanzado</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($profesion)): ?>
                <?php $i = 1;
                foreach ($profesion as $item): ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>
                        <td><?= esc($item['ppr_grado'] ?? '') ?></td>
                        <td><?= esc($item['ppr_titulo']) ?></td>
                        <td><?= esc($item['ppr_institucion']) ?></td>
                        <td><?= esc($item['ppr_grado']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No registra formación registrada.</td>
                </tr>
            <?php endif; ?>
            <?php if (!empty($formacion)): ?>
                <?php $i=0; foreach ($formacion as $item): ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>
                        <td><?= esc($item['nfo_codigo']) ?></td>
                        <td><?= esc($item['pfo_carrera']) ?></td>
                        <td><?= esc($item['pfo_institucion']) ?></td>
                        <td><?= esc($item['pfo_grado']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No registra formación registrada.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <!-- IV. CURSOS Y DIPLOMADOS -->
    <div class="section-header">IV. Capacitaciones y Actualización Profesional</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Tipo</th>
                <th>Nombre del Curso / Evento / Diplomado</th>
                <th>Institución Certificadora</th>
                <th class="text-center">Horas Lectivas</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($capacitaciones)): ?>
                <?php $i = 1;
                foreach ($capacitaciones as $cap): ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>
                        <td><?= esc($cap['pca_tipo']) ?></td>
                        <td><?= esc($cap['pca_nombre']) ?></td>
                        <td><?= esc($cap['pca_institucion']) ?></td>
                        <td class="text-center"><?= esc($cap['pca_horas']) ?> hrs.</td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No registra capacitaciones.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- V. EXPERIENCIA LABORAL -->
    <div class="section-header">V. Experiencia Laboral Declarada</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;">#</th>
                <th style="width: 30%;">Entidad / Empresa</th>
                <th style="width: 36%;">Cargo / Función Desempeñada</th>
                <th style="width: 15%;" class="text-center">Inicio</th>
                <th style="width: 15%;" class="text-center">Fin</th>
                <th style="width: 15%;" class="text-center">Dias</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($experiencia)): ?>
                <?php $i = 1;
                foreach ($experiencia as $exp): ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>
                        <td><?= esc($exp['pex_institucion']) ?></td>
                        <td><?= esc($exp['pex_cargo']) ?></td>
                        <td class="text-center"><?= esc($exp['pex_fecha_inicio']) ?></td>
                        <td class="text-center"><?= esc($exp['pex_fecha_termino']) ?></td>
                        <td class="text-center">
                            <?php
                            $inicio = new DateTime($exp['pex_fecha_inicio']);
                            $fin = new DateTime($exp['pex_fecha_termino']);
                            $dias = $inicio->diff($fin)->days + 1;
                            ?>
                            <?= $dias ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No registra experiencia laboral.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <!-- VI. IDENTIFICACION INTITUCIONAL-->
    <div class="section-header">VI. Identificación Institucional y Otros Anexos</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Tipo</th>
                <th>Descripcion</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($identificacion)): ?>
                <?php $i = 1;
                foreach ($identificacion as $exp): ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>
                        <td><?= esc($exp['otr_tipo']) ?></td>
                        <td><?= esc($exp['otr_descripcion']) ?></td>
                        
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No registra identificacion laboral.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- VI. FIRMA Y DECLARACIÓN JURADA (Protegido contra cortes entre páginas) -->
    <div class="avoid-break" style="margin-top: 25px;">
        <p style="text-align: justify; font-size: 8pt; color: #444;">
            Declaro bajo juramento que la información proporcionada en la presente Ficha Única de Datos es verídica y coincide de manera exacta con el soporte documental original adjunto en mi expediente digital.
        </p>

        <div class="firma-section">


            <div class="firma-box">
                <div style="height: 50px;"></div> <!-- Espacio para la firma manual -->
                <div class="linea-firma"></div>
                <span class="text-bold"><?= esc($postulante['nombres_completos']) ?></span><br>
                DNI N° <?= esc($postulante['documento']) ?><br>
                <b>Postulante</b>
            </div>
            <div class="huella-box">
                Huella Dactilar
            </div>
        </div>
    </div>

</body>

</html>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Constancia de Inscripción</title>
    <style>
        @page {
            margin: 2.0cm 2.0cm 2.0cm 2.0cm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #2b2b2b;
            line-height: 1.5;
        }

        header {
            text-align: center;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }

        .header-title {
            font-size: 14px;
            font-weight: bold;
            color: #0b5ed7;
            text-transform: uppercase;
            margin: 0;
        }

        .header-sub {
            font-size: 10px;
            color: #555555;
            margin: 3px 0 0 0;
        }

        .title-box {
            text-align: center;
            margin: 25px 0;
        }

        .title-box h1 {
            font-size: 16px;
            color: #111;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 0 5px 0;
        }

        .code-badge {
            display: inline-block;
            background-color: #e7f1ff;
            color: #0c63e4;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 4px;
            border: 1px solid #b6d4fe;
        }

        .text-body {
            font-size: 11px;
            text-align: justify;
            margin-bottom: 20px;
        }

        .section-header {
            background-color: #f8f9fa;
            padding: 6px 10px;
            font-weight: bold;
            font-size: 10px;
            color: #0b5ed7;
            border-left: 3px solid #0b5ed7;
            margin-top: 15px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #dee2e6;
            padding: 6px 9px;
            font-size: 10px;
            text-align: left;
        }

        table.data-table th {
            background-color: #f1f3f5;
            color: #333;
            font-weight: bold;
            width: 28%;
        }

        .fw-bold {
            font-weight: bold;
        }

        .footer-note {
            margin-top: 40px;
            border-top: 1px dashed #ccc;
            padding-top: 15px;
            font-size: 9px;
            color: #666;
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- ENCABEZADO INSTITUCIONAL -->
    <header>
        <div class="header-title">COMISION DE <?=  $postulante['con_nombre'] ?>?></div>
        <div class="header-sub">CONSTANCIA OFICIAL DE REGISTRO DE POSTULACIÓN DIGITAL</div>
    </header>

    <!-- TÍTULO PRINCIPAL Y CÓDIGO -->
    <div class="title-box">
        <h1>CONSTANCIA DE INSCRIPCIÓN</h1>
        <div class="code-badge">CÓDIGO DE POSTULACIÓN: <?= esc($postulante['pto_codigo'] ?? '') ?></div>
    </div>

    <!-- PÁRRAFO DECLARATIVO -->
    <div class="text-body">
        La Comisión del Proceso de Selección hace constar que el/la postulante
        <strong><?= esc(mb_strtoupper($postulante['pos_apellido_paterno'] . ' ' . $postulante['pos_apellido_materno'] . ', ' . $postulante['pos_nombres'])) ?></strong>,
        identificado(a) con DNI/Documento N° <strong><?= esc($postulante['pos_documento']) ?></strong>,
        se ha <strong>REGISTRADO CON ÉXITO</strong> en el concurso público de méritos correspondiente a la
        <strong><?= esc($postulante['con_nombre']) ?></strong>.
    </div>

    <!-- DETALLE DE LA POSTULACIÓN -->
    <div class="section-header">1. Detalles del Registro</div>
    <table class="data-table">
        <tr>
            <th>Convocatoria:</th>
            <td class="fw-bold"><?= esc($postulante['con_nombre']) ?></td>
        </tr>
        <tr>
            <th>Cargo al que Postula:</th>
            <td><?= esc($postulante['car_denominacion'] ?? 'N/A') ?></td>
        </tr>
        <tr>
            <th>Fecha de Registro:</th>
            <td><?= date('d/m/Y', strtotime($postulante['pto_fecha_presentacion'])) ?></td>
        </tr>
        <tr>
            <th>Hora de Registro:</th>
            <td><?= date('H:i:s hrs.', strtotime($postulante['pto_fecha_presentacion'])) ?></td>
        </tr>
        <tr>
            <th>Estado de Inscripción:</th>
            <td class="fw-bold" style="color: #198754;">REGISTRADO / POSTULADO</td>
        </tr>
    </table>

    <!-- DATOS DEL POSTULANTE -->
    <div class="section-header">2. Datos del Postulante</div>
    <table class="data-table">
        <tr>
            <th>Nombres y Apellidos:</th>
            <td><?= esc($postulante['pos_apellido_paterno'] . ' ' . $postulante['pos_apellido_materno'] . ' ' . $postulante['pos_nombres']) ?></td>
        </tr>
        <tr>
            <th>N° de Documento:</th>
            <td><?= esc($postulante['pos_documento']) ?></td>
        </tr>
        <tr>
            <th>Correo Electrónico:</th>
            <td><?= esc($postulante['pos_email']) ?></td>
        </tr>
        <tr>
            <th>Teléfono / Celular:</th>
            <td><?= esc($postulante['pos_telefono'] ?? 'N/A') ?></td>
        </tr>
    </table>

    <!-- PIE DE PÁGINA Y NOTAS -->
    <div class="footer-note">
        <p>Este documento es un comprobante oficial de su inscripción digital generado automáticamente por la plataforma de selección.</p>
        <p><strong>Nota:</strong> Guarde o imprima este comprobante para posteriores etapas del concurso público.</p>
        <p style="margin-top: 10px; font-size: 8px;">Fecha de impresión: <?= date('d/m/Y H:i:s') ?></p>
    </div>

</body>

</html>
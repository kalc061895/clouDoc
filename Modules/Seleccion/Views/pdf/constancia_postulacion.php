<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 12px;
        }

        .titulo {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }

        .bloque {
            margin-top: 20px;
        }

        .qr {
            text-align: center;
            margin-top: 30px;
        }

        .footer {
            margin-top: 40px;
            font-size: 10px;
            text-align: center;
            color: #555;
        }
    </style>
</head>

<body>

    <h4 style="text-align:center;">Red Salud San Román</h4>
    <h2 style="text-align:center;">CONSTANCIA DE POSTULACIÓN</h2>
    <h3 style="text-align:center;"><?= esc($convocatoria['titulo'] )?> </h3>

    <p><strong>Postulante:</strong> <?= esc($usuario['nombres'] . ' ' . $usuario['paterno'] . ' ' . $usuario['materno']) ?></p>
    <p><strong>DNI:</strong> <?= esc($usuario['dni']) ?></p>
    <p><strong>Email:</strong> <?= esc($usuario['secret']) ?></p>
    <p><strong>Fecha:</strong> <?= date('d/m/Y', strtotime($postulacion['fecha_postulacion'])) ?></p>
    <hr>

    <h4>1. Plaza a la que postula</h4>
    <table width="100%" border="1" cellspacing="0" cellpadding="5">
        <tr>
            <td><strong>Plaza</strong></td>
            <td><?= esc($plaza['codigo_plaza']) ?></td>
        </tr>
        <tr>
            <td><strong>Cargo al que Postula</strong></td>
            <td><?= esc($plaza['cargo']) ?></td>
        </tr>
        <tr>
            <td><strong>Área</strong></td>
            <td><?= esc($plaza['establecimiento']) ?></td>
        </tr>
    </table>


    <h4>2. Formación profesional</h4>
    <table width="100%" border="1" cellspacing="0" cellpadding="5">
        <tr>
            <th>Nivel</th>
            <th>Carrera</th>
            <th>Institución</th>
        </tr>
        <?php foreach ($formaciones as $f): ?>
            <tr>
                <td><?= esc($f['nivel']) ?></td>
                <td><?= esc($f['carrera']) ?></td>
                <td><?= esc($f['institucion']) ?></td>
            </tr>
        <?php endforeach ?>
    </table>

    <h4>3. Experiencia laboral</h4>
    <table width="100%" border="1" cellspacing="0" cellpadding="5">
        <tr>
            <th>Entidad</th>
            <th>Cargo</th>
            <th>Periodo</th>
        </tr>
        <?php foreach ($experiencias as $e): ?>
            <tr>
                <td><?= esc($e['entidad']) ?></td>
                <td><?= esc($e['cargo']) ?></td>
                <td><?= $e['fecha_inicio'] ?> - <?= $e['fecha_fin'] ?? 'Actual' ?></td>
            </tr>
        <?php endforeach ?>
    </table>

    <h4>4. Información adicional</h4>
    <table width="100%" border="1" cellspacing="0" cellpadding="5">
        <tr>
            <th>Tipo</th>
            <th>Descripción</th>
        </tr>
        <?php foreach ($extras as $x): ?>
            <tr>
                <td><?= esc($x['tipo']) ?></td>
                <td><?= esc($x['descripcion']) ?></td>
            </tr>
        <?php endforeach ?>
    </table>




    <div class="footer">
        Documento generado automáticamente – Sistema de Contrataciones
    </div>

</body>

</html>
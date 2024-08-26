<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Recepción de Expediente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
        }

        .content {
            font-size: 16px;
            line-height: 1.5;
        }

        .content p {
            margin: 10px 0;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Confirmación de Envío de Expediente</h1>
        </div>
        <div class="content">
            <p>Estimado/a, <?= $entidad['nombre'] ?></p>
            <p>Hemos recibido correctamente su expediente. El cual será evaluado y derivado a la oficina correspondiente para su respuesta o conocimiento.</p>
            <p><strong>Número de Expediente:</strong> <?= $expediente['numero_expediente'] ?></p>
            <p>Adjunto encontrará la constancia de presentación de su documento.</p>
            <p>Para seguir el estado de su expediente, haga clic en el siguiente enlace:</p>
            <p><a href="<?= base_url('/buscarexpediente') . '?id=' . $expediente['numero_expediente'] ?>">Seguir Expediente</a></p>
        </div>
        <div class="footer">
            <p>Red de salud San Román</p>
            <p>Cel: 991175569 - Av. Huancane Km 2 - Juliaca</p>
            <p>Gracias por utilizar nuestro Sistema de Trámite Virtual. ClouDoc - QuillaSoftware</p>
        </div>
    </div>
</body>

</html>
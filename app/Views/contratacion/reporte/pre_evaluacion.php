<h3 style="text-align:center;">RESULTADOS DE PRE-EVALUACIÓN CURRICULAR</h3>

<?php foreach ($resultados as $plaza => $postulantes): ?>

    <h4>Plaza: <?= esc($plaza) ?></h4>

    <table border="1" width="100%" cellspacing="0" cellpadding="4">
        <thead>
            <tr>
                <th>#</th>
                <th>DNI</th>
                <th>Postulante</th>
                <th>Título</th>
                <th>Esp.</th>
                <th>Doc.</th>
                <th>Maes.</th>
                <th>Exp.</th>
                <th>Meses</th>
                <th>Cap.</th>
                <th>Estado</th>
                <th>Observación</th>
            </tr>
        </thead>
        <tbody>

            <?php $i = 1;
            foreach ($postulantes as $p): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= esc($p['dni']) ?></td>
                    <td><?= esc($p['postulante']) ?></td>
                    <td align="center"><?= $p['titulo'] ? '✔' : '-' ?></td>
                    <td align="center"><?= $p['titulo_especialidad'] ? '✔' : '-' ?></td>
                    <td align="center"><?= $p['doctorado'] ? '✔' : '-' ?></td>
                    <td align="center"><?= $p['maestria'] ? '✔' : '-' ?></td>
                    <td align="center"><?= $p['experiencia'] ? '✔' : '-' ?></td>
                    <td align="center"><?= $p['experiencia_meses'] ?></td>
                    <td align="center"><?= $p['capacitacion'] ? '✔' : '-' ?></td>
                    <td align="center"><?= $p['estado_evaluacion'] ?></td>
                    <td><?= esc($p['observacion']) ?></td>
                </tr>
            <?php endforeach ?>

        </tbody>
    </table>

    <br>

<?php endforeach ?>
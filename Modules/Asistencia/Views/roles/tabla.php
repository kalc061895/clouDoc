<?php

use Modules\Asistencia\Services\RolDocumentoData;

$filas = $filas ?? $documento['personal'];
$offset = $offset ?? 0;
$numero = static fn($n) => rtrim(rtrim(number_format((float) $n, 2, '.', ''), '0'), '.');
$letras = [1 => 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa', 'Do'];
$codigos = count($documento['codigos']);
$anchoDia = ($codigos ? 55 : 67) / $documento['dias_mes'];
$anchoConteo = 12 / max(1, $codigos);
?>
<table class="rol-tabla">
    <thead>
        <tr>
            <th rowspan="3" style="width:2%">Nº</th>
            <th rowspan="3" style="width:15%">Apellidos y nombres</th>
            <th rowspan="3" style="width:6%">DNI / Doc.</th>
            <th rowspan="3" style="width:7%">Cargo</th>
            <th colspan="<?= $documento['dias_mes'] ?>">MES Y AÑO: <?= esc($documento['mes_nombre']) ?> <?= (int) $documento['filtros']['anio'] ?></th>
            <?php if ($codigos): ?><th colspan="<?= $codigos ?>">Nº DE TURNOS</th><?php endif ?>
            <th rowspan="3" style="width:3%">TOTAL<br>HORAS</th>
        </tr>
        <tr>
            <?php foreach ($documento['dias'] as $dia): ?><th style="width:<?= $anchoDia ?>%" class="<?= $dia['es_fin_de_semana'] ? 'fin-semana' : '' ?>"><?= (int) $dia['dia'] ?></th><?php endforeach ?>
            <?php foreach ($documento['codigos'] as $codigo): ?><th rowspan="2" style="width:<?= $anchoConteo ?>%" class="rol-codigo"><?= esc($codigo) ?></th><?php endforeach ?>
        </tr>
        <tr><?php foreach ($documento['dias'] as $dia): ?><th class="<?= $dia['es_fin_de_semana'] ? 'fin-semana' : '' ?>"><?= $letras[$dia['dia_semana']] ?></th><?php endforeach ?></tr>
    </thead>
    <tbody>
        <?php foreach ($filas as $i => $persona): ?>
            <tr>
                <td><?= $offset + $i + 1 ?></td>
                <td class="nombre"><?= esc($persona['trabajador']) ?></td>
                <td><?= esc($persona['dni']) ?></td>
                <td class="nombre cargo"><?= esc($persona['cargo']) ?></td>
                <?php $omitirHasta = 0;
                foreach ($documento['dias'] as $dia):
                    $numeroDia = (int) $dia['dia'];
                    if ($numeroDia <= $omitirHasta) continue;
                    $ausencia = $persona['ausencias'][$numeroDia] ?? null;
                    if ($ausencia):
                        $omitirHasta = $numeroDia + $ausencia['dias'] - 1; ?>
                        <td class="ausencia" colspan="<?= (int) $ausencia['dias'] ?>"><?= esc($ausencia['marca'] ?? $ausencia['detalle']) ?></td>
                        <?php continue; ?>
                    <?php endif ?>
                    <td class="dia <?= $dia['es_fin_de_semana'] ? 'fin-semana' : '' ?>">
                        <?php foreach ($persona['dias'][$dia['dia']] ?? [] as $turno): ?>
                            <div class="turno" style="background-color:<?= RolDocumentoData::color($turno['tur_color']) ?>;color:<?= RolDocumentoData::tinta($turno['tur_color']) ?>"><?= esc($turno['tur_codigo']) ?></div>
                        <?php endforeach ?>
                    </td>
                <?php endforeach ?>
                <?php foreach ($documento['codigos'] as $codigo): ?><td><?= (int) ($persona['conteos'][$codigo] ?? 0) ?></td><?php endforeach ?>
                <td class="horas"><?= $numero($persona['total_horas']) ?></td>
            </tr>
            <?php if (!empty($persona['detalle_ausencias'])): ?>
                <tr><td colspan="<?= 5 + $documento['dias_mes'] + $codigos ?>" class="detalle-ausencias"><?= esc(implode(' | ', $persona['detalle_ausencias'])) ?></td></tr>
            <?php endif ?>
        <?php endforeach ?>
        <?php if (! $filas): ?><tr>
                <td colspan="<?= 5 + $documento['dias_mes'] + $codigos ?>">No hay personal para los filtros seleccionados.</td>
            </tr><?php endif ?>
    </tbody>
</table>

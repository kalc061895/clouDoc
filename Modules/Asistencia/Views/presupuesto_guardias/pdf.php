<?php use Modules\Asistencia\Services\PresupuestoGuardiasService as Presupuesto; ?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title><?= esc($reporte['codigo']) ?></title>
    <style>
        @page { margin: 28pt 30pt 40pt }
        body { font-family: "DejaVu Sans", sans-serif; color: #172b40; font-size: 8pt }
        h1 { font-size: 15pt; margin: 0 0 7pt }
        p { margin: 0 0 5pt }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; margin-top: 12pt }
        thead { display: table-header-group }
        tr { page-break-inside: avoid }
        td, th { border: .5pt solid #aebdca; padding: 4pt 3pt; overflow-wrap: break-word }
        th { background: #17324d; color: white }
        .centro { text-align: center }
        .monto { text-align: right }
        .total td { background: #e9eff5; font-weight: bold }
    </style>
</head>
<body>
    <h1>Presupuesto de guardias de roles aprobados</h1>
    <p><strong><?= esc($reporte['codigo']) ?></strong> · <?= esc($reporte['generado_at']) ?> · Usuario <?= (int) $reporte['usuario_id'] ?></p>
    <p>Período <?= esc($reporte['periodo']['mes']) ?> <?= esc($reporte['periodo']['grupo'] ?? '') ?></p>
    <?php foreach ($reporte['filtros'] as $campo => $valor): ?>
        <p><?= esc(Presupuesto::FILTROS[$campo]) ?> · <?= esc($valor) ?></p>
    <?php endforeach ?>
    <p>GDF y GNF incluyen domingos y feriados según la fecha de inicio. Los importes están en soles.</p>
    <?php if ($reporte['pendientes']): ?><p><strong>Presupuesto parcial. Filas pendientes de valorizar: <?= (int) $reporte['pendientes'] ?>.</strong></p><?php endif ?>
    <table>
        <thead><tr>
            <?php foreach (Presupuesto::COLUMNAS as $i => $columna): ?>
                <th style="width:<?= [4, 10, 24, 17, 7, 5, 5, 5, 5, 8, 10][$i] ?>%"><?= esc($columna) ?></th>
            <?php endforeach ?>
        </tr></thead>
        <tbody>
            <?php foreach ($reporte['filas'] as $i => $fila): ?>
                <tr>
                    <td class="centro"><?= $i + 1 ?></td>
                    <?php foreach (array_slice($fila['valores'], 0, 9) as $j => $valor): ?>
                        <td class="<?= $j >= 4 ? 'centro' : '' ?>"><?= esc((string) $valor) ?></td>
                    <?php endforeach ?>
                    <?php $monto = $fila['valores'][9]; ?>
                    <td class="monto"><?= $monto === null ? 'Sin tarifa' : number_format($monto / 100, 2) ?></td>
                </tr>
            <?php endforeach ?>
            <tr class="total">
                <td colspan="5"><?= $reporte['pendientes'] ? 'Total valorizado parcial' : 'Total' ?></td>
                <?php foreach (array_slice($reporte['totales'], 0, 5) as $valor): ?><td class="centro"><?= (int) $valor ?></td><?php endforeach ?>
                <td class="monto"><?= number_format($reporte['totales'][5] / 100, 2) ?></td>
            </tr>
        </tbody>
    </table>
</body>
</html>

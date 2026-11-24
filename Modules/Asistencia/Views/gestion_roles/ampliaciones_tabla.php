<?php
helper('ui');
$bandeja = $bandeja ?? false;
ob_start();
?>
<?php if (!$ampliaciones): ?>
    <p class="text-muted mb-0"><?= $bandeja ? 'No hay ampliaciones pendientes de Dirección.' : 'Todavía no hay ampliaciones para este rol.' ?></p>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-sm table-hover align-middle mb-0">
            <thead>
                <tr>
                    <?php if ($bandeja): ?><th scope="col">Período</th><th scope="col">Establecimiento y oficina</th><?php endif ?>
                    <th scope="col">Personal</th>
                    <th scope="col">Solicitada el</th>
                    <?php if (!$bandeja): ?><th scope="col">Estado</th><?php endif ?>
                    <th scope="col" class="text-end">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ampliaciones as $a): ?>
                    <tr>
                        <?php if ($bandeja): ?>
                            <td><?= sprintf('%02d/%04d', $a['mes'], $a['anio']) ?></td>
                            <td><?= esc($a['est_nombre']) ?><br><?= esc($a['ofi_nombre']) ?></td>
                        <?php endif ?>
                        <td><?= esc($a['datos']['nombre']) ?> <span class="text-muted"><?= esc($a['datos']['codigo']) ?></span></td>
                        <td><?= esc($a['solicitado_at']) ?></td>
                        <?php if (!$bandeja): ?>
                            <td><?= match ($a['estado']) {
                                'APROBADA' => ui_badge('Aprobada', 'success'),
                                'RECHAZADA' => ui_badge('Rechazada', 'danger'),
                                default => ui_badge('Pendiente de Dirección', 'warning'),
                            } ?></td>
                        <?php endif ?>
                        <td class="text-end">
                            <?= ui_button($bandeja ? 'Revisar' : 'Ver detalle', [
                                'href' => site_url('asistencia/gestion-roles/ampliaciones/' . (int) $a['id']),
                                'size' => 'sm', 'outline' => true,
                            ]) ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
<?php endif ?>
<?= view('components/card', [
    'title' => $bandeja ? 'Ampliaciones pendientes de Dirección' : 'Historial de ampliaciones',
    'bodyHtml' => ob_get_clean(), 'class' => 'mb-4',
], ['saveData' => false]) ?>

<?php
helper('ui');
$estados = ['PENDIENTE_DIRECCION' => ['Pendiente de Dirección', 'warning'], 'APROBADA' => ['Aprobada', 'success'], 'RECHAZADA' => ['Rechazada', 'danger']];
$base = 'asistencia/gestion-roles/';
$datos = $ampliacion['datos'];
?>
<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Detalle de la ampliación<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
        <div>
            <h1 class="h3 mb-1">Ampliación del rol <?= ui_badge(...$estados[$ampliacion['estado']]) ?></h1>
            <p class="text-muted mb-0"><?= esc($envio['ofi_nombre']) ?> · <?= esc($envio['est_nombre']) ?> · <?= sprintf('%02d/%04d', $envio['mes'], $envio['anio']) ?></p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <?php if ($esDireccion): ?>
                <?= ui_button('Bandeja de Dirección', ['href' => site_url($base . 'direccion'), 'outline' => true]) ?>
            <?php endif ?>
            <?= ui_button('Ampliaciones del rol', [
                'href' => site_url($base . 'envios/' . (int) $envio['id'] . '/ampliaciones'), 'variant' => 'secondary', 'outline' => true,
            ]) ?>
        </div>
    </div>
    <?php foreach (['error' => 'danger', 'success' => 'success'] as $clave => $variante): ?>
        <?php if ($mensaje = session()->getFlashdata($clave)): ?>
            <?= view('components/alert', ['variant' => $variante, 'message' => $mensaje], ['saveData' => false]) ?>
        <?php endif ?>
    <?php endforeach ?>
    <?php ob_start(); ?>
    <dl class="row mb-0">
        <dt class="col-sm-3">Trabajador</dt>
        <dd class="col-sm-9"><?= esc($datos['nombre']) ?> · <?= esc($datos['codigo']) ?></dd>
        <dt class="col-sm-3">Fecha de ingreso</dt>
        <dd class="col-sm-9"><?= esc($datos['ingreso']) ?></dd>
        <dt class="col-sm-3">Motivo de la ampliación</dt>
        <dd class="col-sm-9 text-break"><?= nl2br(esc($ampliacion['motivo'])) ?></dd>
        <dt class="col-sm-3">Solicitada por</dt>
        <dd class="col-sm-9">Usuario #<?= (int) $ampliacion['solicitado_por'] ?> · <?= esc($ampliacion['solicitado_at']) ?></dd>
        <?php if ($ampliacion['resuelto_at']): ?>
            <dt class="col-sm-3">Resuelta por</dt>
            <dd class="col-sm-9">Usuario #<?= (int) $ampliacion['resuelto_por'] ?> · <?= esc($ampliacion['resuelto_at']) ?></dd>
            <?php if ($ampliacion['comentario']): ?>
                <dt class="col-sm-3">Comentario de Dirección</dt>
                <dd class="col-sm-9 text-break"><?= nl2br(esc($ampliacion['comentario'])) ?></dd>
            <?php endif ?>
        <?php endif ?>
    </dl>
    <?= view('components/card', ['bodyHtml' => ob_get_clean(), 'class' => 'mb-4'], ['saveData' => false]) ?>
    <?php ob_start(); ?>
    <div class="table-responsive">
        <table class="table table-sm align-middle mb-0">
            <thead><tr><th scope="col">Fecha</th><th scope="col">Turno</th><th scope="col">Entrada</th><th scope="col">Salida</th></tr></thead>
            <tbody>
                <?php foreach ($datos['turnos'] as $turno): ?>
                    <tr>
                        <td><?= esc($turno['prog_fecha']) ?></td>
                        <td><?= esc($turno['tur_codigo']) ?> · <?= esc($turno['th_nombre']) ?></td>
                        <td><?= esc(substr($turno['th_hora_ingreso'], 0, 5)) ?></td>
                        <td><?= esc(substr($turno['th_hora_salida'], 0, 5)) ?><?= $turno['th_hora_salida'] <= $turno['th_hora_ingreso'] ? ' (+1 día)' : '' ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <?= view('components/card', ['title' => 'Turnos solicitados', 'bodyHtml' => ob_get_clean(), 'class' => 'mb-4'], ['saveData' => false]) ?>
    <?php if ($puedeResolver): ?>
        <?php ob_start(); $comentario = old('comentario', '', false); ?>
        <form method="post" action="<?= site_url($base . 'ampliaciones/' . (int) $ampliacion['id'] . '/resolver') ?>">
            <?= csrf_field() ?>
            <?= view('components/form_field', [
                'name' => 'comentario', 'id' => 'ampliacion-comentario', 'label' => 'Comentario', 'type' => 'textarea',
                'attributes' => ['maxlength' => 1000], 'helpText' => 'Obligatorio al rechazar la solicitud.',
                'value' => is_string($comentario) ? $comentario : '',
            ], ['saveData' => false]) ?>
            <?= ui_button('Aprobar ampliación', ['type' => 'submit', 'attrs' => ['name' => 'accion', 'value' => 'APROBAR']]) ?>
            <?= ui_button('Rechazar ampliación', ['type' => 'submit', 'variant' => 'danger', 'outline' => true, 'attrs' => ['name' => 'accion', 'value' => 'RECHAZAR']]) ?>
        </form>
        <?= view('components/card', ['title' => 'Decisión de Dirección', 'bodyHtml' => ob_get_clean()], ['saveData' => false]) ?>
    <?php endif ?>
</div>
<?= $this->endSection() ?>

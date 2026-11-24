<?php
helper('ui');
$rolesUrl = 'asistencia/gestion-roles/envios/' . (int) $envio['id'];
$valorAnterior = static fn(string $campo): string => is_string($valor = old($campo, '', false)) ? $valor : '';
$fechasAnteriores = old('fecha', [], false);
$horariosAnteriores = old('horario', [], false);
$fechasAnteriores = is_array($fechasAnteriores) ? array_slice(array_values($fechasAnteriores), 0, $maxTurnos) : [];
$horariosAnteriores = is_array($horariosAnteriores) ? array_values($horariosAnteriores) : [];
$opcionesPersonal = array_map(static fn(array $p): string => "{$p['nombre']} · {$p['codigo']} · Ingreso {$p['desde']}", $personal);
?>
<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Ampliaciones del rol de turnos<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
        <div>
            <h1 class="h3 mb-1">Ampliaciones del rol de turnos</h1>
            <p class="text-muted mb-0"><?= esc($envio['ofi_nombre']) ?> · <?= esc($envio['est_nombre']) ?> · <?= sprintf('%02d/%04d', $envio['mes'], $envio['anio']) ?></p>
        </div>
        <?= ui_button('Ver rol aprobado', ['href' => site_url($rolesUrl), 'variant' => 'secondary', 'outline' => true]) ?>
    </div>
    <?php if ($error = session()->getFlashdata('error')): ?>
        <?= view('components/alert', ['variant' => 'danger', 'message' => $error], ['saveData' => false]) ?>
    <?php endif ?>
    <?php ob_start(); ?>
    <?php if ($personal && $horarios): ?>
        <p class="text-muted">Los turnos se incorporan a la programación cuando Dirección aprueba la solicitud.</p>
        <form method="post" id="solicitar-ampliacion" action="<?= site_url($rolesUrl . '/ampliaciones') ?>">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-lg-6">
                    <?= view('components/form_field', [
                        'name' => 'personal', 'id' => 'ampliacion-personal', 'label' => 'Personal nuevo', 'type' => 'select',
                        'options' => $opcionesPersonal, 'placeholder' => 'Seleccione un trabajador',
                        'value' => $valorAnterior('personal'), 'required' => true,
                    ], ['saveData' => false]) ?>
                </div>
                <div class="col-lg-6">
                    <?= view('components/form_field', [
                        'name' => 'motivo', 'id' => 'ampliacion-motivo', 'label' => 'Motivo', 'type' => 'textarea',
                        'attributes' => ['maxlength' => 1000, 'rows' => 2], 'value' => $valorAnterior('motivo'), 'required' => true,
                    ], ['saveData' => false]) ?>
                </div>
            </div>
            <fieldset>
                <legend class="h6">Turnos solicitados</legend>
                <div id="ampliacion-turnos">
                    <?php foreach ($fechasAnteriores ?: [''] as $i => $fecha): ?>
                        <?= view('Modules\Asistencia\Views\gestion_roles\ampliacion_turno', [
                            'indice' => (string) $i, 'fecha' => is_string($fecha) ? $fecha : '',
                            'horario' => is_string($horariosAnteriores[$i] ?? null) ? $horariosAnteriores[$i] : '',
                            'horarios' => $horarios, 'desde' => $desde, 'hasta' => $hasta,
                        ], ['saveData' => false]) ?>
                    <?php endforeach ?>
                </div>
                <template id="ampliacion-turno-template">
                    <?= view('Modules\Asistencia\Views\gestion_roles\ampliacion_turno', [
                        'indice' => 'nuevo', 'fecha' => '', 'horario' => '', 'horarios' => $horarios, 'desde' => $desde, 'hasta' => $hasta,
                    ], ['saveData' => false]) ?>
                </template>
            </fieldset>
            <div class="d-flex flex-wrap gap-2">
                <?= ui_button('Añadir turno', ['outline' => true, 'attrs' => ['id' => 'ampliacion-agregar-turno']]) ?>
                <?= ui_button('Enviar a Dirección', ['type' => 'submit']) ?>
            </div>
        </form>
    <?php elseif ($puedeSolicitar): ?>
        <p class="text-muted mb-0"><?= $personal ? 'No hay horarios activos para programar.' : 'No hay personal nuevo elegible en esta oficina para el mes del rol.' ?></p>
    <?php endif ?>
    <?php $formulario = ob_get_clean(); ?>
    <?php if ($puedeSolicitar): ?>
        <?= view('components/card', ['title' => 'Agregar personal por ampliación', 'bodyHtml' => $formulario, 'class' => 'mb-4'], ['saveData' => false]) ?>
    <?php endif ?>
    <?= view('Modules\Asistencia\Views\gestion_roles\ampliaciones_tabla', ['ampliaciones' => $ampliaciones, 'bandeja' => false], ['saveData' => false]) ?>
</div>
<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
<?php if ($personal && $horarios): ?>
    <script>
        (() => {
            const formulario = document.getElementById('solicitar-ampliacion');
            const personal = document.getElementById('ampliacion-personal');
            const turnos = document.getElementById('ampliacion-turnos');
            const plantilla = document.getElementById('ampliacion-turno-template');
            const agregar = document.getElementById('ampliacion-agregar-turno');
            const rangos = <?= json_encode(array_map(static fn(array $p): array => [$p['desde'], $p['hasta']], $personal), JSON_THROW_ON_ERROR) ?>;
            const rangoMes = <?= json_encode([$desde, $hasta], JSON_THROW_ON_ERROR) ?>;
            const maxTurnos = <?= (int) $maxTurnos ?>;
            let siguiente = turnos.children.length;

            function actualizarTurnos() {
                const [desde, hasta] = rangos[personal.value] ?? rangoMes;
                for (const fecha of turnos.querySelectorAll('input[type="date"]')) {
                    fecha.min = desde;
                    fecha.max = hasta;
                }
                for (const boton of turnos.querySelectorAll('[data-quitar-turno]')) {
                    boton.disabled = turnos.children.length === 1;
                }
                agregar.disabled = turnos.children.length >= maxTurnos;
            }

            personal.addEventListener('change', actualizarTurnos);
            agregar.addEventListener('click', () => {
                const fila = plantilla.content.firstElementChild.cloneNode(true);
                const indice = siguiente++;
                for (const campo of fila.querySelectorAll('[id]')) {
                    const id = campo.id;
                    campo.id = id.replace('nuevo', indice);
                    fila.querySelector('label[for="' + id + '"]').htmlFor = campo.id;
                }
                turnos.append(fila);
                actualizarTurnos();
                fila.querySelector('input').focus();
            });
            turnos.addEventListener('click', (evento) => {
                const boton = evento.target.closest('[data-quitar-turno]');
                if (!boton || turnos.children.length === 1) return;
                boton.closest('[data-turno]').remove();
                actualizarTurnos();
                agregar.focus();
            });
            formulario.addEventListener('submit', () => {
                formulario.querySelector('button[type="submit"]').disabled = true;
            });
            actualizarTurnos();
        })();
    </script>
<?php endif ?>
<?= $this->endSection() ?>

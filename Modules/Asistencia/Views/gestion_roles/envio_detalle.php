<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Revisión de envío de rol<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h1 class="h3 mb-1"><iconify-icon icon="solar:document-text-bold-duotone" class="text-primary me-2"></iconify-icon>Revisión del rol</h1>
            <p class="text-muted mb-0">Versión <?= (int) $envio['version'] ?> · <?= sprintf('%02d/%04d', (int) $envio['mes'], (int) $envio['anio']) ?> · Estado: <span class="badge text-bg-primary"><?= esc(str_replace('_', ' ', $envio['estado'])) ?></span></p>
        </div>
        <a class="btn btn-outline-secondary" href="<?= site_url('asistencia/gestion-roles/envios') ?>"><iconify-icon icon="lucide:arrow-left" class="me-1"></iconify-icon>Volver a envíos</a>
    </div>
    <?= view('Modules\Asistencia\Views\gestion_roles\navegacion', ['admin' => $admin ?? false]) ?>
    <?php foreach (['error' => 'error', 'success' => 'success'] as $key => $toast): if ($mensaje = session()->getFlashdata($key)): ?><script>
                document.addEventListener('DOMContentLoaded', () => toastr.<?= $toast ?>(<?= json_encode($mensaje, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>));
            </script><?php endif;
                endforeach ?>

    <div class="card card-body mb-4">
        <dl class="row mb-0">
            <dt class="col-sm-3">Oficina</dt>
            <dd class="col-sm-9"><?= esc($envio['ofi_nombre']) ?></dd>
            <dt class="col-sm-3">Establecimiento</dt>
            <dd class="col-sm-9"><?= esc($envio['est_nombre']) ?></dd>
            <dt class="col-sm-3">Enviado por</dt>
            <dd class="col-sm-9">Usuario #<?= (int) $envio['usuario_envio_id'] ?> · <?= esc($envio['enviado_at']) ?></dd>
            <dt class="col-sm-3">Integridad del snapshot</dt>
            <dd class="col-sm-9"><code class="text-break"><?= esc($envio['hash_sha256']) ?></code></dd>
            <?php if ($envio['rrhh_at']): ?><dt class="col-sm-3">Revisión RR. HH.</dt>
                <dd class="col-sm-9">Usuario #<?= (int) $envio['usuario_rrhh_id'] ?> · <?= esc($envio['rrhh_at']) ?><?= $envio['comentario_rrhh'] ? '<br>' . esc($envio['comentario_rrhh']) : '' ?></dd><?php endif ?>
            <?php if ($envio['direccion_at']): ?><dt class="col-sm-3">Decisión Dirección</dt>
                <dd class="col-sm-9">Usuario #<?= (int) $envio['usuario_direccion_id'] ?> · <?= esc($envio['direccion_at']) ?><?= $envio['comentario_direccion'] ? '<br>' . esc($envio['comentario_direccion']) : '' ?></dd><?php endif ?>
        </dl>
    </div>

    <?php
    $anioRol = (int) $envio['anio'];
    $mesRol = (int) $envio['mes'];
    $diasMesRol = (int) (new DateTimeImmutable(sprintf('%04d-%02d-01', $anioRol, $mesRol)))->format('t');
    $miembrosRol = $envio['snapshot'];
    $jsonAnterior = old('observaciones', '{}', false);
    $observacionesAnteriores = is_string($jsonAnterior) ? json_decode($jsonAnterior, true) : [];
    $comentarioAnterior = old('comentario', '', false);
    $comentarioAnterior = is_string($comentarioAnterior) ? $comentarioAnterior : '';
    $conAlertas = 0;
    foreach ($calendarios as $id => $calendario) {
        if ($calendario['alertas'] || $alertas[$id]) $conAlertas++;
    }
    ?>
    <h2 class="h5">Programaci&#243;n capturada</h2>
    <p class="small text-muted">Personal incluido: <?= count($miembrosRol) ?>. Los datos corresponden a esta versi&#243;n enviada.</p>
    <?php if ($ausenciasPorConsultar): ?>
        <p class="small text-muted">Para esta versión, las ausencias se consultan en los registros actuales.</p>
    <?php endif ?>
    <?php if ($conAlertas): ?>
        <?= view('components/alert', ['variant' => 'warning', 'message' => "Personal con advertencias: {$conAlertas}. Revise el detalle en la última columna."]) ?>
    <?php endif ?>
    <div class="row g-3 mb-3">
        <?php foreach ([
            'regimen' => ['Régimen contractual', $regimenes],
            'modalidad' => ['Tipo de personal', ['ASISTENCIAL' => 'Asistencial', 'ADMINISTRATIVO' => 'Administrativo']],
        ] as $campo => [$label, $opciones]): ?>
            <div class="col-md-4">
                <?= view('components/form_field', [
                    'id' => 'filtrar-' . $campo, 'name' => $campo, 'label' => $label, 'type' => 'select',
                    'placeholder' => 'Todos', 'options' => $opciones, 'attributes' => ['disabled' => true],
                ]) ?>
            </div>
        <?php endforeach ?>
    </div>
    <div class="table-responsive border rounded mb-4 p-2">
        <table id="tablaRevisionRol" class="table table-sm table-bordered table-hover align-middle mb-0 text-center rol-revision-tabla">
            <thead class="table-light">
                <tr>
                    <th scope="col" class="rol-fijo">N.</th>
                    <th scope="col" class="rol-fijo rol-documento">N.&#186; documento</th>
                    <th scope="col" class="rol-fijo rol-personal">Apellidos y nombres</th>
                    <th scope="col" class="rol-fijo rol-cargo">Cargo</th>
                    <?php for ($dia = 1; $dia <= $diasMesRol; $dia++): ?><th scope="col" class="rol-dia"><?= $dia ?></th><?php endfor ?>
                    <th scope="col" class="rol-total">Turnos</th>
                    <th scope="col" class="rol-total">Horas</th>
                    <th scope="col" class="rol-observaciones">Advertencias y observaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($miembrosRol as $indice => $miembro):
                    $persona = $miembro['personal'] ?? [];
                    $personalId = (int) ($persona['perl_ide'] ?? 0);
                    $calendario = $calendarios[$personalId];
                    $observacionAnterior = $observacionesAnteriores[$personalId] ?? '';
                    $turnosPorDia = [];
                    $totalTurnos = 0;
                    $totalHoras = 0.0;
                    foreach (($miembro['turnos'] ?? []) as $turno) {
                        $fechaTurno = $turno['prog_fecha'] ?? '';
                        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaTurno) || substr($fechaTurno, 0, 7) !== sprintf('%04d-%02d', $anioRol, $mesRol)) continue;
                        $diaTurno = (int) substr($fechaTurno, 8, 2);
                        if ($diaTurno < 1 || $diaTurno > $diasMesRol) continue;
                        $inicio = new DateTimeImmutable($fechaTurno . ' ' . ($turno['th_hora_ingreso'] ?? '00:00:00'));
                        $fin = new DateTimeImmutable($fechaTurno . ' ' . ($turno['th_hora_salida'] ?? '00:00:00'));
                        if ($fin <= $inicio) $fin = $fin->modify('+1 day');
                        $horas = ($fin->getTimestamp() - $inicio->getTimestamp()) / 3600;
                        $turnosPorDia[$diaTurno][] = ['codigo' => $turno['tur_codigo'] ?? '', 'inicio' => substr((string) ($turno['th_hora_ingreso'] ?? ''), 0, 5), 'fin' => substr((string) ($turno['th_hora_salida'] ?? ''), 0, 5), 'horas' => $horas];
                        $totalTurnos++;
                        $totalHoras += $horas;
                    }
                ?>
                    <tr data-regimen="<?= (int) ($persona['perl_mco_ide'] ?? 0) ?>" data-modalidad="<?= esc(strtoupper((string) ($persona['car_modalidad'] ?? '')), 'attr') ?>">
                        <th scope="row"><?= $indice + 1 ?></th>
                        <td class="text-start font-monospace"><?= esc($persona['documento_snapshot'] ?? '-') ?></td>
                        <td class="text-start fw-semibold"><?= esc($persona['nombre_snapshot'] ?? 'Personal') ?></td>
                        <td class="text-start"><?= esc($persona['cargo_snapshot'] ?? 'Sin cargo') ?></td>
                        <?php for ($dia = 1; $dia <= $diasMesRol; $dia++): ?>
                            <?php
                            $ausenciasDia = $calendario['dias'][$dia] ?? [];
                            $hayTurno = !empty($turnosPorDia[$dia]);
                            $tramo = $calendario['tramos'][$dia] ?? null;
                            $claseAusencia = $ausenciasDia ? ($hayTurno ? 'table-warning' : 'table-success') : '';
                            ?>
                            <td class="rol-dia <?= $claseAusencia ?>" data-dias-ausencia="<?= (int) ($tramo['dias'] ?? 1) ?>">
                                <?php foreach ($turnosPorDia[$dia] ?? [] as $turnoDia): ?>
                                    <span class="badge text-bg-primary mb-1" title="<?= esc($turnoDia['inicio'] . ' - ' . $turnoDia['fin'] . ' (' . number_format($turnoDia['horas'], 2) . ' h)', 'attr') ?>"><?= esc($turnoDia['codigo'] ?: 'Turno') ?></span>
                                <?php endforeach ?>
                                <?php if ($ausenciasDia): ?>
                                    <div class="small rol-ausencia"><?= esc(implode(' / ', $ausenciasDia)) ?></div>
                                <?php elseif (!$hayTurno): ?><span class="text-muted">-</span><?php endif ?>
                            </td>
                        <?php endfor ?>
                        <td class="fw-semibold"><?= $totalTurnos ?></td>
                        <td class="fw-semibold text-nowrap"><?= number_format($totalHoras, 2) ?> h</td>
                        <td class="rol-observaciones text-start text-break">
                            <?php foreach ($calendario['alertas'] as $alerta): ?>
                                <div class="small text-dark bg-warning-subtle rounded p-1 mb-1"><?= esc($alerta) ?></div>
                            <?php endforeach ?>
                            <?php foreach ($alertas[$personalId] ?? [] as $alerta): ?>
                                <div class="small text-dark bg-warning-subtle rounded p-1 mb-1"><?= esc($alerta) ?></div>
                            <?php endforeach ?>
                            <?php foreach (['RR. HH.' => $observacionesRRHH, 'Dirección' => $observacionesDireccion] as $etapa => $notas): ?>
                                <?php if (isset($notas[$personalId])): ?>
                                    <div class="small mb-1"><strong><?= esc($etapa) ?></strong><br><?= nl2br(esc($notas[$personalId])) ?></div>
                                <?php endif ?>
                            <?php endforeach ?>
                            <?php if ($puedeRRHH || $puedeDireccion): ?>
                                <textarea class="form-control form-control-sm" data-observacion-personal="<?= $personalId ?>" rows="2" maxlength="1000"
                                    aria-label="<?= esc('Observación para ' . ($persona['nombre_snapshot'] ?? 'este trabajador'), 'attr') ?>"
                                    placeholder="Agregar observación"><?= esc(is_string($observacionAnterior) ? $observacionAnterior : '') ?></textarea>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <style>
        .rol-revision-tabla {
            min-width: max-content;
        }

        .rol-revision-tabla th,
        .rol-revision-tabla td {
            min-width: 58px;
        }

        .rol-revision-tabla .rol-fijo {
            min-width: 48px;
        }

        .rol-revision-tabla .rol-documento {
            min-width: 115px;
        }

        .rol-revision-tabla .rol-personal {
            min-width: 230px;
        }

        .rol-revision-tabla .rol-cargo {
            min-width: 180px;
        }

        .rol-revision-tabla .rol-dia {
            min-width: 58px;
        }

        .rol-revision-tabla .rol-total {
            min-width: 75px;
        }

        .rol-revision-tabla .rol-ausencia {
            max-width: 300px;
            margin-inline: auto;
            white-space: normal;
            overflow-wrap: anywhere;
        }

        .rol-revision-tabla .rol-observaciones {
            min-width: 260px;
            max-width: 320px;
            white-space: normal;
        }
    </style>
    <?php if ($puedeRRHH): ?>
        <form method="post" action="<?= site_url('asistencia/gestion-roles/envios/' . (int) $envio['id'] . '/rrhh') ?>" class="card card-body mb-3 js-confirmar-revision">
            <?= csrf_field() ?><h2 class="h5">Revisión de Recursos Humanos</h2>
            <label for="comentario-rrhh" class="form-label">Comentario <span class="text-muted">(obligatorio para observar o rechazar)</span></label>
            <textarea id="comentario-rrhh" name="comentario" rows="3" maxlength="2000" class="form-control mb-3"><?= esc($comentarioAnterior) ?></textarea>
            <input type="hidden" name="observaciones" value="{}">
            <div class="d-flex flex-wrap gap-2"><button name="accion" value="VISTO_BUENO" class="btn btn-success"><iconify-icon icon="lucide:check" class="me-1"></iconify-icon>Dar visto bueno</button><button name="accion" value="OBSERVAR" class="btn btn-warning"><iconify-icon icon="lucide:message-square-warning" class="me-1"></iconify-icon>Observar y devolver</button></div>
        </form>
    <?php endif ?>
    <?php if ($puedeDireccion): ?>
        <form method="post" action="<?= site_url('asistencia/gestion-roles/envios/' . (int) $envio['id'] . '/direccion') ?>" class="card card-body mb-3 js-confirmar-revision">
            <?= csrf_field() ?><h2 class="h5">Aprobación de Dirección</h2>
            <label for="comentario-direccion" class="form-label">Comentario <span class="text-muted">(obligatorio al devolver o rechazar)</span></label>
            <textarea id="comentario-direccion" name="comentario" rows="3" maxlength="2000" class="form-control mb-3"><?= esc($comentarioAnterior) ?></textarea>
            <input type="hidden" name="observaciones" value="{}">
            <div class="d-flex flex-wrap gap-2"><button name="accion" value="APROBAR" class="btn btn-success"><iconify-icon icon="lucide:check-check" class="me-1"></iconify-icon>Aprobar rol</button><button name="accion" value="DEVOLVER" class="btn btn-warning"><iconify-icon icon="lucide:corner-up-left" class="me-1"></iconify-icon>Devolver para corregir</button><button name="accion" value="RECHAZAR" class="btn btn-outline-danger"><iconify-icon icon="lucide:x" class="me-1"></iconify-icon>Rechazar definitivamente</button></div>
        </form>
    <?php endif ?>
    <?php if ($envio['estado'] === 'APROBADO_DIRECCION'): ?>
        <div class="card card-body border-success mb-3">
            <h2 class="h5">Rol aprobado</h2>
            <p>La programación operativa coincide con el snapshot aprobado. Puede generar el PDF oficial para este envío.</p>
            <form method="post" action="<?= site_url('asistencia/gestion-roles/envios/' . (int) $envio['id'] . '/pdf') ?>" class="js-confirmar-pdf">
                <?= csrf_field() ?><button class="btn btn-success"><iconify-icon icon="lucide:file-down" class="me-1"></iconify-icon>Generar / abrir PDF oficial</button>
            </form>
            <?php if ($firmaDocumentoId): ?>
                <hr>
                <h3 class="h6">Firmas digitales</h3>
                <ol class="mb-3">
                    <li>Elaborador: <?= $envio['firma_elaborador_at'] ? esc($envio['firma_elaborador_at']) : 'Pendiente' ?></li>
                    <li>Recursos Humanos: <?= $envio['firma_rrhh_at'] ? esc($envio['firma_rrhh_at']) : 'Pendiente' ?></li>
                    <li>Dirección: <?= $envio['firma_direccion_at'] ? esc($envio['firma_direccion_at']) : 'Pendiente' ?></li>
                </ol>
                <?php if ($envio['firma_completada_at']): ?><div class="alert alert-success mb-2">Las tres firmas fueron recibidas: <?= esc($envio['firma_completada_at']) ?></div>
                <?php elseif ($puedeFirmar): ?><a class="btn btn-outline-primary" href="<?= site_url('firma') . '?rol_id=' . (int) $firmaDocumentoId ?>">Firmar ahora en FirmaPerú</a>
                <?php else: ?><p class="small text-muted mb-0">El siguiente actor podrá firmar después de completarse la etapa anterior.</p><?php endif ?>
            <?php endif ?>
        </div>
    <?php endif ?>
</div>
<?= $this->section('pageScripts') ?><script>
    const tablaRevision = document.getElementById('tablaRevisionRol');
    const camposObservacion = tablaRevision.querySelectorAll('[data-observacion-personal]');
    const celdasCalendario = tablaRevision.querySelectorAll('[data-dias-ausencia]');
    if (window.jQuery?.fn.DataTable) {
        const regimen = document.getElementById('filtrar-regimen');
        const modalidad = document.getElementById('filtrar-modalidad');
        $.fn.dataTable.ext.search.push((settings, data, index) => {
            if (settings.nTable !== tablaRevision) return true;
            const fila = settings.aoData[index].nTr;
            return (!regimen.value || fila.dataset.regimen === regimen.value)
                && (!modalidad.value || fila.dataset.modalidad === modalidad.value);
        });
        const tabla = $(tablaRevision).DataTable({
            order: [], pageLength: 25, autoWidth: false,
            language: {
                emptyTable: 'No hay personal en el envío', zeroRecords: 'No se encontró personal',
                info: 'Mostrando _START_ a _END_ de _TOTAL_ trabajadores', infoEmpty: 'Mostrando 0 trabajadores',
                infoFiltered: '(de _MAX_ trabajadores)', lengthMenu: 'Mostrar _MENU_ trabajadores', search: 'Buscar:',
                paginate: { next: 'Siguiente', previous: 'Anterior' }
            },
            columnDefs: [{ targets: -1, orderable: false }]
        });
        [regimen, modalidad].forEach(filtro => {
            filtro.disabled = false;
            filtro.addEventListener('change', () => tabla.draw());
        });
    }
    // DataTables lee una celda por día; la agrupación visual se aplica después.
    celdasCalendario.forEach(celda => {
        const dias = Number(celda.dataset.diasAusencia);
        if (dias <= 1) return;
        celda.colSpan = dias;
        let cubierta = celda.nextElementSibling;
        for (let i = 1; i < dias; i++) {
            cubierta.hidden = true;
            cubierta = cubierta.nextElementSibling;
        }
    });
    document.querySelectorAll('.js-confirmar-revision').forEach(form => form.addEventListener('submit', async event => {
        event.preventDefault();
        const accion = event.submitter?.value || '';
        const comentario = form.querySelector('textarea[name="comentario"]');
        const requiere = accion !== 'VISTO_BUENO' && accion !== 'APROBAR';
        if (requiere && !comentario.value.trim()) {
            comentario.classList.add('is-invalid');
            comentario.focus();
            toastr.warning('Ingrese un comentario para devolver u observar este rol.');
            return;
        }
        comentario.classList.remove('is-invalid');
        const textos = {
            VISTO_BUENO: ['¿Dar el visto bueno?', 'El rol pasará a la bandeja de Dirección.'],
            APROBAR: ['¿Aprobar el rol?', 'Se verificará que la programación coincida con el snapshot aprobado.'],
            OBSERVAR: ['¿Observar y devolver el rol?', 'La oficina podrá corregir la programación y enviarla nuevamente.'],
            DEVOLVER: ['¿Devolver el rol para corregir?', 'La oficina podrá corregir la programación y enviarla nuevamente.'],
            RECHAZAR: ['¿Rechazar el rol?', 'Esta decisión queda registrada en el historial.']
        };
        const [title, text] = textos[accion] || ['¿Confirmar decisión?', ''];
        const result = await Swal.fire({
            title,
            text,
            icon: requiere ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: accion === 'RECHAZAR' ? '#d33' : undefined
        });
        if (result.isConfirmed) {
            const observaciones = {};
            camposObservacion.forEach(campo => {
                if (campo.value.trim()) observaciones[campo.dataset.observacionPersonal] = campo.value;
            });
            form.elements.observaciones.value = JSON.stringify(observaciones);
            const actionInput = document.createElement("input");
            actionInput.type = "hidden";
            actionInput.name = "accion";
            actionInput.value = accion;
            form.append(actionInput);
            form.submit();
        }
    }));
    document.querySelectorAll('.js-confirmar-pdf').forEach(form => form.addEventListener('submit', async event => {
        event.preventDefault();
        const result = await Swal.fire({
            title: '¿Abrir PDF oficial?',
            text: 'El documento se generará desde el snapshot aprobado.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Generar PDF',
            cancelButtonText: 'Cancelar'
        });
        if (result.isConfirmed) {
            // Abrir la pestaña solo después de confirmar. Abrirla antes deja
            // una pestaña about:blank visible mientras el usuario decide.
            const target = 'rol-pdf-' + Date.now();
            const popup = window.open('about:blank', target);
            if (!popup) {
                toastr.warning('Permita las ventanas emergentes para abrir el PDF oficial.');
                return;
            }
            form.target = target;
            form.submit();
        }
    }));
</script><?= $this->endSection() ?>
<?= $this->endSection() ?>

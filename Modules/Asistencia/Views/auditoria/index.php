<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Auditoría de Asistencia<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div><h1 class="h3 mb-1">Auditoría de Asistencia</h1><p class="text-muted mb-0">Consulta global de accesos y eventos registrados por el sistema.</p></div>
        <span class="badge bg-light-primary text-primary">Solo lectura · últimos 200 registros por sección</span>
    </div>

    <div class="card mb-3"><div class="card-header fw-semibold">Accesos al sistema</div><div class="table-responsive"><table class="table table-sm table-striped table-hover mb-0"><thead><tr><th>Fecha</th><th>Usuario</th><th>Identificador</th><th>IP</th><th>Resultado</th><th>Agente</th></tr></thead><tbody>
        <?php foreach ($accesos as $r): ?><tr><td><?= esc($r['date']) ?></td><td><?= esc($r['username'] ?? '—') ?></td><td><?= esc($r['identifier']) ?></td><td><?= esc($r['ip_address']) ?></td><td><span class="badge <?= (int)$r['success'] === 1 ? 'bg-success' : 'bg-danger' ?>"><?= (int)$r['success'] === 1 ? 'Correcto' : 'Fallido' ?></span></td><td class="text-truncate" style="max-width:240px" title="<?= esc($r['user_agent'] ?? '') ?>"><?= esc($r['user_agent'] ?? '—') ?></td></tr><?php endforeach ?>
        <?php if (!$accesos): ?><tr><td colspan="6" class="text-center text-muted py-3">Sin accesos registrados.</td></tr><?php endif ?>
    </tbody></table></div></div>

    <div class="card mb-3"><div class="card-header fw-semibold">Cambios en perfiles y accesos</div><div class="table-responsive"><table class="table table-sm table-striped mb-0"><thead><tr><th>Fecha</th><th>Acción</th><th>Tipo</th><th>ID</th><th>Responsable</th><th>Detalle</th><th>IP</th></tr></thead><tbody>
        <?php foreach ($perfiles as $r): ?><tr><td><?= esc($r['created_at']) ?></td><td><?= esc($r['accion']) ?></td><td><?= esc($r['entidad_tipo']) ?></td><td><?= esc($r['entidad_id'] ?? '—') ?></td><td><?= esc($r['username'] ?? '—') ?></td><td><?= esc($r['detalle'] ?? '—') ?></td><td><?= esc($r['ip_address'] ?? '—') ?></td></tr><?php endforeach ?>
        <?php if (!$perfiles): ?><tr><td colspan="7" class="text-center text-muted py-3">Sin cambios de perfiles registrados.</td></tr><?php endif ?>
    </tbody></table></div></div>

    <div class="card mb-3"><div class="card-header fw-semibold">Cambios de periodos</div><div class="table-responsive"><table class="table table-sm table-striped mb-0"><thead><tr><th>Fecha</th><th>Periodo</th><th>Acción</th><th>Estado anterior</th><th>Estado nuevo</th><th>Responsable</th><th>Motivo</th><th>IP</th></tr></thead><tbody>
        <?php foreach ($periodos as $r): ?><tr><td><?= esc($r['created_at']) ?></td><td><?= esc($r['per_nombre'] ?? ('#'.$r['per_ide'])) ?></td><td><?= esc($r['log_accion']) ?></td><td><?= esc($r['estado_anterior'] ?? '—') ?></td><td><?= esc($r['estado_nuevo']) ?></td><td><?= esc($r['username'] ?? ('Usuario '.$r['created_by'])) ?></td><td><?= esc($r['log_motivo'] ?? '—') ?></td><td><?= esc($r['log_ip_address'] ?? '—') ?></td></tr><?php endforeach ?>
        <?php if (!$periodos): ?><tr><td colspan="8" class="text-center text-muted py-3">Sin cambios registrados.</td></tr><?php endif ?>
    </tbody></table></div></div>

    <div class="card mb-3"><div class="card-header fw-semibold">Cambios de personal</div><div class="table-responsive"><table class="table table-sm table-striped mb-0"><thead><tr><th>Fecha</th><th>Acción</th><th>ID personal</th><th>Código</th><th>Establecimiento ID</th><th>Responsable</th></tr></thead><tbody>
        <?php foreach ($personal as $r): ?><tr><td><?= esc($r['hist_creado_en']) ?></td><td><?= esc($r['hist_accion']) ?></td><td><?= (int)$r['perl_ide'] ?></td><td><?= esc($r['perl_codigo'] ?? '—') ?></td><td><?= (int)$r['perl_est_ide'] ?></td><td><?= esc($r['username'] ?? ('Usuario '.$r['hist_hecho_por'])) ?></td></tr><?php endforeach ?>
        <?php if (!$personal): ?><tr><td colspan="6" class="text-center text-muted py-3">Sin cambios de personal registrados.</td></tr><?php endif ?>
    </tbody></table></div></div>

    <div class="card mb-3"><div class="card-header fw-semibold">Marcaciones manuales</div><div class="table-responsive"><table class="table table-sm table-striped mb-0"><thead><tr><th>Fecha del evento</th><th>Fecha marcación</th><th>Documento</th><th>Tipo</th><th>Motivo</th><th>Responsable</th><th>IP</th></tr></thead><tbody>
        <?php foreach ($marcaciones as $r): ?><tr><td><?= esc($r['created_at']) ?></td><td><?= esc($r['asi_fecha_hora']) ?></td><td><?= esc($r['asi_numero_documento'] ?? '—') ?></td><td><?= esc($r['asi_tipo'] ?? '—') ?></td><td><?= esc($r['asi_motivo'] ?? '—') ?></td><td><?= esc($r['username'] ?? '—') ?></td><td><?= esc($r['asi_ip_log'] ?? '—') ?></td></tr><?php endforeach ?>
        <?php if (!$marcaciones): ?><tr><td colspan="7" class="text-center text-muted py-3">Sin marcaciones manuales registradas.</td></tr><?php endif ?>
    </tbody></table></div></div>

    <div class="row g-3"><div class="col-xl-6"><div class="card h-100"><div class="card-header fw-semibold">Eventos de programación de roles</div><div class="table-responsive"><table class="table table-sm table-striped mb-0"><thead><tr><th>Fecha</th><th>Rol</th><th>Acción</th><th>Responsable</th><th>Detalle</th></tr></thead><tbody>
        <?php foreach ($roles as $r): ?><tr><td><?= esc($r['created_at']) ?></td><td><?= esc($r['rol_id'] ?? '—') ?></td><td><?= esc($r['accion']) ?></td><td><?= esc($r['username'] ?? '—') ?></td><td><?= esc($r['detalle']) ?></td></tr><?php endforeach ?>
        <?php if (!$roles): ?><tr><td colspan="5" class="text-center text-muted py-3">Sin eventos registrados.</td></tr><?php endif ?>
    </tbody></table></div></div></div>
    <div class="col-xl-6"><div class="card h-100"><div class="card-header fw-semibold">Eventos de calificación mensual</div><div class="table-responsive"><table class="table table-sm table-striped mb-0"><thead><tr><th>Fecha</th><th>Calificación</th><th>Acción</th><th>Responsable</th><th>Detalle</th></tr></thead><tbody>
        <?php foreach ($calificaciones as $r): ?><tr><td><?= esc($r['created_at']) ?></td><td><?= (int)$r['calificacion_id'] ?></td><td><?= esc($r['accion']) ?></td><td><?= esc($r['username'] ?? '—') ?></td><td><?= esc($r['detalle']) ?></td></tr><?php endforeach ?>
        <?php if (!$calificaciones): ?><tr><td colspan="5" class="text-center text-muted py-3">Sin eventos registrados.</td></tr><?php endif ?>
    </tbody></table></div></div></div></div>
</div>
<?= $this->endSection() ?>

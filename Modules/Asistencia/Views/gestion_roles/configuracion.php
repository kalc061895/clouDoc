<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?><?= $tipo === 'periodos' ? 'Períodos de roles' : 'Plantillas de reglas' ?><?= $this->endSection() ?>
<?= $this->section('content') ?>
<?php
$esPeriodo = $tipo === 'periodos';
$titulo = $esPeriodo ? 'Períodos de roles' : 'Plantillas de reglas';
$base = site_url('asistencia/gestion-roles/' . $tipo);
$valor = static fn($campo, $default = '') => old($campo, $editar[$campo] ?? $default, false);
$fuentes = [];
foreach ($plantillas as $p) $fuentes['plantilla-' . $p['id']] = ['nombre' => 'Plantilla: ' . $p['nombre'], 'reglas' => json_decode($p['reglas'], true)];
foreach ($periodos as $p) $fuentes['periodo-' . $p['id']] = ['nombre' => $p['mes'] . ' — ' . $p['est_nombre'], 'reglas' => json_decode($p['reglas'], true)];
?>
<div class="container-fluid py-3">
    <h1 class="h3"><?= $titulo ?></h1>
    <?= view('Modules\Asistencia\Views\gestion_roles\navegacion', ['admin' => $admin]) ?>
    <?php foreach (['error' => 'danger', 'success' => 'success'] as $key => $color): if ($mensaje = session()->getFlashdata($key)): ?>
        <div class="alert alert-<?= $color ?>"><?= esc($mensaje) ?></div>
    <?php endif; endforeach ?>
    <p class="text-muted"><?= $esPeriodo ? 'Configure el mes trabajado y los plazos de programación, aprobación y cierre (hora de Lima). Las reglas se guardan como una copia propia del período.' : 'Guarde conjuntos de reglas para reutilizarlos en distintos meses. Editar o eliminar una plantilla no cambia los períodos ya configurados.' ?></p>
    <div class="table-responsive card mb-4">
        <table class="table table-hover align-middle mb-0">
            <thead><tr>
                <?php if ($esPeriodo): ?><th>Mes / establecimiento</th><th>Apertura</th><th>Aprobación</th><th>Cierre</th><th>Etapa</th>
                <?php else: ?><th>Plantilla</th><th>Descripción</th><?php endif ?>
                <th>Reglas</th><th>Acciones</th>
            </tr></thead><tbody>
            <?php $filas = $esPeriodo ? $periodos : $plantillas; foreach ($filas as $fila): ?>
                <tr>
                    <?php if ($esPeriodo): ?>
                        <td><strong><?= esc($fila['mes']) ?></strong><br><?= esc($fila['est_nombre']) ?></td>
                        <td><?= esc($fila['apertura']) ?></td><td><?= esc($fila['aprobacion']) ?></td><td><?= esc($fila['cierre']) ?></td><td><?= esc($fila['etapa']) ?></td>
                    <?php else: ?><td><?= esc($fila['nombre']) ?></td><td><?= esc($fila['descripcion']) ?></td><?php endif ?>
                    <td><details><summary><?= count(json_decode($fila['reglas'], true) ?: []) ?> reglas</summary>
                        <ul><?php foreach (json_decode($fila['reglas'], true) ?: [] as $regla): ?><li><?= esc($regla['nombre']) ?></li><?php endforeach ?></ul>
                    </details></td>
                    <td class="text-nowrap">
                        <button type="button" class="btn btn-sm btn-outline-primary copiar-reglas" data-fuente="<?= ($esPeriodo ? 'periodo-' : 'plantilla-') . (int)$fila['id'] ?>">Usar reglas</button>
                        <?php if (!$esPeriodo || $fila['etapa'] === 'PROGRAMADO'): ?>
                            <a class="btn btn-sm btn-outline-secondary" href="<?= $base ?>?editar=<?= (int)$fila['id'] ?>#formulario">Editar</a>
                            <form method="post" action="<?= $base ?>/eliminar" class="d-inline eliminar-configuracion">
                                <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$fila['id'] ?>"><button class="btn btn-sm btn-outline-danger">Eliminar</button>
                            </form>
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach; if (!$filas): ?><tr><td colspan="<?= $esPeriodo ? 7 : 4 ?>" class="text-center text-muted py-4">Todavía no hay registros. Cree el primero con el formulario.</td></tr><?php endif ?>
            </tbody>
        </table>
    </div>
    <?php if ($esPeriodo): ?><p class="small text-muted">Solo se editan o eliminan períodos antes de su apertura, sin roles ni turnos asociados. Los grupos de corte de asistencia mantienen su calendario independiente.</p><?php endif ?>
    <form method="post" action="<?= $base ?>/guardar" id="formulario" class="card card-body">
        <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)($editar['id'] ?? 0) ?>">
        <h2 class="h5"><?= $editar ? 'Editar' : 'Crear' ?> <?= $esPeriodo ? 'período' : 'plantilla' ?></h2>
        <div class="row g-3 mb-3">
            <?php if ($esPeriodo): ?>
                <label class="col-md-6">Establecimiento<select name="est_id" class="form-select" required><option value="">Seleccione...</option><?php foreach ($establecimientos as $e): ?><option value="<?= (int)$e['est_ide'] ?>" <?= (string)$valor('est_id') === (string)$e['est_ide'] ? 'selected' : '' ?>><?= esc($e['est_nombre']) ?></option><?php endforeach ?></select></label>
                <label class="col-md-6">Mes trabajado<input type="month" name="mes" value="<?= esc($valor('mes'), 'attr') ?>" required class="form-control"></label>
                <?php foreach (['apertura' => 'Apertura de programación', 'aprobacion' => 'Inicio de aprobación', 'cierre' => 'Cierre'] as $campo => $label): ?>
                    <label class="col-md-4"><?= $label ?><input type="datetime-local" name="<?= $campo ?>" value="<?= esc(str_replace(' ', 'T', $valor($campo)), 'attr') ?>" required class="form-control"></label>
                <?php endforeach ?>
            <?php else: ?>
                <label class="col-md-5">Nombre de la plantilla<input name="nombre" maxlength="150" required value="<?= esc($valor('nombre'), 'attr') ?>" class="form-control"></label>
                <label class="col-md-7">Descripción<input name="descripcion" value="<?= esc($valor('descripcion'), 'attr') ?>" class="form-control"></label>
            <?php endif ?>
        </div>
        <div class="bg-light rounded p-3 mb-3">
            <label for="fuente-reglas" class="form-label">Reutilizar reglas de una plantilla o un período</label>
            <div class="d-flex gap-2"><select id="fuente-reglas" class="form-select"><option value="">Seleccione una fuente...</option><?php foreach ($fuentes as $key => $fuente): ?><option value="<?= esc($key, 'attr') ?>"><?= esc($fuente['nombre']) ?></option><?php endforeach ?></select><button type="button" id="cargar-reglas" class="btn btn-outline-primary text-nowrap">Cargar reglas</button></div>
            <small>Puede ajustar la copia antes de guardar. Cargar una fuente reemplaza las reglas del formulario.</small>
        </div>
        <h3 class="h6">Reglas de programación</h3>
        <p class="small text-muted">Los ámbitos vacíos aplican a todos. Si selecciona oficina, régimen y modalidad, deben coincidir todos. Siempre se controlan superposiciones y la compatibilidad de modalidad.</p>
        <div id="reglas"></div>
        <input type="hidden" name="reglas" id="reglas-json">
        <div class="d-flex gap-2 mt-3"><button type="button" id="agregar-regla" class="btn btn-outline-secondary">Agregar regla</button><button class="btn btn-primary">Guardar <?= $esPeriodo ? 'período' : 'plantilla' ?></button><a class="btn btn-light" href="<?= $base ?>">Nuevo / cancelar</a></div>
    </form>
    <template id="regla-template">
        <div class="regla border rounded p-3 mb-3 row g-3">
            <label class="col-md-5">Nombre<input data-key="nombre" maxlength="150" required class="form-control"></label>
            <label class="col-md-4">Validación<select data-key="tipo" class="form-select"><option value="MAX_TURNOS">Máximo de turnos por mes</option><option value="TURNOS_PERMITIDOS">Turnos permitidos</option><option value="MAX_HORAS_MES">Máximo de horas por mes</option><option value="MAX_CONTINUAS">Máximo de horas continuas</option></select></label>
            <label class="col-md-3 campo-limite">Límite<input data-key="limite" type="number" min="0" step="0.5" class="form-control" value="4"></label>
            <label class="col-md-5 campo-turnos">Turnos<select data-key="turnos" multiple class="form-select" size="4"><?php foreach ($turnos as $t): ?><option value="<?= esc($t['tur_codigo'], 'attr') ?>"><?= esc($t['tur_codigo'] . ' — ' . $t['tur_nombre']) ?></option><?php endforeach ?></select><small>Use Ctrl o Cmd para seleccionar varios.</small></label>
            <label class="col-md-3">Oficina<select data-key="oficina_id" class="form-select"><option value="">Todas</option><?php foreach ($oficinas as $o): ?><option value="<?= (int)$o['ofi_ide'] ?>"><?= esc($o['ofi_nombre']) ?></option><?php endforeach ?></select></label>
            <label class="col-md-4">Régimen contractual<select data-key="regimen_id" class="form-select"><option value="">Todos</option><?php foreach ($regimenes as $r): ?><option value="<?= (int)$r['mco_ide'] ?>"><?= esc($r['mco_nombre']) ?></option><?php endforeach ?></select></label>
            <label class="col-md-4">Modalidad<select data-key="modalidad" class="form-select"><option value="">Todas</option><option>ASISTENCIAL</option><option>ADMINISTRATIVO</option></select></label>
            <div class="col-md-3 d-flex align-items-end"><button type="button" class="btn btn-outline-danger quitar">Quitar regla</button></div>
        </div>
    </template>
</div>
<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
<script>
(() => {
    const fuentes = <?= json_encode($fuentes, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const iniciales = <?= json_encode(json_decode($valor('reglas', '[]'), true) ?: [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const box = document.getElementById('reglas');
    function actualizar(row) {
        const tipo = row.querySelector('[data-key="tipo"]').value;
        const usaTurnos = ['TURNOS_PERMITIDOS', 'MAX_TURNOS'].includes(tipo);
        row.querySelector('.campo-turnos').hidden = !usaTurnos;
        row.querySelector('[data-key="turnos"]').required = usaTurnos;
        row.querySelector('.campo-limite').hidden = tipo === 'TURNOS_PERMITIDOS';
        row.querySelector('[data-key="limite"]').required = tipo !== 'TURNOS_PERMITIDOS';
        row.querySelector('[data-key="limite"]').step = tipo === 'MAX_TURNOS' ? '1' : '0.5';
    }
    function agregar(regla = {}) {
        const row = document.getElementById('regla-template').content.firstElementChild.cloneNode(true);
        row.querySelectorAll('[data-key]').forEach(el => {
            const value = regla[el.dataset.key];
            if (value === undefined) return;
            if (el.multiple) {
                (value || []).forEach(code => { if (![...el.options].some(o => o.value === code)) el.add(new Option(code + ' (no disponible)', code)); });
                [...el.options].forEach(o => o.selected = value.includes(o.value));
            } else {
                if (el.tagName === 'SELECT' && ![...el.options].some(o => o.value === String(value))) el.add(new Option('No disponible (#' + value + ')', value));
                el.value = value;
            }
        });
        actualizar(row);
        box.append(row);
    }
    function cargar(key) {
        if (!fuentes[key]) return;
        if (box.children.length && !confirm('¿Reemplazar las reglas del formulario por las de la fuente seleccionada?')) return;
        box.replaceChildren();
        fuentes[key].reglas.forEach(agregar);
        document.getElementById('fuente-reglas').value = key;
        document.getElementById('formulario').scrollIntoView({behavior: 'smooth'});
    }
    iniciales.forEach(agregar);
    document.getElementById('agregar-regla').addEventListener('click', () => agregar());
    document.getElementById('cargar-reglas').addEventListener('click', () => cargar(document.getElementById('fuente-reglas').value));
    document.querySelectorAll('.copiar-reglas').forEach(btn => btn.addEventListener('click', () => cargar(btn.dataset.fuente)));
    box.addEventListener('click', e => { if (e.target.closest('.quitar')) e.target.closest('.regla').remove(); });
    box.addEventListener('change', e => { if (e.target.dataset.key === 'tipo') actualizar(e.target.closest('.regla')); });
    document.getElementById('formulario').addEventListener('submit', () => {
        const reglas = [...box.children].map(row => {
            const regla = {};
            row.querySelectorAll('[data-key]').forEach(el => {
                if (el.multiple) regla[el.dataset.key] = [...el.selectedOptions].map(o => o.value);
                else if (el.value !== '') regla[el.dataset.key] = el.value;
            });
            return regla;
        });
        document.getElementById('reglas-json').value = JSON.stringify(reglas);
    });
    document.querySelectorAll('.eliminar-configuracion').forEach(form => form.addEventListener('submit', e => {
        if (!confirm('¿Eliminar este registro? Los períodos existentes conservarán sus reglas.')) e.preventDefault();
    }));
})();
</script>
<?= $this->endSection() ?>

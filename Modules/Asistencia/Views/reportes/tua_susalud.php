<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Reporte TUA SUSALUD<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container-fluid py-3" id="tua-app" data-catalogo="<?= site_url('asistencia/reportes/tua-susalud/catalogo') ?>" data-periodos="<?= site_url('asistencia/reportes/tua-susalud/periodos') ?>" data-consultar="<?= site_url('asistencia/reportes/tua-susalud/consultar') ?>" data-excel="<?= site_url('asistencia/reportes/tua-susalud/excel') ?>">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1">TUA SUSALUD</h3>
            <div class="text-muted">Reporte mensual de programación de turnos del personal con cargo asistencial.</div>
        </div><?php if ($puedeConfigurar): ?><a class="btn btn-outline-primary" href="<?= site_url('asistencia/reportes/tua-susalud/asignaciones') ?>"><i class="ti ti-adjustments"></i> Servicios UPSS por oficina</a><?php endif ?>
    </div>
    <form id="tua-filtros" class="card card-body mb-3" onsubmit="return false">
        <div class="row g-2 align-items-end">
            <div class="col-md-6"><label class="form-label" for="tua-establecimiento">Establecimiento</label><select class="form-select" id="tua-establecimiento" required>
                    <option value="">Cargando establecimientos...</option>
                </select></div>
            <div class="col-md-3"><label class="form-label" for="tua-periodo">Período programado</label><select class="form-select" id="tua-periodo" disabled>
                    <option value="">Seleccione establecimiento primero</option>
                </select></div>
            <div class="col-md-3 d-flex gap-2"><button id="tua-consultar" class="btn btn-primary" type="button" disabled>Consultar</button><a id="tua-excel" class="btn btn-success d-none"><i class="ti ti-file-spreadsheet"></i> Excel</a></div>
        </div>
        <div class="small text-muted mt-2">Solo se listan períodos programados en el establecimiento seleccionado.</div>
    </form>
    <div id="tua-resultados"></div>
</div>
<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
<script>
    (() => {
        const app = document.getElementById('tua-app'),
            est = document.getElementById('tua-establecimiento');
        if (!app) return;
        const loading = title => Swal.fire({
            title,
            text: 'Espere un momento…',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading()
        });
        const close = () => Swal.close();
        async function json(url) {
            const r = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            const d = await r.json();
            if (!r.ok || d.ok === false) throw new Error(d.message || 'No se pudo completar la solicitud.');
            return d;
        }
        async function periodos() {
            const sel = document.getElementById('tua-periodo'),
                btn = document.getElementById('tua-consultar');
            sel.innerHTML = '<option value="">Seleccione período...</option>';
            sel.disabled = true;
            btn.disabled = true;
            document.getElementById('tua-resultados').innerHTML = '';
            document.getElementById('tua-excel').classList.add('d-none');
            if (!est.value) return;
            loading('Buscando períodos programados');
            try {
                const u = new URL(app.dataset.periodos, location.origin);
                u.searchParams.set('establecimiento', est.value);
                const d = await json(u);
                for (const p of d.periodos) sel.append(new Option(p.mes + ' · ' + p.nivel, p.mes));
                sel.disabled = !d.periodos.length;
                close();
                if (!d.periodos.length) Swal.fire({
                    icon: 'info',
                    title: 'Sin períodos programados',
                    text: 'Este establecimiento no tiene períodos con programación asistencial registrada.'
                });
            } catch (e) {
                close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error al cargar períodos',
                    text: e.message
                });
            }
        }
        async function init() {
            loading('Cargando establecimientos');
            try {
                const d = await json(app.dataset.catalogo);
                est.innerHTML = '<option value="">Seleccione establecimiento...</option>';
                for (const e of d.establecimientos) est.append(new Option((e.est_ipress ? e.est_ipress + ' - ' : '') + e.est_nombre, e.est_ide));
                const initial = '<?= (int)$establecimientoId ?>';
                if (initial && [...est.options].some(o => o.value === initial)) est.value = initial;
                close();
                if (initial) await periodos();
            } catch (e) {
                close();
                Swal.fire({
                    icon: 'error',
                    title: 'No se pudieron cargar establecimientos',
                    text: e.message
                });
            }
        }
        est.addEventListener('change', periodos);
        document.getElementById('tua-periodo').addEventListener('change', e => document.getElementById('tua-consultar').disabled = !(est.value && e.target.value));
        document.getElementById('tua-consultar').addEventListener('click', async () => {
            loading('Generando reporte TUA SUSALUD');
            try {
                const u = new URL(app.dataset.consultar, location.origin);
                u.searchParams.set('establecimiento', est.value);
                u.searchParams.set('periodo', document.getElementById('tua-periodo').value);
                const d = await json(u);
                document.getElementById('tua-resultados').innerHTML = d.html;
                const ex = document.getElementById('tua-excel');
                ex.href = app.dataset.excel + '?' + new URLSearchParams({
                    establecimiento: est.value,
                    periodo: document.getElementById('tua-periodo').value
                });
                ex.classList.remove('d-none');
                if (window.jQuery && $.fn.DataTable && $('#tablaTua').length) $('#tablaTua').DataTable({
                    scrollX: true,
                    pageLength: 25,
                    order: [],
                    language: {
                        search: 'Buscar:',
                        lengthMenu: 'Mostrar _MENU_ registros',
                        info: 'Mostrando _START_ a _END_ de _TOTAL_',
                        emptyTable: 'Sin registros para este período',
                        paginate: {
                            previous: 'Anterior',
                            next: 'Siguiente'
                        }
                    }
                });
                close();
            } catch (e) {
                close();
                Swal.fire({
                    icon: 'error',
                    title: 'No se pudo generar el reporte',
                    text: e.message
                });
            }
        });
        init();
    })();
</script>
<?= $this->endSection() ?>
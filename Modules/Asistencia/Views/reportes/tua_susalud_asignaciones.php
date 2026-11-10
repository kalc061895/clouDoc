<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Servicios UPSS por oficina - TUA SUSALUD<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container-fluid py-3" id="tua-crud" data-catalogo="<?= site_url('asistencia/reportes/tua-susalud/asignaciones/catalogo') ?>" data-listar="<?= site_url('asistencia/reportes/tua-susalud/asignaciones/listar') ?>" data-guardar="<?= site_url('asistencia/reportes/tua-susalud/asignaciones') ?>" data-eliminar="<?= site_url('asistencia/reportes/tua-susalud/asignaciones') ?>">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1">Servicios UPSS por oficina</h3>
            <p class="text-muted mb-0">Administre la asignación de un servicio UPSS a cada oficina.</p>
        </div><a class="btn btn-outline-secondary" href="<?= site_url('asistencia/reportes/tua-susalud') ?>">Volver al reporte</a>
    </div>
    <div class="card bg-white p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div class="col-md-5"><label class="form-label" for="filtro-est">Filtrar por establecimiento</label><select id="filtro-est" class="form-select">
                    <option value="">Todos los establecimientos</option>
                </select></div><button id="btn-nueva" type="button" class="btn btn-primary"><i class="ti ti-plus"></i> Nueva asignación</button>
        </div>
        <div class="table-responsive">
            <table id="tablaAsignacionesTua" class="table table-hover align-middle w-100">
                <thead>
                    <tr>
                        <th>Establecimiento</th>
                        <th>RED</th>
                        <th>Oficina</th>
                        <th>Servicio UPSS</th>
                        <th>Actualizado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="modalAsignacionTua" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo-modal">Nueva asignación de servicio</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formAsignacionTua">
                <div class="modal-body"><input type="hidden" id="asig-id" name="id" value="0"><input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                    <div class="mb-3"><label class="form-label" for="asig-est">Establecimiento</label><select class="form-select" id="asig-est" name="establecimiento_id" required>
                            <option value="">Seleccione...</option>
                        </select></div>
                    <div class="mb-3"><label class="form-label" for="asig-oficina">Oficina</label><select class="form-select" id="asig-oficina" name="oficina_id" required disabled>
                            <option value="">Seleccione establecimiento primero...</option>
                        </select></div>
                    <div><label class="form-label" for="asig-servicio">Servicio UPSS</label><select class="form-select" id="asig-servicio" name="servicio_id" required disabled>
                            <option value="">Seleccione establecimiento primero...</option>
                        </select></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button><button type="submit" id="btn-guardar-asig" class="btn btn-primary">Guardar</button></div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
<script>
    (() => {
        const root = document.getElementById('tua-crud');
        if (!root) return;
        const $est = $('#filtro-est'),
            $modalEst = $('#asig-est'),
            $ofi = $('#asig-oficina'),
            $serv = $('#asig-servicio');
        let modal, tabla, csrfHash = '<?= csrf_hash() ?>';
        const csrfName = '<?= csrf_token() ?>';
        const loading = title => Swal.fire({
            title,
            text: 'Espere un momento…',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading()
        });
        const close = () => Swal.close();
        async function request(url, opts = {}) {
            const r = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    ...(opts.headers || {})
                },
                ...opts
            });
            const d = await r.json();
            if (!r.ok || d.status === 'error' || d.ok === false) {
                const e = new Error(d.message || 'No se pudo completar la solicitud.');
                e.csrf = d.csrf;
                throw e;
            }
            return d;
        }

        function fill(sel, items, valueField, labelFn, first) {
            sel.innerHTML = '';
            sel.append(new Option(first, ''));
            (items || []).forEach(item => sel.append(new Option(labelFn(item), item[valueField])));
        }
        async function loadCatalog(est = '') {
            const u = new URL(root.dataset.catalogo, location.origin);
            if (est) u.searchParams.set('establecimiento', est);
            return request(u);
        }
        async function loadOptions(est, selectedOffice = '', selectedService = '') {
            if (!est) {
                fill($ofi[0], [], 'ofi_ide', x => x.ofi_nombre, 'Seleccione establecimiento primero...');
                fill($serv[0], [], 'uss_ide', x => x.uss_nombre, 'Seleccione establecimiento primero...');
                $ofi.prop('disabled', true);
                $serv.prop('disabled', true);
                return;
            }
            loading('Cargando oficinas y servicios UPSS');
            try {
                const d = await loadCatalog(est);
                fill($ofi[0], d.oficinas, 'ofi_ide', x => x.ofi_nombre, 'Seleccione oficina...');
                fill($serv[0], d.servicios, 'uss_ide', x => x.uss_nombre, 'Seleccione servicio...');
                $ofi.prop('disabled', !d.oficinas.length).val(String(selectedOffice || ''));
                $serv.prop('disabled', !d.servicios.length).val(String(selectedService || ''));
                close();
            } catch (e) {
                close();
                Swal.fire({
                    icon: 'error',
                    title: 'No se pudo cargar el catálogo',
                    text: e.message
                });
            }
        }

        function html(v) {
            return $('<div>').text(v ?? '').html();
        }

        function initTable() {
            tabla = $('#tablaAsignacionesTua').DataTable({
                ajax: {
                    url: root.dataset.listar,
                    data: d => {
                        d.establecimiento = $est.val() || '';
                    },
                    dataSrc: 'data'
                },
                columns: [{
                    data: 'est_nombre',
                    render: (v, t, r) => `${r.est_ipress?`<span class="text-muted">${html(r.est_ipress)}</span> · `:''}${html(v)}`
                }, {
                    data: 'red_nombre',
                    render: v => html(v || '-')
                }, {
                    data: 'ofi_nombre',
                    render: html
                }, {
                    data: 'uss_nombre',
                    render: html
                }, {
                    data: 'updated_at',
                    render: v => html(v ? v.split(' ')[0] : '-')
                }, {
                    data: null,
                    orderable: false,
                    searchable: false,
                    className: 'text-end',
                    render: (d, t, r) => `<button type="button" class="btn btn-outline-primary btn-sm js-editar" data-id="${r.id}" title="Editar" aria-label="Editar"><iconify-icon icon="lucide:edit-2"></iconify-icon></button> <button type="button" class="btn btn-outline-danger btn-sm js-eliminar" data-id="${r.id}" title="Eliminar" aria-label="Eliminar"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>`
                }],
                order: [
                    [0, 'asc']
                ],
                pageLength: 25,
                language: {
                    search: 'Buscar:',
                    lengthMenu: 'Mostrar _MENU_ registros',
                    zeroRecords: 'No se encontraron asignaciones',
                    emptyTable: 'No hay asignaciones registradas',
                    info: 'Mostrando _START_ a _END_ de _TOTAL_',
                    paginate: {
                        first: 'Primero',
                        last: 'Último',
                        next: 'Siguiente',
                        previous: 'Anterior'
                    }
                }
            });
            $('#tablaAsignacionesTua').on('click', '.js-editar', editar).on('click', '.js-eliminar', eliminar);
        }
        async function inicializar() {
            modal = new bootstrap.Modal(document.getElementById('modalAsignacionTua'));
            loading('Cargando establecimientos');
            try {
                const d = await loadCatalog();
                fill($est[0], d.establecimientos, 'est_ide', e => (e.est_ipress ? e.est_ipress + ' - ' : '') + e.est_nombre, 'Todos los establecimientos');
                fill($modalEst[0], d.establecimientos, 'est_ide', e => (e.est_ipress ? e.est_ipress + ' - ' : '') + e.est_nombre, 'Seleccione establecimiento...');
                close();
                initTable();
            } catch (e) {
                close();
                Swal.fire({
                    icon: 'error',
                    title: 'No se pudieron cargar establecimientos',
                    text: e.message
                });
            }
        }
        $('#filtro-est').on('change', () => tabla?.ajax.reload());
        $('#asig-est').on('change', e => loadOptions(e.target.value));
        $('#btn-nueva').on('click', () => {
            $('#formAsignacionTua')[0].reset();
            $('#asig-id').val(0);
            $('#titulo-modal').text('Nueva asignación de servicio');
            $modalEst.prop('disabled', false);
            loadOptions('').then(() => modal.show());
        });
        async function editar() {
            const row = tabla.row($(this).closest('tr')).data();
            $('#formAsignacionTua')[0].reset();
            $('#asig-id').val(row.id);
            $('#titulo-modal').text('Editar asignación de servicio');
            $modalEst.val(String(row.est_ide)).prop('disabled', true);
            await loadOptions(row.est_ide, row.oficina_id, row.servicio_id);
            modal.show();
        }
        $('#formAsignacionTua').on('submit', async function(e) {
            e.preventDefault();
            const button = $('#btn-guardar-asig');
            button.prop('disabled', true);
            loading('Guardando asignación');
            try {
                const data = new FormData(this);
                if ($modalEst.prop('disabled')) data.set('establecimiento_id', $modalEst.val());
                const d = await request(root.dataset.guardar, {
                    method: 'POST',
                    body: data
                });
                if (d.csrf) csrfHash = d.csrf.hash;
                close();
                modal.hide();
                Swal.fire({
                    icon: 'success',
                    title: 'Guardado',
                    text: d.message
                });
                tabla.ajax.reload(null, false);
            } catch (err) {
                close();
                if (err.csrf) csrfHash = err.csrf.hash;
                Swal.fire({
                    icon: 'error',
                    title: 'No se pudo guardar',
                    text: err.message
                });
            } finally {
                button.prop('disabled', false);
                const csrf = this.querySelector(`input[name="${csrfName}"]`);
                if (csrf) csrf.value = csrfHash;
            }
        });
        async function eliminar() {
            const id = $(this).data('id');
            const result = await Swal.fire({
                title: '¿Eliminar esta asignación?',
                text: 'La oficina quedará sin servicio UPSS asignado para TUA SUSALUD.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#d33'
            });
            if (!result.isConfirmed) return;
            loading('Eliminando asignación');
            try {
                const d = await request(`${root.dataset.eliminar}/${id}`, {
                    method: 'DELETE'
                });
                close();
                Swal.fire({
                    icon: 'success',
                    title: 'Eliminado',
                    text: d.message
                });
                tabla.ajax.reload(null, false);
            } catch (e) {
                close();
                Swal.fire({
                    icon: 'error',
                    title: 'No se pudo eliminar',
                    text: e.message
                });
            }
        }
        inicializar();
    })();
</script>
<?= $this->endSection() ?>

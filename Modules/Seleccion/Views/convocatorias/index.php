<?= $this->extend('layouts/seleccionLayout') ?>
<?= $this->section('title') ?>Convocatorias<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold"><iconify-icon icon="solar:file-text-bold"
                    class="me-2 text-primary"></iconify-icon>Convocatorias</h4>
            <p class="text-muted small mb-0">Gestión de procesos de selección</p>
        </div><button class="btn btn-primary btn-sm rounded-pill px-3" onclick="nuevo()"><iconify-icon
                icon="lucide:plus" class="me-1"></iconify-icon>Nueva convocatoria</button>
    </div>
    <div class="table-responsive">
        <table id="tabla" class="table table-hover align-middle w-100">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Número</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Año</th>
                    <th>Fechas</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div class="modal fade" id="modal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 id="titulo" class="modal-title">Nueva convocatoria</h5><button class="btn-close"
                    data-bs-dismiss="modal"></button>
            </div>
            <form id="form">
                <div class="modal-body"><input type="hidden" id="con_ide">
                    <div class="row g-3">
                        <div class="col-md-4"><label class="form-label">Código *</label><input id="con_codigo"
                                class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label">Número *</label><input id="con_numero"
                                class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label">Año *</label><input id="con_anio" type="number"
                                class="form-control" required value="<?= date('Y') ?>"></div>
                        <div class="col-md-12"><label class="form-label">Nombre *</label><input id="con_nombre"
                                class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label">Tipo *</label><select id="con_tco_ide"
                                class="form-select" required></select></div>
                        <div class="col-md-6"><label class="form-label">Estado *</label><select id="con_eco_ide"
                                class="form-select" required></select></div>
                        <div class="col-md-4"><label class="form-label">Publicación</label><input
                                id="con_fecha_publicacion" type="datetime-local" class="form-control"></div>
                        <div class="col-md-4"><label class="form-label">Inicio</label><input id="con_fecha_inicio"
                                type="datetime-local" class="form-control"></div>
                        <div class="col-md-4"><label class="form-label">Cierre</label><input id="con_fecha_cierre"
                                type="datetime-local" class="form-control"></div>
                        <div class="col-md-12"><label class="form-label">Observación</label><textarea
                                id="con_observacion" class="form-control"></textarea></div>
                    </div>
                </div>
                <div class="modal-footer border-0"><button class="btn btn-light" data-bs-dismiss="modal"
                        type="button">Cancelar</button><button id="guardar" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
<script>
    const api = '<?= base_url('api/seleccion/convocatorias') ?>', modal = new bootstrap.Modal(document.getElementById('modal')); let tabla, registros = new Map();
    const escapeHtml = v => String(v ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[c]));
    function lookups() { $.get('<?= base_url('seleccion/api/tipos-convocatoria') ?>', r => $('#con_tco_ide').html('<option value="">Seleccione...</option>' + r.data.map(x => '<option value="' + x.tco_ide + '">' + escapeHtml(x.tco_nombre) + '</option>').join(''))); $.get('<?= base_url('seleccion/api/estados-convocatoria') ?>', r => $('#con_eco_ide').html('<option value="">Seleccione...</option>' + r.data.map(x => '<option value="' + x.eco_ide + '">' + escapeHtml(x.eco_nombre) + '</option>').join(''))); }
    $(function () { lookups(); tabla = $('#tabla').DataTable({ ajax: { url: api, dataSrc: r => { registros = new Map((r.data || []).map(x => [String(x.con_ide), x])); return r.data || []; } }, columns: [{ data: 'con_codigo' }, { data: 'con_numero' }, { data: 'con_nombre' }, { data: 'tco_nombre' }, { data: 'eco_nombre' }, { data: 'con_anio' }, { data: null, render: r => escapeHtml(r.con_fecha_inicio || '-') + ' / ' + escapeHtml(r.con_fecha_cierre || '-') }, { data: null, className: 'text-end', render: r => '<a class="btn btn-outline-primary btn-sm me-1" href="<?= base_url('seleccion/convocatorias') ?>/' + r.con_ide + '" title="Configurar"><iconify-icon icon="lucide:settings"></iconify-icon></a><button class="btn btn-outline-warning btn-sm me-1 editar" data-id="' + r.con_ide + '"><iconify-icon icon="lucide:edit"></iconify-icon></button><button class="btn btn-outline-success btn-sm publicar" data-id="' + r.con_ide + '">Publicar</button>' }], language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' } }); $('#tabla').on('click', '.editar', e => editar(registros.get(String($(e.currentTarget).data('id'))))).on('click', '.publicar', e => publicar($(e.currentTarget).data('id'))); $('#form').submit(e => { e.preventDefault(); guardar(); }); });
    function nuevo() { $('#form')[0].reset(); $('#con_ide').val(''); $('#titulo').text('Nueva convocatoria'); modal.show(); } function editar(d) { nuevo(); $('#titulo').text('Editar convocatoria'); Object.keys(d).forEach(k => $('#' + k).val(d[k] ?? '')); } function guardar() { let id = $('#con_ide').val(), d = {};['con_codigo', 'con_numero', 'con_anio', 'con_nombre', 'con_tco_ide', 'con_eco_ide', 'con_fecha_publicacion', 'con_fecha_inicio', 'con_fecha_cierre', 'con_observacion'].forEach(k => d[k] = $('#' + k).val()); $('#guardar').prop('disabled', true); $.ajax({ url: id ? api + '/' + id : api, type: id ? 'PUT' : 'POST', contentType: 'application/json', data: JSON.stringify(d), success: r => { if (r.status) { toastr.success(r.message); modal.hide(); tabla.ajax.reload(null, false) } else Swal.fire('Error', r.message, 'error') }, error: x => Swal.fire('Error', Object.values(x.responseJSON?.errors || { error: x.responseJSON?.message || 'Solicitud inválida' }).join('<br>'), 'error'), complete: () => $('#guardar').prop('disabled', false) }); } function publicar(id) { Swal.fire({ title: '¿Publicar convocatoria?', icon: 'warning', showCancelButton: true }).then(x => x.isConfirmed && $.post(api + '/' + id + '/publicar', r => { if (r.status) { toastr.success(r.message); tabla.ajax.reload(null, false) } else Swal.fire('Error', r.message, 'error') })); }
</script><?= $this->endSection() ?>
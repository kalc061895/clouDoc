<?= $this->extend('Modules\Firma\Views\layout') ?>
<?= $this->section('title') ?>Firma de documentos<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div id="firma-app" data-base="<?= esc(base_url('firma'), 'attr') ?>">
    <h3>Firma de documentos</h3>
    <p>Incorpore un PDF o un rol de Asistencia y fírmelo con el cliente de escritorio Firma Perú. Cada firma conserva una nueva versión.</p>
    <div id="firma-mensaje" role="status" aria-live="polite"></div>
    <div class="row">
        <div class="col-lg-6">
            <form id="firma-subir" class="card card-body">
                <label for="firma-documento">Documento PDF externo (máximo <?= (int) ($config->maxBytes / 1048576) ?> MB)</label>
                <input class="form-control my-2" id="firma-documento" name="documento" type="file" accept="application/pdf,.pdf" required>
                <button class="btn btn-primary" type="submit">Incorporar PDF</button>
            </form>
        </div>
        <div class="col-lg-6">
            <form id="firma-rol" class="card card-body">
                <label for="firma-rol-id">ID de un rol generado por usted</label>
                <input class="form-control my-2" id="firma-rol-id" name="rol" type="number" min="1" required>
                <button class="btn btn-outline-primary" type="submit">Incorporar rol de Asistencia</button>
                <a class="mt-2" href="<?= base_url('asistencia/roles/historial') ?>">Consultar historial de roles</a>
            </form>
        </div>
    </div>
    <form id="firma-opciones" class="card card-body">
        <h4>Opciones de firma</h4>
        <p class="text-muted">Todos los campos son obligatorios.</p>
        <div class="mb-3"><label for="firma-asunto">Asunto *</label><input id="firma-asunto" name="asunto" class="form-control" maxlength="200" required placeholder="Asunto del documento que va a firmar"></div>
        <div class="row">
            <div class="col-md-5"><label for="firma-motivo">Motivo *</label><input id="firma-motivo" name="motivo" class="form-control" maxlength="200" required placeholder="Ej.: Soy el autor del documento"></div>
            <div class="col-md-4"><label for="firma-cargo">Cargo *</label><input id="firma-cargo" name="cargo" class="form-control" maxlength="150" required></div>
            <div class="col-md-3"><label for="firma-estilo">Estampa *</label><select id="firma-estilo" name="estilo" class="form-select" required>
                    <option value="1">Horizontal</option>
                    <option value="2">Vertical</option>
                </select></div>
        </div>
        <p class="text-muted mt-2 mb-0">Seleccione «Firmar» en un documento y confirme la ubicación de la firma en Firma Perú.</p>
    </form>
    <div class="d-flex gap-2 mb-3"><button id="firma-actualizar" class="btn btn-outline-secondary">Actualizar</button><button id="firma-cancelar" class="btn btn-outline-danger" hidden>Cancelar operación pendiente</button></div>
    <div class="table-responsive">
        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>Documento</th>
                    <th>Origen</th>
                    <th>Versión</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="firma-lista"></tbody>
        </table>
    </div>
    <div class="d-flex gap-3"><button id="firma-anterior" class="btn btn-outline-secondary">Anterior</button><span id="firma-pagina"></span><button id="firma-siguiente" class="btn btn-outline-secondary">Siguiente</button></div>
    <div class="modal fade" id="firma-versiones-modal" tabindex="-1" aria-labelledby="firma-versiones-titulo" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable"><div class="modal-content">
            <div class="modal-header"><h5 class="modal-title" id="firma-versiones-titulo">Historial de versiones</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button></div>
            <div class="modal-body"><p id="firma-versiones-documento" class="fw-semibold"></p><div id="firma-versiones" aria-live="polite"></div></div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button></div>
        </div></div>
    </div>
</div>
<script id="firma-config" type="application/json">
    <?= json_encode(['csrf' => ['name' => csrf_token(), 'hash' => csrf_hash()]], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>
</script>
<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
<script src="<?= esc($config->scriptUrl, 'attr') ?>"></script>
<script>
    var jqFirmaPeru = jQuery.noConflict();
</script>
<script src="<?= base_url('assets/js/firma/documentos.js') ?>"></script>
<?= $this->endSection() ?>

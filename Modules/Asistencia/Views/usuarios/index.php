<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Usuarios de Asistencia<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container-fluid py-3" id="usuarios-asistencia" data-api="<?= site_url('asistencia/usuarios') ?>">
    <div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
        <div><h1 class="h3">Usuarios de Asistencia</h1><p class="text-muted">Cree cuentas con perfiles asi*, consulte sus datos por DNI y designe sus tareas.</p></div>
        <div class="d-flex gap-2 align-items-start"><a class="btn btn-outline-primary" href="<?= site_url('asistencia/accesos') ?>">Menús por perfil</a><button class="btn btn-primary" id="nuevo-usuario">Nuevo usuario</button></div>
    </div>
    <div class="alert alert-info">Los menús se asignan por perfil. Las tareas de Programación, Recursos Humanos y Dirección requieren una designación con establecimiento u oficina y vigencia.</div>
    <div id="usuarios-error" class="alert alert-danger d-none" role="alert"></div>
    <div class="card"><div class="card-body"><div class="table-responsive"><table id="tablaUsuariosAsistencia" class="table table-striped align-middle">
        <thead><tr><th>DNI</th><th>Usuario</th><th>Nombre completo</th><th>Perfiles</th><th>Estado</th><th>Acciones</th></tr></thead><tbody></tbody>
    </table></div></div></div>
</div>
<div class="modal fade" id="modalUsuarioAsistencia" tabindex="-1" aria-labelledby="tituloUsuarioAsistencia" aria-hidden="true"><div class="modal-dialog modal-lg"><div class="modal-content">
    <form id="formUsuarioAsistencia">
        <div class="modal-header"><h2 class="modal-title fs-5" id="tituloUsuarioAsistencia">Nuevo usuario</h2><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button></div>
        <div class="modal-body">
            <div id="usuario-error" class="alert alert-danger d-none" role="alert"></div>
            <input type="hidden" name="id">
            <div class="row g-3">
                <div class="col-md-6"><label for="u-dni" class="form-label">DNI</label><div class="input-group"><input id="u-dni" name="dni" class="form-control" required pattern="[0-9]{8}" maxlength="8" inputmode="numeric"><button type="button" class="btn btn-outline-primary" id="buscar-dni">Buscar DNI</button></div></div>
                <div class="col-md-6"><label for="u-username" class="form-label">Nombre de usuario</label><input id="u-username" name="username" class="form-control" required maxlength="30" autocomplete="off"></div>
                <?php foreach (['nombres'=>'Nombres', 'paterno'=>'Apellido paterno', 'materno'=>'Apellido materno', 'email'=>'Correo electrónico', 'telefono'=>'Teléfono', 'cargo'=>'Cargo'] as $campo=>$label): ?>
                    <div class="col-md-6"><label for="u-<?= $campo ?>" class="form-label"><?= $label ?></label><input id="u-<?= $campo ?>" name="<?= $campo ?>" class="form-control" type="<?= $campo === 'email' ? 'email' : 'text' ?>" maxlength="<?= $campo === 'telefono' ? 20 : ($campo === 'email' ? 254 : 100) ?>" <?= in_array($campo, ['nombres','paterno','email'], true) ? 'required' : '' ?>></div>
                <?php endforeach ?>
                <div class="col-12"><p id="dni-resultado" class="small text-muted mb-0" role="status">Busque el DNI para completar los datos registrados en Personas. Si no existe, puede ingresarlos manualmente.</p></div>
                <div class="col-md-6"><label for="u-password" class="form-label">Contraseña</label><input id="u-password" name="password" type="password" class="form-control" autocomplete="new-password"><div class="form-text">Al editar, déjela vacía para conservar la contraseña.</div></div>
                <div class="col-md-6"><label for="u-password-confirm" class="form-label">Confirmar contraseña</label><input id="u-password-confirm" name="password_confirm" type="password" class="form-control" autocomplete="new-password"></div>
                <div class="col-md-8"><label for="u-grupos" class="form-label">Perfiles de Asistencia</label><select id="u-grupos" name="grupos[]" class="form-select" multiple size="6" required></select><div class="form-text">Puede seleccionar varios perfiles. Sus menús se configuran en «Menús por perfil».</div></div>
                <div class="col-md-4"><label for="u-active" class="form-label">Estado</label><select id="u-active" name="active" class="form-select"><option value="1">Activo</option><option value="0">Inactivo</option></select></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary" id="guardar-usuario">Guardar usuario</button></div>
    </form>
</div></div></div>
<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
<script>
(() => {
    const api = document.getElementById('usuarios-asistencia').dataset.api;
    const tareasUrl = <?= json_encode(site_url('asistencia/gestion-roles/delegaciones')) ?>;
    const csrfName = <?= json_encode(csrf_token()) ?>;
    let csrfHash = <?= json_encode(csrf_hash()) ?>, tabla = null;
    const form = document.getElementById('formUsuarioAsistencia');
    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalUsuarioAsistencia'));
    const esc = v => String(v ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
    function error(id, text = '') { const el = document.getElementById(id); el.textContent = text; el.classList.toggle('d-none', !text); }
    async function request(path, body) {
        const options = {headers:{'X-Requested-With':'XMLHttpRequest'}};
        if (body) { body.set(csrfName, csrfHash); options.method = 'POST'; options.body = body; }
        const response = await fetch(`${api}/${path}`, options);
        const json = await response.json();
        if (json.csrfHash) csrfHash = json.csrfHash;
        if (!response.ok || !json.ok) throw new Error(json.message || 'No se pudo completar la operación.');
        return json.data;
    }
    async function cargar() {
        const data = await request('datos');
        document.getElementById('u-grupos').innerHTML = data.perfiles.map(p => `<option value="${esc(p.name)}">${esc(p.name)}</option>`).join('');
        if (tabla) { tabla.destroy(); tabla = null; }
        document.querySelector('#tablaUsuariosAsistencia tbody').innerHTML = data.usuarios.map(u => `<tr><td>${esc(u.dni)}</td><td>${esc(u.username)}</td><td>${esc([u.nombres,u.paterno,u.materno].filter(Boolean).join(' '))}</td><td>${u.grupos.map(esc).join(', ')}</td><td>${Number(u.active) ? 'Activo' : 'Inactivo'}</td><td><div class="d-flex gap-1"><button type="button" class="btn btn-sm btn-outline-primary" data-editar="${Number(u.id)}">Editar</button><a class="btn btn-sm btn-outline-secondary" href="${tareasUrl}?usuario_id=${Number(u.id)}">Designar tareas</a><button type="button" class="btn btn-sm btn-outline-danger" data-eliminar="${Number(u.id)}">Eliminar</button></div></td></tr>`).join('');
        if (window.jQuery?.fn?.DataTable) tabla = $('#tablaUsuariosAsistencia').DataTable({pageLength:10, order:[[1,'asc']], columnDefs:[{targets:5,orderable:false}], language:{search:'Buscar:',emptyTable:'No hay usuarios de Asistencia',lengthMenu:'Mostrar _MENU_',info:'_START_ a _END_ de _TOTAL_',infoEmpty:'Sin usuarios',zeroRecords:'Sin coincidencias',paginate:{next:'Siguiente',previous:'Anterior'}}});
    }
    function abrir(data = {}) {
        form.reset(); error('usuario-error');
        document.getElementById('tituloUsuarioAsistencia').textContent = data.id ? 'Editar usuario' : 'Nuevo usuario';
        for (const name of ['id','username','dni','nombres','paterno','materno','email','telefono','cargo']) form.elements[name].value = data[name] ?? '';
        form.elements.active.value = String(data.active ?? 1);
        form.elements.password.required = form.elements.password_confirm.required = !data.id;
        for (const o of document.getElementById('u-grupos').options) o.selected = (data.grupos ?? ['asi_rol']).includes(o.value);
        document.getElementById('dni-resultado').textContent = 'Busque el DNI para completar los datos existentes.';
        modal.show();
    }
    document.getElementById('nuevo-usuario').addEventListener('click', () => abrir());
    document.querySelector('#tablaUsuariosAsistencia tbody').addEventListener('click', async e => {
        const editar = e.target.closest('[data-editar]'), eliminar = e.target.closest('[data-eliminar]');
        try {
            if (editar) abrir(await request(editar.dataset.editar));
            if (eliminar) {
                const result = await Swal.fire({title:'¿Eliminar esta cuenta?',text:'Se retirará el acceso y se conservará su historial.',icon:'warning',showCancelButton:true,confirmButtonText:'Eliminar',cancelButtonText:'Cancelar'});
                if (!result.isConfirmed) return;
                await request(`${eliminar.dataset.eliminar}/eliminar`, new FormData()); await cargar(); toastr.success('Usuario eliminado.');
            }
        } catch (e) { error('usuarios-error', e.message); }
    });
    document.getElementById('buscar-dni').addEventListener('click', async function() {
        const dni = form.elements.dni.value.trim();
        if (!/^[0-9]{8}$/.test(dni)) return error('usuario-error', 'Ingrese un DNI de ocho dígitos.');
        this.disabled = true; error('usuario-error');
        try {
            const data = await request(`dni?dni=${encodeURIComponent(dni)}`);
            if (form.elements.dni.value.trim() !== dni) return;
            if (data) {
                for (const campo of ['nombres','paterno','materno','email','telefono']) form.elements[campo].value = data[campo] ?? '';
                if (!form.elements.username.value) form.elements.username.value = dni;
            }
            document.getElementById('dni-resultado').textContent = data ? 'Datos encontrados y completados. Revise el correo antes de guardar.' : 'No se encontró el DNI. Puede registrar los datos manualmente.';
        } catch (e) { error('usuario-error', e.message); } finally { this.disabled = false; }
    });
    form.addEventListener('submit', async e => {
        e.preventDefault(); error('usuario-error'); const btn = document.getElementById('guardar-usuario'); btn.disabled = true;
        try { await request('guardar', new FormData(form)); modal.hide(); await cargar(); toastr.success('Usuario guardado. Puede designarle tareas desde la lista.'); }
        catch (e) { error('usuario-error', e.message); } finally { btn.disabled = false; }
    });
    cargar().catch(e => error('usuarios-error', e.message));
})();
</script>
<?= $this->endSection() ?>

<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Perfiles y accesos de Asistencia<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container-fluid py-3" id="accesos-app" data-api="<?= site_url('asistencia/accesos') ?>">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div><h1 class="h3 mb-1">Perfiles y accesos de Asistencia</h1><p class="text-muted mb-0">Administre perfiles, usuarios y los menús disponibles para cada perfil.</p></div>
        <button class="btn btn-primary" id="crear-perfil"><iconify-icon icon="lucide:plus" class="me-1"></iconify-icon>Crear perfil</button>
    </div>
    <div class="card border-0 shadow-sm mb-3"><div class="card-body">
        <h2 class="h5">Perfiles de Asistencia</h2>
        <div class="table-responsive"><table class="table table-striped table-hover align-middle w-100" id="tablaPerfiles">
            <thead class="table-light"><tr><th>Perfil</th><th>Usuarios</th><th>Menús activos</th><th>Acción</th></tr></thead><tbody></tbody>
        </table></div>
    </div></div>
</div>
<div class="modal fade" id="editor-perfil" tabindex="-1" aria-labelledby="titulo-editor-perfil" aria-hidden="true"><div class="modal-dialog modal-xl modal-dialog-scrollable"><div class="modal-content">
    <div class="modal-header"><div><h2 class="modal-title fs-5" id="titulo-editor-perfil">Editar perfil: <span id="perfil-actual"></span></h2><p class="small text-muted mb-0">Los cambios quedan pendientes hasta confirmarlos.</p></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button></div>
    <div class="modal-body"><ul class="nav nav-tabs mb-3"><li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-menus" type="button">Menús y accesos</button></li><li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-usuarios" type="button">Usuarios</button></li></ul>
        <div class="tab-content"><div class="tab-pane fade show active" id="tab-menus"><p class="text-muted small">Activa o desactiva cada opción. Los menús se agrupan por su menú padre.</p><div id="menus-agrupados"></div></div>
            <div class="tab-pane fade" id="tab-usuarios"><div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-2"><p class="text-muted small mb-0">Asigna usuarios de Asistencia a este perfil.</p><input class="form-control" id="filtro-usuario" placeholder="Filtrar por nombre o usuario" style="max-width:320px"></div><div id="usuarios-perfil" class="list-group"></div></div>
        </div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" id="descartar-cambios" disabled>Descartar</button><button type="button" class="btn btn-success" id="confirmar-cambios" disabled><iconify-icon icon="lucide:check" class="me-1"></iconify-icon>Confirmar cambios</button></div>
</div></div></div>
<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
<script>
(() => {
    const root = document.getElementById('accesos-app');
    const api = root.dataset.api;
    const csrfName = <?= json_encode(csrf_token()) ?>;
    let csrfHash = <?= json_encode(csrf_hash()) ?>;
    let state = {perfiles:[], usuarios:[], menus:[], asignaciones:[]};
    let perfilId = 0, pendientes = {menus:{}, usuarios:{}};
    let dataTable = null;
    const editorElement = document.getElementById('editor-perfil');
    const editorModal = bootstrap.Modal.getOrCreateInstance(editorElement);
    let permitirCerrar = false;

    const esc = value => String(value ?? '').replace(/[&<>"']/g, char => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[char]));
    const menuOriginal = id => state.asignaciones.some(item => Number(item.group_user_id) === perfilId && Number(item.menu_id) === Number(id));
    const usuarioOriginal = id => {
        const user = state.usuarios.find(item => Number(item.id) === Number(id));
        const perfil = state.perfiles.find(item => Number(item.id) === perfilId);
        return !!user && (user.grupos || '').split(', ').includes(perfil?.name);
    };
    const menuActivo = id => pendientes.menus[id] ?? menuOriginal(id);
    const usuarioActivo = id => pendientes.usuarios[id] ?? usuarioOriginal(id);
    const hayPendientes = () => Object.keys(pendientes.menus).length + Object.keys(pendientes.usuarios).length > 0;

    async function post(path, datos) {
        const body = new URLSearchParams({...datos, [csrfName]:csrfHash});
        const response = await fetch(`${api}/${path}`, {method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest'}, body});
        const json = await response.json();
        if (json.csrfHash) csrfHash = json.csrfHash;
        if (!json.ok) throw new Error(json.message || 'No se pudo guardar.');
        return json.data;
    }
    async function load() {
        const response = await fetch(`${api}/datos`, {headers:{'X-Requested-With':'XMLHttpRequest'}});
        const json = await response.json();
        if (!json.ok) throw new Error(json.message || 'No se pudo cargar la información.');
        state = json.data;
        drawProfiles();
        if (perfilId && state.perfiles.some(item => Number(item.id) === perfilId)) openProfile(perfilId, false);
        else closeProfile();
    }
    function drawProfiles() {
        const rows = state.perfiles.map(profile => {
            const users = state.usuarios.filter(user => (user.grupos || '').split(', ').includes(profile.name)).length;
            const menus = state.asignaciones.filter(item => Number(item.group_user_id) === Number(profile.id)).length;
            return `<tr><td><strong>${esc(profile.name)}</strong></td><td>${users}</td><td>${menus}</td><td><button type="button" class="btn btn-sm btn-outline-primary editar-perfil" data-id="${profile.id}"><iconify-icon icon="lucide:pencil" class="me-1"></iconify-icon>Editar</button></td></tr>`;
        }).join('');
        const body = document.querySelector('#tablaPerfiles tbody');
        if (dataTable) { dataTable.clear().destroy(); dataTable = null; }
        body.innerHTML = rows;
        if (window.jQuery?.fn?.DataTable) dataTable = $('#tablaPerfiles').DataTable({language:{emptyTable:'No hay perfiles',info:'Mostrando _START_ a _END_ de _TOTAL_ perfiles',infoEmpty:'Mostrando 0 perfiles',lengthMenu:'Mostrar _MENU_ perfiles',search:'Buscar:',zeroRecords:'No se encontraron perfiles',paginate:{first:'Primero',last:'Último',next:'Siguiente',previous:'Anterior'}},pageLength:10,order:[],columnDefs:[{targets:3,orderable:false,searchable:false}]});
    }
    function menuGroups() {
        const map = new Map(state.menus.map(menu => [Number(menu.id), menu]));
        const groups = new Map();
        state.menus.filter(menu => String(menu.url || '').startsWith('asistencia/')).forEach(menu => {
            const chain = []; let parentId = Number(menu.parent_id || 0);
            while (parentId && map.has(parentId)) { const parent = map.get(parentId); chain.unshift(parent.name); parentId = Number(parent.parent_id || 0); }
            const title = chain.join(' / ') || 'Otros accesos';
            if (!groups.has(title)) groups.set(title, []);
            groups.get(title).push(menu);
        });
        return [...groups.entries()];
    }
    function drawMenus() {
        document.getElementById('menus-agrupados').innerHTML = menuGroups().map(([parent, menus]) => `<section class="border rounded mb-3"><h3 class="h6 bg-light border-bottom rounded-top p-3 mb-0">${esc(parent)}</h3><div class="list-group list-group-flush">${menus.map(menu => `<label class="list-group-item d-flex align-items-center gap-3"><span class="form-check form-switch mb-0"><input class="form-check-input menu-switch" type="checkbox" data-id="${menu.id}" ${menuActivo(menu.id)?'checked':''}></span><span>${esc(menu.name)}</span><small class="text-muted ms-auto">${esc(menu.url)}</small></label>`).join('')}</div></section>`).join('') || '<p class="text-muted">No hay menús activos de Asistencia.</p>';
    }
    function drawUsers() {
        const term = document.getElementById('filtro-usuario').value.toLowerCase();
        const users = state.usuarios.filter(user => `${user.username} ${user.nombres} ${user.paterno} ${user.materno}`.toLowerCase().includes(term));
        document.getElementById('usuarios-perfil').innerHTML = users.map(user => `<label class="list-group-item d-flex align-items-center gap-3"><span class="form-check form-switch mb-0"><input class="form-check-input usuario-switch" type="checkbox" data-id="${user.id}" ${usuarioActivo(user.id)?'checked':''}></span><span>${esc([user.nombres,user.paterno,user.materno].filter(Boolean).join(' ') || user.username)}</span><small class="text-muted ms-auto">${esc(user.username)}</small></label>`).join('') || '<p class="text-muted">No hay usuarios de perfiles asi*.</p>';
    }
    function updateButtons() {
        document.getElementById('confirmar-cambios').disabled = !hayPendientes();
        document.getElementById('descartar-cambios').disabled = !hayPendientes();
    }
    function openProfile(id, reset = true) {
        perfilId = Number(id);
        if (reset) pendientes = {menus:{}, usuarios:{}};
        const profile = state.perfiles.find(item => Number(item.id) === perfilId);
        if (!profile) return closeProfile();
        document.getElementById('perfil-actual').textContent = profile.name;
        drawMenus(); drawUsers(); updateButtons();
        editorModal.show();
    }
    function closeProfile() { if (editorElement.classList.contains('show')) editorModal.hide(); else resetEditor(); }
    function resetEditor() { perfilId = 0; pendientes = {menus:{},usuarios:{}}; updateButtons(); }
    async function askDiscard() {
        if (!hayPendientes()) return true;
        const result = await Swal.fire({title:'¿Descartar cambios pendientes?',text:'Se perderán los cambios que todavía no se confirmaron.',icon:'warning',showCancelButton:true,confirmButtonText:'Descartar',cancelButtonText:'Seguir editando'});
        return result.isConfirmed;
    }
    editorElement.addEventListener('hide.bs.modal', event => {
        if (permitirCerrar || !hayPendientes()) return;
        event.preventDefault();
        askDiscard().then(discard => {
            if (!discard) return;
            pendientes = {menus:{},usuarios:{}};
            updateButtons();
            permitirCerrar = true;
            editorModal.hide();
            permitirCerrar = false;
        });
    });
    editorElement.addEventListener('hidden.bs.modal', resetEditor);

    document.querySelector('#tablaPerfiles tbody').addEventListener('click', async event => {
        const button = event.target.closest('.editar-perfil'); if (!button) return;
        const id = Number(button.dataset.id);
        if (id === perfilId) return;
        if (id !== perfilId && !(await askDiscard())) return;
        openProfile(id);
    });
    editorElement.addEventListener('change', event => {
        const input = event.target;
        if (input.matches('.menu-switch')) {
            const id = input.dataset.id; if (input.checked === menuOriginal(id)) delete pendientes.menus[id]; else pendientes.menus[id] = input.checked;
        } else if (input.matches('.usuario-switch')) {
            const id = input.dataset.id; if (input.checked === usuarioOriginal(id)) delete pendientes.usuarios[id]; else pendientes.usuarios[id] = input.checked;
        } else return;
        updateButtons();
    });
    document.getElementById('filtro-usuario').addEventListener('input', drawUsers);
    document.getElementById('descartar-cambios').addEventListener('click', () => { pendientes={menus:{},usuarios:{}}; drawMenus(); drawUsers(); updateButtons(); toastr.info('Se descartaron los cambios pendientes.'); });
    document.getElementById('confirmar-cambios').addEventListener('click', async () => {
        const confirmed = await Swal.fire({title:'¿Confirmar cambios?',text:'Se actualizarán los menús y usuarios del perfil seleccionado.',icon:'question',showCancelButton:true,confirmButtonText:'Guardar cambios',cancelButtonText:'Cancelar'});
        if (!confirmed.isConfirmed) return;
        const cambios = {menus:Object.entries(pendientes.menus).map(([id,activo])=>({id,activo})),usuarios:Object.entries(pendientes.usuarios).map(([id,activo])=>({id,activo}))};
        try { await post('guardar-cambios',{perfil_id:perfilId,cambios:JSON.stringify(cambios)}); pendientes={menus:{},usuarios:{}}; await load(); toastr.success('Los cambios del perfil se guardaron.'); }
        catch (error) { toastr.error(error.message); }
    });
    document.getElementById('crear-perfil').addEventListener('click', async () => {
        if (!(await askDiscard())) return;
        const result = await Swal.fire({title:'Crear perfil de Asistencia',input:'text',inputLabel:'Nombre (debe comenzar con asi)',inputPlaceholder:'asi_reportes',showCancelButton:true,confirmButtonText:'Crear',cancelButtonText:'Cancelar',inputValidator:value=>/^asi[a-z0-9_]{1,38}$/.test(value||'')?'':'Use asi seguido de letras, números o guion bajo.'});
        if (!result.isConfirmed) return;
        pendientes={menus:{},usuarios:{}}; updateButtons();
        try { const profile=await post('perfil/guardar',{nombre:result.value}); await load(); openProfile(profile.id); toastr.success('Perfil creado. Configure sus menús y confirme los cambios.'); }
        catch (error) { toastr.error(error.message); }
    });
    load().catch(error => toastr.error(error.message));
})();
</script>
<?= $this->endSection() ?>

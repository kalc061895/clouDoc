(() => {
    'use strict';
    const root = document.getElementById('firma-app');
    if (!root) return;
    const base = root.dataset.base;
    let csrf = JSON.parse(document.getElementById('firma-config').textContent).csrf;
    let page = 1, pages = 1, operation = null, timer = null, posting = false, preparing = false, checking = false;
    const el = id => document.getElementById('firma-' + id);
    const toast = Swal.mixin({toast: true, position: 'top-end', showConfirmButton: false, timer: 5000, timerProgressBar: true,
        didOpen: popup => { popup.onmouseenter = Swal.stopTimer; popup.onmouseleave = Swal.resumeTimer; }});
    const versionsModal = new bootstrap.Modal(el('versiones-modal'));
    let versionsRequest = 0;
    const message = (text, error = false, icon = 'info') => {
        el('mensaje').className = 'alert ' + (error ? 'alert-danger' : 'alert-info');
        el('mensaje').textContent = text;
        toast.fire({icon: error ? 'error' : icon, title: text});
    };
    async function api(path, data) {
        const options = {headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}};
        if (data) {
            if (posting) throw new Error('Espere a que termine la operación anterior.');
            posting = true;
            data.set(csrf.name, csrf.hash);
            options.method = 'POST'; options.body = data;
        }
        try {
            const response = await fetch(base + '/' + path, options);
            let json;
            try { json = await response.json(); } catch (_) { throw new Error('La sesión o la respuesta del servidor no está disponible. Recargue la página.'); }
            if (json.data?.csrf) csrf = json.data.csrf;
            if (!response.ok || json.status !== 'success') throw new Error(json.message || 'No se pudo completar la operación.');
            return json.data.resultado;
        } finally { if (data) posting = false; }
    }
    function button(text, action) {
        const b = document.createElement('button'); b.type = 'button'; b.className = 'btn btn-sm btn-outline-primary me-2'; b.textContent = text;
        b.onclick = () => Promise.resolve().then(action).catch(e => message(e.message, true));
        return b;
    }
    function pdfLink(id, version) {
        const a = document.createElement('a'); a.href = `${base}/documentos/${id}/pdf/${version}`;
        a.target = '_blank'; a.rel = 'noopener'; a.className = 'btn btn-sm btn-outline-secondary me-2'; a.textContent = 'Ver PDF'; return a;
    }
    async function versions(id, nombre) {
        const request = ++versionsRequest;
        el('versiones-documento').textContent = nombre;
        el('versiones').textContent = 'Cargando versiones…';
        versionsModal.show();
        let rows;
        try { rows = await api(`documentos/${id}/versiones`); }
        catch (e) { if (request === versionsRequest) el('versiones').textContent = e.message; return; }
        if (request !== versionsRequest) return;
        el('versiones').replaceChildren();
        const wrapper = document.createElement('div'); wrapper.className = 'table-responsive';
        const table = document.createElement('table'); table.className = 'table table-striped align-middle';
        const head = table.createTHead().insertRow();
        ['Versión', 'Estado', 'Asunto', 'Motivo', 'Cargo', 'Fecha', 'Archivo'].forEach(label => {
            const th = document.createElement('th'); th.scope = 'col'; th.textContent = label; head.append(th);
        });
        const body = table.createTBody();
        rows.forEach(v => {
            const row = body.insertRow();
            ['V' + v.numero, v.estado === 'ORIGINAL' ? 'Original' : 'Firma recibida', v.asunto || '—', v.motivo || '—', v.cargo || '—', v.created_at].forEach(value => { row.insertCell().textContent = value; });
            row.insertCell().append(pdfLink(id, v.numero));
        });
        wrapper.append(table); el('versiones').append(wrapper);
        if (!rows.length) el('versiones').textContent = 'No hay versiones disponibles.';
    }
    async function list() {
        const data = await api('documentos?pagina=' + page); pages = data.paginas;
        el('lista').replaceChildren();
        data.filas.forEach(d => {
            const tr = document.createElement('tr');
            [d.nombre, d.origen + (d.referencia ? ' #' + d.referencia : ''), d.version_actual, d.created_at].forEach(text => {
                const td = document.createElement('td'); td.textContent = text; tr.append(td);
            });
            const actions = document.createElement('td');
            actions.append(pdfLink(d.id, d.version_actual), button('Versiones', () => versions(d.id, d.nombre)), button('Firmar', () => sign(d.id, d.nombre)));
            tr.append(actions); el('lista').append(tr);
        });
        if (!data.filas.length) { const tr = document.createElement('tr'), td = document.createElement('td'); td.colSpan = 5; td.textContent = 'Todavía no hay documentos.'; tr.append(td); el('lista').append(tr); }
        el('pagina').textContent = `${page} / ${pages}`;
        el('anterior').disabled = page <= 1; el('siguiente').disabled = page >= pages;
    }
    function finish() { clearTimeout(timer); operation = null; el('cancelar').hidden = true; }
    async function check() {
        if (!operation || checking) return;
        const current = operation;
        checking = true;
        try {
        const op = await api('operaciones/' + current);
        if (operation !== current) return;
        if (op.estado === 'RECIBIDA') {
            finish();
            el('mensaje').className = 'alert alert-success';
            el('mensaje').textContent = 'Firma concluida: el servidor guardó una nueva versión del PDF.';
            Swal.fire({icon: 'success', title: 'Firma concluida con éxito', text: 'El PDF firmado fue recibido y guardado como una nueva versión.', confirmButtonText: 'Entendido'});
            await list();
        } else if (op.estado !== 'PENDIENTE') {
            finish(); message('Operación ' + op.estado.toLowerCase() + '. Puede iniciar otra firma.');
        } else {
            clearTimeout(timer); timer = setTimeout(() => check().catch(e => message(e.message, true)), 3000);
        }
        } finally { checking = false; }
    }
    async function cancel(confirm = false) {
        if (!operation) return;
        const current = operation;
        if (confirm) {
            clearTimeout(timer);
            const answer = await Swal.fire({icon: 'question', title: '¿Cancelar la operación de firma?', text: 'Las versiones ya guardadas se conservarán.', showCancelButton: true, confirmButtonText: 'Sí, cancelar firma', cancelButtonText: 'Continuar firmando'});
            if (operation !== current) return;
            if (!answer.isConfirmed) { await check(); return; }
        }
        await api(`operaciones/${current}/cancelar`, new FormData());
        // Si el retorno llegó antes de cancelar, mostrar el resultado real del servidor.
        await check();
        await list();
    }
    async function sign(id, nombre) {
        if (preparing) return;
        if (operation) throw new Error('Complete o cancele la firma pendiente antes de iniciar otra.');
        ['asunto', 'motivo', 'cargo'].forEach(field => { el(field).value = el(field).value.trim(); });
        if (!el('opciones').reportValidity()) return;
        if (typeof window.startSignature !== 'function') throw new Error('No se pudo cargar el cliente web de Firma Perú. Recargue la página.');
        preparing = true;
        try {
        const answer = await Swal.fire({icon: 'question', title: '¿Iniciar firma?', text: `Documento: ${nombre}. Asunto: ${el('asunto').value}. Motivo: ${el('motivo').value}. Cargo: ${el('cargo').value}.`, showCancelButton: true, confirmButtonText: 'Sí, iniciar firma', cancelButtonText: 'Revisar datos'});
        if (!answer.isConfirmed) return;
        const op = await api(`documentos/${id}/firmar`, new FormData(el('opciones')));
        operation = op.id; el('cancelar').hidden = false;
        window.signatureInit();
        try { window.startSignature(op.port, op.param_b64); await check(); }
        catch (e) { await cancel(); throw e; }
        } finally { preparing = false; }
    }
    window.signatureInit = () => message('Firma iniciada. Confirme en Firma Perú; esperando el PDF firmado…');
    window.signatureOk = () => check().catch(e => message(e.message, true));
    window.signatureCancel = () => cancel().catch(e => message(e.message, true));
    el('subir').onsubmit = async e => {
        e.preventDefault();
        try { await api('documentos', new FormData(e.currentTarget)); el('subir').reset(); page = 1; await list(); message('PDF incorporado. Ya puede firmarlo.', false, 'success'); }
        catch (err) { message(err.message, true); }
    };
    el('rol').onsubmit = async e => {
        e.preventDefault();
        try { await api('roles/' + el('rol-id').value, new FormData()); page = 1; await list(); message('Rol incorporado al módulo de firma.', false, 'success'); }
        catch (err) { message(err.message, true); }
    };
    el('opciones').onsubmit = e => e.preventDefault();
    el('actualizar').onclick = () => list().then(check).catch(e => message(e.message, true));
    el('cancelar').onclick = () => cancel(true).catch(e => message(e.message, true));
    el('anterior').onclick = () => { if (page > 1) { page--; list().catch(e => message(e.message, true)); } };
    el('siguiente').onclick = () => { if (page < pages) { page++; list().catch(e => message(e.message, true)); } };
    const rol = new URLSearchParams(location.search).get('rol');
    if (/^[1-9]\d*$/.test(rol || '')) { el('rol-id').value = rol; message('Pulse «Incorporar rol de Asistencia» para agregar el documento.'); }
    list().catch(e => message(e.message, true));
})();

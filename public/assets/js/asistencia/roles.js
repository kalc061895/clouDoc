(() => {
    'use strict';
    const app = document.getElementById('roles-app');
    if (!app) return;
    const config = JSON.parse(document.getElementById('roles-config').textContent);
    const form = document.getElementById('roles-filtros');
    const base = app.dataset.base.replace(/\/$/, '');
    const message = document.getElementById('roles-mensaje');
    let csrf = config.csrf;
    let busy = false;
    const params = () => new URLSearchParams(new FormData(form));
    const notify = (text, error = false) => {
        message.className = 'alert ' + (error ? 'alert-danger' : 'alert-info');
        message.textContent = text;
    };
    async function request(path, data = null) {
        const options = {headers: {'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json'}, credentials: 'same-origin'};
        if (data) {
            data.set(csrf.name, csrf.hash);
            options.method = 'POST';
            options.body = data;
        }
        const response = await fetch(base + path, options);
        let json;
        try { json = await response.json(); } catch (_) { throw new Error('La sesión o la solicitud no es válida. Recargue la página e intente nuevamente.'); }
        if (json.data?.csrf) csrf = json.data.csrf;
        if (!response.ok || json.status !== 'success') throw new Error(json.message || 'No se pudo completar la solicitud.');
        return json;
    }
    function lock(value) {
        busy = value;
        form.querySelectorAll('input, select, button').forEach(el => { el.disabled = value; });
    }
    if (app.dataset.modo === 'generacion') {
        const catalogs = config.catalogos;
        const gen = document.getElementById('rol-generar');
        const pdf = document.getElementById('rol-pdf');
        const preview = document.getElementById('roles-preview');
        const summary = document.getElementById('roles-resumen');
        let reviewed = null;
        const field = name => form.elements.namedItem(name);
        function options(name, rows, id, label, empty) {
            const select = field(name);
            select.replaceChildren(new Option(empty, ''));
            const seen = new Set();
            rows.forEach(row => {
                if (!seen.has(String(row[id]))) select.add(new Option(row[label], row[id]));
                seen.add(String(row[id]));
            });
        }
        function oficinas() {
            options('ofi_ide', catalogs.oficinas.filter(o => String(o.ofi_est_ide) === field('est_ide').value && (!field('tofi_ide').value || String(o.ofi_tofi_ide) === field('tofi_ide').value)), 'ofi_ide', 'ofi_nombre', 'Todas las oficinas');
        }
        function servicios() {
            options('uss_ide', catalogs.servicios.filter(s => String(s.est_ide) === field('est_ide').value && (!field('ups_ide').value || String(s.ups_ide) === field('ups_ide').value)), 'uss_ide', 'uss_nombre', 'Todos los servicios');
        }
        form.addEventListener('change', event => {
            if (event.target.name === 'est_ide') {
                oficinas();
                options('ups_ide', catalogs.upss.filter(u => String(u.est_ide) === field('est_ide').value), 'ups_ide', 'ups_nombre', 'Todas las UPSS');
                servicios();
            }
            if (event.target.name === 'tofi_ide') oficinas();
            if (event.target.name === 'ups_ide') servicios();
        });
        form.addEventListener('input', () => {
            reviewed = null;
            gen.disabled = true;
            pdf.hidden = true;
            preview.replaceChildren();
            summary.textContent = 'Filtros modificados. Consulte nuevamente.';
        });
        form.addEventListener('submit', async event => {
            event.preventDefault();
            if (busy || !form.reportValidity()) return;
            const filters = params();
            reviewed = null;
            pdf.hidden = true;
            lock(true);
            notify('Consultando programación…');
            try {
                const {data} = await request('/consultar?' + filters);
                // HTML escapado y construido por la vista del servidor, nunca por datos del navegador.
                preview.innerHTML = data.html;
                summary.textContent = data.cabecera.est_nombre + ' · ' + data.cabecera.ambito + ' · ' + data.personal + ' trabajadores · ' + data.horas + ' horas';
                if (data.personal > 0) reviewed = {filters: filters.toString(), huella: data.huella};
                notify(data.personal > 0 ? 'Revise los turnos y pulse Generar y guardar PDF.' : 'No hay turnos programados para estos filtros.');
            } catch (e) {
                preview.replaceChildren();
                summary.textContent = '';
                notify(e.message, true);
            } finally {
                lock(false);
                gen.disabled = !reviewed;
            }
        });
        gen.addEventListener('click', async () => {
            if (busy || !reviewed) return;
            if (params().toString() !== reviewed.filters) { gen.disabled = true; notify('Consulte nuevamente los filtros seleccionados.', true); return; }
            const data = new URLSearchParams(reviewed.filters);
            data.set('huella', reviewed.huella);
            lock(true);
            notify('Generando y guardando el PDF…');
            try {
                const result = await request('/generar', data);
                pdf.href = result.data.url;
                pdf.hidden = false;
                reviewed = null;
                notify(result.message + ' Documento: ' + result.data.codigo);
            } catch (e) {
                reviewed = null;
                notify(e.message, true);
            } finally {
                lock(false);
                gen.disabled = !reviewed;
            }
        });
    } else {
        const tbody = document.getElementById('roles-historial');
        const prev = document.getElementById('roles-anterior');
        const next = document.getElementById('roles-siguiente');
        let page = 1;
        const cell = (row, text) => { const td = row.insertCell(); td.textContent = text; return td; };
        async function load() {
            if (busy) return;
            const query = params();
            query.set('pagina', page);
            lock(true);
            prev.disabled = next.disabled = true;
            tbody.querySelectorAll('button').forEach(b => { b.disabled = true; });
            notify('Cargando historial…');
            try {
                const {data} = await request('/listar?' + query);
                tbody.replaceChildren();
                data.filas.forEach(rol => {
                    const row = tbody.insertRow();
                    cell(row, rol.codigo + ' / ' + rol.created_at);
                    cell(row, rol.mes + '/' + rol.anio);
                    cell(row, rol.establecimiento + ' / ' + rol.ambito);
                    cell(row, rol.total_personal);
                    cell(row, rol.total_horas);
                    cell(row, rol.estado + (rol.motivo_anulacion ? ': ' + rol.motivo_anulacion : ''));
                    const actions = cell(row, '');
                    const link = document.createElement('a');
                    link.className = 'btn btn-sm btn-outline-primary me-2';
                    link.href = base + '/' + Number(rol.id) + '/pdf';
                    link.target = '_blank'; link.rel = 'noopener'; link.textContent = 'Ver PDF';
                    actions.append(link);
                    if (rol.estado === 'GENERADO') {
                        const button = document.createElement('button');
                        button.type = 'button'; button.className = 'btn btn-sm btn-outline-danger'; button.textContent = 'Anular';
                        button.addEventListener('click', async () => {
                            if (busy) return;
                            const reason = window.prompt('Motivo para anular ' + rol.codigo + ' (5 a 500 caracteres). El PDF original se conservará.');
                            if (reason === null) return;
                            if (reason.trim().length < 5 || reason.trim().length > 500) { notify('El motivo debe tener entre 5 y 500 caracteres.', true); return; }
                            lock(true); button.disabled = true;
                            try {
                                await request('/' + Number(rol.id) + '/anular', new URLSearchParams({motivo: reason}));
                                lock(false);
                                await load();
                            } catch (e) { notify(e.message, true); } finally { lock(false); button.disabled = false; }
                        });
                        actions.append(button);
                    }
                });
                if (!data.filas.length) { const td = cell(tbody.insertRow(), 'No se encontraron documentos.'); td.colSpan = 7; }
                document.getElementById('roles-pagina').textContent = 'Página ' + data.pagina + ' de ' + data.paginas + ' · ' + data.total + ' documentos';
                prev.disabled = page <= 1;
                next.disabled = page >= data.paginas;
                message.className = ''; message.textContent = '';
            } catch (e) { notify(e.message, true); } finally { lock(false); }
        }
        form.addEventListener('submit', event => { event.preventDefault(); if (!busy) { page = 1; load(); } });
        prev.addEventListener('click', () => { if (!busy && page > 1) { page--; load(); } });
        next.addEventListener('click', () => { if (!busy) { page++; load(); } });
        load();
    }
})();

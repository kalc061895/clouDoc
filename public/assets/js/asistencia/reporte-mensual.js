(() => {
    'use strict';
    const app = document.getElementById('reporte-app');
    if (!app) return;
    const form = document.getElementById('reporte-filtros');
    const catalogs = JSON.parse(document.getElementById('reporte-catalogos').textContent);
    const value = key => Number(form.elements.namedItem(key).value) || null;
    const fields = ['dir_ide','red_ide','mic_ide','est_ide','ups_ide','uss_ide','tofi_ide','ofi_ide'];
    const names = {dir_ide:'dir_nombre',red_ide:'red_nombre',mic_ide:'mic_nombre',est_ide:'est_nombre',ups_ide:'ups_nombre',uss_ide:'uss_nombre',tofi_ide:'tofi_nombre',ofi_ide:'ofi_nombre'};
    const redMap = new Map(catalogs.red_ide.map(r => [Number(r.red_ide), r]));
    const micMap = new Map(catalogs.mic_ide.map(r => [Number(r.mic_ide), r]));
    const message = document.getElementById('reporte-mensaje');
    const result = document.getElementById('reporte-resultado');
    const exports = document.getElementById('reporte-exportaciones');
    let revision = 0, pending = null;
    function options(key, rows) {
        const select = form.elements.namedItem(key), old = select.value;
        select.replaceChildren(new Option('Todos', ''));
        rows.forEach(row => select.add(new Option(row[names[key]], row[key])));
        select.value = rows.some(r => String(r[key]) === old) ? old : '';
    }
    function cascade() {
        options('dir_ide', catalogs.dir_ide);
        options('red_ide', catalogs.red_ide.filter(r => !value('dir_ide') || Number(r.red_dir_ide) === value('dir_ide')));
        options('mic_ide', catalogs.mic_ide.filter(r => (!value('red_ide') || Number(r.mic_red_ide) === value('red_ide')) && (!value('dir_ide') || Number(redMap.get(Number(r.mic_red_ide))?.red_dir_ide) === value('dir_ide'))));
        const establishments = catalogs.est_ide.filter(r => {
            const mic = micMap.get(Number(r.est_mic_ide)), red = redMap.get(Number(mic?.mic_red_ide));
            return (!value('dir_ide') || Number(red?.red_dir_ide) === value('dir_ide')) && (!value('red_ide') || Number(mic?.mic_red_ide) === value('red_ide')) && (!value('mic_ide') || Number(r.est_mic_ide) === value('mic_ide'));
        });
        options('est_ide', establishments);
        const estIds = new Set(establishments.filter(e => !value('est_ide') || Number(e.est_ide) === value('est_ide')).map(e => Number(e.est_ide)));
        const eup = catalogs.eup.filter(e => estIds.has(Number(e.eup_est_ide)));
        options('ups_ide', catalogs.ups_ide.filter(u => eup.some(e => Number(e.eup_ups_ide) === Number(u.ups_ide))));
        const eupIds = new Set(eup.filter(e => !value('ups_ide') || Number(e.eup_ups_ide) === value('ups_ide')).map(e => Number(e.eup_ide)));
        options('uss_ide', catalogs.uss_ide.filter(s => catalogs.eus.some(e => eupIds.has(Number(e.eus_eup_ide)) && Number(e.eus_uss_ide) === Number(s.uss_ide))));
        options('tofi_ide', catalogs.tofi_ide);
        options('ofi_ide', catalogs.ofi_ide.filter(o => estIds.has(Number(o.ofi_est_ide)) && (!value('tofi_ide') || Number(o.ofi_tofi_ide) === value('tofi_ide'))));
    }
    form.addEventListener('input', () => {
        revision++; pending?.abort(); exports.classList.add('d-none'); result.replaceChildren();
        message.className = 'alert alert-info'; message.textContent = 'Filtros modificados. Consulte nuevamente.';
    });
    form.addEventListener('change', e => { if (fields.includes(e.target.name)) cascade(); });
    form.addEventListener('submit', async event => {
        event.preventDefault(); if (!form.reportValidity()) return;
        pending?.abort(); pending = new AbortController(); const current = ++revision;
        const query = new URLSearchParams(new FormData(form)).toString();
        exports.classList.add('d-none'); result.replaceChildren();
        message.className = 'alert alert-info'; message.textContent = 'Preparando el reporte mensual…';
        try {
            const response = await fetch(app.dataset.base + '/consultar?' + query, {signal: pending.signal, headers: {'Accept':'application/json', 'X-Requested-With':'XMLHttpRequest'}});
            const data = await response.json();
            if (current !== revision) return;
            if (!response.ok || data.status !== 'success') throw new Error(data.message || 'No se pudo consultar.');
            result.innerHTML = data.data.html; // Vista del servidor con datos escapados.
            message.className = 'alert alert-success'; message.textContent = `Reporte disponible: ${data.data.total} trabajadores. Las exportaciones consultan los datos vigentes con estos mismos filtros.`;
            ['excel','pdf','imprimir'].forEach(format => { document.getElementById('reporte-' + format).href = app.dataset.base + '/' + format + '?' + query; });
            if (data.data.total) exports.classList.remove('d-none');
        } catch (error) {
            if (error.name === 'AbortError' || current !== revision) return;
            message.className = 'alert alert-danger'; message.textContent = error instanceof SyntaxError ? 'La sesión o respuesta no está disponible. Recargue la página.' : error.message;
        }
    });
    cascade();
})();

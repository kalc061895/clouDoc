document.addEventListener('DOMContentLoaded', function () {
    'use strict';
    document.querySelectorAll('.asis-user-photo').forEach(img => {
        const fallback = () => { img.hidden = true; };
        img.addEventListener('error', fallback);
        if (img.complete && img.naturalWidth === 0) fallback();
    });
    const data = document.getElementById('asis-menu-options');
    const modal = document.getElementById('exampleModal');
    const input = document.getElementById('asis-menu-query');
    const results = document.getElementById('asis-menu-results');
    const status = document.getElementById('asis-search-status');
    if (!data || !modal || !input || !results || !status || typeof bootstrap === 'undefined') return;
    const normalize = value => String(value).normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLocaleLowerCase('es').trim();
    const options = JSON.parse(data.textContent).filter(item => {
        try { const url = new URL(item.url, location.href); return url.origin === location.origin && ['http:', 'https:'].includes(url.protocol); }
        catch (_) { return false; }
    }).map(item => ({ ...item, search: normalize(item.nombre + ' ' + item.categoria) }));
    const searchModal = bootstrap.Modal.getOrCreateInstance(modal);
    function render() {
        const terms = normalize(input.value).split(/\s+/).filter(Boolean);
        const matching = options.filter(item => terms.every(term => item.search.includes(term)));
        const visible = matching.slice(0, 30);
        results.replaceChildren();
        status.textContent = matching.length
            ? (terms.length ? matching.length + ' opciones encontradas' : 'Opciones disponibles: ' + matching.length) + (matching.length > 30 ? '. Se muestran 30; escribe para acotar la búsqueda.' : '.')
            : (options.length ? 'No se encontraron opciones. Prueba otro nombre o categoría.' : 'Tu usuario no tiene opciones de menú habilitadas para buscar.');
        visible.forEach(item => {
            const li = document.createElement('li');
            const link = document.createElement('a');
            link.className = 'asis-search-result'; link.href = item.url;
            const icon = document.createElement('i'); icon.className = 'ti ti-layout-grid'; icon.setAttribute('aria-hidden', 'true');
            const text = document.createElement('span'); text.className = 'flex-grow-1';
            const title = document.createElement('strong'); title.textContent = item.nombre;
            const category = document.createElement('small'); category.textContent = item.categoria || 'Menú principal';
            const arrow = document.createElement('i'); arrow.className = 'ti ti-arrow-up-right'; arrow.setAttribute('aria-hidden', 'true');
            text.append(title, category); link.append(icon, text, arrow); li.append(link); results.append(li);
        });
        if (!visible.length) {
            const empty = document.createElement('li'); empty.className = 'asis-search-empty';
            empty.textContent = options.length ? 'Sin coincidencias para tu búsqueda.' : 'No hay funcionalidades disponibles.';
            results.append(empty);
        }
    }
    function open(query = '') { input.value = query; render(); searchModal.show(); }
    document.querySelectorAll('.asis-menu-search-form').forEach(form => {
        const field = form.querySelector('input');
        form.addEventListener('submit', event => { event.preventDefault(); open(field.value); });
        field.addEventListener('input', () => { if (field.value.trim()) open(field.value); });
    });
    modal.addEventListener('shown.bs.modal', () => { render(); input.focus(); input.setSelectionRange?.(input.value.length, input.value.length); });
    modal.addEventListener('hidden.bs.modal', () => {
        input.value = '';
        document.querySelectorAll('.asis-menu-search-form input').forEach(field => { field.value = ''; });
    });
    input.addEventListener('input', render);
    input.addEventListener('keydown', event => {
        const links = results.querySelectorAll('a');
        if (event.key === 'ArrowDown' && links.length) { event.preventDefault(); links[0].focus(); }
        if (event.key === 'ArrowUp' && links.length) { event.preventDefault(); links[links.length - 1].focus(); }
        if (event.key === 'Enter' && links.length) { event.preventDefault(); links[0].click(); }
    });
    results.addEventListener('keydown', event => {
        const links = Array.from(results.querySelectorAll('a'));
        const index = links.indexOf(document.activeElement);
        if (index < 0) return;
        if (event.key === 'ArrowDown') { event.preventDefault(); links[Math.min(index + 1, links.length - 1)].focus(); }
        if (event.key === 'ArrowUp') { event.preventDefault(); if (index === 0) input.focus(); else links[index - 1].focus(); }
    });
    document.addEventListener('keydown', event => {
        if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k' && !document.querySelector('.modal.show:not(#exampleModal)')) { event.preventDefault(); open(input.value); }
    });
    render();
});

document.addEventListener('DOMContentLoaded', function () {
    'use strict';
    const node = document.getElementById('user-appearance-config');
    if (!node) return;
    const config = JSON.parse(node.textContent);
    const status = document.getElementById('appearance-save-status');
    const retry = document.getElementById('appearance-retry');
    const root = document.documentElement;
    const defaults = { Layout: 'vertical', SidebarType: 'full', BoxedLayout: true, Direction: 'ltr', Theme: 'light', ColorTheme: 'Blue_Theme', cardBorder: false };
    const controls = {
        'vertical-layout': ['Layout', 'vertical'], 'horizontal-layout': ['Layout', 'horizontal'],
        'full-sidebar': ['SidebarType', 'full'], 'mini-sidebar': ['SidebarType', 'mini-sidebar'],
        'boxed-layout': ['BoxedLayout', true], 'full-layout': ['BoxedLayout', false],
        'ltr-layout': ['Direction', 'ltr'], 'rtl-layout': ['Direction', 'rtl'],
        'light-layout': ['Theme', 'light'], 'dark-layout': ['Theme', 'dark'],
        'card-with-border': ['cardBorder', true], 'card-without-border': ['cardBorder', false]
    };
    ['Blue_Theme', 'Aqua_Theme', 'Purple_Theme', 'Green_Theme', 'Cyan_Theme', 'Orange_Theme'].forEach(color => { controls[color] = ['ColorTheme', color]; });
    let current = { ...config.settings };
    let saved = config.available ? JSON.stringify(current) : null;
    let busy = false;
    let failed = false;
    function notify(text, error = false) {
        status.textContent = text;
        status.classList.toggle('text-danger', error);
        status.classList.toggle('text-muted', !error);
        retry.hidden = !error;
    }
    function sidebar() {
        // La adaptación de pantalla nunca se persiste como elección del usuario.
        document.body.setAttribute('data-sidebartype', window.innerWidth < 1300 ? 'mini-sidebar' : current.SidebarType);
    }
    function apply() {
        root.setAttribute('data-layout', current.Layout);
        root.setAttribute('data-bs-theme', current.Theme);
        root.setAttribute('data-color-theme', current.ColorTheme);
        root.setAttribute('data-boxed-layout', current.BoxedLayout ? 'boxed' : 'full');
        root.setAttribute('data-card', current.cardBorder ? 'border' : 'shadow');
        root.setAttribute('dir', current.Direction);
        Object.entries(controls).forEach(([id, [key, value]]) => { const el = document.getElementById(id); if (el) el.checked = current[key] === value; });
        document.querySelectorAll('.container-fluid').forEach(el => el.classList.toggle('mw-100', !current.BoxedLayout));
        document.querySelectorAll('.dark-logo, .moon').forEach(el => { el.style.display = current.Theme === 'light' ? '' : 'none'; });
        document.querySelectorAll('.light-logo, .sun').forEach(el => { el.style.display = current.Theme === 'dark' ? '' : 'none'; });
        const panel = document.getElementById('offcanvasExample');
        panel.classList.toggle('offcanvas-start', current.Direction === 'rtl');
        panel.classList.toggle('offcanvas-end', current.Direction !== 'rtl');
        sidebar();
    }
    function token(data) {
        if (!data.csrf) return;
        const old = config.csrf.hash;
        config.csrf = data.csrf;
        // Mantener vigentes los formularios presentes cuando CI regenera el token.
        document.querySelectorAll('input[type="hidden"]').forEach(el => { if (el.name === config.csrf.name && el.value === old) el.value = config.csrf.hash; });
    }
    async function send(snapshot, refreshed = false) {
        const response = await fetch(config.url, {
            method: 'POST', credentials: 'same-origin', keepalive: true,
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', [config.csrf.header]: config.csrf.hash },
            body: snapshot
        });
        if (response.status === 403 && !refreshed) {
            const fresh = await fetch(config.url, { credentials: 'same-origin', cache: 'no-store', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            if (!fresh.ok || fresh.redirected) throw new Error('No se pudo renovar la sesión. Recarga la página.');
            token(await fresh.json());
            return send(snapshot, true);
        }
        if (response.redirected || response.status === 401) throw new Error('Tu sesión terminó. Vuelve a iniciar sesión para guardar.');
        const result = await response.json();
        token(result);
        if (!response.ok) throw new Error(result.message || 'No se pudo guardar tu apariencia.');
    }
    async function save() {
        if (busy || JSON.stringify(current) === saved) return;
        busy = true; failed = false;
        notify('Guardando apariencia…');
        try {
            // Serializar evita que una respuesta antigua sobrescriba el último cambio.
            while (JSON.stringify(current) !== saved) {
                const snapshot = JSON.stringify(current);
                await send(snapshot);
                saved = snapshot;
            }
            notify('Apariencia guardada en tu perfil.');
        } catch (error) {
            failed = true;
            notify(error instanceof TypeError ? 'Sin conexión. Tu apariencia todavía no se guardó.' : error.message, true);
        } finally { busy = false; }
    }
    function select(key, value) {
        if (current[key] === value) return;
        current[key] = value;
        apply();
        void save();
    }
    document.addEventListener('change', event => {
        const choice = controls[event.target.id];
        if (choice && event.target.checked) select(...choice);
    });
    document.addEventListener('click', event => {
        const theme = event.target.closest('.dark-layout, .light-layout');
        if (theme && theme.tagName !== 'INPUT') select('Theme', theme.classList.contains('dark-layout') ? 'dark' : 'light');
        const toggle = event.target.closest('.sidebartoggler');
        if (toggle && window.innerWidth >= 1300) select('SidebarType', document.body.getAttribute('data-sidebartype'));
    });
    document.getElementById('appearance-reset').addEventListener('click', () => { current = { ...defaults }; apply(); void save(); });
    retry.addEventListener('click', () => { void save(); });
    window.addEventListener('online', () => { if (failed) void save(); });
    window.addEventListener('resize', sidebar);
    apply();
});

<script>
document.addEventListener('DOMContentLoaded', () => {
    'use strict';
    const form = document.getElementById('asis-password-form');
    if (!form) return;
    const modal = document.getElementById('asis-password-modal');
    const submit = document.getElementById('asis-password-submit');
    const status = document.getElementById('asis-password-status');
    const config = JSON.parse(document.getElementById('asis-password-config').textContent);
    let saving = false;
    let opening = 0;
    function clearPasswords() {
        // Reset only secrets: form.reset() would restore an expired CSRF token.
        form.querySelectorAll('input[type="password"]').forEach(input => { input.value = ''; });
    }
    function notify(message, ok = false) {
        status.textContent = message;
        status.className = 'alert ' + (ok ? 'alert-success' : 'alert-danger');
    }
    function clearErrors() {
        status.className = 'alert d-none';
        form.querySelectorAll('[data-password-error]').forEach(node => node.remove());
        form.querySelectorAll('input[type="password"]').forEach(input => {
            input.classList.remove('is-invalid');
            input.removeAttribute('aria-invalid');
            input.removeAttribute('aria-describedby');
        });
    }
    function updateToken(result) {
        const csrf = result.meta?.csrf;
        if (!csrf) return;
        document.querySelectorAll('input[type="hidden"]').forEach(input => {
            if (input.name === config.csrfName) input.value = csrf.hash;
        });
    }
    async function read(response) {
        if (response.redirected || response.status === 401) throw new Error('Su sesión terminó. Vuelva a iniciar sesión.');
        if (!(response.headers.get('content-type') || '').includes('application/json')) throw new Error('No se pudo completar la solicitud. Recargue la página e intente nuevamente.');
        const result = await response.json();
        updateToken(result);
        return result;
    }
    async function refreshToken() {
        const response = await fetch(config.tokenUrl, {credentials: 'same-origin', cache: 'no-store', headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}});
        const result = await read(response);
        if (!response.ok) throw new Error(result.message || 'No se pudo renovar el formulario.');
    }
    modal.addEventListener('show.bs.modal', async () => {
        const generation = ++opening;
        clearPasswords(); clearErrors(); submit.disabled = true;
        try { await refreshToken(); }
        catch (error) { if (generation === opening) notify(error.message); }
        finally { if (generation === opening) submit.disabled = false; }
    });
    modal.addEventListener('hide.bs.modal', event => {
        if (saving) event.preventDefault();
    });
    modal.addEventListener('hidden.bs.modal', () => { ++opening; clearPasswords(); clearErrors(); });
    form.addEventListener('submit', async event => {
        event.preventDefault();
        if (saving || !form.reportValidity()) return;
        saving = true; submit.disabled = true; clearErrors(); form.setAttribute('aria-busy', 'true');
        try {
            const response = await fetch(form.action, {method: 'POST', credentials: 'same-origin', headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}, body: new FormData(form)});
            if (response.status === 403) {
                await refreshToken();
                throw new Error('El formulario venció. Presione Guardar contraseña para volver a intentar.');
            }
            const result = await read(response);
            if (!response.ok || !result.success) {
                Object.entries(result.errors || {}).forEach(([name, message]) => {
                    const input = form.elements.namedItem(name);
                    if (!(input instanceof HTMLInputElement) || input.type !== 'password') return;
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback'; feedback.id = input.id + '-error';
                    feedback.dataset.passwordError = 'true'; feedback.textContent = message;
                    input.classList.add('is-invalid'); input.setAttribute('aria-invalid', 'true');
                    input.setAttribute('aria-describedby', feedback.id); input.after(feedback);
                });
                throw new Error(result.message || 'No se pudo cambiar la contraseña.');
            }
            clearPasswords(); notify(result.message, true);
        } catch (error) {
            notify(error instanceof TypeError ? 'No se pudo conectar. Compruebe su conexión antes de volver a intentar.' : error.message);
        } finally {
            saving = false; submit.disabled = false; form.removeAttribute('aria-busy'); status.focus();
        }
    });
});
</script>

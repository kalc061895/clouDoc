<script>
// Separate transport/state from presentation; the latest request owns each select.
function crearCargadorCatalogosPersonal(request, render) {
    const entries = new Map();
    function reset(key, state = 'idle') {
        const previous = entries.get(key);
        const entry = {state};
        entries.set(key, entry);
        if (previous?.xhr) previous.xhr.abort();
        render(key, state, []);
        return entry;
    }
    function load(key, url, onData = () => {}) {
        const entry = reset(key, 'loading');
        const retry = () => load(key, url, onData);
        entry.xhr = request(url).done(response => {
            if (entries.get(key) !== entry) return;
            if (!response || response.status !== 'success' || !Array.isArray(response.data)
                || response.data.some(item => item === null || typeof item !== 'object' || item.id == null || typeof item.nombre !== 'string')) {
                entry.state = 'error'; render(key, 'error', [], retry); return;
            }
            entry.state = response.data.length ? 'ready' : 'empty';
            render(key, entry.state, response.data, retry);
            onData(response.data);
        }).fail((xhr, status) => {
            if (entries.get(key) !== entry || status === 'abort') return;
            entry.state = 'error'; render(key, 'error', [], retry, xhr.status);
        });
    }
    return {load, reset, state: key => entries.get(key)?.state};
}

$(function () {
    const URL_SELECTS = <?= json_encode(base_url('asistencia/personal/select')) ?>;
    const URL_API = <?= json_encode(base_url('asistencia/personal/api/guardar-wizard')) ?>;
    const URL_TOKEN = <?= json_encode(base_url('asistencia/personal/api/token-wizard')) ?>;
    const URL_LIST = <?= json_encode(base_url('asistencia/personal')) ?>;
    const form = $('#wizardPersonal');
    const wizard = $('#example-basic');
    const status = $('#personal-wizard-status');
    let saving = false;
    function notify(message) { status.text(message).removeClass('d-none'); status[0].focus(); }
    function hint(key) {
        let node = document.getElementById(key.slice(1) + '-catalog-status');
        if (!node) {
            node = document.createElement('div'); node.id = key.slice(1) + '-catalog-status';
            node.className = 'form-text'; node.setAttribute('role', 'status'); node.setAttribute('aria-live', 'polite');
            $(key).after(node).attr('aria-describedby', node.id);
        }
        return $(node).empty();
    }
    const catalogs = crearCargadorCatalogosPersonal(
        url => $.ajax({url, dataType: 'json', timeout: 20000, headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}}),
        (key, state, items, retry, code) => {
            const select = $(key).empty().prop('disabled', state !== 'ready').attr('aria-busy', state === 'loading' ? 'true' : 'false');
            const message = hint(key);
            const optional = key === '#pp_col_ide' || key === '#pp_se_ide';
            const placeholders = {loading: 'Cargando opciones…', error: 'No se pudo cargar', idle: key === '#perl_ofi_ide' ? 'Seleccione un establecimiento…' : 'Seleccione una profesión…', empty: optional ? 'NINGUNO' : 'Sin registros disponibles', ready: 'Seleccione…'};
            select.append(new Option(placeholders[state], state === 'empty' && optional ? '0' : ''));
            items.forEach(item => select.append(new Option(item.nombre, String(item.id))));
            if (state === 'loading') {
                message.append($('<span class="spinner-border spinner-border-sm text-primary me-2" aria-hidden="true"></span>'), document.createTextNode('Cargando catálogo…'));
            } else if (state === 'error') {
                message.append($('<span class="text-danger me-2"></span>').text(code === 401 || code === 403 ? 'Su sesión o permisos requieren revisión.' : 'No se pudo cargar el catálogo.'));
                message.append($('<button type="button" class="btn btn-outline-primary btn-sm"></button>').text('Reintentar').on('click', retry));
            } else if (state === 'empty') message.text(optional ? 'No hay opciones asociadas a esta profesión.' : 'No hay opciones disponibles. Consulte con el administrador.');
        }
    );
    function ready(scope) {
        const blocked = $(scope).find('select[id]').toArray().find(select => {
            const state = catalogs.state('#' + select.id);
            return state === 'loading' || state === 'error' || (state === 'empty' && (select.required || select.classList.contains('required')));
        });
        if (!blocked) { status.addClass('d-none'); return true; }
        notify(catalogs.state('#' + blocked.id) === 'loading' ? 'Espere a que terminen de cargar las opciones.' : 'Hay un catálogo pendiente. Revise el aviso junto al campo y vuelva a intentar.');
        return false;
    }
    // Connect labels and expose all required fields consistently, including later steps.
    form.find('input, select, textarea').each(function () {
        if (!this.id) this.id = 'personal-' + this.name;
        const label = $(this).siblings('label'); label.attr('for', this.id);
        if ((this.required || this.classList.contains('required')) && !label.find('.text-danger').length) label.append($('<span class="text-danger" aria-hidden="true"></span>').text(' *'));
    });
    form.find('[name="per_ruc"], [name="perl_fecha_termino"]').siblings('label').append(document.createTextNode(' (opcional)'));
    $.validator.addMethod('fechaPosteriorPersonal', function (value, element, other) {
        return this.optional(element) || !form.find('[name="' + other + '"]').val() || value >= form.find('[name="' + other + '"]').val();
    }, 'La fecha final debe ser igual o posterior a la fecha inicial.');
    const validator = form.validate({
        ignore: ':disabled,:hidden', errorClass: 'is-invalid', validClass: 'is-valid',
        rules: {per_ruc: {digits: true, minlength: 11, maxlength: 11}, per_numero_documento: {digits: true, minlength: 8, maxlength: 15},
            perl_fecha_termino: {fechaPosteriorPersonal: 'perl_fecha_inicio'}, pp_fecha_vencimiento: {fechaPosteriorPersonal: 'pp_fecha_habilitacion'}},
        messages: {per_ruc: 'El RUC debe contener 11 dígitos.', per_numero_documento: 'Ingrese entre 8 y 15 dígitos.'},
        errorPlacement: (error, element) => error.addClass('invalid-feedback').insertAfter(element),
        invalidHandler: () => notify('Revise los campos marcados antes de continuar.')
    });
    wizard.steps({
        headerTag: 'h3', bodyTag: 'section', transitionEffect: 'fade', autoFocus: true,
        labels: {finish: 'Finalizar y registrar', next: 'Siguiente', previous: 'Anterior'},
        onStepChanging: (event, current, next) => {
            if (saving) return false;
            if (next < current) return true;
            validator.settings.ignore = ':disabled,:hidden';
            return ready(wizard.find('section').eq(current)) && form.valid();
        },
        onFinishing: () => {
            validator.settings.ignore = ':disabled';
            if (saving || !ready(form)) return false;
            if (form.valid()) return true;
            const first = validator.errorList[0]?.element;
            if (first) {
                const target = wizard.find('section').index($(first).closest('section'));
                while (wizard.steps('getCurrentIndex') > target) wizard.steps('previous');
                first.focus();
            }
            return false;
        },
        onFinished: guardar
    });
    form.on('submit', event => event.preventDefault());
    catalogs.reset('#perl_ofi_ide'); catalogs.reset('#pp_col_ide'); catalogs.reset('#pp_se_ide');
    form.find('[name="pp_numero_colegiatura"], [name="pp_rne"]').prop('disabled', true);
    [['tipos-documento', '#per_tdi_ide'], ['establecimientos', '#perl_est_ide'], ['modalidades-contrato', '#perl_mco_ide'], ['cargos', '#perl_car_ide'], ['profesiones', '#pp_pro_ide']]
        .forEach(([path, key]) => catalogs.load(key, URL_SELECTS + '/' + path));
    $('#perl_est_ide').on('change', function () {
        if (!this.value) { catalogs.reset('#perl_ofi_ide'); return; }
        catalogs.load('#perl_ofi_ide', URL_SELECTS + '/oficinas?est_ide=' + encodeURIComponent(this.value));
    });
    $('#pp_pro_ide').on('change', function () {
        form.find('[name="pp_numero_colegiatura"], [name="pp_rne"]').val('').prop('disabled', true);
        catalogs.reset('#pp_col_ide'); catalogs.reset('#pp_se_ide');
        if (!this.value) return;
        const id = encodeURIComponent(this.value);
        catalogs.load('#pp_col_ide', URL_SELECTS + '/colegios?pro_ide=' + id, items => form.find('[name="pp_numero_colegiatura"]').prop('disabled', !items.length));
        catalogs.load('#pp_se_ide', URL_SELECTS + '/especialidades?pro_ide=' + id, items => form.find('[name="pp_rne"]').prop('disabled', !items.length));
    });
    function csrf(response) {
        const token = response.csrf;
        if (token) form.find('input[type="hidden"]').filter(function () { return this.name === token.name; }).val(token.hash);
    }
    function errorText(xhr) {
        if (xhr.status === 401 || xhr.responseText?.includes('<html')) return 'Su sesión terminó. Vuelva a iniciar sesión en otra pestaña y conserve este formulario.';
        if (xhr.status === 403) return 'No se autorizó el envío. Compruebe su sesión y permisos antes de volver a intentar.';
        const data = xhr.responseJSON || {};
        const errors = data.errors || data.messages;
        if (errors && typeof errors === 'object') return Object.values(errors).join('\n');
        return data.message || 'No se pudo confirmar el registro. Compruebe su conexión y el listado antes de volver a enviar.';
    }
    async function guardar() {
        if (saving || !ready(form)) return;
        saving = true;
        try {
            const result = await Swal.fire({
                title: '¿Confirmar registro de personal?', text: 'Se guardarán los datos personales, laborales y profesionales.', icon: 'question',
                showCancelButton: true, confirmButtonText: 'Sí, registrar', cancelButtonText: 'Cancelar', showLoaderOnConfirm: true,
                allowOutsideClick: () => !Swal.isLoading(), allowEscapeKey: () => !Swal.isLoading(),
                preConfirm: async () => {
                    form.attr('aria-busy', 'true');
                    try {
                        // Renew before a mutation; appearance settings can rotate the shared token.
                        const token = await $.ajax({url: URL_TOKEN, dataType: 'json', cache: false,
                            headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}});
                        csrf(token);
                        const payload = Object.fromEntries(form.serializeArray().map(item => [item.name, item.value]));
                        const response = await $.ajax({url: URL_API, type: 'POST', contentType: 'application/json', dataType: 'json',
                            headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}, data: JSON.stringify(payload)});
                        csrf(response);
                        if (response.status !== 'success') throw {responseJSON: response};
                        return response;
                    } catch (xhr) {
                        csrf(xhr.responseJSON || {});
                        // SweetAlert renders validation messages as HTML: escape server-provided text.
                        const safe = document.createElement('div'); safe.textContent = errorText(xhr);
                        Swal.showValidationMessage(safe.innerHTML);
                        return false;
                    } finally { form.removeAttr('aria-busy'); }
                }
            });
            if (result.isConfirmed && result.value?.status === 'success') {
                notify('Personal registrado correctamente. Abriendo el listado…');
                window.location.assign(URL_LIST);
                return;
            }
        } finally { saving = false; }
    }
});
</script>

(function ($) {
    'use strict';
    const raiz = '.vacaciones-panel';

    function actualizarDias(form) {
        const opcion = form.find('[name="periodo"] :selected');
        const inicio = form.find('[name="fecha_inicio"]');
        const fin = form.find('[name="fecha_fin"]');
        const desde = opcion.data('desde') || '';
        inicio.attr('min', desde);
        fin.attr('min', inicio.val() || desde);
        const saldo = Number(opcion.data('saldo') || 0) + (opcion.val() === form.data('periodoOriginal') ? form.data('diasOriginales') : 0);
        const dias = (Date.parse(fin.val() + 'T00:00:00Z') - Date.parse(inicio.val() + 'T00:00:00Z')) / 86400000 + 1;
        let mensaje = 'Se cuentan días calendario, incluyendo las fechas de inicio y fin.';
        if (opcion.val()) mensaje = 'Saldo para este uso: ' + saldo + ' días.';
        if (Number.isFinite(dias)) mensaje += ' Días solicitados: ' + dias + '.';
        form.find('.vac-resumen').text(mensaje);
    }

    function csrf(panel, datos) {
        const token = panel.children('input[type="hidden"]')[0];
        datos.append(token.name, token.value);
    }

    function solicitar(panel, url, datos) {
        csrf(panel, datos);
        return $.ajax({ url, type: 'POST', data: datos, processData: false, contentType: false, dataType: 'json' })
            .always(function () {
                const xhr = arguments[2]?.getResponseHeader ? arguments[2] : arguments[0];
                const renovado = xhr.getResponseHeader('X-Vacacion-CSRF');
                if (renovado) panel.children('input[type="hidden"]').val(renovado);
            });
    }

    function recargar(panel) {
        if (!$.contains(document, panel[0])) return;
        const mes = panel.find('.vac-filtro').val();
        if (panel.closest('#modalGestionPersonal').length && typeof cargarContenidoTab === 'function') {
            cargarContenidoTab(panel.data('personal'), 'pane-vacaciones', { mes });
            return;
        }
        const url = new URL(window.location.href);
        if (mes) url.searchParams.set('mes', mes);
        else url.searchParams.delete('mes');
        window.location.assign(url);
    }

    $(document).on('change', raiz + ' .vac-filtro', function () {
        const mes = this.value;
        const panel = $(this).closest('.vacaciones-panel');
        let visibles = 0;
        panel.find('.vac-uso').each(function () {
            const coincide = !mes || (this.dataset.inicio.slice(0, 7) <= mes && this.dataset.fin.slice(0, 7) >= mes);
            this.hidden = !coincide;
            if (coincide) visibles++;
        });
        panel.find('.vac-sin-usos').toggleClass('d-none', visibles > 0);
    }).on('click', raiz + ' .vac-limpiar', function () {
        $(this).closest('.vacaciones-panel').find('.vac-filtro').val('').trigger('change');
    });

    $(document).on('click', raiz + ' .vac-nuevo, ' + raiz + ' .vac-editar', function () {
        const boton = this;
        const panel = $(boton).closest('.vacaciones-panel');
        const elemento = panel.find('.vac-modal')[0];
        const form = $(elemento).find('form');
        const edicion = Boolean(boton.dataset.uso);
        const periodo = boton.dataset.periodo || '';
        const dias = Number(boton.dataset.dias || 0);
        form[0].reset();
        form.data({ panel, uso: boton.dataset.uso || '', periodoOriginal: periodo, diasOriginales: dias, guardando: false });
        form.find('.vac-error').addClass('d-none').text('');
        form.find('[name="periodo"] option[data-saldo]').each(function () {
            this.disabled = Number(this.dataset.saldo) + (this.value === periodo ? dias : 0) <= 0;
        });
        if (edicion) {
            const fila = boton.closest('.vac-uso').dataset;
            for (const [nombre, valor] of Object.entries({
                periodo, fecha_inicio: fila.inicio, fecha_fin: fila.fin,
                documento: boton.dataset.documento, observacion: boton.dataset.observacion,
            })) form.find('[name="' + nombre + '"]').val(valor);
        }
        actualizarDias(form);
        $(elemento).find('.modal-title').text(edicion ? 'Editar uso de vacaciones' : 'Registrar uso de vacaciones');
        const anidado = panel.closest('.modal').hasClass('show');
        if (anidado) elemento.style.zIndex = '1070';
        elemento.addEventListener('hidden.bs.modal', () => {
            bootstrap.Modal.getInstance(elemento)?.dispose();
            if ($.contains(document, panel[0])) {
                $(elemento).appendTo(panel);
                if (panel.closest('.modal').hasClass('show')) document.body.classList.add('modal-open');
                boton.focus();
            } else {
                elemento.remove();
            }
        }, { once: true });
        document.body.append(elemento);
        bootstrap.Modal.getOrCreateInstance(elemento).show(boton);
        if (anidado) $('.modal-backdrop').last().css('z-index', '1065');
    }).on('change', '.vac-modal .form-uso-vacaciones select, .vac-modal .form-uso-vacaciones input[type="date"]', function () {
        actualizarDias($(this).closest('form'));
    });

    $(document).on('submit', '.vac-modal .form-uso-vacaciones', function (e) {
        e.preventDefault();
        const form = $(this);
        if (form.data('guardando') || form.find('button[type="submit"]').prop('disabled') || !this.reportValidity()) return;
        const panel = form.data('panel');
        const modal = form.closest('.vac-modal')[0];
        const uso = form.data('uso');
        const url = panel.data('url') + (uso ? '/' + uso + '/editar' : '');
        const botones = form.find('button').add($(modal).find('.btn-close'));
        const campos = form.find('input, select, textarea');
        const datos = new FormData(this);
        form.data('guardando', true);
        botones.prop('disabled', true);
        campos.prop('disabled', true);
        form.attr('aria-busy', 'true');
        form.find('button[type="submit"] .spinner-border').removeClass('d-none');
        form.find('.vac-guardar-texto').text(uso ? 'Guardando cambios…' : 'Registrando uso…');
        form.find('.vac-error').addClass('d-none');
        solicitar(panel, url, datos).done(function (res) {
            toastr.success(res.message);
            modal.addEventListener('hidden.bs.modal', () => recargar(panel), { once: true });
            bootstrap.Modal.getInstance(modal).hide();
        }).fail(function (xhr) {
            const mensaje = xhr.responseJSON?.message || 'No se pudo guardar el uso. Si la sesión expiró, vuelva a abrir la pestaña.';
            form.find('.vac-error').removeClass('d-none').text(mensaje);
            toastr.error(mensaje, '', { escapeHtml: true });
        }).always(function () {
            form.data('guardando', false);
            form.removeAttr('aria-busy');
            form.find('button[type="submit"] .spinner-border').addClass('d-none');
            form.find('.vac-guardar-texto').text('Guardar uso');
            botones.prop('disabled', false);
            campos.prop('disabled', false);
        });
    });

    $(document).on('click', raiz + ' .vac-eliminar', function () {
        const panel = $(this).closest('.vacaciones-panel');
        const uso = this.dataset.uso;
        Swal.fire({
            title: 'Eliminar uso de vacaciones',
            text: 'Los días se devolverán al saldo del periodo. Indique el motivo.',
            icon: 'warning', input: 'textarea', inputAttributes: { maxlength: '2000' },
            target: document.querySelector('#modalGestionPersonal.show') || document.body,
            heightAuto: false, showCancelButton: true, confirmButtonText: 'Eliminar y devolver saldo', cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true, allowOutsideClick: () => !Swal.isLoading(),
            inputValidator: value => !value?.trim() ? 'Indique el motivo.' : undefined,
            preConfirm: function (motivo) {
                const datos = new FormData();
                datos.append('motivo', motivo.trim());
                return solicitar(panel, panel.data('url') + '/' + uso + '/eliminar', datos)
                    .catch(xhr => {
                        const mensaje = xhr.responseJSON?.message || 'No se pudo eliminar el uso.';
                        const texto = document.createElement('span');
                        texto.textContent = mensaje;
                        Swal.showValidationMessage(texto.innerHTML);
                        toastr.error(mensaje, '', { escapeHtml: true });
                    });
            },
        }).then(function (result) {
            if (result.isConfirmed && result.value) {
                toastr.success(result.value.message);
                recargar(panel);
            }
        });
    });
})(jQuery);

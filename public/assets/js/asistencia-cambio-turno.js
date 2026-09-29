(function ($) {
    'use strict';
    const root = '#modalGestionPersonal';

    $(document).on('click', `${root} .ct-anexos`, function () {
        Swal.fire({
            title: 'Documentos anexos',
            html: $(this).siblings('.ct-lista-anexos').html(),
            target: document.querySelector(`${root}.show`) || document.body,
            heightAuto: false, width: 420, confirmButtonText: 'Cerrar'
        });
    });

    $(document).on('click', `${root} .ct-eliminar`, function () {
        const boton = $(this);
        const panel = boton.closest('.cambio-turno-panel');
        const id = panel.data('personal');
        const cambio = boton.data('cambio');
        const fecha = `${panel.find('.calendario-anio').val()}-${panel.find('.calendario-mes').val()}-01`;
        Swal.fire({
            title: 'Eliminar cambio de turno',
            text: 'Se restablecerán los turnos originales de ambos trabajadores. Indique el motivo de eliminación.',
            icon: 'warning', input: 'textarea', inputAttributes: { maxlength: '2000', rows: '3' },
            target: document.querySelector(`${root}.show`) || document.body,
            heightAuto: false, showCancelButton: true, showLoaderOnConfirm: true,
            confirmButtonText: 'Eliminar y restablecer', cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
            inputValidator: value => !value?.trim() ? 'Debe indicar un motivo.' : undefined,
            allowOutsideClick: () => !Swal.isLoading(),
            allowEscapeKey: () => !Swal.isLoading(),
            preConfirm: function (motivo) {
                const data = new FormData();
                data.append('motivo_cambio', motivo.trim());
                panel.find('.form-cambio-turno input[type="hidden"]').each(function () {
                    if (this.name !== 'version_sol' && this.name !== 'version_ace') data.append(this.name, this.value);
                });
                return $.ajax({ url: `${panel.data('url')}/eliminar/${cambio}`, type: 'POST', data: data,
                    processData: false, contentType: false, dataType: 'json'
                }).catch(function (xhr) {
                    Swal.showValidationMessage(xhr.responseJSON?.message || 'No se pudo eliminar el cambio.');
                });
            }
        }).then(function (result) {
            if (result.isConfirmed && result.value) {
                toastr.success(result.value.message);
                if ($.contains(document, panel[0])) cargarContenidoTab(id, 'pane-cambio-turno', { fecha_inicio: fecha });
            }
        });
    });

    function actualizar(form) {
        const sol = form.find('[name="prog_sol_id"]');
        const ace = form.find('[name="prog_ace_id"]');
        const valido = !!(sol.val() && ace.val() && !sol.prop('disabled') && !ace.prop('disabled'));
        form.find('.ct-guardar').prop('disabled', !valido || !!form.data('guardando'));
        form.find('.ct-resumen').toggleClass('d-none', !valido).text(valido
            ? `El solicitante asumirá ${form.find('#ct-fecha-ace').val()} (${ace.find(':selected').text()}). El compañero asumirá ${form.find('#ct-fecha-sol').val()} (${sol.find(':selected').text()}).`
            : '');
    }

    function cargar(form, lado) {
        const panel = form.closest('.cambio-turno-panel');
        const select = form.find(`[name="prog_${lado}_id"]`);
        const anterior = select.data('peticion');
        if (anterior) anterior.abort();
        const id = lado === 'sol' ? panel.data('personal') : form.find('[name="otro_personal_id"]').val();
        const fecha = form.find(`.ct-fecha[data-lado="${lado}"]`).val();
        select.empty().append(new Option('Seleccione trabajador y fecha', '')).prop('disabled', true);
        form.find(`[name="version_${lado}"]`).val('');
        actualizar(form);
        if (!id || !fecha) return;
        select.empty().append(new Option('Cargando turnos...', ''));
        select.data('peticion', $.ajax({
            url: `${panel.data('url')}/turnos`, data: { personal_id: id, fecha: fecha }, dataType: 'json'
        }).done(function (res) {
            if (!$.contains(document, form[0])) return;
            select.empty().append(new Option(res.data.length ? 'Seleccione turno programado' : 'Sin turnos programados en esta fecha', ''));
            res.data.forEach(function (t) {
                const opcion = new Option(`${t.tur_codigo} / ${t.th_codigo} · ${t.th_hora_ingreso.substring(0, 5)}–${t.th_hora_salida.substring(0, 5)}`, t.prog_ide);
                $(opcion).attr('data-version', t.version);
                select.append(opcion);
            });
            select.prop('disabled', !res.data.length);
        }).fail(function (xhr, status) {
            if (status === 'abort') return;
            select.empty().append(new Option('No se pudieron cargar los turnos', ''));
            form.find('.ct-error').removeClass('d-none').text(xhr.responseJSON?.message || 'No se pudieron consultar los turnos.');
        }).always(function () { actualizar(form); }));
    }

    $(document).on('change', `${root} .ct-fecha, ${root} .ct-personal`, function () {
        const form = $(this).closest('form');
        form.find('.ct-error').addClass('d-none');
        cargar(form, $(this).data('lado') || 'ace');
    });
    $(document).on('change', `${root} .ct-turno`, function () {
        const form = $(this).closest('form');
        form.find(`[name="version_${$(this).data('lado')}"]`).val($(this).find(':selected').attr('data-version') || '');
        actualizar(form);
    });
    $(document).on('submit', `${root} .form-cambio-turno`, function (event) {
        event.preventDefault();
        const form = $(this);
        if (form.data('guardando') || !this.reportValidity() || form.find('.ct-guardar').prop('disabled')) return;
        const panel = form.closest('.cambio-turno-panel');
        const id = panel.data('personal');
        const fecha = form.find('#ct-fecha-sol').val();
        const data = new FormData(this);
        form.data('guardando', true);
        form.find('.ct-error').addClass('d-none');
        // Mantener la selección visible mientras se aplica el intercambio.
        form.find(':input').prop('disabled', true);
        $.ajax({ url: `${panel.data('url')}/guardar`, type: 'POST', data: data,
            processData: false, contentType: false, dataType: 'json'
        }).done(function (res) {
            toastr.success(res.message);
            if ($.contains(document, form[0])) {
                cargarContenidoTab(id, 'pane-cambio-turno', { fecha_inicio: fecha });
            }
        }).fail(function (xhr) {
            form.find('.ct-error').removeClass('d-none').text(xhr.responseJSON?.message || 'No se pudo registrar el cambio.');
        }).always(function () {
            form.data('guardando', false);
            form.find(':input').prop('disabled', false);
            actualizar(form);
        });
    });
})(jQuery);

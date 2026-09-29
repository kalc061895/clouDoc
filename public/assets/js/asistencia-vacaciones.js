(function ($) {
    'use strict';
    $(document).on('change', '#modalGestionPersonal .form-uso-vacaciones select, #modalGestionPersonal .form-uso-vacaciones input[type="date"]', function () {
        const form = $(this).closest('form');
        const opt = form.find('[name="periodo"] :selected');
        form.find('[name="fecha_inicio"], [name="fecha_fin"]').attr('min', opt.data('desde') || '');
        const inicio = form.find('[name="fecha_inicio"]').val();
        const fin = form.find('[name="fecha_fin"]').val();
        if (inicio && fin) {
            const dias = Math.round((Date.parse(fin + 'T00:00:00Z') - Date.parse(inicio + 'T00:00:00Z')) / 86400000) + 1;
            form.find('.vac-resumen').text(dias > 0 ? `Días solicitados: ${dias}. Saldo del periodo: ${opt.data('saldo') ?? 0}.` : 'Revise el orden de las fechas.');
        }
    });
    $(document).on('submit', '#modalGestionPersonal .form-uso-vacaciones', function (e) {
        e.preventDefault();
        const form = $(this);
        if (form.data('guardando') || !this.reportValidity()) return;
        const panel = form.closest('.vacaciones-panel');
        form.data('guardando', true).find('button[type="submit"]').prop('disabled', true);
        form.find('.vac-error').addClass('d-none');
        $.ajax({url: panel.data('url'), type: 'POST', data: new FormData(this), processData: false, contentType: false, dataType: 'json'})
            .done(function (res) {
                toastr.success(res.message);
                if ($.contains(document, panel[0])) cargarContenidoTab(panel.data('personal'), 'pane-vacaciones');
            }).fail(function (xhr) {
                form.find('.vac-error').removeClass('d-none').text(xhr.responseJSON?.message || 'No se pudo registrar el uso.');
            }).always(function () { form.data('guardando', false).find('button[type="submit"]').prop('disabled', false); });
    });
    $(document).on('click', '#modalGestionPersonal .vac-eliminar', function () {
        const panel = $(this).closest('.vacaciones-panel');
        const uso = $(this).data('uso');
        Swal.fire({
            title: 'Eliminar uso de vacaciones',
            text: 'Los días se devolverán al saldo del periodo. Indique el motivo.',
            icon: 'warning', input: 'textarea', inputAttributes: { maxlength: '2000' },
            target: document.querySelector('#modalGestionPersonal.show') || document.body,
            heightAuto: false, showCancelButton: true, confirmButtonText: 'Eliminar y devolver saldo', cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true, allowOutsideClick: () => !Swal.isLoading(),
            inputValidator: value => !value?.trim() ? 'Indique el motivo.' : undefined,
            preConfirm: function (motivo) {
                const data = new FormData();
                data.append('motivo', motivo.trim());
                panel.find('input[type="hidden"]').each(function () { data.append(this.name, this.value); });
                return $.ajax({url: `${panel.data('url')}/${uso}/eliminar`, type: 'POST', data: data,
                    processData: false, contentType: false, dataType: 'json'
                }).catch(xhr => Swal.showValidationMessage(xhr.responseJSON?.message || 'No se pudo eliminar el uso.'));
            }
        }).then(function (result) {
            if (result.isConfirmed && result.value) {
                toastr.success(result.value.message);
                if ($.contains(document, panel[0])) cargarContenidoTab(panel.data('personal'), 'pane-vacaciones');
            }
        });
    });
})(jQuery);

$(document).ready(function () {

    function verificarNotificaciones() {

        $.get(URLNotification + 'notificaciones/pendientes', function (response) {

            response.data.forEach(item => {

                mostrarNotificacion(item);

            });

        });

        $.get(URLNotification + 'notificaciones/contador', function (response) {

            $('#contadorNotificaciones')
                .text(response.total);

        });

    }

    function cargarNotificaciones() {
        $.get(
            URLNotification + 'notificaciones/listado',
            function (response) {

                let html = '';

                icons = {
                    'alert': 'bell-bing',
                    'mail': 'letter',
                    'calendar': 'calendar',
                    'settings': 'settings',
                    'info': 'info',
                    'default': 'notification',
                    'success': 'check',
                    'warning': 'warning',
                    'error': 'error',
                    'danger': 'error'

                };
                class_colors = {
                    'success': 'bg-success-subtle text-success',
                    'warning': 'bg-warning-subtle text-warning',
                    'error': 'bg-danger-subtle text-danger',
                    'danger': 'bg-danger-subtle text-danger',
                    'info': 'bg-info-subtle text-info'
                };
                response.items.forEach(item => {

                    html += `
                <a href="${item.url ?? '#'}"
                   class="p-3 pe-0 border-bottom d-flex align-items-center dropdown-item gap-3">

                    <span class="flex-shrink-0 ${class_colors[item.not_tipo]} rounded-circle round d-flex align-items-center justify-content-center">

                        <iconify-icon
                            icon="solar:${icons[item.not_icono] ?? 'info'}-line-duotone"></iconify-icon>
                        </iconify-icon>

                    </span>

                    <div class="w-75">

                        <div class="d-flex align-items-center justify-content-between">

                            <h6 class="mb-1 fw-semibold">
                                ${item.not_titulo}
                            </h6>

                        </div>

                        <span class="d-block text-truncate fs-11">
                            ${item.not_mensaje}
                        </span>

                    </div>

                </a>
                `;
                });

                $('.contenedorNotificaciones').html(html);


                if (response.total > 0) {
                    $('#notifyBadge').show();
                } else {
                    $('#notifyBadge').hide();
                }
            }
        );
    }

    function verificarToast() {
        $.get(
            URLNotification + "notificaciones/pendientes",
            function (response) {

                response.data.forEach(item => {
                    $info = {
                        not_titulo: item.not_titulo,
                        not_mensaje: item.not_mensaje,
                        not_icono: item.not_icono,
                        not_url: item.not_url,
                        not_prioridad: item.not_prioridad
                    };
                    mostrarNotificacion(
                        $info
                    );

                });

            }
        );
    }

    function mostrarNotificacion(item) {
        toastr.options = {
            positionClass: "toastr toast-bottom-right",
            containerId: "toast-bottom-right",
            closeButton: true,
            progressBar: true,
            newestOnTop: true,
            preventDuplicates: false,
            showMethod: "fadeIn",
            hideMethod: "fadeOut",
            timeOut: 5000
        };
        switch (item.not_icono) {
            case 'success':

                toastr.success(
                    item.not_mensaje,
                    item.not_titulo,

                );

                break;

            case 'warning':

                toastr.warning(
                    item.not_mensaje,
                    item.not_titulo,

                );

                break;

            case 'error': case 'danger':

                toastr.error(
                    item.not_mensaje,
                    item.not_titulo,

                );

                break;

            default:

                toastr.info(
                    item.not_mensaje,
                    item.not_titulo,

                );

        }
    }


    cargarNotificaciones();
    verificarToast();

    setInterval(function () {

        cargarNotificaciones();

        verificarToast();

    }, 5000);


});
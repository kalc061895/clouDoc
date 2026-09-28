<div class="card shadow-sm border-0">
    <div class="card-header text-bg-primary">
        <h5 class="mb-0 text-white card-title"><i class="fas fa-user"></i> Datos Personales del Postulante</h5>
    </div>
    <div class="card-body">
        <form id="form-datos-personales">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label font-weight-bold">Tipo Documento (*)</label>
                    <select name="pos_tdo_ide" id="pos_tdo_ide" class="form-select" required>
                        <option value="">Cargando...</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label font-weight-bold">N° Documento (*)</label>
                    <input type="text" name="pos_documento" id="pos_documento" class="form-control" maxlength="30" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label font-weight-bold">Nombres (*)</label>
                    <input type="text" name="pos_nombres" id="pos_nombres" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label font-weight-bold">Apellido Paterno (*)</label>
                    <input type="text" name="pos_apellido_paterno" id="pos_apellido_paterno" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Apellido Materno</label>
                    <input type="text" name="pos_apellido_materno" id="pos_apellido_materno" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fecha de Nacimiento</label>
                    <input type="date" name="pos_fecha_nacimiento" id="pos_fecha_nacimiento" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Sexo</label>
                    <select name="pos_sexo" id="pos_sexo" class="form-select" required>
                        <option value="">-- Seleccionar --</option>
                        <option value="MASCULINO">MASCULINO</option>
                        <option value="FEMENINO">FEMENINO</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Teléfono / Celular</label>
                    <input type="text" name="pos_telefono" id="pos_telefono" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Correo Electrónico (*)</label>
                    <input type="email" name="pos_email" id="pos_email" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Dirección de Domicilio</label>
                    <input type="text" name="pos_direccion" id="pos_direccion" class="form-control" required>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success" id="btn-guardar">
                    <i class="fas fa-save"></i> Guardar y Continuar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {

        // Toast Config Standard
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        Toast.fire({
            icon: 'info',
            title: 'Cargando sección de Datos Personales...'
        });

        // 1. Cargar combo Tipos de Documento
        function cargarTiposDocumento() {
            return $.ajax({
                url: '<?= base_url('seleccion/api/tipos-documento') ?>',
                type: 'GET',
                dataType: 'json'
            }).done(function(res) {
                res = res.data;
                let options = '<option value="">-- Seleccione --</option>';
                // Adapta 'tdo_ide' y 'tdo_nombre' según tu API
                $.each(res, function(i, item) {
                    options += `<option value="${item.tdo_ide}">${item.tdo_codigo || item.nombre}</option>`;
                });
                $('#pos_tdo_ide').html(options);
            }).fail(function() {
                Toast.fire({
                    icon: 'error',
                    title: 'Error al cargar tipos de documento'
                });
            });
        }

        // Cargar Datos del Postulante pasando la convocatoria
        function verificarYcargarDatos() {
            let convocatoriaId = $('#convocatoria_id').val();

            // 1. Mostrar el SweetAlert de carga antes de hacer la petición
            Swal.fire({
                title: 'Cargando datos...',
                text: 'Por favor espere un momento.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '<?= base_url('seleccion/postulacion/postulante/ver-datos') ?>',
                type: 'POST',
                data: {
                    convocatoria_id: convocatoriaId
                },
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).done(function(res) {
                if (res.status === 'success' && res.postulante) {
                    const d = res.postulante;

                    $('#pos_tdo_ide').val(d.pos_tdo_ide);
                    $('#pos_documento').val(d.pos_documento);
                    $('#pos_nombres').val(d.pos_nombres);
                    $('#pos_apellido_paterno').val(d.pos_apellido_paterno);
                    $('#pos_apellido_materno').val(d.pos_apellido_materno);
                    $('#pos_fecha_nacimiento').val(d.pos_fecha_nacimiento);
                    $('#pos_sexo').val(d.pos_sexo);
                    $('#pos_telefono').val(d.pos_telefono);
                    $('#pos_email').val(d.pos_email);
                    $('#pos_direccion').val(d.pos_direccion);

                    // Verificamos si ya inició postulación a esta convocatoria
                    if (res.postulacion) {
                        Toast.fire({
                            icon: 'info',
                            title: `Postulación en proceso (Código: ${res.postulacion.pto_codigo})`
                        });
                    } else {
                        Toast.fire({
                            icon: 'success',
                            title: 'Datos personales cargados.'
                        });
                    }
                    // Cerramos el loading si todo salió bien (opcional si usas Toast)
                    Swal.close();
                } else {
                    // Si el servidor responde pero los datos están incompletos, 
                    // reemplazamos el loading por la alerta de advertencia.
                    Swal.fire({
                        icon: 'warning',
                        title: 'Ficha Incompleta',
                        text: 'Complete sus datos personales para iniciar la postulación a esta convocatoria.',
                        confirmButtonText: 'Entendido'
                    });
                }
            }).fail(function() {
                // Manejo de errores por si falla la petición AJAX
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al intentar cargar los datos.',
                    confirmButtonText: 'Cerrar'
                });
            });
        }
        // Ejecutar carga inicial en secuencia
        cargarTiposDocumento().then(function() {
            verificarYcargarDatos();
        });

        // 3. Procesar Formulario vía POST
        $('#form-datos-personales').on('submit', function(e) {
            e.preventDefault();

            let btn = $('#btn-guardar');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

            $.ajax({
                url: '<?= base_url('seleccion/postulacion/postulante/guardar-datos') ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).done(function(res) {
                if (res.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Guardado!',
                        text: res.message,
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Si existe función global para avanzar de paso parcial, ejecutarla aquí
                    if (typeof cargarSiguientePaso === 'function') {
                        cargarSiguientePaso();
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de Validación',
                        text: res.message
                    });
                }
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un fallo en el servidor. Intente nuevamente.'
                });
            }).always(function() {
                btn.prop('disabled', false).html('<i class="fas fa-save"></i> Guardar y Continuar');
            });
        });

    });
</script>
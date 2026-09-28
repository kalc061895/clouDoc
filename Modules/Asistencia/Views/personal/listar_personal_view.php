<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?> Padrón de Personal
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="card-title mb-0">Padrón General de Personal</h4>
                <h6 class="card-subtitle text-muted small">Gestión de datos personales, laborales y asistenciales de la
                    Red San Román.</h6>
            </div>
            <a href="<?= base_url('asistencia/personal/nuevo') ?>" class="btn btn-primary d-flex align-items-center">
                <iconify-icon icon="solar:user-plus-bold" class="me-2" style="font-size: 1.2rem;"></iconify-icon>
                Registrar Nuevo
            </a>
        </div>

        <div class="table-responsive">
            <table id="tablaPersonal" class="table table-hover table-bordered align-middle w-100">
                <thead>
                    <tr>
                        <th style="width: 8%;">Código</th>
                        <th style="width: 12%;">N° Documento</th>
                        <th>Apellidos y Nombres</th>
                        <th>Establecimiento</th>
                        <th>Cargo</th>
                        <th style="width: 10%;" class="text-center">Estado</th>
                        <th style="width: 12%;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Se alimenta dinámicamente vía AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script>
    const URL_BASE = '<?= base_url('asistencia/personal') ?>';

    $(document).ready(function () {
        // Inicialización optimizada de DataTables
        const tabla = $('#tablaPersonal').DataTable({
            ajax: {
                url: `${URL_BASE}/api/listar`,
                dataSrc: 'data'
            },
            columns: [
                { data: 'codigo' },
                { data: 'documento' },
                { data: 'nombre_completo' },
                { data: 'establecimiento' },
                { data: 'cargo', defaultContent: '<span class="text-muted small">NO ASIGNADO</span>' },
                {
                    data: 'estado',
                    className: 'text-center',
                    render: function (data) {
                        return data == 1
                            ? '<span class="badge bg-light-success text-success fw-bold px-2 py-1">ACTIVO</span>'
                            : '<span class="badge bg-light-danger text-danger fw-bold px-2 py-1">DE BAJA</span>';
                    }
                },
                {
                    data: 'id',
                    className: 'text-center',
                    orderable: false,
                    render: function (data, type, row) {
                        let btnBaja = '';

                        // Si está activo, mostramos el botón para dar de baja
                        if (row.estado == 1) {
                            btnBaja = `
                                <button class="btn btn-sm btn-light-danger text-danger p-1" onclick="confirmarBaja(${data})" title="Dar de Baja">
                                    <iconify-icon icon="solar:user-block-rounded-bold" style="font-size: 1.1rem; vertical-align: middle;"></iconify-icon>
                                </button>
                            `;
                        }

                        return `
                            <div class="btn-group" role="group">
                                <a href="${URL_BASE}/ver/${data}" class="btn btn-sm btn-light-info text-info p-1" title="Ver Ficha">
                                    <iconify-icon icon="solar:eye-bold" style="font-size: 1.1rem; vertical-align: middle;"></iconify-icon>
                                </a>
                                <a href="${URL_BASE}/editar/${data}" class="btn btn-sm btn-light-warning text-warning p-1" title="Editar Información">
                                    <iconify-icon icon="solar:pen-bold" style="font-size: 1.1rem; vertical-align: middle;"></iconify-icon>
                                </a>
                                ${btnBaja}
                            </div>
                        `;
                    }
                }
            ],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            }
        });
    });

    // Acción Asíncrona Seguro con SweetAlert2 para la Baja Médica/Laboral
    function confirmarBaja(id) {
        Swal.fire({
            title: '¿Confirmar Cese o Baja?',
            text: "El empleado pasará al estado inactivo y no figurará en los controles de asistencia vigentes.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, dar de baja',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#d33',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    url: `${URL_BASE}/api/baja/${id}`,
                    type: 'POST',
                    dataType: 'json'
                }).catch(xhr => {
                    let msg = 'Error al procesar la baja.';
                    if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                    Swal.showValidationMessage(msg);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                toastr.warning('El estado del trabajador se actualizó a INACTIVO.');
                $('#tablaPersonal').DataTable().ajax.reload(null, false); // Recarga sin perder paginación
            }
        });
    }
</script>
<?= $this->endSection() ?>
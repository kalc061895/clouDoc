<div class="container-fluid py-3">
    <!-- Cabecera de la Sección -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="fw-bold mb-0 text-dark">
                <iconify-icon icon="solar:clock-circle-bold-duotone" class="text-primary me-2 align-middle"></iconify-icon>
                Control de Permisos y Papeletas
            </h4>
            <p class="text-muted small mb-0">Gestión e historial de papeletas de salida e incidencias por horas.</p>
        </div>
        <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-1 shadow-sm" onclick="nuevoPermiso()">
            <iconify-icon icon="solar:add-circle-bold" class="fs-5"></iconify-icon>
            <span>Nueva Papeleta</span>
        </button>
    </div>

    <!-- Filtros de Búsqueda -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label for="filtroMesPermiso" class="form-label small fw-semibold">Mes</label>
                    <select id="filtroMesPermiso" class="form-select form-select-sm" onchange="cargarListadoPermisos()">
                        <?php
                        $meses = [1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'];
                        $mesActual = (int)date('m');
                        foreach ($meses as $num => $nombre): ?>
                            <option value="<?= $num ?>" <?= $num === $mesActual ? 'selected' : '' ?>><?= $nombre ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filtroAnioPermiso" class="form-label small fw-semibold">Año</label>
                    <select id="filtroAnioPermiso" class="form-select form-select-sm" onchange="cargarListadoPermisos()">
                        <?php
                        $anioActual = (int)date('Y');
                        for ($a = $anioActual; $a >= $anioActual - 2; $a--): ?>
                            <option value="<?= $a ?>"><?= $a ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-sm btn-outline-primary w-100" onclick="cargarListadoPermisos()">
                        <iconify-icon icon="solar:magnifer-linear" class="me-1"></iconify-icon> Filtrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla con DataTables RowGroup -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="tablaPermisos" class="table table-hover align-middle w-100 border">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 40px;">#</th>
                            <th>Tipo Permiso</th> <!-- Columna Agrupadora (Oculta por DataTables) -->
                            <th>Fecha y Horario</th>
                            <th>Tiempo</th>
                            <th>Documento / Papeleta</th>
                            <th class="text-center">Anexos</th>
                            <th>Motivo</th>
                            <th class="text-center" style="width: 80px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyPermisos">
                        <!-- Carga dinámica mediante JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================
     MODAL: REGISTRAR / EDITAR PERMISO
=========================================== -->
<div class="modal fade" id="modalRegistroPermiso" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fs-6 fw-bold text-dark d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:document-add-bold" class="text-primary fs-5"></iconify-icon>
                    <span id="modalPermisoTitulo">Registrar Nueva Papeleta / Permiso</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="formRegistroPermiso" enctype="multipart/form-data">
                <input type="hidden" id="per_ide" name="per_ide" value="<?= $perl_ide ?? '' ?>">
                <input type="hidden" id="perm_ide" name="perm_ide">

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <!-- Tipo de Permiso -->
                        <div class="col-md-8">
                            <label for="tipo_permiso_id" class="form-label small fw-semibold">Tipo de Permiso / Incidencia <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="tipo_permiso_id" name="tipo_permiso_id" required>
                                <option value="" selected disabled>Seleccione una opción...</option>
                                <!-- Se llena vía API o mediante backend -->
                            </select>
                        </div>

                        <!-- Número de Documento / Papeleta -->
                        <div class="col-md-4">
                            <label for="perm_numero_doc" class="form-label small fw-semibold">N° Papeleta / Memo</label>
                            <input type="text" class="form-control form-control-sm" id="perm_numero_doc" name="perm_numero_doc" placeholder="Ej: PAP-2026-0012">
                        </div>

                        <!-- Fecha del Permiso -->
                        <div class="col-md-4">
                            <label for="perm_fecha" class="form-label small fw-semibold">Fecha del Permiso <span class="text-danger">*</span></label>
                            <input type="date" class="form-control form-control-sm" id="perm_fecha" name="perm_fecha" required>
                        </div>

                        <!-- Hora Inicio -->
                        <div class="col-md-4">
                            <label for="perm_hora_inicio" class="form-label small fw-semibold">Hora Salida <span class="text-danger">*</span></label>
                            <input type="time" class="form-control form-control-sm" id="perm_hora_inicio" name="perm_hora_inicio" onchange="calcularDiferenciaHoras()" required>
                        </div>

                        <!-- Hora Fin -->
                        <div class="col-md-4">
                            <label for="perm_hora_fin" class="form-label small fw-semibold">Hora Retorno <span class="text-danger">*</span></label>
                            <input type="time" class="form-control form-control-sm" id="perm_hora_fin" name="perm_hora_fin" onchange="calcularDiferenciaHoras()" required>
                        </div>

                        <!-- Calculador informativo de horas -->
                        <div class="col-12">
                            <div class="alert alert-soft-primary py-2 px-3 mb-0 d-flex align-items-center justify-content-between rounded">
                                <span class="small text-muted">Tiempo acumulado estimado:</span>
                                <span class="fw-bold text-primary" id="lblTiempoCalculado">0 hrs 0 min</span>
                            </div>
                        </div>

                        <!-- Motivo / Sustento -->
                        <div class="col-12">
                            <label for="perm_motivo" class="form-label small fw-semibold">Motivo o Justificación</label>
                            <textarea class="form-control form-control-sm" id="perm_motivo" name="perm_motivo" rows="2" placeholder="Describa brevemente el motivo de la salida..."></textarea>
                        </div>

                        <!-- Adjuntar Documentos / Papeletas firmadas -->
                        <div class="col-12">
                            <label for="adjuntos_permiso" class="form-label small fw-semibold">Adjuntar Sustentos / Archivos (PDF, JPG, PNG)</label>
                            <input class="form-control form-control-sm" type="file" id="adjuntos_permiso" name="adjuntos[]" multiple accept=".pdf,.png,.jpg,.jpeg">
                            <div class="form-text extra-small">Puedes seleccionar múltiples archivos para sustentación.</div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1" id="btnGuardarPermiso">
                        <iconify-icon icon="solar:diskette-bold"></iconify-icon>
                        <span>Guardar Permiso</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const URL_BASE_PERMISO = '<?= base_url() ?>';
    const PERL_IDE = '<?= $perl_ide ?? 0 ?>';

    $(document).ready(function() {
        cargarListadoPermisos();
    });

    // 1. Cargar datos con DataTables y RowGroup
    window.cargarListadoPermisos = function() {
        const mes = $('#filtroMesPermiso').val();
        const anio = $('#filtroAnioPermiso').val();

        if ($.fn.DataTable.isDataTable('#tablaPermisos')) {
            $('#tablaPermisos').DataTable().destroy();
        }

        $('#tbodyPermisos').html(`
        <tr>
            <td colspan="8" class="text-center py-4 text-muted">
                <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                Consultando papeletas...
            </td>
        </tr>
    `);

        $.ajax({
            url: `${URL_BASE_PERMISO}/api/permisos/personal/${PERL_IDE}`,
            type: 'GET',
            data: {
                mes: mes,
                anio: anio
            },
            dataType: 'json',
            success: function(res) {
                const data = res.data || [];

                $('#tablaPermisos').DataTable({
                    data: data,
                    destroy: true,
                    responsive: true,
                    pageLength: 10,
                    order: [
                        [1, 'asc']
                    ], // Ordena por tipo de permiso
                    columns: [{
                            data: null,
                            render: (data, type, row, meta) => meta.row + 1,
                            className: 'text-center text-muted small'
                        },
                        {
                            data: 'tipo_nombre',
                            visible: false // Oculta columna agrupadora
                        },
                        {
                            data: null,
                            className: 'small text-nowrap',
                            render: function(row) {
                                return `
                                <iconify-icon icon="solar:calendar-minimalistic-bold" class="text-primary me-1"></iconify-icon>
                                <b>${row.perm_fecha}</b> 
                                <span class="text-muted ms-1">(${row.perm_hora_inicio} - ${row.perm_hora_fin})</span>
                            `;
                            }
                        },
                        {
                            data: null,
                            className: 'text-center small',
                            render: function(row) {
                                const min = calcularMinutosTotales(row.perm_hora_inicio, row.perm_hora_fin);
                                return `<span class="badge bg-light-primary text-primary border border-primary-subtle">${formatearMinutos(min)}</span>`;
                            }
                        },
                        {
                            data: 'perm_numero_doc',
                            className: 'small',
                            render: data => data ?? '<span class="text-muted">-</span>'
                        },
                        {
                            data: 'adjuntos',
                            className: 'text-center',
                            render: function(adjuntos) {
                                if (adjuntos && adjuntos.length > 0) {
                                    const jsonAnexos = esc(JSON.stringify(adjuntos));
                                    return `
                                    <button class="btn btn-xs btn-outline-info d-inline-flex align-items-center gap-1 py-0 px-2" 
                                            onclick='verAnexos(${jsonAnexos})' title="Ver anexos">
                                        <iconify-icon icon="solar:paperclip-linear"></iconify-icon>
                                        <span class="fw-bold">${adjuntos.length}</span>
                                    </button>
                                `;
                                }
                                return '<span class="text-muted small">-</span>';
                            }
                        },
                        {
                            data: 'perm_motivo',
                            className: 'small text-truncate',
                            render: data => data ?? '-'
                        },
                        {
                            data: 'perm_ide',
                            className: 'text-center',
                            orderable: false,
                            render: function(id) {
                                return `
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-danger btn-sm" title="Eliminar" onclick="eliminarPermiso(${id})">
                                        <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon>
                                    </button>
                                </div>
                            `;
                            }
                        }
                    ],
                    rowGroup: {
                        dataSrc: 'tipo_nombre',
                        startRender: function(rows, group) {
                            let totalMinutos = 0;

                            rows.data().each(function(item) {
                                totalMinutos += calcularMinutosTotales(item.perm_hora_inicio, item.perm_hora_fin);
                            });

                            return $('<tr class="table-light border-bottom border-primary border-2"/>')
                                .append(`
                                <td colspan="7" class="py-2">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            <iconify-icon icon="solar:clock-circle-bold" class="text-primary fs-5"></iconify-icon>
                                            <span class="fw-bold text-uppercase text-dark">${group}</span>
                                        </div>
                                        <span class="badge bg-primary fs-6 px-3 py-1">
                                            Total Acumulado: ${formatearMinutos(totalMinutos)}
                                        </span>
                                    </div>
                                </td>
                            `);
                        }
                    },
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
                    }
                });
            },
            error: function() {
                $('#tbodyPermisos').html('<tr><td colspan="8" class="text-center text-danger py-3">Error al conectar con el servidor.</td></tr>');
            }
        });
    };

    // 2. Funciones auxiliares de tiempo
    function calcularMinutosTotales(hInicio, hFin) {
        if (!hInicio || !hFin) return 0;
        const [h1, m1] = hInicio.split(':').map(Number);
        const [h2, m2] = hFin.split(':').map(Number);
        const minInicio = h1 * 60 + m1;
        const minFin = h2 * 60 + m2;
        return (minFin > minInicio) ? (minFin - minInicio) : 0;
    }

    function formatearMinutos(min) {
        const horas = Math.floor(min / 60);
        const minutosRestantes = min % 60;
        let resultado = '';
        if (horas > 0) resultado += `${horas} hrs `;
        resultado += `${minutosRestantes} min`;
        return resultado;
    }

    function calcularDiferenciaHoras() {
        const inicio = $('#perm_hora_inicio').val();
        const fin = $('#perm_hora_fin').val();
        if (inicio && fin) {
            const totalMin = calcularMinutosTotales(inicio, fin);
            $('#lblTiempoCalculado').text(formatearMinutos(totalMin));
        }
    }

    function nuevoPermiso() {
        $('#formRegistroPermiso')[0].reset();
        $('#perm_ide').val('');
        $('#lblTiempoCalculado').text('0 hrs 0 min');
        $('#modalRegistroPermiso').modal('show');
    }
</script>
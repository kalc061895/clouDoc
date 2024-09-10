<div class="datatables">

    <!-- start Alternative Pagination -->
    <div class="card">
        <div class="card-body">

            <h4 class="card-title"><?= lang('Reporte.registroDiarioTitle') ?></h4>

            <p class="card-subtitle mb-3">
                <?= lang('Reporte.filtroBody') ?>
            </p>
            <form id="formExpedienteFiltro" method="POST" action="<?= base_url('reportes/registrofiltrado') ?>">
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label class="form-label" for="numExpediente">Num. de expediente</label>
                        <input type="text" class="form-control form-control-sm" id="numExpediente" name="numExpediente" />
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label" for="fechaInicio">Fecha de Inicio</label>
                        <input type="date" class="form-control form-control-sm" id="fechaInicio" name="fechaInicio" />
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label" for="fechaFin">Fecha de Fin</label>
                        <input type="date" class="form-control form-control-sm" id="fechaFin" name="fechaFin" />
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="nroDocumento">Nro. Documento</label>
                        <input type="text" class="form-control form-control-sm" id="nroDocumento" name="nroDocumento" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="nombres">Nombres</label>
                        <input type="text" class="form-control form-control-sm" id="nombres" name="nombres" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="asunto">Asunto</label>
                        <input type="text" class="form-control form-control-sm" id="asunto" name="asunto" />
                    </div>
                    <div class="col-md-12 mb-3 text-center">
                        <div class="btn-group">

                            <button type="submit" class="btn btn-sm btn-success hstack gap-6 mb-1">
                                <i class="ti ti-search fs-4"></i>
                                Buscar
                            </button>
                            <button type="reset" class="btn btn-sm btn-danger hstack gap-6 mb-1">
                                <i class="ti ti-trash fs-4"></i>
                                Limpiar Filtro
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <hr />
            <div class="table-responsive">
                <table id="alt_pagination" class="table table-sm table-bordered display ">
                    <thead>
                        <!-- start row -->
                        <tr>
                            <th width="10%" >EXP. NRO</th>
                            <th>FECHA</th>
                            <th>FOLIOS</th>
                            <th>DOCUMENTO</th>
                            <th>NOMBRES O RAZON SOCIAL</th>
                            <th>ASUNTO</th>
                            <th>DESTINO</th>
                            <th>T.</th>
                            <th>FIRMA</th>
                            <th>OBS.</th>
                        </tr>
                        <!-- end row -->
                    </thead>
                    <tbody>
                        <?php foreach ($expedientes as $item) : ?>
                            <tr>
                                <td> <?= $item->numero_expediente . '-' . substr($item->fecha_recepcion, 0, 4) ?></td>
                                <td> <?= date("d/m/Y", strtotime(substr($item->fecha_recepcion, 0, 10))) ?></td>
                                <td> <?= $item->folios ?></td>
                                <td> <?= $item->numero_documento ?></td>
                                <td> <?= $item->nombre ?></td>
                                <td> <?= $item->asunto ?></td>
                                <td> <?= $item->nombre_oficina ?></td>
                                <td> <?= ($item->procedencia == 'Externo') ? 'Virt.' : 'Fisi.' ?></td>
                                <td> </td>
                                <td> </td>


                            </tr>

                        <?php endforeach ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
    var table = $("#alt_pagination").DataTable({
        columns: [{
                data: 'numero_expediente',
                title: 'EXP.NRO.',
                width: "6%"
            },
            {
                data: 'fecha_recepcion',
                title: 'FECHA'
            },
            {
                data: 'folios',
                title: 'FOLIOS'
            },
            {
                data: 'numero_documento',
                title: 'DOCUMENTO'
            },
            {
                data: 'nombre',
                title: 'NOMBRES O RAZON SOCIAL'
            },
            {
                data: 'asunto',
                title: 'ASUNTO',
                width: '30%'
            },
            {
                data: 'nombre_oficina',
                title: 'DESTINO'
            },
            {
                data: 'procedencia',
                title: 'T.'
            },
            {
                data: 'firma',
                title: 'FIRMA'
            },
            {
                data: 'observacion',
                title: 'OBS.'
            },
        ],
        pagingType: "full_numbers",
        dom: 'Bfrtip', // Define la posición de los botones
        buttons: [{
            extend: 'pdfHtml5',
            text: 'Exportar a PDF',
            title: 'REPORTE DE TRAMITE DOCUMENTARIOS - RED DE SALUD SAN ROMÁN', // titulo de la primera hoja
            filename: 'Reporte_Registro_Diario_Cloudoc',
            orientation: 'landscape',
            pageSize: 'A4',
            exportOptions: {
                columns: ':visible'
            },
            customize: function(doc) {
                // Reducir márgenes de la página
                doc.header = function(currentPage, pageCount, pageSize) {
                    // you can apply any logic and return any valid pdfmake element

                    return {
                        columns: [{
                                image: "minsa",
                                width: 60,
                                alignment: 'left',
                                fontSize: 7,
                                margin: [20, 15, 0, 0]
                            },
                            {
                                fontSize: 7,
                                text: 'Trámite Documentario Virtual - Red de Salud San Róman',
                                alignment: 'center',
                                margin: [0, 15, 0, 0]
                            },
                            {
                                fontSize: 7,
                                text: 'Generado el - <?= date("Y-m-d h:i:s") ?>',
                                alignment: 'right',
                                margin: [0, 15, 20, 0]
                            },
                        ]
                    }
                };
                doc.pageMargins = [30, 35, 30, 35]; // [izquierda, arriba, derecha, abajo]

                // Estilo del título
                doc.styles.title = {
                    fontSize: 9,
                    bold: true,
                    alignment: 'center',
                    margin: [0, 10, 0, 10], // [izquierda, arriba, derecha, abajo]
                    FontFace: 'Helvetica',
                };

                // Aplicar estilo a todas las celdas
                doc.content[1].table.body.forEach(function(row, rowIndex) {
                    row.forEach(function(cell, cellIndex) {

                        cell.border = [true, true, true, true]; // [superior, derecho, inferior, izquierdo]
                        cell.borderColor = '#222'; // Color de los bordes
                        cell.borderWidth = 0.5; // Ancho de los bordes
                        if (rowIndex === 0) {
                            cell.fillColor = '#fff'; // Color de fondo de la cabecera
                            cell.color = '#000'; // Color de texto de la cabecera
                            cell.fontSize = 8; // Tamaño de la fuente del cuerpo
                            cell.alignment = 'center'; // Alineación de la cabecera
                        } else {

                            cell.fontSize = 8; // Tamaño de la fuente del cuerpo
                            cell.fillColor = 'white'; // Color de fondo de las filas
                            cell.color = 'black'; // Color de texto de las filas

                            if (cellIndex < 4) {
                                cell.margin = [0, 10, 0, 10]
                                cell.bold = true;
                                cell.alignment = 'center'; // Alineación de la cabecera
                            } else {
                                cell.margin = [0, 3, 0, 0]
                            }
                        }
                    });
                });
                doc.images = {

                    hcmm: "data:image/jpeg;base64,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",
                    minsa: "data:image/jpeg;base64,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"
                };
                doc.content[1].layout = [{
                    hLineWidth: 1,
                    vLineWidth: 1,
                    hLineColor: '#222',
                    vLineColor: '#222'
                }]; // Especifica el ancho de cada columna

                // Ajustar la anchura de las columnas
                doc.content[1].table.widths = [50, 45, 35, 60, 135, 175, 70, 15, 60, 65]; // Especifica el ancho de cada columna

                //doc.content[1].table.widths = ['*', '*', '*', '*', '*', '*', '*', '*', '*', '*', '*']; // Especifica el ancho de cada columna

                // Estilo de la cabecera de la tabla
                doc.styles.tableHeader = {
                    fillColor: 'gray',
                    color: 'black',
                    alignment: 'center',
                    bold: true,
                    fontSize: 9, // Tamaño de la fuente de la cabecera
                    margin: [0, 5] // Margen superior e inferior
                };

                // Ajusta el tamaño de fuente y el espaciado dentro de las celdas
                doc.styles.tableBody = {
                    fontSize: 8,
                    cellPadding: 5, // Controla el espaciado dentro de las celdas
                    lineHeight: 1.2 // Ajusta la altura de la línea
                };

                // Pie de página
                doc.footer = function(currentPage, pageCount) {
                    return {
                        columns: [{
                                fontSize: 7,
                                text: 'Reporte de Registro de Expedientes ',
                                alignment: 'left',
                                margin: [25, 0] // Margen del pie de página
                            },
                            {
                                fontSize: 7,
                                text: 'Trámite Documentario Virtual',
                                alignment: 'center',

                            },
                            {
                                fontSize: 7,
                                text: 'Página ' + currentPage.toString() + ' de ' + pageCount,
                                alignment: 'right',
                                margin: [0, 0, 25, 0] // Margen del pie de página
                            },
                        ]
                    };

                };
            }
        }]
    });

    $('#formExpedienteFiltro').on('submit', function(e) {
        e.preventDefault(); // Evita el envío por defecto del formulario
        var searchParam = $(this).serialize();
        fetchTableData(searchParam);
    });

    function fetchTableData(param) {
        Swal.fire("Buscando ...!");
        $.ajax({
            url: '<?= base_url('reportes/registrofiltrado') ?>',
            type: 'POST',
            data: param,
            success: function(data) {
                console.log(data);
                var formattedData = formatData(data);
                // Limpiar la tabla existente
                table.clear();
                // Añadir los nuevos datos
                table.rows.add(formattedData).draw();
                Swal.close('');
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
            }
        });
    }

    function formatData(data) {
        return data.map(function(item) {
            
            return {
                numero_expediente: item.numero_expediente+'-'+item.fecha_recepcion.substring(0, 4),
                fecha_recepcion: formatDateOnly(item.fecha_recepcion),
                folios: item.folios,
                numero_documento: item.numero_documento,
                nombre: item.nombre,
                asunto: item.asunto,
                nombre_oficina: item.nombre_oficina,
                procedencia: item.procedencia.substring(0, 3) + '.',
                firma: '',
                observacion: item.observacion,
            };
        });
    }

    function formatDateOnly(dateString) {
        var date = new Date(dateString);
        var day = ('0' + date.getDate()).slice(-2);
        var month = ('0' + (date.getMonth() + 1)).slice(-2); // Los meses empiezan desde 0
        var year = date.getFullYear();
        return `${day}/${month}/${year}`; // Solo la fecha (Y-M-D)
    }
</script>
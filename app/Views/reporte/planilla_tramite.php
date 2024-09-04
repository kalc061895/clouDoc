<div class="datatables">

    <!-- start Alternative Pagination -->
    <div class="card">
        <div class="card-body">

            <h4 class="card-title">Alternative Pagination</h4>

            <p class="card-subtitle mb-3">
                The default page control presented by DataTables
                (forward and backward buttons with up to 7 page numbers
                in-between) is fine for most situations, but there are
                cases where you may wish to customise the options
                presented to the end user. This is done through
                DataTables' extensible pagination mechanism, the
                <code> pagingType</code> option.
            </p>
            <div class="table-responsive">
                <table id="alt_pagination" class="table table-sm table-bordered display ">
                    <thead>
                        <!-- start row -->
                        <tr>
                            <th>Nro</th>
                            <th>Fecha</th>
                            <th>Folio</th>
                            <th>Documento</th>
                            <th>Razon Social</th>
                            <th>Asunto</th>
                            <th>Destinatario</th>
                            <th>Tipo</th>
                            <th>Firma</th>
                            <th>Observación</th>
                        </tr>
                        <!-- end row -->
                    </thead>
                    <tbody>
                        <?php foreach ($expedientes as $item) : ?>
                            <tr>
                                <td> <?= $item->numero_expediente?></td>
                                <td> <?= substr($item->fecha_recepcion,0,10)?></td>
                                <td> <?= $item->folios?></td>
                                <td> <?= $item->numero_documento?></td>
                                <td> <?= $item->nombre?></td>
                                <td> <?= $item->asunto?></td>
                                <td> <?= 'oficina destino' ?></td>
                                <td> <?= 'procedencia' ?></td>
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
<!-- DataTables Buttons Extension -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.flash.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

<!-- pdfMake -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

<script>
    $("#alt_pagination").DataTable({
        dom: 'Bfrtip', // Define la posición de los botones
        buttons: [{
            extend: 'pdfHtml5',
            text: 'Exportar a PDF',
            title: 'Reporte de Trámites',
            filename: 'reporte_tramites',
            orientation: 'landscape',
            pageSize: 'A4',
            exportOptions: {
                columns: ':visible'
            },
            customize: function(doc) {
                // Reducir márgenes de la página
                doc.pageMargins = [10, 15, 10, 15]; // [izquierda, arriba, derecha, abajo]

                // Definir estilo para los bordes de la tabla
                var tableStyle = {
                    border: [true, true, true, true], // Borde para todas las celdas
                    borderColor: '#000', // Color de los bordes
                    borderWidth: 1 // Ancho de los bordes
                };

                // Aplicar el estilo a todas las celdas
                doc.content[1].table.body.forEach(function(row) {
                    row.forEach(function(cell) {
                        if (!cell.hasOwnProperty('border')) {
                            cell.border = tableStyle.border;
                        }
                        if (!cell.hasOwnProperty('borderColor')) {
                            cell.borderColor = tableStyle.borderColor;
                        }
                        if (!cell.hasOwnProperty('borderWidth')) {
                            cell.borderWidth = tableStyle.borderWidth;
                        }
                    });
                });

                // Ajustar la anchura de las columnas
                doc.content[1].table.widths = [50, 50, 30, 80, 180, 200, 100, 30, 30, 30]; // Especifica el ancho de cada columna
                //doc.content[1].table.widths = ['*', '*', '*', '*', '*', '*', '*', '*', '*', '*', '*']; // Especifica el ancho de cada columna

                // Estilo de la cabecera de la tabla
                doc.styles.tableHeader = {
                    fillColor: '#fff',
                    color: '#000',
                    alignment: 'center',
                    bold: true,
                    fontSize: 8,
                    margin: [0, 2] // Margen superior e inferior
                };


                // Ajusta el tamaño de fuente y el espaciado dentro de las celdas
                doc.styles.tableBody = {
                    fontSize: 7,
                    borderColor: '#000',
                    borderWidth: 1, 
                    cellPadding: 5, // Controla el espaciado dentro de las celdas
                    lineHeight: 1.2 // Ajusta la altura de la línea
                };

                // Pie de página
                doc.footer = function(currentPage, pageCount) {
                    return {
                        text: 'Tramite Documentario Virtual - Red Salud San Román '+ currentPage.toString() + ' de ' + pageCount,
                        alignment: 'right',
                        margin: [100, 0] // Margen del pie de página
                    };
                };
            }
        }]
    });
</script>
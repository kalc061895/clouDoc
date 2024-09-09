<div class="datatables">

    <!-- start Alternative Pagination -->
    <div class="card">
        <div class="card-body">

            <h4 class="card-title"><?= lang('Reporte.hojaRutaTitle') ?></h4>

            <p class="card-subtitle mb-3">
                <?= lang('Reporte.hojaRutaBody') ?>
            </p>
            <form id="formExpedienteFiltro" method="POST" action="<?= base_url('reportes/rutafiltrado') ?>">
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label class="form-label" for="numExpediente">Num. de expediente</label>
                        <input type="text" class="form-control form-control-sm" id="numExpediente" name="numExpediente" />
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label" for="numExpedienteFin">Num. de expediente Fin</label>
                        <input type="text" class="form-control form-control-sm" id="numExpedienteFin" name="numExpedienteFin" />
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
                            <th width="10%">EXP. NRO</th>
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
<!-- DataTables Buttons Extension -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.flash.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

<!-- pdfMake -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

<img src="<?= base_url('assets/images/logos/hoja_ruta.jpg') ?>" alt="" hidden id="hoja_ruta">
<img src="<?= base_url('assets/images/logos/minsa-logo.jpg') ?>" alt="" hidden id="logo_minsa">
<script>
    var arrayExpedientes;
    var imgRuta = document.getElementById('hoja_ruta');
    var base64ImgRuta = getBase64Image(imgRuta);
    var imgMinsa = document.getElementById('logo_minsa');
    var base64ImgMinsa = getBase64Image(imgMinsa);
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
            title: 'sda', // titulo de la primera hoja
            filename: 'Hoja Ruta',
            //orientation: 'VERTICA',

            exportOptions: {
                columns: ':visible'
            },
            customize: function(doc) {
                // Reducir márgenes de la página
                //doc.pageSize = { width: '165mm', height: '215mm' };

                doc.pageSize = {
                    width: 466,
                    height: 609,
                };
                doc.background = {
                    image: 'data:image/png;base64,' + base64ImgRuta,
                    width: 466,
                    height: 609,
                    opacity: 0.3
                };
                doc.header = function(currentPage, pageCount, pageSize) {
                    // you can apply any logic and return any valid pdfmake element

                    return {
                        columns: [
                            /*{
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
                            */
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
                doc.content[2] = [

                    {
                        text: 'NOMBRE COMPLETO DEL REMITENTE POR SI ES MUY LARGO NOMBRE COMPLETO DEL REMITENTE POR SI ES MUY LARGO NOMBRE COMPLETO DEL REMITENTE ',
                        fontSize: 10,
                        width: 400,
                        absolutePosition: {
                            x: 90,
                            y: 163
                        }
                    },
                    {
                        text: 'ASUNTO SOBR EL CUAL SE TIENE NOMBRE COMPLETO DEL REMITENTE POR SI ES MUY LARGO NOMBRE COMPLETO DEL REMITENTE POR ',
                        fontSize: 10,
                        width: 400,
                        absolutePosition: {
                            x: 90,
                            y: 210
                        }
                    },
                    {
                        style: 'tableExample',
                        absolutePosition: {
                            x: 21,
                            y: 268
                        },
                        table: {
                            widths: [79, 79, 79, 79, 79],
                            body: [
                                ['Column 1', 'Column 2', 'Column 3', 'Column 4', 'Column 5'],
                                [{
                                        text: 'asdasd'
                                    },
                                    {
                                        text: 'or a nested table',
                                    },
                                    {
                                        text: 'asdasd'
                                    },
                                    {
                                        text: 'asdasd'
                                    },
                                    {
                                        text: 'asdasd'
                                    }
                                ]
                            ],
                        },
                        layout: 'noBorders',
                    }

                ];
                // Aplicar estilo a todas las celdas
                /** doc.content[1].table.body.forEach(function(row, rowIndex) {
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
                */
                doc.images = {

                    hcmm: 'data:image/png;base64,' + base64ImgMinsa
                };
                doc.content[1].layout = [{
                    hLineWidth: 1,
                    vLineWidth: 1,
                    hLineColor: '#222',
                    vLineColor: '#222'
                }]; // Especifica el ancho de cada columna
                /*
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
                */

                // Ajusta el tamaño de fuente y el espaciado dentro de las celdas
                doc.styles.tableBody = {
                    fontSize: 8,
                    cellPadding: 5, // Controla el espaciado dentro de las celdas
                    lineHeight: 1.2 // Ajusta la altura de la línea
                };

                // Pie de página
                doc.footer = function(currentPage, pageCount) {
                    return {
                        columns: [
                            /*{
                                fontSize: 7,
                                text: 'Hoja de Ruta',
                                alignment: 'left',
                                margin: [25, 0] // Margen del pie de página
                            },
                            */
                            {
                                fontSize: 7,
                                text: 'Trámite Documentario Virtual',
                                alignment: 'center',

                            },
                            /*
                            {
                                fontSize: 7,
                                text: 'Página ' + currentPage.toString() + ' de ' + pageCount,
                                alignment: 'right',
                                margin: [0, 0, 25, 0] // Margen del pie de página
                            },
                            */
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
            url: '<?= base_url('reportes/rutafiltrado') ?>',
            type: 'POST',
            data: param,
            success: function(data) {
                console.log(data);
                arrayExpedientes = data;
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
                numero_expediente: item.numero_expediente + '-' + item.fecha_recepcion.substring(0, 4),
                fecha_recepcion: formatDateOnly(item.fecha_recepcion),
                folios: item.folios,
                numero_documento: item.numero_documento,
                nombre: item.nombre,
                asunto: item.asunto,
                nombre_oficina: item.nombre_oficina,
                procedencia: item.procedencia.substring(0, 3) + '.',
                firma: '',
                observacion: item.observacion,
                movimientos: item.movimientos
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

    function getBase64Image(img) {
        var canvas = document.createElement("canvas");
        canvas.width = img.width;
        canvas.height = img.height;
        var ctx = canvas.getContext("2d");
        ctx.drawImage(img, 0, 0);
        var dataURL = canvas.toDataURL("image/png");
        return dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
    }
</script>

<button id="generatePDF">Generate PDF</button>
<script>
    $(document).ready(function() {
        $('#generatePDF').click(function() {
            // Llamada AJAX para obtener los datos
            data = formatData(arrayExpedientes);
            generatePDF(data);
            /* $.ajax({
                url: 'your-data-endpoint', // Cambia esta URL a la ruta que proporciona los datos
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Aquí 'data' es el array que contiene los datos para la tabla
                    generatePDF(data);
                },
                error: function() {
                    alert('Error al obtener los datos.');
                }
            });
            */
        });

        function generatePDF(data) {

            // Genera el documento pdf
            var docDefinition = {
                content: data.map((item, index) => [{
                        text: item.numero_expediente,
                        fontSize: 12,
                        width: 200,
                        bold: true,
                        alignment: 'center',
                        absolutePosition: {
                            x: 360,
                            y: 53
                        }
                    },
                    {
                        text: item.fecha_recepcion,
                        fontSize: 12,
                        width: 200,
                        bold: true,
                        alignment: 'center',
                        absolutePosition: {
                            x: 360,
                            y: 83
                        }
                    },
                    {
                        text: item.nombre,
                        fontSize: 10,
                        width: 400,
                        absolutePosition: {
                            x: 90,
                            y: 163
                        }
                    },
                    {
                        text: item.asunto,
                        fontSize: 10,
                        width: 400,
                        absolutePosition: {
                            x: 90,
                            y: 210
                        }
                    },
                    {
                        style: 'tableExample',
                        fontSize: 9,
                        absolutePosition: {
                            x: 21,
                            y: 268
                        },
                        table: {
                            widths: [75, 75, 75, 75, 75],
                            body: item.movimientos.map(item_mov => [
                                item_mov.column1,
                                item_mov.column2,
                                item_mov.column3,
                                item_mov.column4,
                                item_mov.column5
                            ])
                            //layout: 'noBorders',
                        },
                    },
                    {
                        text: '',
                        pageBreak: index < data.length - 1 ? 'after' : null
                    } // Añade salto de página si no es la última

                ]).flat(),
                pageSize: {
                    width: 466,
                    height: 609
                },
                background: {
                    image: 'data:image/png;base64,' + base64ImgRuta,
                    width: 466,
                    height: 609,
                    opacity: 0.3
                },
                header: function(currentPage, pageCount, pageSize) {
                    return {
                        columns: [{
                            fontSize: 7,
                            text: 'Generado el - ' + new Date().toLocaleString(),
                            alignment: 'right',
                            margin: [0, 15, 20, 0]
                        }]
                    }
                },
                pageMargins: [30, 35, 30, 35],
                styles: {
                    title: {
                        fontSize: 9,
                        bold: true,
                        alignment: 'center',
                        margin: [0, 10, 0, 10],
                    },
                    tableExample: {
                        fontSize: 8,
                        cellPadding: 5,
                        lineHeight: 1.2
                    }
                },
                images: {
                    hcmm: 'data:image/png;base64,' + base64ImgMinsa
                },
                footer: function(currentPage, pageCount) {
                    return {
                        columns: [{
                            fontSize: 7,
                            text: 'Trámite Documentario Virtual',
                            alignment: 'center',
                        }]
                    }
                }
            };

            // Crear y descargar el PDF
            pdfMake.createPdf(docDefinition).download('hoja_ruta.pdf');
        }
    });
</script>
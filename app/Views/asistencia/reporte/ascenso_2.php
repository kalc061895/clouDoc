 <style>
     body {
         background-color: #f8f9fa;
         font-family: 'Inter', sans-serif;
         /* Usando Inter como se recomienda */
     }

     .container {
         max-width: 800px;
         margin-top: 50px;
         padding: 30px;
         background-color: #ffffff;
         border-radius: 15px;
         /* Bordes redondeados */
         box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
     }

     .form-label {
         font-weight: bold;
         color: #343a40;
     }

     .btn-primary {
         background-color: #007bff;
         border-color: #007bff;
         border-radius: 8px;
         /* Bordes redondeados para botones */
         padding: 10px 20px;
     }

     .btn-primary:hover {
         background-color: #0056b3;
         border-color: #0056b3;
     }

     .btn-success {
         background-color: #28a745;
         border-color: #28a745;
         border-radius: 8px;
         /* Bordes redondeados para botones */
         padding: 10px 20px;
     }

     .btn-success:hover {
         background-color: #218838;
         border-color: #1e7e34;
     }

     .pdf-viewer {
         border: 1px solid #dee2e6;
         border-radius: 10px;
         min-height: 400px;
         background-color: #e9ecef;
         display: flex;
         justify-content: center;
         align-items: center;
         color: #6c757d;
         font-style: italic;
     }

     .loading-spinner {
         display: none;
         /* Oculto por defecto */
     }
 </style>

 <div class="container">
     <h2 class="mb-4 text-center">Generar Reportes de Ascenso 2025</h2>
     <hr>

     <div class="mb-3">
         <label for="inputDNI" class="form-label">Ingresar DNI:</label>
         <div class="input-group mb-3">
             <input type="text" class="form-control rounded-start-2" id="inputDNI" placeholder="Ej: 12345678" aria-label="DNI" aria-describedby="button-buscar">
             <button class="btn btn-primary rounded-end-2" type="button" id="btnBuscar">Buscar</button>
         </div>
     </div>

     <div class="d-grid gap-2 mb-4">
         <button class="btn btn-success" type="button" id="btnGenerarReporte">Generar Reportes Ascenso 2025</button>
     </div>

     <div class="text-center my-3">
         <div id="loadingSpinner" class="spinner-border text-primary loading-spinner" role="status">
             <span class="visually-hidden">Cargando...</span>
         </div>
         <p id="messageArea" class="text-muted"></p>
     </div>

     <hr>
     <h4 class="mb-3 text-center">Previsualización del Reporte PDF</h4>
     <div id="pdfViewer" class="pdf-viewer">
         El reporte PDF se mostrará aquí.
     </div>
 </div>

 <script>
     $(document).ready(function() {
         const inputDNI = $('#inputDNI');
         const btnBuscar = $('#btnBuscar');
         const btnGenerarReporte = $('#btnGenerarReporte');
         const loadingSpinner = $('#loadingSpinner');
         const messageArea = $('#messageArea');
         const pdfViewer = $('#pdfViewer');

         // Evento para el botón "Buscar"
         btnBuscar.on('click', function() {
             const dni = inputDNI.val().trim();
             if (dni) {
                 console.log('DNI ingresado:', dni);
                 messageArea.text(`Buscando información para DNI: ${dni}... (Esta funcionalidad es solo de ejemplo)`);
                 $.ajax({
                     url: 'asistencia/buscarDni', // Ruta de CodeIgniter
                     method: 'POST',
                     data: {
                         dni: dni
                     }, // Envía el DNI en formato JSON
                     success: function(data) {
                         // Imprimir los datos en la consola (como se solicitó)
                         //data = JSON.parse(data); // Asegúrate de que 'data' sea un objeto JSON válido
                         console.log('Datos recibidos del backend:', data);
                         // Aquí puedes procesar 'data' para mostrar el PDF.
                         // Si 'data' contiene una URL al PDF:
                         //return 0;
                         if (data.pdf_url) {
                             pdfViewer.html(`<iframe src="${data.pdf_url}" width="100%" height="400px" style="border:none;"></iframe>`);
                             messageArea.text(data.per_paterno + " " + data.per_materno + " " + data.per_nombre + " - Reporte generado con éxito. Visualizando PDF.");
                         } else if (data.message) {
                             // Si el backend devuelve solo un mensaje (ej. "DNI no encontrado")
                             pdfViewer.html(`<p class="text-center text-danger">${data.message}</p>`);
                             messageArea.text(data.message);
                         } else {
                             // Si la respuesta no es una URL de PDF ni un mensaje específico
                             pdfViewer.html('<p class="text-center">El backend no devolvió una URL de PDF o un mensaje claro.</p>');
                             //messageArea.text('Respuesta del backend procesada. Ver consola para detalles.');
                             messageArea.text('Dtaso encontrados para: ' + data.per_paterno + " " + data.per_materno + " " + data.per_nombre);
                         }
                     },
                     error: function(jqXHR, textStatus, errorThrown) {
                         console.error('Error al buscar DNI:', textStatus, errorThrown, jqXHR.responseText);
                         messageArea.text(`Error al buscar el DNI: ${textStatus}. Revisa la consola para más detalles.`);
                         pdfViewer.html('<p class="text-center text-danger">No se pudo cargar el reporte PDF.</p>');
                     },
                     complete: function() {
                         // Ocultar spinner de carga
                         loadingSpinner.hide();
                     }
                 });
             } else {
                 messageArea.text('Por favor, ingrese un DNI.');
                 console.warn('DNI no ingresado.');
             }
         });

         // Evento para el botón "Generar Reportes Ascenso 2025"
         btnGenerarReporte.on('click', function() {
             const dni = inputDNI.val().trim();

             if (!dni) {
                 messageArea.text('Por favor, ingrese un DNI antes de generar el reporte.');
                 console.warn('DNI no ingresado para generar reporte.');
                 return;
             }

             // Mostrar spinner de carga y mensaje
             loadingSpinner.show();
             messageArea.text('Generando reporte... Por favor, espere.');
             pdfViewer.html('El reporte PDF se mostrará aquí.'); // Limpiar visor anterior

             $.ajax({
                 url: 'asistencia/generar_reporte_ascenso', // Ruta de CodeIgniter
                 method: 'POST',
                 data: {
                     dni: dni
                 }, // Envía el DNI en formato JSON
                 success: function(data) {
                     // Asume que data es un objeto JSON con { status: 'success', message: '...', file_url: '...' }
                     console.log('Datos recibidos del backend:', data);

                     if (data.status === 'success' && data.file_url) {
                         messageArea.text('Reporte generado con éxito. Descargando...');
                         pdfViewer.html('<p class="text-center text-success">El reporte DOCX ha sido generado y se está descargando.</p>');

                         // Redirige al navegador para descargar el archivo desde la URL pública
                         window.location.href = data.file_url;

                     } else if (data.message) {
                         messageArea.text(data.message);
                         pdfViewer.html(`<p class="text-center text-danger">${data.message}</p>`);
                     } else {
                         messageArea.text('Respuesta del backend procesada. Ver consola para detalles.');
                         pdfViewer.html('<p class="text-center">El backend no devolvió una URL de archivo o un mensaje claro.</p>');
                     }
                     loadingSpinner.hide();
                 },
                 error: function(jqXHR, textStatus, errorThrown) {
                     // ... (mismo manejo de error que antes) ...
                     loadingSpinner.hide();
                 },
                 complete: function() {
                     // Ocultar spinner de carga
                     loadingSpinner.hide();
                 }
             });
         });
     });
 </script>
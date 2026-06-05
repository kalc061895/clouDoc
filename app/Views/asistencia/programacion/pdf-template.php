<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Programación de Turnos</title>
    <style>
        /* Configuración de los márgenes de la página para dar espacio al header y footer */
        @page {
            margin: 140px 20px 100px 20px;
            /* Superior, Derecho, Inferior, Izquierdo */
            font-family: Arial, sans-serif;
        }

        /* La cabecera se fija en la parte superior de cada página */
        header {
            position: fixed;
            top: -120px;
            left: 0px;
            right: 0px;
            height: 100px;
            text-align: center;
        }

        /* El pie de página se fija en la parte inferior de cada página */
        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 50px;
            font-size: 9px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        /* Estilos generales de la tabla y tipografía */
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .bold {
            font-weight: bold;
        }

        .header-title {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 10px;
            font-size: 10px;
        }

        .info-table td {
            padding: 2px;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            vertical-align: middle;
        }

        .main-table th {
            background-color: #f2f2f2;
        }

        /* Anchos de columna para optimizar el espacio */
        .col-dni {
            width: 50px;
        }

        .col-nombres {
            width: 150px;
            text-align: left;
        }

        .col-cargo {
            width: 80px;
        }

        .col-dias {
            width: 15px;
        }

        /* Para las columnas de los días del mes */

        /* Borra el código anterior y coloca este */
        .page-number::before {
            content: counter(page);
        }

        .header-logo-left {
            position: absolute;
            top: 0;
            left: 0;
        }

        .header-logo-right {
            position: absolute;
            top: 0;
            right: 0;
        }

        /* Controlar el tamaño máximo de las imágenes para que no desfiguren el header */
        .header-logo-left img,
        .header-logo-right img {
            height: 50px;
            /* Ajusta la altura según el diseño de tus logos */
            width: auto;
            /* Mantiene la proporción original */
            display: block;
        }

        /* --- Estilos del Footer con Bloques de Firma (2x2) --- */

        

        .footer-obs {
            margin-bottom: 6px;
            line-height: 1.2;
        }

        .footer-signatures-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 15px 4px;
            /* Espaciado horizontal y vertical entre los bloques de firma */
            margin-bottom: 5px;
        }

        /* Forzamos que las celdas del footer no hereden estilos de la tabla principal */
        .footer-signatures-table td {
            border: none !important;
            padding: 0px !important;
            width: 50%;
            /* Divide la tabla exactamente a la mitad */
            vertical-align: top;
        }

        /* Contenedor individual de cada firma */
        .signature-box {
            text-align: left;
        }

        .signature-title {
            font-size: 7.5px;
            color: #555;
            font-style: italic;
            margin-bottom: 2px;
        }

        /* El espacio físico en blanco donde se estampará la firma digital */
        .signature-space {
            height: 50px;
            /* Altura ideal para el recuadro visual de firmas tipo REFIRMA */
            background-color: transparent;
            /* Se mantiene invisible, listo para ser firmado */
        }

        .signature-charge {
            font-weight: bold;
            font-size: 8.5px;
            border-top: 0.5px dashed #ccc;
            /* Una línea sutil divisoria superior para el cargo */
            padding-top: 2px;
        }

        /* Paginación */
        .footer-pagination {
            text-align: right;
            font-weight: bold;
            font-size: 8.5px;
            position: absolute;
            bottom: -5px;
            right: 15px;
        }
    </style>
</head>

<body>

    <header>
        <div class="header-logo-right">
            <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents(base_url('images/logos/logo-header-rssr.png'))); ?>" alt="Logo">
        </div>
        <div class="header-logo-left">
            <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents(base_url('images/logos/logo-header-hcmm.png'))); ?>" alt="Logo_hcmm">
        </div>
        <div class="header-title">
            MINISTERIO DE SALUD | DIRESA | RED DE SALUD SAN ROMAN
        </div>
        <div class="header-title" style="font-size: 13px; margin-top: 5px;">
            PROGRAMACIÓN DE TURNOS DE TRABAJO DE PERSONAL PROFESIONAL, TÉCNICO Y AUXILIAR DE LA SALUD </div>

        <table class="info-table">
            <tbody>
                <tr>
                    <th colspan="3"> Datos de Establecimiento </th>
                </tr>
                <tr>
                    <td class="text-left"><span class="bold">ESTABLECIMIENTO:</span> HOSPITAL CARLOS MONGE MEDRANO</td>
                    <td class="text-left"><span class="bold">IPRESS:</span> 00003299</td>
                    <td class="text-left"><span class="bold">NIVEL:</span> II-2</td>
                </tr>
                <tr>
                    <td class="text-left"><span class="bold">DEPARTAMENTO:</span> ENFERMERIA</td>
                    <td class="text-left"><span class="bold">SERVICIO O ÁREA:</span> EMERGENCIA (UPSS)</td>
                    <td class="text-left"><span class="bold">MES/AÑO:</span> MAYO-2026</td>
                </tr>
            </tbody>
        </table>
    </header>

    <footer>
        <div class="footer-obs">
            <div class="signature-title">Firmado digitalmente por:</div>
        </div>

        <table class="footer-signatures-table">
            <tr>
                <td class="signature-box">
                    <div class="signature-space"></div>
                    <div class="signature-charge">DIRECTOR DE ESTABLECIMIENTO</div>
                </td>
                <td class="signature-box">
                    <div class="signature-space"></div>
                    <div class="signature-charge">JEFE DE DEPARTAMENTO</div>
                </td>
  
                <td class="signature-box">
                    <div class="signature-space"></div>
                    <div class="signature-charge">JEFE DE SERVICIO</div>
                </td>
                <td class="signature-box">
                    <div class="signature-space"></div>
                    <div class="signature-charge">CONTROL DE ASISTENCIA</div>
                </td>
            </tr>
        </table>

        <div class="footer-pagination">
            Pág. <span class="page-number"></span> de <span class="page-count"></span>
        </div>
    </footer>
    <main>
        <table class="main-table">
            <thead>
                <tr>
                    <th colspan="4" rowspan="2">MES Y AÑO: </th>
                    <th colspan="31"><b style="font-size: 14px ;">MAYO 2026</b></th>
                    <th colspan="6" rowspan="2">TOTAL HORAS</th>
                </tr>
                <tr style="text-align:center" id="dia_mes" class="table-warning text-primary text-uppercase">
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                    <th>5</th>
                    <th>6</th>
                    <th>7</th>
                    <th>8</th>
                    <th>9</th>
                    <th>10</th>
                    <th>11</th>
                    <th>12</th>
                    <th>13</th>
                    <th>14</th>
                    <th>15</th>
                    <th>16</th>
                    <th>17</th>
                    <th>18</th>
                    <th>19</th>
                    <th>20</th>
                    <th>21</th>
                    <th>22</th>
                    <th>23</th>
                    <th>24</th>
                    <th>25</th>
                    <th>26</th>
                    <th>27</th>
                    <th>28</th>
                    <th>29</th>
                    <th>30</th>
                    <th>31</th>

                </tr>
                <tr>
                    <th class="text-left"><strong>Nº:</strong></th>
                    <th class="text-left"><strong>Nombres: </strong></th>
                    <th class="text-left"><strong>DNI: </strong></th>
                    <th class="text-left"><strong>Cargo: </strong></th>
                    <th class="" scope="col">Vi</th>
                    <th class="" scope="col">Sa</th>
                    <th class="" scope="col">Do</th>
                    <th class="" scope="col">Lu</th>
                    <th class="" scope="col">Ma</th>
                    <th class="" scope="col">Mi</th>
                    <th class="" scope="col">Ju</th>
                    <th class="" scope="col">Vi</th>
                    <th class="" scope="col">Sa</th>
                    <th class="" scope="col">Do</th>
                    <th class="" scope="col">Lu</th>
                    <th class="" scope="col">Ma</th>
                    <th class="" scope="col">Mi</th>
                    <th class="" scope="col">Ju</th>
                    <th class="" scope="col">Vi</th>
                    <th class="" scope="col">Sa</th>
                    <th class="" scope="col">Do</th>
                    <th class="" scope="col">Lu</th>
                    <th class="" scope="col">Ma</th>
                    <th class="" scope="col">Mi</th>
                    <th class="" scope="col">Ju</th>
                    <th class="" scope="col">Vi</th>
                    <th class="" scope="col">Sa</th>
                    <th class="" scope="col">Do</th>
                    <th class="" scope="col">Lu</th>
                    <th class="" scope="col">Ma</th>
                    <th class="" scope="col">Mi</th>
                    <th class="" scope="col">Ju</th>
                    <th class="" scope="col">Vi</th>
                    <th class="" scope="col">Sa</th>
                    <th class="" scope="col">Do</th>
                    <th>GD</th>
                    <th>GN</th>
                    <th>M</th>
                    <th>T</th>
                    <th>N</th>
                    <th>Total</th>
                </tr>

            </thead>
            <tbody>
                <?php for ($i = 1; $i < 30; $i++) : ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td>25832980</td>
                        <td class="text-left">MAMANI CALLA MARIO MENELIO</td>
                        <td>TÉCNICO EN ENFERMERÍA</td>
                        <td>GD</td>
                        <td>GN</td>
                        <td>MT</td>
                        <td>GN</td>
                        <td></td>
                        <td>GD</td>
                        <td>GN</td>
                        <td>MT</td>
                        <td>GN</td>
                        <td></td>
                        <td>GD</td>
                        <td>GN</td>
                        <td>M</td>
                        <td>GN</td>
                        <td></td>
                        <td>GD</td>
                        <td>GN</td>
                        <td>MT</td>
                        <td>GN</td>
                        <td></td>
                        <td>GD</td>
                        <td>GN</td>
                        <td>MT</td>
                        <td>GN</td>
                        <td></td>
                        <td>GD</td>
                        <td>GN</td>
                        <td>M</td>
                        <td>GN</td>
                        <td></td>
                        <td></td>
                        <th>4</th>
                        <th>4</th>
                        <th>5</th>
                        <th>2</th>
                        <th>1</th>
                        <td class="bold">150</td>

                    </tr>

                <?php endfor; ?>



            </tbody>
        </table>
    </main>

</body>

</html>
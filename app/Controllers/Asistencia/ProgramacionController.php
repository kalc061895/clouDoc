<?php

namespace App\Controllers\Asistencia;

use App\Controllers\BaseController;
use App\Models\Asistencia\EventoModel;
use Dompdf\Dompdf;
use App\Models\Asistencia\PersonalModel;
use App\Models\Asistencia\PersonaModel;
use App\Models\Asistencia\ServicioModel;

class ProgramacionController extends BaseController
{
    public function index()
    {

        //return view('asistencia/programacion/full-calendar');
        return view('asistencia/programacion/programar-calendario');
    }

    /**
     * LISTAR EVENTOS (FULLCALENDAR)
     */
    public function listar()
    {
        $model = new EventoModel();
        $data = $model->findAll();

        $eventos = [];

        foreach ($data as $row) {

            $eventos[] = [
                'id'    => $row['id'],
                'title' => $row['upss'] . ' - ' . $row['trabajador_apellidos'] . ' ' . $row['trabajador_nombres'] . ' - ' . $row['turno'],

                'start' => $row['fecha_hora_inicio'],
                'end'   => $row['fecha_hora_fin'],

                'extendedProps' => [
                    'trabajador_id'            => $row['trabajador_id'],
                    'trabajador_dni'           => $row['trabajador_dni'],
                    'trabajador_apellidos'     => $row['trabajador_apellidos'],
                    'trabajador_nombres'       => $row['trabajador_nombres'],
                    'trabajador_cargo'         => $row['trabajador_cargo'],
                    'trabajador_especialidad'  => $row['trabajador_especialidad'],

                    'tipo'      => $row['tipo'],
                    'turno'     => $row['turno'],

                    'upss'      => $row['upss'],
                    'ambiente'  => $row['ambiente'],
                    'actividad' => $row['actividad'],

                    'color_actividad' => $row['color_actividad']
                ]
            ];
        }

        return $this->response->setJSON($eventos);
    }

    /**
     * GUARDAR EVENTO
     */

    public function guardar()
    {
        $model = new EventoModel();
        $data = $this->request->getJSON(true);

        //return $this->response->setJSON($data); // Detener la ejecución después de la prueba
        $titulo = $data['trabajador_apellidos'] . ' ' . $data['trabajador_nombres'] . ' - ' . $data['turno'];

        $model->insert([
            'trabajador_id'           => $data['trabajador_id'],
            'trabajador_dni'          => $data['trabajador_dni'],
            'trabajador_apellidos'    => $data['trabajador_apellidos'],
            'trabajador_nombres'      => $data['trabajador_nombres'],
            'trabajador_cargo'        => $data['trabajador_cargo'],
            'trabajador_especialidad' => $data['trabajador_especialidad'],

            'tipo'    => $data['tipo'],
            'turno'   => $data['turno'],

            'upss'      => $data['upss'],
            'ambiente'  => $data['ambiente'],
            'actividad' => $data['actividad'],

            'color_actividad' => $data['color_actividad'],

            'fecha_hora_inicio' => $data['start'],
            'fecha_hora_fin'    => $data['end']
        ]);

        return $this->response->setJSON(['status' => 'ok']);
    }

    /**
     * ACTUALIZAR EVENTO
     */
    public function actualizar($id)
    {
        $model = new EventoModel();
        $data = $this->request->getJSON(true);

        $titulo = $data['trabajador_apellidos'] . ' ' . $data['trabajador_nombres'] . ' - ' . $data['turno'];

        $model->update($id, [
            'trabajador_id'           => $data['trabajador_id'],
            'trabajador_dni'          => $data['trabajador_dni'],
            'trabajador_apellidos'    => $data['trabajador_apellidos'],
            'trabajador_nombres'      => $data['trabajador_nombres'],
            'trabajador_cargo'        => $data['trabajador_cargo'],
            'trabajador_especialidad' => $data['trabajador_especialidad'],

            'tipo'    => $data['tipo'],
            'turno'   => $data['turno'],

            'upss'      => $data['upss'],
            'ambiente'  => $data['ambiente'],
            'actividad' => $data['actividad'],

            'color_actividad' => $data['color_actividad'],

            'fecha_hora_inicio' => $data['start'],
            'fecha_hora_fin'    => $data['end']
        ]);

        return $this->response->setJSON(['status' => 'ok']);
    }

    /**
     * ELIMINAR
     */
    public function eliminar($id)
    {
        $model = new EventoModel();
        $model->delete($id);

        return $this->response->setJSON(['status' => 'ok']);
    }

    /**
     * TRABAJADORES (SIMULADO / REAL)
     */
    public function trabajadores()
    {
        $personalModel = new PersonalModel();

        // Seleccionamos los campos necesarios y unimos las tablas
        $data = $personalModel->select('
                casis_personal.perl_ide as id, 
                casis_persona.per_dni as dni, 
                casis_persona.per_nombre as nombres,
                CONCAT(casis_persona.per_paterno, " ", casis_persona.per_materno) as apellidos,
                IFNULL(casis_servicio.ser_nombre, "N/A") as cargo
            ')
            ->join('casis_persona', 'casis_persona.per_dni = casis_personal.perl_per_dni', 'left')
            ->join('casis_servicio', 'casis_servicio.ser_ide = casis_personal.perl_ser_ide', 'left')
            ->findAll();

        return $this->response->setJSON($data);
    }



    public function pdfMensual()
    {
        $mes = 'Mayo';
        $anio = '2026';

        $model = new EventoModel();

        $data = $model->findAll();

        // 🔁 MATRIZ
        $filas = [];

        foreach ($data as $row) {

            $id = $row['trabajador_id'];
            $dia = date('j', strtotime($row['fecha_hora_inicio']));

            if (!isset($filas[$id])) {
                $filas[$id] = [
                    'dni' => $row['trabajador_dni'],
                    'nombre' => $row['trabajador_apellidos'] . ' ' . $row['trabajador_nombres'],
                    'cargo' => $row['trabajador_cargo'],
                    'dias' => array_fill(1, 31, '')
                ];
            }

            $filas[$id]['dias'][$dia] = $row['turno'];
        }

        $html = $this->generarHTMLPDF($filas, $mes, $anio);

        $dompdf = new Dompdf();
        $html = view('asistencia/programacion/pdf-template', ['html' => $html]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        // =========================
        // CREAR CARPETA SI NO EXISTE
        // =========================
        $rutaCarpeta = WRITEPATH . 'uploads/programaciones/';

        if (!is_dir($rutaCarpeta)) {
            mkdir($rutaCarpeta, 0777, true);
        }

        // =========================
        // NOMBRE ARCHIVO
        // =========================
        $nombre = 'programacion_' . date('Ymd_His') . '.pdf';

        $rutaCompleta = $rutaCarpeta . $nombre;

        // =========================
        // GUARDAR PDF
        // =========================
        file_put_contents($rutaCompleta, $dompdf->output());

        return "PDF guardado en: " . $rutaCompleta;

        /*
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setBody($dompdf->output());
            */
    }

    /**
     * HTML DEL PDF
     */
    private function generarHTMLPDF($filas, $mes, $anio)
    {
        $html = '
            <style>
            body { font-family: Arial; font-size:8px; }

            .titulo {
                text-align:center;
                font-weight:bold;
                font-size:14px;
            }

            .sub {
                text-align:center;
                font-size:10px;
            }

            table {
                width:100%;
                border-collapse:collapse;
            }

            th, td {
                border:1px solid #000;
                padding:2px;
                text-align:center;
            }

            .nombre {
                text-align:left;
            }

            .small {
                font-size:7px;
            }

            .footer {
                margin-top:10px;
                font-size:9px;
            }
            </style>

            <div class="titulo">HOSPITAL CARLOS MONGE MEDRANO</div>
            <div class="sub">PROGRAMACION DE TURNOS DE TRABAJO</div>
            <div class="sub">MES: ' . $mes . ' - ' . $anio . '</div>

            <br>

            <table>
            <tr>
            <th>N°</th>
            <th>DNI</th>
            <th>APELLIDOS Y NOMBRES</th>
            <th>CARGO</th>';

        for ($d = 1; $d <= 31; $d++) {
            $html .= "<th>$d</th>";
        }

        $html .= '</tr>';

        $i = 1;

        foreach ($filas as $f) {

            $html .= '<tr>';
            $html .= '<td>' . $i++ . '</td>';
            $html .= '<td class="nombre">' . $f['nombre'] . '</td>';

            for ($d = 1; $d <= 31; $d++) {

                $turno = $f['dias'][$d];

                $bg = '';
                if ($turno == 'M') $bg = '#cfe2ff';
                if ($turno == 'T') $bg = '#d1e7dd';
                if ($turno == 'N') $bg = '#f8d7da';

                $html .= "<td style='background:$bg'>$turno</td>";
            }

            $html .= '</tr>';
        }

        $html .= '
            <br>

            <table class="small">
            <tr><td><b>LEYENDA:</b></td></tr>
            <tr><td>M = Mañana (07:00 - 13:00)</td></tr>
            <tr><td>T = Tarde (13:00 - 19:00)</td></tr>
            <tr><td>GD = Guardia Diurna</td></tr>
            <tr><td>GN = Guardia Nocturna</td></tr>
            <tr><td>MT = Doble turno</td></tr>
            </table>

            <br>

            <div class="footer">
            OBSERVACIONES:<br>
            - Programación referencial.<br>
            - Cumple normativa de horas laborales.<br>
            </div>

            <br><br>

            <table width="100%">
            <tr>
            <td align="center">____________________<br>JEFE DE SERVICIO</td>
            <td align="center">____________________<br>RRHH</td>
            <td align="center">____________________<br>DIRECCIÓN</td>
            </tr>
            </table>
        ';

        return $html;
    }

    public function loadFromExcel()
    {
        // Aquí iría la lógica para cargar datos desde un archivo Excel
        // Puedes usar una biblioteca como PhpSpreadsheet para esto

        return view('asistencia/programacion/cargar-excel');
    }
}

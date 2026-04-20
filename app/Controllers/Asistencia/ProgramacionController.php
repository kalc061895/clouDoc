<?php

namespace App\Controllers\Asistencia;

use App\Controllers\BaseController;
use App\Models\Asistencia\EventoModel;

class ProgramacionController extends BaseController
{
    public function index()
    {
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
                'title' => $row['trabajador_apellidos'] . ' ' . $row['trabajador_nombres'] . ' - ' . $row['turno'],

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
        // 👉 puedes cambiar esto por tu tabla real
        $data = [
            [
                'id' => 1,
                'dni' => '70000001',
                'apellidos' => 'Pérez',
                'nombres' => 'Juan',
                'cargo' => 'Médico',
                'especialidad' => 'Cardiología',
                'tipo' => 'ASIS'
            ],
            [
                'id' => 2,
                'dni' => '70000002',
                'apellidos' => 'García',
                'nombres' => 'Luis',
                'cargo' => 'Médico',
                'especialidad' => 'Gastroenterología',
                'tipo' => 'ASIS'
            ],
            [
                'id' => 3,
                'dni' => '80000001',
                'apellidos' => 'Quispe',
                'nombres' => 'María',
                'cargo' => 'Enfermera',
                'especialidad' => 'General',
                'tipo' => 'ASIS'
            ],
            [
                'id' => 4,
                'dni' => '90000001',
                'apellidos' => 'Vargas',
                'nombres' => 'Pedro',
                'cargo' => 'Administrador',
                'especialidad' => 'Gestión',
                'tipo' => 'ADMIN'
            ]
        ];

        return $this->response->setJSON($data);
    }
}
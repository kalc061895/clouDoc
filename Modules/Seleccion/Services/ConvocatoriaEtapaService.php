<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\ConvocatoriaEtapaModel;
use Throwable;

class ConvocatoriaEtapaService
{
    protected ConvocatoriaEtapaModel $model;

    public function __construct()
    {
        $this->model = new ConvocatoriaEtapaModel();
    }

    public function obtenerPorConvocatoria(int $convocatoriaId): array
    {
        return $this->model->getEtapasPorConvocatoria($convocatoriaId);
    }

    public function guardar(array $data): array
    {
        foreach ($data as $key => $value) {
            if ($value === '') {
                $data[$key] = null;
            }
        }

        $rules = [
            'cet_con_ide'      => 'required|is_natural_no_zero',
            'cet_eta_ide'      => 'required|is_natural_no_zero',
            'cet_fecha_inicio' => 'required|valid_date',
            'cet_fecha_cierre' => 'required|valid_date',
            'cet_hora_inicio'  => 'permit_empty',
            'cet_hora_cierre'  => 'permit_empty',
            'cet_estado'       => 'permit_empty|in_list[PENDIENTE,EN PROCESO,FINALIZADO,CANCELADO]',
            'cet_observacion'  => 'permit_empty',
        ];

        $validation = \Config\Services::validation();
        $validation->setRules($rules);

        if (!$validation->run($data)) {
            return [
                'ok'      => false,
                'code'    => 400,
                'message' => 'Datos de entrada no válidos.',
                'errors'  => $validation->getErrors()
            ];
        }

        // Validación de coherencia de fechas
        if (strtotime($data['cet_fecha_cierre']) < strtotime($data['cet_fecha_inicio'])) {
            return [
                'ok'      => false,
                'code'    => 400,
                'message' => 'La fecha de cierre no puede ser menor a la fecha de inicio.'
            ];
        }

        $id = !empty($data['cet_ide']) ? (int) $data['cet_ide'] : null;

        try {
            if ($id) {
                $this->model->update($id, $data);
                return [
                    'ok'      => true,
                    'code'    => 200,
                    'message' => 'Etapa del cronograma actualizada correctamente.',
                    'data'    => $this->model->find($id)
                ];
            } else {
                $nuevoId = $this->model->insert($data, true);
                return [
                    'ok'      => true,
                    'code'    => 201,
                    'message' => 'Etapa agregada al cronograma con éxito.',
                    'data'    => $this->model->find($nuevoId)
                ];
            }
        } catch (Throwable $e) {
            log_message('error', '[Seleccion] Error al guardar etapa en convocatoria: {message}', ['message' => $e->getMessage()]);
            return [
                'ok'      => false,
                'code'    => 500,
                'message' => 'Error interno al intentar guardar la etapa.'
            ];
        }
    }

    public function eliminar(int $id): array
    {
        try {
            if (!$this->model->find($id)) {
                return ['ok' => false, 'code' => 404, 'message' => 'El registro no existe o ya fue eliminado.'];
            }

            $this->model->delete($id);
            return ['ok' => true, 'code' => 200, 'message' => 'Etapa removida del cronograma correctamente.'];
        } catch (Throwable $e) {
            log_message('error', '[Seleccion] Error al eliminar etapa: {message}', ['message' => $e->getMessage()]);
            return ['ok' => false, 'code' => 500, 'message' => 'Error al intentar eliminar la etapa.'];
        }
    }
}

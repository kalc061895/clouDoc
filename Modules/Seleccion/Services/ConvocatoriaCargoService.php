<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\ConvocatoriaCargoModel;
use Throwable;

class ConvocatoriaCargoService
{
    protected ConvocatoriaCargoModel $model;

    public function __construct()
    {
        $this->model = new ConvocatoriaCargoModel();
    }

    /**
     * Obtiene los cargos asignados a una convocatoria.
     */
    public function obtenerPorConvocatoria(int $convocatoriaId): array
    {
        return $this->model->getCargosPorConvocatoria($convocatoriaId);
    }

    /**
     * Crear o actualizar un cargo en la convocatoria.
     */
    public function guardar(array $data): array
    {
        // Limpiar cadenas vacías para campos opcionales/foreign keys
        foreach ($data as $key => $value) {
            if ($value === '') {
                $data[$key] = null;
            }
        }

        // Reglas de validación
        $rules = [
            'cco_con_ide'       => 'required|is_natural_no_zero',
            'cco_car_ide'       => 'required|is_natural_no_zero',
            'cco_numero_plazas' => 'required|integer|greater_than[0]',
            'cco_remuneracion'    => 'required|numeric|greater_than_equal_to[0]',
            'cco_dependencia'   => 'permit_empty|max_length[200]',
            'cco_area'          => 'permit_empty|max_length[200]',
            'cco_establecimiento' => 'permit_empty|max_length[200]',
            'cco_observacion'   => 'permit_empty',
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

        // Verificar duplicados (Un mismo cargo maestro no debería estar repetido en la misma convocatoria)
        $id = !empty($data['cco_ide']) ? (int) $data['cco_ide'] : null;
        if ($this->esDuplicado((int) $data['cco_con_ide'], (int) $data['cco_car_ide'], $id)) {
            return [
                'ok'      => false,
                'code'    => 409,
                'message' => 'El cargo seleccionado ya se encuentra agregado a esta convocatoria.'
            ];
        }

        try {
            if ($id) {
                // Actualización
                $this->model->update($id, $data);
                $registro = $this->model->find($id);
                return [
                    'ok'      => true,
                    'code'    => 200,
                    'message' => 'Plaza de convocatoria actualizada correctamente.',
                    'data'    => $registro
                ];
            } else {
                // Inserción
                $nuevoId = $this->model->insert($data, true);
                return [
                    'ok'      => true,
                    'code'    => 201,
                    'message' => 'Plaza agregada a la convocatoria con éxito.',
                    'data'    => $this->model->find($nuevoId)
                ];
            }
        } catch (Throwable $e) {
            log_message('error', '[Seleccion] Error al guardar cargo en convocatoria: {message}', ['message' => $e->getMessage()]);
            return [
                'ok'      => false,
                'code'    => 500,
                'message' => 'Ocurrió un error interno al intentar guardar el registro.'
            ];
        }
    }

    /**
     * Eliminar un cargo (Soft Delete).
     */
    public function eliminar(int $id): array
    {
        try {
            $cargo = $this->model->find($id);
            if (!$cargo) {
                return ['ok' => false, 'code' => 404, 'message' => 'El registro no existe o ya fue eliminado.'];
            }

            $this->model->delete($id);
            return ['ok' => true, 'code' => 200, 'message' => 'Cargo eliminado de la convocatoria correctamente.'];
        } catch (Throwable $e) {
            log_message('error', '[Seleccion] Error al eliminar cargo de convocatoria: {message}', ['message' => $e->getMessage()]);
            return ['ok' => false, 'code' => 500, 'message' => 'Error al intentar eliminar el cargo.'];
        }
    }

    /**
     * Valida la restricción única entre Convocatoria y Cargo.
     */
    private function esDuplicado(int $convocatoriaId, int $cargoId, ?int $ignoreId = null): bool
    {
        $builder = $this->model->where('cco_con_ide', $convocatoriaId)
            ->where('cco_car_ide', $cargoId);

        if ($ignoreId) {
            $builder->where('cco_ide !=', $ignoreId);
        }

        return $builder->countAllResults() > 0;
    }
}

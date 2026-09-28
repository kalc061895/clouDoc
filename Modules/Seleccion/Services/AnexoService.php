<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\AnexoModel;
use Modules\Seleccion\Models\ConvocatoriaModel;
use Throwable;

class AnexoService
{
    protected AnexoModel $model;
    protected ConvocatoriaModel $convocatoriaModel;

    public function __construct()
    {
        $this->model = new AnexoModel();
        $this->convocatoriaModel = new ConvocatoriaModel();
    }

    /**
     * Obtiene los anexos asignados a una convocatoria.
     */
    public function obtenerPorConvocatoria(int $convocatoriaId): array
    {
        return $this->model->getAnexosPorConvocatoria($convocatoriaId);
    }

    /**
     * Crear o actualizar un anexo en la convocatoria.
     */
    public function guardar(array $data): array
    {
        // Limpiar cadenas vacías para campos opcionales/foreign keys
        foreach ($data as $key => $value) {
            if ($value === '') {
                $data[$key] = null;
            }
        }

        // Reglas de validación basadas en los campos reales de AnexoModel
        $rules = [
            'ane_con_ide' => 'required|is_natural_no_zero',
            'ane_codigo' => 'required|max_length[50]',
            'ane_nombre' => 'required|max_length[255]',
            'ane_descripcion' => 'permit_empty|max_length[500]',
            'ane_obligatorio' => 'required|in_list[0,1]',
            'ane_condicion' => 'permit_empty|max_length[100]',
            'ane_archivo_ide' => 'permit_empty|is_natural_no_zero',
            'ane_estado' => 'required|max_length[50]',
        ];

        $validation = \Config\Services::validation();
        $validation->setRules($rules);

        if (!$validation->run($data)) {
            return [
                'ok' => false,
                'code' => 400,
                'message' => 'Datos de entrada no válidos.',
                'errors' => $validation->getErrors()
            ];
        }

        $id = !empty($data['ane_ide']) ? (int) $data['ane_ide'] : null;
        $convocatoriaId = (int) $data['ane_con_ide'];
        $codigoAnexo = $data['ane_codigo'];

        if (!$this->convocatoriaModel->find($convocatoriaId)) {
            return ['ok' => false, 'code' => 404, 'message' => 'La convocatoria seleccionada no existe.'];
        }

        if ($id) {
            $registroActual = $this->model->find($id);
            if (!$registroActual) {
                return ['ok' => false, 'code' => 404, 'message' => 'El anexo que desea actualizar no existe.'];
            }

            if ((int) $registroActual['ane_con_ide'] !== $convocatoriaId) {
                return ['ok' => false, 'code' => 422, 'message' => 'El anexo no pertenece a la convocatoria indicada.'];
            }
        }

        // Verificar duplicados (Un mismo código de anexo no debería repetirse en la misma convocatoria)
        if ($this->esDuplicado($convocatoriaId, $codigoAnexo, $id)) {
            return [
                'ok' => false,
                'code' => 409,
                'message' => 'Ya existe un anexo con el mismo código en esta convocatoria.'
            ];
        }

        try {
            if ($id) {
                // Actualización
                $this->model->update($id, $data);
                $registro = $this->model->find($id);
                return [
                    'ok' => true,
                    'code' => 200,
                    'message' => 'Anexo de convocatoria actualizado correctamente.',
                    'data' => $registro
                ];
            } else {
                // Inserción
                $nuevoId = $this->model->insert($data, true);
                return [
                    'ok' => true,
                    'code' => 201,
                    'message' => 'Anexo agregado a la convocatoria con éxito.',
                    'data' => $this->model->find($nuevoId)
                ];
            }
        } catch (Throwable $e) {
            log_message('error', '[Seleccion] Error al guardar anexo en convocatoria: {message}', ['message' => $e->getMessage()]);
            return [
                'ok' => false,
                'code' => 500,
                'message' => 'Ocurrió un error interno al intentar guardar el registro.'
            ];
        }
    }

    /**
     * Eliminar un anexo (Soft Delete).
     */
    public function eliminar(int $id): array
    {
        try {
            $anexo = $this->model->find($id);
            if (!$anexo) {
                return ['ok' => false, 'code' => 404, 'message' => 'El registro no existe o ya fue eliminado.'];
            }

            $this->model->delete($id);
            return ['ok' => true, 'code' => 200, 'message' => 'Anexo eliminado de la convocatoria correctamente.'];
        } catch (Throwable $e) {
            log_message('error', '[Seleccion] Error al eliminar anexo de convocatoria: {message}', ['message' => $e->getMessage()]);
            return ['ok' => false, 'code' => 500, 'message' => 'Error al intentar eliminar el anexo.'];
        }
    }

    /**
     * Valida la restricción única entre Convocatoria y el Código del Anexo.
     */
    private function esDuplicado(int $convocatoriaId, string $codigoAnexo, ?int $ignoreId = null): bool
    {
        $builder = $this->model->where('ane_con_ide', $convocatoriaId)
            ->where('ane_codigo', $codigoAnexo);

        if ($ignoreId) {
            $builder->where('ane_ide !=', $ignoreId);
        }

        return $builder->countAllResults() > 0;
    }
}
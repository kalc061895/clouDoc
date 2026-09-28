<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\ConvocatoriaModel;
use Modules\Seleccion\Models\PostulacionModel;
use Modules\Seleccion\Models\PostulanteModel;
use Modules\Seleccion\Models\PlazaModel;



class ConvocatoriaService
{
    protected $convocatoriaModel;

    public function __construct()
    {
        $this->convocatoriaModel = new ConvocatoriaModel();
    }

    public function listar(): array
    {
        return $this->convocatoriaModel->getListadoDatatable();
    }

    public function obtenerPorId(int $id): array
    {
        $convocatoria = $this->convocatoriaModel->find($id);

        if (!$convocatoria) {
            throw new \Exception("La convocatoria solicitada no existe.", 404);
        }

        return $convocatoria;
    }

    public function guardar(array $data): array
    {
        if (!$this->convocatoriaModel->save($data)) {
            return [
                'status' => false,
                'errors' => $this->convocatoriaModel->errors()
            ];
        }

        $id = !empty($data['con_ide']) ? $data['con_ide'] : $this->convocatoriaModel->getInsertID();

        return [
            'status' => true,
            'id'     => $id,
            'msg'    => 'Convocatoria guardada correctamente.'
        ];
    }

    public function eliminar(int $id): array
    {
        $this->obtenerPorId($id); // Lanza excepción si no existe

        if ($this->convocatoriaModel->delete($id)) {
            return ['status' => true, 'msg' => 'Registro eliminado con éxito.'];
        }

        return ['status' => false, 'msg' => 'No se pudo eliminar el registro.'];
    }
}

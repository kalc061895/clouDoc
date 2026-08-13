<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\ConvocatoriaModel;
use Modules\Seleccion\Models\ConvocatoriaCargoModel;
use Modules\Seleccion\Models\ConvocatoriaDocumentoModel;
use Modules\Seleccion\Models\ConvocatoriaEtapaModel;

class ConvocatoriaWorkflowService
{
    public function __construct(private readonly ConvocatoriaModel $model = new ConvocatoriaModel())
    {
    }

    public function listar(): array
    {
        return $this->model->select('selec_convocatorias.*, t.tco_nombre, e.eco_nombre')
            ->join('selec_tipos_convocatoria t', 't.tco_ide = selec_convocatorias.con_tco_ide')
            ->join('selec_estados_convocatoria e', 'e.eco_ide = selec_convocatorias.con_eco_ide')
            ->orderBy('con_ide', 'DESC')->findAll();
    }

    public function obtener(int $id): ?array
    {
        return $this->model->find($id);
    }

    public function guardar(array $data, ?int $id = null): array
    {
        $rules = ['con_codigo' => 'required|max_length[50]', 'con_numero' => 'required|max_length[50]', 'con_nombre' => 'required|max_length[255]', 'con_tco_ide' => 'required|is_not_unique[selec_tipos_convocatoria.tco_ide]', 'con_eco_ide' => 'required|is_not_unique[selec_estados_convocatoria.eco_ide]', 'con_anio' => 'required|integer'];
        if (isset($data['con_responsable_ide']) && $data['con_responsable_ide'] !== '')
            $rules['con_responsable_ide'] = 'is_not_unique[users.id]';
        $validation = service('validation');
        if (!$validation->setRules($rules)->run($data))
            return ['ok' => false, 'code' => 400, 'errors' => $validation->getErrors()];
        if ($id !== null && $this->model->find($id) === null)
            return ['ok' => false, 'code' => 404];
        if ($id === null && $this->model->where('con_codigo', $data['con_codigo'])->first())
            return ['ok' => false, 'code' => 409, 'message' => 'El código ya está registrado'];
        if ($id !== null && $this->model->where('con_codigo', $data['con_codigo'])->where('con_ide !=', $id)->first())
            return ['ok' => false, 'code' => 409, 'message' => 'El código ya está registrado'];
        $this->model->save(($id === null ? [] : ['con_ide' => $id]) + $data);
        $record = $this->model->find($id ?? $this->model->getInsertID());
        return ['ok' => true, 'code' => $id === null ? 201 : 200, 'data' => $record];
    }

    public function publicar(int $id): array
    {
        $convocatoria = $this->model->find($id);
        if (!$convocatoria)
            return ['ok' => false, 'code' => 404];
        $cargos = (new ConvocatoriaCargoModel())->where('cco_con_ide', $id)->countAllResults();
        $etapas = (new ConvocatoriaEtapaModel())->where('cet_con_ide', $id)->countAllResults();
        $documentos = (new ConvocatoriaDocumentoModel())->where('cod_con_ide', $id)->where('cod_obligatorio', 1)->countAllResults();
        if ($cargos === 0 || $etapas === 0 || $documentos === 0)
            return ['ok' => false, 'code' => 400, 'message' => 'La convocatoria requiere cargos, cronograma y documentos obligatorios antes de publicarse.'];
        $estado = $this->model->db->table('selec_estados_convocatoria')->where('eco_codigo', 'PUBLICADA')->get()->getRowArray();
        if (!$estado)
            return ['ok' => false, 'code' => 400, 'message' => 'No está configurado el estado PUBLICADA.'];
        $this->model->update($id, ['con_eco_ide' => $estado['eco_ide']]);
        return ['ok' => true, 'code' => 200, 'data' => $this->model->find($id)];
    }
}

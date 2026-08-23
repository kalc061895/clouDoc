<?php

namespace Modules\Seleccion\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use Modules\Seleccion\Services\RequisitoService;

class RequisitoController extends BaseController
{
    use ResponseTrait;

    protected RequisitoService $requisitoService;

    public function __construct()
    {
        $this->requisitoService = new RequisitoService();
    }

    public function renderPartial(int $cargoId)
    {
        return view('Modules\Seleccion\Views\convocatorias\partials\requisitos_cargo', [
            'cargo_id' => $cargoId,
            'requisitos' => $this->requisitoService->getRequisitosByCargo($cargoId),
        ]);
    }

    public function store()
    {
        if (!$this->validarEntrada()) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        if ($this->requisitoService->guardarRequisito($this->request->getPost())) {
            return $this->respondCreated(['message' => 'Requisito registrado correctamente.']);
        }

        return $this->fail('No fue posible registrar el requisito.', 422);
    }

    public function update(int $id)
    {
        if (!$this->validarEntrada(false)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        if ($this->requisitoService->actualizarRequisito($id, $this->request->getPost())) {
            return $this->respond(['message' => 'Requisito actualizado correctamente.']);
        }

        return $this->failNotFound('El requisito no existe o no pudo actualizarse.');
    }

    public function delete(int $id)
    {
        if ($this->requisitoService->eliminarRequisito($id)) {
            return $this->respondDeleted(['message' => 'Requisito eliminado correctamente.']);
        }

        return $this->failNotFound('El requisito no existe o ya fue eliminado.');
    }

    private function validarEntrada(bool $requiereCargo = true): bool
    {
        $rules = [
            'req_nombre' => 'required|min_length[3]|max_length[255]',
            'req_tipo' => 'required|in_list[FORMACION,EXPERIENCIA,GENERAL]',
            'req_orden' => 'permit_empty|integer|greater_than[0]',
            'rex_anios' => 'permit_empty|integer|greater_than_equal_to[0]',
            'rex_meses' => 'permit_empty|integer|greater_than_equal_to[0]|less_than[12]',
            'rex_dias' => 'permit_empty|integer|greater_than_equal_to[0]|less_than[31]',
        ];

        if ($requiereCargo) {
            $rules['req_cco_ide'] = 'required|is_natural_no_zero';
        }

        return $this->validate($rules);
    }
}

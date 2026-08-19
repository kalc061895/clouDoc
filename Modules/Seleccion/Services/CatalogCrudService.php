<?php

namespace Modules\Seleccion\Services;

use CodeIgniter\Model;
use Throwable;

abstract class CatalogCrudService
{
    protected Model $model;
    protected string $primaryKey;
    protected string $uniqueField;
    protected array $rules;
    protected array $searchFields;
    protected array $orderFields;

    protected function configure(string $modelClass, string $primaryKey, string $uniqueField, array $rules, array $searchFields): void
    {
        $this->model = new $modelClass();
        $this->primaryKey = $primaryKey;
        $this->uniqueField = $uniqueField;
        $this->rules = $rules;
        $this->searchFields = $searchFields;
        $this->orderFields = array_keys($rules);
    }

    public function index(array $filters): array
    {
        $page = max(1, (int) ($filters['page'] ?? 1));
        $perPage = min(100, max(1, (int) ($filters['per_page'] ?? 20)));
        $orderBy = in_array($filters['order_by'] ?? '', $this->orderFields, true) ? $filters['order_by'] : $this->primaryKey;
        $direction = strtolower($filters['order'] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';
        $builder = $this->model->builder();
        $search = trim((string) ($filters['search'] ?? ''));
        if ($search !== '') {
            $builder->groupStart();
            foreach ($this->searchFields as $index => $field) {
                $index === 0 ? $builder->like($field, $search) : $builder->orLike($field, $search);
            }
            $builder->groupEnd();
        }
        $total = $builder->countAllResults(false);
        $data = $builder->orderBy($orderBy, $direction)->limit($perPage, ($page - 1) * $perPage)->get()->getResultArray();

        return ['ok' => true, 'code' => 200, 'data' => $data, 'meta' => ['page' => $page, 'per_page' => $perPage, 'total' => $total, 'total_pages' => (int) ceil($total / $perPage)]];
    }

    public function find(int $id): array
    {
        $record = $this->model->find($id);
        return $record === null ? ['ok' => false, 'code' => 404, 'message' => 'Registro no encontrado'] : ['ok' => true, 'code' => 200, 'data' => $record];
    }

    public function create(array $data): array
    {
        $data = $this->onlyAllowed($data);
        if ($errors = $this->validate($data, true)) return ['ok' => false, 'code' => 400, 'message' => 'Datos inválidos', 'errors' => $errors];
        if ($this->isDuplicate($data)) return ['ok' => false, 'code' => 409, 'message' => 'El código ya se encuentra registrado'];
        try {
            $id = $this->model->insert($data, true);
            return $id === false ? ['ok' => false, 'code' => 400, 'message' => 'No se pudo crear el registro', 'errors' => $this->model->errors()] : ['ok' => true, 'code' => 201, 'data' => $this->model->find($id)];
        } catch (Throwable $exception) {
            log_message('error', '[Seleccion] Error al crear catálogo: {message}', ['message' => $exception->getMessage()]);
            return ['ok' => false, 'code' => 500, 'message' => 'Error interno del servidor'];
        }
    }

    public function update(int $id, array $data): array
    {
        if ($this->model->find($id) === null) return ['ok' => false, 'code' => 404, 'message' => 'Registro no encontrado'];
        $data = $this->onlyAllowed($data);
        if ($data === []) return ['ok' => false, 'code' => 400, 'message' => 'No se enviaron campos permitidos para actualizar'];
        if ($errors = $this->validate($data, false)) return ['ok' => false, 'code' => 400, 'message' => 'Datos inválidos', 'errors' => $errors];
        if ($this->isDuplicate($data, $id)) return ['ok' => false, 'code' => 409, 'message' => 'El código ya se encuentra registrado'];
        try {
            return ! $this->model->update($id, $data) ? ['ok' => false, 'code' => 400, 'message' => 'No se pudo actualizar el registro', 'errors' => $this->model->errors()] : ['ok' => true, 'code' => 200, 'data' => $this->model->find($id)];
        } catch (Throwable $exception) {
            log_message('error', '[Seleccion] Error al actualizar catálogo: {message}', ['message' => $exception->getMessage()]);
            return ['ok' => false, 'code' => 500, 'message' => 'Error interno del servidor'];
        }
    }

    public function delete(int $id): array
    {
        if ($this->model->find($id) === null) return ['ok' => false, 'code' => 404, 'message' => 'Registro no encontrado'];
        try {
            return ! $this->model->delete($id) ? ['ok' => false, 'code' => 400, 'message' => 'No se pudo eliminar el registro'] : ['ok' => true, 'code' => 200];
        } catch (Throwable $exception) {
            log_message('error', '[Seleccion] Error al eliminar catálogo: {message}', ['message' => $exception->getMessage()]);
            return ['ok' => false, 'code' => 409, 'message' => 'No se puede eliminar el registro porque tiene dependencias'];
        }
    }

    private function onlyAllowed(array $data): array
    {
        return array_intersect_key($data, array_flip(array_keys($this->rules)));
    }
    private function validate(array $data, bool $creating): array
    {
        $rules = $this->rules;
        if (! $creating) foreach ($rules as $field => $rule) {
            if (! array_key_exists($field, $data)) unset($rules[$field]);
            else $rules[$field] = str_replace('required', 'permit_empty', $rule);
        }
        $validation = service('validation');
        return $validation->setRules($rules)->run($data) ? [] : $validation->getErrors();
    }
    private function isDuplicate(array $data, ?int $id = null): bool
    {
        if (! isset($data[$this->uniqueField]) || $data[$this->uniqueField] === '') return false;
        $builder = $this->model->builder()->where($this->uniqueField, $data[$this->uniqueField]);
        if ($id !== null) $builder->where($this->primaryKey . ' !=', $id);
        return $builder->countAllResults() > 0;
    }
}

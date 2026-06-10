<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class DiresaModel extends Model
{
    protected $table            = 'casis_diresa';
    protected $primaryKey       = 'dir_ide';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';

    protected $protectFields = true;

    protected $allowedFields = [
        'dir_nombre',
        'dir_director',
        'dir_ubicacion',
        'dir_telefono',
        'dir_email',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';

    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    protected $validationRules = [
        'dir_nombre' => 'required|max_length[150]',
        'dir_email'  => 'permit_empty|valid_email',
    ];

    protected $skipValidation = false;

    public function listar()
    {
        return $this->orderBy('dir_nombre', 'ASC')
                    ->findAll();
    }

    public function obtener(int $id)
    {
        return $this->find($id);
    }
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    protected $validationMessages   = [];
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}

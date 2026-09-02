<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class ProfesionModel extends Model
{
    protected $table            = 'casis_profesion';
    protected $primaryKey       = 'pro_ide';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $protectFields = true;

    protected $allowedFields = [
        'pro_codigo',
        'pro_nombre',
        'pro_abreviatura',
        'pro_estado',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'pro_nombre' => 'required|max_length[200]',
    ];

    protected $skipValidation = false;

    public function activos()
    {
        return $this->where('pro_estado', 1)
            ->orderBy('pro_nombre', 'ASC')
            ->findAll();
    }

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $dateFormat    = 'datetime';
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

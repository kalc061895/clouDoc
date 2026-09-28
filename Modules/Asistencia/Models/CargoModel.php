<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class CargoModel extends Model
{
    protected $table            = 'casis_cargo';
    protected $primaryKey       = 'car_ide';
    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $protectFields  = true;

    protected $allowedFields = [
        'car_codigo',
        'car_nombre',
        'car_descripcion',
        'car_estado',
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
        'car_nombre' => 'required|max_length[150]',
    ];

    public function activos()
    {
        return $this->where('car_estado', 1)
                    ->orderBy('car_nombre')
                    ->findAll();
    }
    
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $dateFormat    = 'datetime';
    
    // Validation
    protected $validationMessages   = [];
    protected $skipValidation       = false;
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

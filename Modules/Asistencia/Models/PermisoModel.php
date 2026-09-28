<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class PermisoModel extends Model
{
    protected $table            = 'casis_permiso';
    protected $primaryKey       = 'pero_ide';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $protectFields = true;

    protected $allowedFields = [
        'pero_nombre',
        'pero_abreviatura',
        'pero_descripcion',
        'pero_remunerado',
        'pero_horas_maximas',
        'pero_estado',
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
        'pero_nombre' => 'required|max_length[100]',
    ];

    protected $skipValidation = false;

    public function activos()
    {
        return $this->where('pero_estado', 1)
                    ->orderBy('pero_nombre', 'ASC')
                    ->findAll();
    }

    public function remunerados()
    {
        return $this->where('pero_remunerado', 1)
                    ->where('pero_estado', 1)
                    ->findAll();
    }

    public function noRemunerados()
    {
        return $this->where('pero_remunerado', 0)
                    ->where('pero_estado', 1)
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

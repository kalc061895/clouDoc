<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class LicenciaModel extends Model
{
    protected $table            = 'casis_licencia';
    protected $primaryKey       = 'lic_ide';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $protectFields = true;

    protected $allowedFields = [
        'lic_nombre',
        'lic_abreviatura',
        'lic_remunerado',
        'lic_dias_maximos',
        'lic_descripcion',
        'lic_estado',
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
        'lic_nombre' => 'required|max_length[150]',
    ];

    public function activos()
    {
        return $this->where('lic_estado', 1)
                    ->orderBy('lic_nombre')
                    ->findAll();
    }

    public function remuneradas()
    {
        return $this->where('lic_remunerado', 1)
                    ->where('lic_estado', 1)
                    ->findAll();
    }

    public function noRemuneradas()
    {
        return $this->where('lic_remunerado', 0)
                    ->where('lic_estado', 1)
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

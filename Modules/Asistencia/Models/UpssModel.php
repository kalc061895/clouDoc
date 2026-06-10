<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class UpssModel extends Model
{
    protected $table            = 'casis_upss';
    protected $primaryKey       = 'ups_ide';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $protectFields = true;

    protected $allowedFields = [
        'ups_codigo',
        'ups_nombre',
        'ups_agrupacion',
        'ups_agrupacion_abreviatura',
        'ups_estado',
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
        'ups_nombre' => 'required|max_length[200]',
    ];

    public function activos()
    {
        return $this->where('ups_estado', 1)
            ->orderBy('ups_nombre')
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

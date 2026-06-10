<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class SegundaEspecialidadModel extends Model
{
    protected $table            = 'casis_segunda_especialidad';
    protected $primaryKey       = 'se_ide';
    protected $useAutoIncrement = true;

    protected $returnType    = 'array';
    protected $protectFields = true;

    protected $allowedFields = [
        'se_nombre',
        'se_abreviatura',
        'se_estado',
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
        'se_nombre' => 'required|max_length[200]',
    ];

    public function activos()
    {
        return $this->where('se_estado', 1)
            ->orderBy('se_nombre', 'ASC')
            ->findAll();
    }

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $dateFormat    = 'datetime';
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

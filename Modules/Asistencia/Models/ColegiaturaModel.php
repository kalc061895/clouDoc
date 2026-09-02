<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class ColegiaturaModel extends Model
{
    protected $table            = 'casis_colegiatura';
    protected $primaryKey       = 'col_ide';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $protectFields = true;

    protected $allowedFields = [
        'col_pro_ide',
        'col_nombre',
        'col_abreviatura',
        'col_estado',
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
        'col_pro_ide' => 'required|integer',
        'col_nombre'  => 'required|max_length[150]',
    ];

    public function activos()
    {
        return $this->where('col_estado', 1)
            ->orderBy('col_nombre')
            ->findAll();
    }

    public function obtenerPorProfesion(int $proIde)
    {
        return $this->where('col_pro_ide', $proIde)
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

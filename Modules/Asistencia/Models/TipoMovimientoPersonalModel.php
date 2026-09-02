<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class TipoMovimientoPersonalModel extends Model
{
    protected $table            = 'casis_tipo_movimiento_personal';
    protected $primaryKey       = 'tmp_ide';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $protectFields = true;

    protected $allowedFields = [
        'tmp_codigo',
        'tmp_nombre',
        'tmp_descripcion',
        'tmp_estado',
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
        'tmp_nombre' => 'required|max_length[100]',
    ];

    protected $skipValidation = false;

    public function activos()
    {
        return $this->where('tmp_estado', 1)
            ->orderBy('tmp_nombre', 'ASC')
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

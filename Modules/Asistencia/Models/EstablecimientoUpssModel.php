<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class EstablecimientoUpssModel extends Model
{
    protected $table            = 'casis_establecimiento_upss';
    protected $primaryKey       = 'eup_ide';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $protectFields = true;

    protected $allowedFields = [

        'eup_est_ide',
        'eup_ups_ide',

        'eup_estado',
        'eup_observacion',

        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $useSoftDeletes = true;

    protected $deletedField = 'deleted_at';

    protected $useTimestamps = true;

    protected $createdField = 'created_at';

    protected $updatedField = 'updated_at';

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $dateFormat    = 'datetime';

    // Validation
    protected $validationRules      = [];
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

<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class ProgramacionModel extends Model
{
    protected $table      = 'casis_programacion';
    protected $primaryKey = 'prog_ide';

    protected $returnType = 'array';

    protected $allowedFields = [

        'prog_perl_ide',
        'prog_fecha',
        'prog_th_ide',

        'prog_eup_ide',
        'prog_eus_ide',

        'prog_estado',
        'prog_observacion',

        'prog_es_cambio',
        'prog_origen_id',
        'prog_reg',

        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $protectFields    = true;

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];


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

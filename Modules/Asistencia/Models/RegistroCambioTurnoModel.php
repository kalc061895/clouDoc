<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class RegistroCambioTurnoModel extends Model
{
    protected $table            = 'casis_registro_cambio_turno';
    protected $primaryKey       = 'rc_ide';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $protectFields = true;

    protected $allowedFields = [

        'rc_sol_perl_ide',
        'rc_ace_perl_ide',

        'rc_fecha_sol',
        'rc_fecha_ace',

        'rc_turno_sol_ide',
        'rc_turno_ace_ide',

        'rc_justificacion',
        'rc_observacion',

        'rc_estado',

        'rc_fecha_aprobacion',
        'rc_aprobado_por',

        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $useSoftDeletes = true;

    protected $useTimestamps = true;

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

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

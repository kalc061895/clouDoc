<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class TurnoHorarioModel extends Model
{
    protected $table      = 'casis_turno_horario';
    protected $primaryKey = 'th_ide';

    protected $returnType = 'array';

    protected $allowedFields = [
        'th_tur_ide',
        'th_codigo',
        'th_nombre',
        'th_hora_ingreso',
        'th_hora_salida',
        'th_tolerancia_ingreso',
        'th_tolerancia_salida',
        'th_refrigerio_salida',
        'th_refrigerio_retorno',
        'th_estado'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $useSoftDeletes = true;
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

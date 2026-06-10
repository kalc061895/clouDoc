<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class RegistroVacacionModel extends Model
{
    protected $table            = 'casis_registro_vacacion';
    protected $primaryKey       = 'rv_ide';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $protectFields = true;

    protected $allowedFields = [
        'rv_vac_ide',
        'rv_fecha_inicio',
        'rv_fecha_fin',
        'rv_dias',
        'rv_numero_documento',
        'rv_fecha_documento',
        'rv_observacion',
        'rv_estado',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function obtenerPorPeriodo($vacIde)
    {
        return $this->where('rv_vac_ide', $vacIde)
            ->orderBy('rv_fecha_inicio')
            ->findAll();
    }
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

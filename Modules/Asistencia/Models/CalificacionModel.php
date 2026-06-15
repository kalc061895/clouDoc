<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class CalificacionModel extends Model
{
    protected $table      = 'casis_calificacion';
    protected $primaryKey = 'cal_ide';

    protected $allowedFields = [

        'cal_user_ide',

        'cal_titulo',
        'cal_descripcion',

        'cal_inicio',
        'cal_fin',
        
        'cal_anio',
        'cal_mes',

        'cal_estado',

        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function listar()
    {
        return $this->orderBy('cal_ide', 'DESC')
            ->findAll();
    }

    public function obtener(int $id)
    {
        return $this->find($id);
    }

    public function obtenerPorPeriodo(int $anio, int $mes)
    {
        return $this->where('cal_anio', $anio)
            ->where('cal_mes', $mes)
            ->first();
    }

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

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

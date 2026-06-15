<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class CalificacionDetalleModel extends Model
{
    protected $table      = 'casis_calificacion_detalle';
    protected $primaryKey = 'cad_ide';

    protected $allowedFields = [

        'cad_cal_ide',
        'cad_perl_ide',

        'cad_asistencia',

        'cad_guardias',
        'cad_guardias_monto',

        'cad_faltas',
        'cad_faltas_monto',

        'cad_tardanzas',
        'cad_tardanzas_monto',

        'cad_observacion',
        'cad_estado',

        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function obtenerPorCalificacion(int $calIde)
    {
        return $this->where('cad_cal_ide', $calIde)
                    ->findAll();
    }

    public function obtenerPorPersonal(int $perlIde)
    {
        return $this->where('cad_perl_ide', $perlIde)
                    ->findAll();
    }

    public function obtenerDetalleCompleto(int $calIde)
    {
        return $this->select('
                casis_calificacion_detalle.*,
                p.perl_codigo
            ')
            ->join(
                'casis_personal p',
                'p.perl_ide = casis_calificacion_detalle.cad_perl_ide'
            )
            ->where('cad_cal_ide', $calIde)
            ->findAll();
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

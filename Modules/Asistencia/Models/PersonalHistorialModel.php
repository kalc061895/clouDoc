<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class PersonalHistorialModel extends Model
{
    protected $table            = 'casis_personal_historial';
    protected $primaryKey       = 'phis_ide';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $protectFields = true;

    protected $allowedFields = [
        'phis_perl_ide',
        'phis_tmp_ide',
        'phis_est_ide',
        'phis_ofi_ide',
        'phis_car_ide',
        'phis_mco_ide',
        'phis_fecha_inicio',
        'phis_fecha_fin',
        'phis_resolucion',
        'phis_fecha_resolucion',
        'phis_motivo',
        'phis_observacion',
        'phis_estado',
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
        'phis_perl_ide'     => 'required|integer',
        'phis_tmp_ide'      => 'required|integer',
        'phis_fecha_inicio' => 'required|valid_date',
    ];

    /**
     * Historial completo
     */
    public function obtenerHistorial($perlIde)
    {
        return $this->select("
                casis_personal_historial.*,
                tmp.tmp_nombre,
                car.car_nombre,
                ofi.ofi_nombre,
                est.est_denominacion,
                mco.mco_nombre
            ")
            ->join(
                'casis_tipo_movimiento_personal tmp',
                'tmp.tmp_ide = casis_personal_historial.phis_tmp_ide'
            )
            ->join(
                'casis_cargo car',
                'car.car_ide = casis_personal_historial.phis_car_ide',
                'left'
            )
            ->join(
                'casis_oficina ofi',
                'ofi.ofi_ide = casis_personal_historial.phis_ofi_ide',
                'left'
            )
            ->join(
                'casis_establecimiento est',
                'est.est_ide = casis_personal_historial.phis_est_ide',
                'left'
            )
            ->join(
                'casis_modalidad_contrato mco',
                'mco.mco_ide = casis_personal_historial.phis_mco_ide',
                'left'
            )
            ->where('phis_perl_ide', $perlIde)
            ->orderBy('phis_fecha_inicio', 'DESC')
            ->findAll();
    }

    /**
     * Movimiento vigente
     */
    public function obtenerMovimientoActual($perlIde)
    {
        return $this->where('phis_perl_ide', $perlIde)
            ->where('phis_fecha_fin', null)
            ->first();
    }

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $dateFormat    = 'datetime';

    // Validation
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

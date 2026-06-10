<?php

namespace Modules\Asistencia\Models;


use CodeIgniter\Model;

class PersonalModel extends Model
{
    protected $table            = 'casis_personal';
    protected $primaryKey       = 'perl_ide';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $protectFields = true;

    protected $allowedFields = [
        'perl_per_ide',
        'perl_est_ide',
        'perl_ofi_ide',
        'perl_car_ide',
        'perl_mco_ide',
        'perl_se_ide',
        'perl_codigo',
        'perl_fecha_inicio',
        'perl_fecha_termino',
        'perl_numero_colegiatura',
        'perl_plaza',
        'perl_nivel',
        'perl_estado',
        'perl_regimen_laboral',
        'perl_observacion',
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
        'perl_per_ide' => 'required|integer',
        'perl_est_ide' => 'required|integer',
        'perl_mco_ide' => 'required|integer',
    ];

    protected $validationMessages = [
        'perl_per_ide' => [
            'required' => 'Debe seleccionar una persona.',
        ],
        'perl_est_ide' => [
            'required' => 'Debe seleccionar un establecimiento.',
        ],
        'perl_mco_ide' => [
            'required' => 'Debe seleccionar una modalidad.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Personal activo
     */
    public function activos()
    {
        return $this->where('perl_estado', 'ACTIVO')
                    ->findAll();
    }

    /**
     * Personal por establecimiento
     */
    public function porEstablecimiento(int $estIde)
    {
        return $this->where('perl_est_ide', $estIde)
                    ->findAll();
    }

    /**
     * Personal por oficina
     */
    public function porOficina(int $ofiIde)
    {
        return $this->where('perl_ofi_ide', $ofiIde)
                    ->findAll();
    }

    /**
     * Personal por cargo
     */
    public function porCargo(int $carIde)
    {
        return $this->where('perl_car_ide', $carIde)
                    ->findAll();
    }

    /**
     * Obtener ficha completa
     */
    public function obtenerFicha(int $perlIde)
    {
        return $this->select("
                    casis_personal.*,
                    p.per_numero_documento,
                    p.per_paterno,
                    p.per_materno,
                    p.per_nombre,

                    est.est_denominacion,

                    c.car_nombre,

                    o.ofi_nombre,

                    m.mco_nombre
                ")
                ->join(
                    'casis_persona p',
                    'p.per_ide = casis_personal.perl_per_ide'
                )
                ->join(
                    'casis_establecimiento est',
                    'est.est_ide = casis_personal.perl_est_ide',
                    'left'
                )
                ->join(
                    'casis_cargo c',
                    'c.car_ide = casis_personal.perl_car_ide',
                    'left'
                )
                ->join(
                    'casis_oficina o',
                    'o.ofi_ide = casis_personal.perl_ofi_ide',
                    'left'
                )
                ->join(
                    'casis_modalidad_contrato m',
                    'm.mco_ide = casis_personal.perl_mco_ide',
                    'left'
                )
                ->find($perlIde);
    }
    protected $dateFormat    = 'datetime';
    protected $cleanValidationRules = true;

}

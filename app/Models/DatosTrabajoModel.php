<?php

namespace App\Models;

use CodeIgniter\Model;

class DatosTrabajoModel extends Model
{
    protected $table            = 'datos_trabajo';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'dato_personal_id',
        'fecha_ingreso',
        'numero_contrato',
        'resolucion_nombramiento',
        'modalidad',
        'establecimiento',
        'nivel',
        'unidad',
        'servicio',
        'area',
        'cargo',
        'remuneracion',
        'pension',
        'pension_cuenta',
        'numero_historia_sst',
        'numero_legajo',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    

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

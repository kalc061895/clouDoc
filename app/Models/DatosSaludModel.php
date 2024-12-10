<?php

namespace App\Models;

use CodeIgniter\Model;

class DatosSaludModel extends Model
{
    protected $table            = 'datos_salud';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'dato_personal_id',
        'aler_betalactamicos',
        'aler_penicilina',
        'aler_analgesicos',
        'aler_ketorolaco',
        'aler_otras',
        'enf_diabetes',
        'enf_hipertension',
        'enf_asma',
        'enf_epilepsia',
        'enf_otras',
        'med_actuales',
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

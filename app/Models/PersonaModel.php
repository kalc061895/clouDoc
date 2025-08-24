<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonaModel extends Model
{
    protected $table            = 'asis_persona';
    protected $primaryKey       = 'per_ide';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'per_dni',
        'per_paterno',
        'per_materno',
        'per_nombre',
        'per_foto',
        'per_sexo',
        'per_lugar_nacimiento',
        'per_fecha_nacimiento',
        'per_residencia',
        'per_ruc',
        'per_telefono',
        'per_email',
        'per_estadocivil',
        'per_ingreso',
        'per_user',
        'per_pass',
        // 'per_fecha_registro' no se incluye aquí porque tiene CURRENT_TIMESTAMP en la BD
        // y se gestiona automáticamente por la base de datos.
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

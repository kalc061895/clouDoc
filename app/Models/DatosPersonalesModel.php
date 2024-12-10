<?php

namespace App\Models;

use CodeIgniter\Model;

class DatosPersonalesModel extends Model
{
    protected $table            = 'datos_personales';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'apellido_paterno',
        'apellido_materno',
        'nombres',
        'foto',
        'sexo',
        'nacionalidad',
        'fecha_nacimiento',
        'edad',
        'dni',
        'carne_extranjeria',
        'licencia_conducir',
        'estado_civil',
        'numero_hijos',
        'domicilio_actual',
        'distrito',
        'provincia',
        'departamento',
        'telefono_celular',
        'telefono_fijo',
        'correo_electronico',
        'RUC',
        'banco_nombre',
        'numero_cuenta',
        'persona_emergencia',
        'telefono_emergencia',
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

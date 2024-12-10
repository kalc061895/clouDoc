<?php

namespace App\Models;

use CodeIgniter\Model;

class DatosViviendaComunidadModel extends Model
{
    protected $table            = 'datos_vivienda_comunidad';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'dato_personal_id',
        'viv_ubicacion',
        'viv_tenencia',
        'viv_material',
        'viv_servicios_luz',
        'viv_servicios_agua',
        'viv_servicios_telefono',
        'viv_servicios_desague',
        'viv_servicios_cable',
        'viv_servicios_internet',
        'viv_servicios_otros',
        'viv_problemas_drogadiccion',
        'viv_problemas_pandillas',
        'viv_problemas_robos',
        'viv_problemas_alcoholismo',
        'viv_problemas_otros',
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

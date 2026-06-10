<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class OficinaModel extends Model
{
    protected $table            = 'casis_oficina';
    protected $primaryKey       = 'ofi_ide';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $protectFields = true;

    protected $allowedFields = [
        'ofi_est_ide',
        'ofi_tofi_ide',
        'ofi_nombre',
        'ofi_abreviatura',
        'ofi_codigo_referencia',
        'ofi_titulo_encargado',
        'ofi_nombres_encargado',
        'ofi_cargo_encargado',
        'ofi_descripcion',
        'ofi_rango',
        'ofi_padre_ide',
        'ofi_estado',
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
        'ofi_nombre'   => 'required|max_length[255]',
        'ofi_tofi_ide' => 'required|integer',
    ];

    public function obtenerRaices()
    {
        return $this->where('ofi_padre_ide', null)
            ->where('ofi_estado', 1)
            ->findAll();
    }

    public function obtenerHijos(int $ofiIde)
    {
        return $this->where('ofi_padre_ide', $ofiIde)
            ->where('ofi_estado', 1)
            ->findAll();
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

<?php

namespace App\Models;

use CodeIgniter\Model;

class EmpresaConfiguracionModel extends Model
{
    protected $table            = 'empresa_configuracion';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'key',
        'value',
        'description'
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
    public function getConfig($key)
    {
        $this->builder->where('key', $key);
        $result = $this->builder->get()->getFirstRow(); 
        // Obtén la primera fila de resultado

        if ($result) {
            return $result->value;
        }

        return false;
    }
    public function getDriveConfig()
    {
        $this->where('key', 'google_drive');
        $result = $this->get()->getFirstRow(); 
        // Obtén la primera fila de resultado

        if ($result) {
            return $result->value;
        }

        return false;
    }
}

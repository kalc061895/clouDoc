<?php

namespace App\Models;

use CodeIgniter\Model;

class TupaModel extends Model
{
    protected $table            = 'tupa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'numero_orden','denominacion_procedimiento',
        'derecho_admision_uit','derecho_admision_soles',
        'calificacion','plazo','oficina_inicio','oficina_competencia',
        'oficina_reconsideracion','oficina_apelacion'
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
    public function getTupa() {
        $db = \Config\Database::connect();

        $builder = $db->table('tupa');
        //$builder->select('tupa.*');
        //$builder->where('requisimovimientos.id', null);
        $builder->join(
            'tupa_requisitos',
            'tupa_requisitos.tupa_id = tupa.id',
            'inner'
        );
        $builder->orderBy('tupa.numero_orden','ASC');
        $builder->orderBy('tupa_requisitos.numero_requisito','ASC');
        $query = $builder->get();

        return $query->getResultObject();

    }
}

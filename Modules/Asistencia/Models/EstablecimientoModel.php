<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class EstablecimientoModel extends Model
{
    protected $table = 'casis_establecimiento';
    protected $primaryKey = 'est_ide';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = [
        'est_mic_ide',
        'est_codigo',
        'est_ipress',
        'est_tipo',
        'est_nombre',
        'est_categoria',
        'est_ubigeo',
        'est_latitud',
        'est_longitud',
        'est_radio',
        'est_foto_principal',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $validationRules = [
        'est_mic_ide'      => 'required|integer',
        'est_nombre' => 'required|max_length[200]',
    ];

    public function obtenerPorMicrored(int $micIde)
    {
        return $this->where('est_mic_ide', $micIde)
            ->orderBy('est_nombre')
            ->findAll();
    }
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $dateFormat    = 'datetime';
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

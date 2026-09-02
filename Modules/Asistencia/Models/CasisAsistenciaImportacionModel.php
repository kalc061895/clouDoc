<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class CasisAsistenciaImportacionModel extends Model
{
    protected $table      = 'casis_asistencia_importacion';
    protected $primaryKey = 'imp_ide';

    protected $returnType = 'array';

    protected $allowedFields = [

        'imp_nombre_archivo',
        'imp_ruta_archivo',
        'imp_tipo_archivo',
        'imp_hash_archivo',

        'imp_origen',

        'imp_total_registros',
        'imp_total_importados',
        'imp_total_duplicados',
        'imp_total_error',

        'imp_observacion',

        'imp_user_ide',
        'imp_fecha_proceso',
    ];

    protected $useTimestamps = true;

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    /**
     * Obtener última importación
     */
    public function getUltimaImportacion()
    {
        return $this->orderBy('imp_ide', 'DESC')
            ->first();
    }

    /**
     * Buscar por hash
     */
    public function existeHash($hash)
    {
        return $this->where('imp_hash_archivo', $hash)
            ->first();
    }

    /**
     * Historial de importaciones
     */
    public function historial($limit = 50)
    {
        return $this->orderBy('imp_ide', 'DESC')
            ->findAll($limit);
    }
    protected $useAutoIncrement = true;
    protected $protectFields    = true;

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $dateFormat    = 'datetime';

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
